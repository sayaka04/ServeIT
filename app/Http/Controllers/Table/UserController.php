<?php

namespace App\Http\Controllers\Table;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ReportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Exclude admins
        $query->where('is_admin', false);

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'technician') {
                $query->where('is_technician', true);
            } elseif ($request->role === 'client') {
                $query->where('is_technician', false);
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Eager load only after filters are applied ✅
        $query->with('technician');

        // Paginate results
        $users = $query->orderBy('first_name')->paginate(10)->withQueryString();

        return view('user.index', compact('users'));
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
    // public function show(User $user)
    // {

    //     return view('user.show', ['user' => $user]);
    // }
    public function show(User $user)
    {
        // Eager load relationships relevant to a customer:
        $user->load([
            'repairs' => function ($query) {
                $query->where('is_completed', true)
                    ->with('technician.user');
            },
            'repairRatings' => function ($query) {
                $query->whereNotNull('user_weighted_score')
                    ->whereNotNull('technician_weighted_score')
                    ->with('technician.user');
            },
        ]);


        // --- CORRECTED LOGIC START ---
        $existingConversation = null;

        if (Auth::check() && Auth::user()->is_technician) {
            $technicianUserId = Auth::id();
            $clientUserId = $user->id;

            // Fetch the actual Conversation model (or null if it doesn't exist)
            $existingConversation = Conversation::where('user_id', $clientUserId)
                ->where('technician_user_id', $technicianUserId)
                ->first();
        }
        // --- CORRECTED LOGIC END ---

        // Fetch report categories
        $reportCategories = ReportCategory::all();

        // Pass the conversation object to the view
        return view('user.show', compact('user', 'reportCategories', 'existingConversation'));
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'is_banned' => 'required|boolean',
            'is_disabled' => 'required|boolean',
        ]);

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User status updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Change the authenticated user's profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeProfilePicture(Request $request)
    {
        // Step 1: Validate the incoming request
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the file from the request
        $file = $request->file('profile_picture');

        // Step 2: Store the file in the 'public/profile_pictures' directory
        $filePath = $file->store('profile_pictures', 'public');

        // Remove the 'public/' prefix to store the path correctly in the database
        $dbPath = str_replace('public/', '', $filePath);

        // Get the authenticated user
        $user = auth()->user();

        // Delete the old profile picture if one exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Step 3: Update the user's record in the database
        $user->update([
            'profile_picture' => $dbPath,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }
}
