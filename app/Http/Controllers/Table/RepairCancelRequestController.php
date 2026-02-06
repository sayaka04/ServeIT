<?php

namespace App\Http\Controllers\Table;

use App\Models\RepairCancelRequest;
use App\Models\Repair;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairCancelRequestController extends Controller
{
    // Show all pending cancel requests
    public function index()
    {
        // Fetch cancel requests where is_accepted is null (pending)
        $cancelRequests = RepairCancelRequest::where('approver_id', Auth::id())->whereNull('is_accepted')->with(['repair', 'requestor'])->get();

        return view('repair_cancel_requests.index', compact('cancelRequests'));
    }

    // Accept the cancellation request
    public function accept($id)
    {
        $cancelRequest = RepairCancelRequest::findOrFail($id);

        if ($cancelRequest->is_accepted !== null) {
            return back()->with('warning', 'This cancellation request has already been processed.');
        }

        // Mark the cancellation request as accepted
        $cancelRequest->update(['is_accepted' => true]);

        // Also update the repair as cancelled
        $repair = $cancelRequest->repair;
        $repair->update(['is_cancelled' => true]);
        $repair->update(['status' => 'cancelled']);
        return back()->with('success', 'The cancellation request has been accepted and the repair cancelled.');
    }

    // Decline the cancellation request
    public function decline($id)
    {
        $cancelRequest = RepairCancelRequest::findOrFail($id);

        if ($cancelRequest->is_accepted !== null) {
            return back()->with('warning', 'This cancellation request has already been processed.');
        }

        // Mark the cancellation request as declined
        $cancelRequest->update(['is_accepted' => false]);

        return back()->with('success', 'The cancellation request has been declined.');
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        $repairs = Repair::where('is_cancelled', false)->get();
        $users = User::all();
        return view('repair_cancel_requests.create', compact('repairs', 'users'));
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'repair_id' => 'required|exists:repairs,id',
            'requestor_id' => 'required|exists:users,id',
            'approver_id' => 'nullable|exists:users,id',
            'reason' => 'required|string',
        ]);

        RepairCancelRequest::create($validated);

        return redirect()->route('repair-cancel-requests.index')
            ->with('success', 'Cancel request submitted successfully.');
    }

    /** Display the specified resource. */
    public function show(RepairCancelRequest $repairCancelRequest)
    {
        $repairCancelRequest->load(['repair.user', 'repair.technician.user', 'requestor', 'approver']);
        return view('repair_cancel_requests.show', compact('repairCancelRequest'));
    }

    /** Show the form for editing the specified resource. */
    public function edit(RepairCancelRequest $repairCancelRequest)
    {
        $repairs = Repair::all();
        $users = User::all();
        return view('repair_cancel_requests.edit', compact('repairCancelRequest', 'repairs', 'users'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, RepairCancelRequest $repairCancelRequest)
    {
        $validated = $request->validate([
            'repair_id' => 'required|exists:repairs,id',
            'requestor_id' => 'required|exists:users,id',
            'approver_id' => 'nullable|exists:users,id',
            'reason' => 'required|string',
            'is_accepted' => 'nullable|boolean',
        ]);

        $repairCancelRequest->update($validated);

        if ($repairCancelRequest->is_accepted) {
            $repairCancelRequest->repair->update([
                'status' => 'cancelled',
                'is_cancelled' => true,
            ]);
        }

        return redirect()->route('repair-cancel-requests.index')
            ->with('success', 'Cancel request updated successfully.');
    }

    /** Remove the specified resource from storage. */
    public function destroy(RepairCancelRequest $repairCancelRequest)
    {
        $repairCancelRequest->delete();

        return redirect()->route('repair-cancel-requests.index')
            ->with('success', 'Cancel request deleted successfully.');
    }


    /** ===============================================
     *  Custom function: Handle repair cancel request
     *  =============================================== */
    public function cancel(Request $request, $repairId)
    {
        $repair = Repair::findOrFail($repairId);

        // Prevent duplicate cancel requests
        if ($repair->is_cancelled) {
            return back()->with('warning', 'A cancellation request already exists or this repair is already cancelled.');
        }

        $validated = $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        if (Auth::user()->is_technician) {
            $approver_id = $repair->user_id;
        } else {
            $technician = Technician::with('user')->find($repair->technician_id);
            $approver_id = $technician ? $technician->user->id : null;
        }


        // Create new cancel request
        RepairCancelRequest::create([
            'repair_id' => $repair->id,
            'requestor_id' => Auth::id(),
            'approver_id' => $approver_id,
            'reason' => $validated['cancel_reason'] ?? 'No reason provided.',
        ]);

        return back()->with('success', 'Your cancellation request has been submitted for approval.');
    }
}
