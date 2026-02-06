<?php

namespace App\Http\Controllers\Table;

use App\Models\Report;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\AdminAction;
use App\Models\Notification;
use App\Models\ReportCategory;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{


    protected $emailController;

    // Inject the EmailController into the constructor
    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }

    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'category']);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'under_review', 'resolved', 'closed'])) {
            $query->where('status', $request->status);
        }

        // Filter by name (either reporter or reported)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('reporter', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })->orWhereHas('reportedUser', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            });
        }

        // Paginate results
        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        // Status counts
        $statusCounts = Report::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingCount = $statusCounts['pending'] ?? 0;
        $reviewCount = $statusCounts['under_review'] ?? 0;
        $resolvedCount = $statusCounts['resolved'] ?? 0;
        $closedCount = $statusCounts['closed'] ?? 0;

        return view('reports.index', compact(
            'reports',
            'pendingCount',
            'closedCount',
            'resolvedCount',
            'reviewCount'
        ));
    }




    public function userHistory(User $user)
    {
        // Reports where this user is the one being reported
        $reportsAgainstUser = Report::with(['reporter', 'category'])
            ->where('reported_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Reports where this user is the one who submitted the report
        $reportsByUser = Report::with(['reportedUser', 'category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.history', compact('user', 'reportsAgainstUser', 'reportsByUser'));
    }





    public function create()
    {
        $users = User::all(); // For reporter and reported
        $categories = ReportCategory::all();
        return view('reports.create', compact('users', 'categories'));
    }
































//--------STORE-------STORE------STORE-------STORE--------STORE----------STORE-------STORE-------STORE---------STORE-------STORE-------STORE-----STORE----
    /**
     * Store a newly created public report via AJAX.
     */
    public function store(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to report a user.'], 403);
        }

        // Validate inputs
        // Note: 'files.*' validation already allows 'pdf', so no change needed here.
        $validated = $request->validate([
            'reported_user_id' => 'required|exists:users,id',
            'category_id'      => 'required|exists:report_categories,id',
            'description'      => 'required|string|max:2000',
            'files.*'          => 'file|mimes:jpg,jpeg,png,gif,pdf|max:5120', // 5MB per file
            'take_admin_action' => 'nullable|boolean',
            'action_taken'      => 'required_if:take_admin_action,1|in:warn,suspend,ban,dismiss',
            'admin_notes'       => 'nullable|string|max:1000',
        ]);

        // Prevent self-reporting
        if (Auth::id() == $validated['reported_user_id']) {
            return response()->json(['error' => 'You cannot report yourself.'], 403);
        }

        // Handle file upload (Images -> PDF conversion OR Direct PDF storage)
        // Renamed method to be more descriptive, pass $request
        $filePath = $this->processReportFiles($request);

        // Create the report record
        $report = Report::create([
            'user_id'          => Auth::id(),
            'reported_user_id' => $validated['reported_user_id'],
            'category_id'      => $validated['category_id'],
            'description'      => $validated['description'],
            'file_path'        => $filePath, // Saves the path to the PDF (either uploaded or generated)
            'status'           => Report::STATUS_PENDING,
            'is_admin_report'  => Auth::user()->is_admin,
        ]);

        // Handle Admin Actions (if applicable)
        if ($request->boolean('take_admin_action')) {
            AdminAction::create([
                'admin_id'       => Auth::id(),
                'target_user_id' => $validated['reported_user_id'],
                'report_id'      => $report->id,
                'action_taken'   => $request->input('action_taken'),
                'notes'          => $request->input('admin_notes'),
            ]);
        }

        return response()->json([
            'success'   => 'Your report has been submitted for review. Thank you.',
            'report_id' => $report->id,
        ]);
    }

    /**
     * Processes uploaded files.
     * - If a PDF is found, it saves it directly.
     * - If only images are found, it converts them to a single PDF.
     */
    protected function processReportFiles(Request $request): ?string
    {
        if (!$request->hasFile('files')) {
            return null;
        }

        $files = $request->file('files');
        $images = [];
        $pdfFile = null;
        $totalSize = 0;

        foreach ($files as $file) {
            $totalSize += $file->getSize();

            // 1. Check if the file is a PDF
            if ($file->getMimeType() === 'application/pdf') {
                // We prioritize the PDF. If multiple are sent, we take the first one found
                // (Since we only have one 'file_path' column in the database).
                $pdfFile = $file;
                break;
            }

            // 2. If not PDF, check if it is an image
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $images[] = $file->getRealPath();
            }
        }

        // Global size check
        if ($totalSize > 25 * 1024 * 1024) {
            abort(422, 'Total file size must not exceed 25MB.');
        }

        // --- SCENARIO A: User uploaded a PDF ---
        if ($pdfFile) {
            // Save the raw PDF directly to public storage
            // This returns a path like "reports/randomhash.pdf"
            return Storage::disk('public')->putFile('reports', $pdfFile);
        }

        // --- SCENARIO B: User uploaded Images ---
        if (!empty($images)) {
            $html = '<html><body>';
            foreach ($images as $img) {
                $base64 = base64_encode(file_get_contents($img));
                // Center images and page break
                $html .= '<div style="page-break-after: always; text-align:center;">
                            <img src="data:image/jpeg;base64,' . $base64 . '" style="max-width:100%; max-height:100%;" />
                          </div>';
            }
            $html .= '</body></html>';

            $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
            $pdfFileName = 'reports/' . uniqid('report_') . '.pdf';

            Storage::disk('public')->put($pdfFileName, $pdf->output());

            return $pdfFileName;
        }

        return null;
    }
    //--------STORE-------STORE------STORE-------STORE--------STORE----------STORE-------STORE-------STORE---------STORE-------STORE-------STORE-----STORE----





















    public function show(Report $report)
    {
        $report->load(['reporter', 'reportedUser', 'category']);
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        // $report->with(['reporter', 'reportedUser', 'category', 'adminActions']);

        if ($report->status === Report::STATUS_PENDING) {
            $report->update(['status' => Report::STATUS_UNDER_REVIEW]);
        }

        $users = User::all();
        $categories = ReportCategory::all();
        return view('reports.edit', compact('report', 'users', 'categories'));
    }






    //--------UPDATE-------UPDATE------UPDATE-------UPDATE--------UPDATE----------UPDATE-------UPDATE-------UPDATE---------UPDATE-------UPDATE-------UPDATE-----UPDATE----
    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,resolved,closed',
            'admin_notes' => 'nullable|string|max:2000',

            // New admin action fields — only validate if checkbox is checked
            'take_admin_action' => 'nullable|boolean',
            'action_taken'      => 'required_if:take_admin_action,1|in:warn,suspend,ban,dismiss,unban',
            'notes'       => 'nullable|string|max:1000',
        ]);

        $report->update($request->all());

        if ($request->boolean('take_admin_action')) {
            AdminAction::create([
                'admin_id'       => Auth::id(),
                'target_user_id' => $report->reported_user_id,
                'report_id'      => $report->id, // if linked
                'action_taken'   => $request->input('action_taken'),
                'notes'          => $request->input('notes'),
            ]);
        }

        // Fetch user
        $targetUser = User::find($report->reported_user_id);

        // System-level actions or consequences
        switch ($request->input('action_taken')) {
            case 'ban':
                $targetUser->is_banned = true;
                break;
            case 'unban':
                $targetUser->is_banned = false;
                break;
            case 'suspend':
                $targetUser->is_disabled = true; // Assuming this disables login or access
                break;
            case 'dismiss':
                // Maybe log the dismissal or do nothing
                break;
            case 'warn':
                Notification::create([
                    'recipient' => $report->reported_user_id,
                    'subject' => 'Admin Warning', // Specific subject for completed repairs
                    'description' => $request->input('notes'), // Specific description
                    'notifiable_type' => '',
                    'notifiable_id' => null,
                    'has_seen' => false,
                ]);



                try {
                    if (config('custom.enable_email_sender')) {
                        $this->emailController->emailSendNotification(
                            $report->reportedUser->email,
                            'Admin Warning',
                            $request->input('notes'),
                            [''],
                            'mail.new-message-email'
                        );
                    }
                } catch (Exception $e) {
                    // Log the exception or handle it accordingly
                    Log::error('Email send failed: ' . $e->getMessage());
                    // Optionally, you can add custom error handling here (e.g., notify the admin, retry logic, etc.)
                }
                break;
            default:
                break;
        }

        $targetUser->save();


        return redirect()->route('reports.index')->with('success', 'Report updated.');
    }
    //--------UPDATE-------UPDATE------UPDATE-------UPDATE--------UPDATE----------UPDATE-------UPDATE-------UPDATE---------UPDATE-------UPDATE-------UPDATE-----UPDATE----























    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted.');
    }
}
