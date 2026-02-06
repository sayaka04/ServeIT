<?php

namespace App\Http\Controllers\Table;

use App\Models\RepairProgress;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Notification;
use App\Models\Repair;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RepairProgressController extends Controller
{


    protected $emailController;

    // Inject the EmailController into the constructor
    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }

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
        $request->validate([
            'repair_id' => 'required|exists:repairs,id',
            'progress_status' => 'required|string|max:255',
            'progress_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
            'description' => 'nullable|string',
            'completion_rate' => 'required|integer|min:0|max:100',
        ]);

        $check_progress = RepairProgress::where('repair_id', $request->repair_id)->orderBy('created_at', 'desc')->first();

        if ($check_progress && $request->completion_rate < $check_progress->completion_rate) {
            return redirect()->back()->with('failed', 'New progress rate cannot be less than the previous one.');
        }

        $progressFilePath = null;
        if ($request->hasFile('progress_file')) {
            // The file() helper returns a UploadedFile instance
            $progressFilePath = $request->file('progress_file')->store('progress', 'public');
        }

        RepairProgress::create([
            'repair_id' => $request->repair_id,
            'progress_status' => $request->progress_status,
            'description' => $request->description,
            'progress_file_path' => $progressFilePath,
            'completion_rate' => $request->completion_rate,
        ]);

        if ($request->completion_rate == 100) {
            $repair = Repair::find($request->repair_id);
            if ($repair) {
                $repair->status = 'completed';
                $repair->is_completed = true;
                $repair->save();
            }

            $repair = Repair::find($request->repair_id);
            $client = User::find($repair->user_id);
            $linkURL = route('repairs.show', $repair->id);
            Notification::create([
                'recipient' => $client->id,
                'subject' => 'Repair Completed!', // Specific subject for completed repairs
                'description' => 'Your repair has been completed.', // Specific description
                'notifiable_type' => 'App\Models\Repair',
                'notifiable_id' => $request->repair_id,
                'has_seen' => false,
            ]);

            $details = [
                'repair_id' => $request->repair_id,
                'sender' => Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name,
                'link' => $linkURL,
                'device' => $repair->device,
                'device_type' => $repair->device_type
            ];


            try {
                if (config('custom.enable_email_sender')) {
                    $this->emailController->emailSendNotification(
                        $client->email,
                        'Repair Completed!',
                        'Your repair has been completed for:',
                        $details,
                        'mail.repair-progress-email'
                    );
                }
            } catch (Exception $e) {
                // Log the exception or handle it accordingly
                Log::error('Email send failed: ' . $e->getMessage());
                // Optionally, you can add custom error handling here (e.g., notify the admin, retry logic, etc.)
            }

            // Return immediately after sending the completion email
            return redirect()->back()->with('success', 'Repair completed successfully!');
        }

        // This section now only runs for completion rates less than 100
        $repair = Repair::find($request->repair_id);
        $client = User::find($repair->user_id);
        $linkURL = route('repairs.show', $repair->id);

        $details = [
            'repair_id' => $request->repair_id,
            'sender' => Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name,
            'link' => $linkURL,
            'device' => $repair->device,
            'device_type' => $repair->device_type
        ];

        if (config('custom.enable_email_sender')) {
            Notification::create([
                'recipient' => $client->id,
                'subject' => 'Repair Progress Added: ' . $request->progress_status,
                'description' => $request->description ?? null,
                'notifiable_type' => 'App\Models\Repair',
                'notifiable_id' => $request->repair_id,
                'has_seen' => false,
            ]);
            $this->emailController->emailSendNotification(
                $client->email,
                'Repair Progress Added: ' . $request->progress_status,
                $request->description,
                $details,
                'mail.repair-progress-email'
            );
        }
        return redirect()->back()->with('success', 'Progress added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepairProgress $repairProgress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairProgress $repairProgress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairProgress $repairProgress)
    {
        $request->validate([
            'update_progress_status' => 'required|string|max:255',
            'update_description' => 'nullable|string',
            'update_completion_rate' => 'required|integer|min:0|max:100',
            'update_progress_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',

        ]);


        if ($request->update_completion_rate < $repairProgress->completion_rate) {
            return redirect()->back()->with('failed', 'Cannot decrease the completion rate.');
        }

        $update_progress_file = null;
        if ($request->hasFile('update_progress_file')) {
            // The file() helper returns a UploadedFile instance
            $update_progress_file = $request->file('update_progress_file')->store('progress', 'public');
        }

        $repairProgress->update([
            'progress_status' => $request->update_progress_status,
            'description' => $request->update_description,
            'completion_rate' => $request->update_completion_rate,
            'progress_file_path' => $update_progress_file,
        ]);

        return redirect()->back()->with('success', 'Progress updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepairProgress $repairProgress)
    {
        //
    }
}
