<?php

namespace App\Http\Controllers\Table;

use App\Models\RepairRating;
use App\Models\User;
use App\Models\Technician;
use App\Models\Repair;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RepairRatingController extends Controller
{
    public function index()
    {
        $ratings = RepairRating::with(['user', 'technician.user', 'repair'])->latest()->paginate(10);
        return view('repair_ratings.index', compact('ratings'));
    }


    public function create()
    {
        $users = User::all();
        $technicians = Technician::all();
        $repairs = Repair::doesntHave('repairRating')->get(); // Ensure only unrated repairs are shown
        return view('repair_ratings.create', compact('users', 'technicians', 'repairs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'technician_id' => 'required|exists:technicians,id',
            'repair_id' => 'required|exists:repairs,id|unique:repair_ratings,repair_id',
            'user_weighted_score' => 'nullable|numeric|min:0|max:100',
            'technician_weighted_score' => 'nullable|numeric|min:0|max:100',
            'user_comment' => 'nullable|string', // Validate user comment
            'technician_comment' => 'nullable|string', // Validate technician comment
        ]);

        // Create the Repair Rating record
        RepairRating::create($validated);

        // Redirect back with a success message
        return redirect()->route('repair_ratings.index')->with('success', 'Rating created successfully.');
    }



    public function show(RepairRating $repairRating)
    {
        return view('repair_ratings.show', compact('repairRating'));
    }

    public function edit(RepairRating $repairRating)
    {
        $users = User::all();
        $technicians = Technician::all();
        $repairs = Repair::all(); // Not filtering here for simplicity

        return view('repair_ratings.edit', compact('repairRating', 'users', 'technicians', 'repairs'));
    }

    public function update(Request $request, RepairRating $repairRating)
    {
        $validated = $request->validate([
            'user_weighted_score' => 'nullable|numeric|min:0|max:100',
            'technician_weighted_score' => 'nullable|numeric|min:0|max:100',
            'user_comment' => 'nullable|string',
            'technician_comment' => 'nullable|string',
        ]);

        if ($repairRating->user_weighted_score !== null && $repairRating->technician_weighted_score !== null) {
            return;
        }

        if (Auth::user()->is_technician) {
            $data = [
                'technician_weighted_score' => $validated['technician_weighted_score'] ?? null,
                'technician_comment' => $validated['technician_comment'] ?? null,
            ];
        } else {
            $data = [
                'user_weighted_score' => $validated['user_weighted_score'] ?? null,
                'user_comment' => $validated['user_comment'] ?? null,
            ];
        }

        $repairRating->update($data);

        // return redirect()->route('repair_ratings.index')->with('success', 'Rating updated successfully.');
        return redirect()->back()->with('success', 'Score updated.');
    }


    public function destroy(RepairRating $repairRating)
    {
        $repairRating->delete();
        return redirect()->route('repair_ratings.index')->with('success', 'Rating deleted successfully.');
    }
}
