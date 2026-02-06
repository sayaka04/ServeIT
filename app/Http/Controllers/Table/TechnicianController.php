<?php

namespace App\Http\Controllers\Table;

use App\Models\Technician;
use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    // public function show(Technician $technician)
    // {
    //     if (!$technician) {
    //         // This check is technically redundant if route model binding is used, 
    //         // but it doesn't hurt.
    //         return redirect()->back()->with('error', 'Technician not found.');
    //     }

    //     // Eager load related user and technician files
    //     $technician->load(['user', 'technicianFiles', 'technicianLinks', 'repairRatings.user']);

    //     // --- FIX: Fetch the report categories from the database ---
    //     $reportCategories = ReportCategory::all();

    //     // Log technician info (optional)
    //     Log::info($technician);

    //     // Pass technician and technician files (and now categories) to the view
    //     return view('technician.profile.technician_profile', compact('technician', 'reportCategories'));
    //     //                                                                  ^--- ADDED 'reportCategories' HERE
    // }
    public function show(Technician $technician)
    {
        if (!$technician) {
            // This check is technically redundant if route model binding is used, 
            // but it doesn't hurt.
            return redirect()->back()->with('error', 'Technician not found.');
        }


        // Eager load related user, technician files, links, ratings, AND expertise categories
        // Less desirable approach - Do NOT use this.
        $technician->load([
            'user',
            'technicianFiles' => function ($query) {
                $query->where('is_deleted', 0); // Manual filter
            },
            'technicianLinks' => function ($query) {
                $query->where('is_deleted', 0); // Manual filter
            },
            'repairRatings.user',
            'activeExpertiseCategories'
        ]);
        // --- FIX: Fetch the report categories from the database ---
        // NOTE: The user's request suggests ReportCategory might be used for 'available expertise'.
        // Assuming ReportCategory is used as a stand-in for all available ExpertiseCategory models.
        $reportCategories = ReportCategory::all();

        // Log technician info (optional)
        Log::info($technician);

        // Pass technician and technician files (and now categories) to the view
        return view('technician.profile.technician_profile', compact('technician', 'reportCategories'));
    }


    public function editExpertise(Technician $technician)
    {
        $technician->load('expertiseCategories');

        $allCategories = \App\Models\ExpertiseCategory::all();
        $currentExpertise = $technician->expertiseCategories->keyBy('id');

        return view('technician.profile.edit_expertise', compact('technician', 'allCategories', 'currentExpertise'));
    }


    public function updateExpertise(Request $request, Technician $technician)
    {
        $selectedIds = $request->input('expertise_categories', []);
        $selectedIds = array_map('intval', $selectedIds);

        // Get existing expertise including pivot info
        $existingExpertise = $technician->expertiseCategories()->withPivot('is_archived')->get();

        // Attach or unarchive selected categories
        foreach ($selectedIds as $id) {
            $existing = $existingExpertise->firstWhere('id', $id);
            if ($existing) {
                if ($existing->pivot->is_archived) {
                    $technician->expertiseCategories()->updateExistingPivot($id, ['is_archived' => false]);
                }
            } else {
                $technician->expertiseCategories()->attach($id, ['is_archived' => false]);
            }
        }

        // Refresh the expertise list to include newly attached ones
        $updatedExpertise = $technician->expertiseCategories()->withPivot('is_archived')->get();

        // Archive expertise NOT in selected IDs
        foreach ($updatedExpertise as $expertise) {
            if (!in_array($expertise->id, $selectedIds) && !$expertise->pivot->is_archived) {
                $technician->expertiseCategories()->updateExistingPivot($expertise->id, ['is_archived' => true]);
            }
        }

        return redirect()
            ->route('technician.expertise.manage', $technician->technician_code)
            ->with('success', 'Expertise updated successfully!');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technician $technician)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technician $technician)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        //
    }

    /**
     * Change the authenticated technician's banner picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeBannerPicture(Request $request)
    {
        // Step 1: Validate the incoming request
        $request->validate([
            'banner_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        // Get the file from the request
        $file = $request->file('banner_picture');

        // Step 2: Store the file in the 'public/technician_banners' directory
        $filePath = $file->store('technician_banners', 'public');

        // Remove the 'public/' prefix to store the path correctly in the database
        $dbPath = str_replace('public/', '', $filePath);

        // Get the authenticated technician
        // This assumes a one-to-one relationship where a user is also a technician
        $technician = Technician::where('technician_user_id', auth()->id())->firstOrFail();

        // Delete the old banner picture if one exists
        if ($technician->banner_picture) {
            Storage::disk('public')->delete($technician->banner_picture);
        }

        // Step 3: Update the technician's record in the database
        $technician->update([
            'banner_picture' => $dbPath,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Banner picture updated successfully!');
    }



    public function updateLocation(Request $request)
    {
        // 1. Validation
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // 2. Authorization and Fetching Technician Model
        // Auth::user() returns the User model.
        $user = Auth::user();

        // Security check: Only allow technicians to update their own record
        if (!$user->is_technician) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        // The Technician model is related via the 'technician()' relationship on the User model
        $technician = $user->technician;

        if (!$technician) {
            return response()->json(['message' => 'Technician record not found.'], 404);
        }

        // 3. Update the Coordinates
        $technician->latitude = $request->input('latitude');
        $technician->longitude = $request->input('longitude');
        $technician->save();

        // 4. Return success response
        return response()->json([
            'message' => 'Technician location updated successfully.',
            'latitude' => $technician->latitude,
            'longitude' => $technician->longitude,
        ], 200);
    }
}
