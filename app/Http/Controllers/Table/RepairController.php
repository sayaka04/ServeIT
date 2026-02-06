<?php

namespace App\Http\Controllers\Table;

use App\Events\Converse;
use App\Models\Repair;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Notification;
use App\Models\RepairProgress;
use App\Models\RepairRating;
use App\Models\Technician;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RepairController extends Controller
{


    protected $emailController;

    // Inject the EmailController into the constructor
    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }

    /**
     * 🛠️ Ongoing Repairs (Pending / Accepted / In Progress / Completed but Not Claimed)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        /**
         * ---------------------------------------------------------
         * Base query (USED by table, counts, and total)
         * ---------------------------------------------------------
         */
        $baseQuery = Repair::query()
            ->with(['repairRating', 'pendingCancelRequests'])
            ->where('is_cancelled', 0)
            ->where('status', '!=', 'declined')
            ->whereNull('is_claimed');

        /**
         * ---------------------------------------------------------
         * Technician or Client filter
         * ---------------------------------------------------------
         */
        if (Auth::user()->is_technician) {
            $technician = Technician::where('technician_user_id', $userId)->firstOrFail();
            $baseQuery->where('technician_id', $technician->id);
        } else {
            $baseQuery->where('user_id', $userId);
        }

        /**
         * ---------------------------------------------------------
         * Search filter
         * ---------------------------------------------------------
         */
        if ($request->filled('search')) {
            $search = $request->search;

            $baseQuery->where(function ($q) use ($search) {
                $q->whereRaw(
                    "JSON_SEARCH(issues, 'one', ?) IS NOT NULL",
                    ["%{$search}%"]
                )
                    ->orWhere('device', 'like', "%{$search}%")
                    ->orWhere('device_type', 'like', "%{$search}%");
            });
        }


        /**
         * ---------------------------------------------------------
         * Table data (status filter applies ONLY here)
         * ---------------------------------------------------------
         */
        $repairsQuery = clone $baseQuery;

        if ($request->filled('status') && $request->status !== 'all') {
            $repairsQuery->where('status', $request->status);
        }

        $repairs = $repairsQuery
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        /**
         * ---------------------------------------------------------
         * Status counts (NO status filter)
         * ---------------------------------------------------------
         */
        $statusCounts = (clone $baseQuery)
            ->whereIn('status', ['pending', 'accepted', 'in_progress', 'completed'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        /**
         * ---------------------------------------------------------
         * Total count ("All" tab)
         * ---------------------------------------------------------
         */
        $totalCount = (clone $baseQuery)->count();

        /**
         * ---------------------------------------------------------
         * Debug log
         * ---------------------------------------------------------
         */
        Log::info('Ongoing repairs index loaded', [
            'user_id'       => $userId,
            'is_technician' => Auth::user()->is_technician,
            'status_filter' => $request->status ?? 'all',
            'search'        => $request->search,
            'total'         => $totalCount,
            'counts'        => $statusCounts->toArray(),
        ]);

        /**
         * ---------------------------------------------------------
         * Return view
         * ---------------------------------------------------------
         */
        return view('repair.index', [
            'repair'       => $repairs,
            'statusCounts' => $statusCounts,
            'totalCount'   => $totalCount,
        ]);
    }



    public function history(Request $request)
    {
        $userId = Auth::id();
        $query = Repair::query()->with('repairRating');

        if (Auth::user()->is_technician) {
            $technician = Technician::where('technician_user_id', $userId)->first();
            $query->where('technician_id', $technician->id);
        } else {
            $query->where('user_id', $userId);
        }

        // Use the same filter as your SQL
        $query->where(function ($q) {
            $q->where('is_claimed', 1)
                ->orWhere('is_cancelled', 1)
                ->orWhere('status', 'declined');
        });

        // 🔍 Search
        // 🔍 Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereRaw(
                    "JSON_SEARCH(issues, 'one', ?) IS NOT NULL",
                    ["%{$search}%"]
                )
                    ->orWhere('device', 'like', "%{$search}%")
                    ->orWhere('device_type', 'like', "%{$search}%");
            });
        }


        // 🔹 Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pagination
        $repairs = (clone $query)->orderByDesc('created_at')->paginate(10);

        // Status counts using **the same filter as the main query** to avoid duplicates
        $statusCounts = (clone $query)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Fill missing statuses
        foreach (['completed', 'declined', 'cancelled'] as $status) {
            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 0;
            }
        }


        $statusCounts['all'] = $repairs->total();

        Log::info('Total repairs fetched for user: ' . $userId, [
            'count' => $repairs->total(),
            'status_filter' => $request->status ?? 'all',
            'search' => $request->search ?? null,
            'repairs' => $repairs->toArray(),
        ]);

        return view('repair.history', [
            'repair' => $repairs,
            'statusCounts' => $statusCounts
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (!Auth::user()->is_technician) {
            abort(403, 'Forbidden!');
        }

        $conversations = Conversation::where('technician_user_id', Auth::id())->with('user', 'technician.user')->get();
        $is_technician = true;

        return view('repair.create', [
            'conversations' => $conversations,
            'is_technician' => $is_technician,
        ]);
    }































    public function updateBreakdown(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        // Validate that the data is present
        $request->validate([
            'issues_data' => 'required|json',
            'breakdown_data' => 'required|json',
            'estimated_cost_data' => 'required|numeric',
        ]);

        // Update the repair record
        $repair->issues = json_decode($request->issues_data, true);
        $repair->breakdown = json_decode($request->breakdown_data, true);
        $repair->estimated_cost = $request->estimated_cost_data;
        $repair->updated_at = now();
        $repair->client_final_confirmation = null;
        $repair->save();

        return back()->with('success', 'Repair details updated successfully!');
    }









    /**
     * Store a newly created resource in storage.
     */




    /**
     * Store a newly created resource in storage. (SECURED: Enforces structure and sanitizes input)
     */
    // public function store(Request $request)
    // {
    //     // --- 1. Validation and Data Retrieval ---
    //     $validated = $request->validate([
    //         'conversation_id'                 => 'required|exists:conversations,id',
    //         'device'                          => 'required|string|max:100',
    //         'device_type'                     => 'required|string|max:100',

    //         // These contain JSON strings from the frontend
    //         'issues_data'                     => 'required|string',
    //         'breakdown_data'                  => 'required|string',
    //         'estimated_cost_data'             => 'required|numeric',

    //         'serial_number'                   => 'nullable|string|max:100',
    //         'completion_date'                 => 'nullable|date',
    //         'is_received'                     => 'nullable|boolean',
    //         'receive_notes'                   => 'nullable|string',
    //         'receive_file'                    => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
    //         'client_final_confirmation'       => 'nullable|boolean',
    //         'confirmation_signature_data'     => 'nullable|string',
    //     ]);

    //     $conversation = Conversation::findOrFail($validated['conversation_id']);

    //     // --- 2. SECURE Data Preparation (Sanitization and Structure Enforcement) ---

    //     // --- A. Handle Issues Data (Incoming is array of objects: [{'issue': 'name'}]) ---
    //     // Decode to PHP array. Use '?? []' to handle non-JSON or null input safely.
    //     $issues = json_decode($request->issues_data, true) ?? [];

    //     // Loop over the structured array, sanitize the value, and keep the 'issue' key.
    //     $issuesSanitized = collect($issues)
    //         ->map(function ($item) {
    //             // Safely get the 'issue' value, defaulting to empty string
    //             $issueName = $item['issue'] ?? '';
    //             return [
    //                 // STRICTLY enforce the 'issue' key and sanitize the value
    //                 'issue' => Str::limit(strip_tags($issueName), 100)
    //             ];
    //         })
    //         // Filter out empty items after sanitization
    //         ->filter(fn($item) => $item['issue'] !== '')
    //         ->toArray();

    //     // Re-encode the structured data for database storage
    //     $issuesJson = json_encode($issuesSanitized);


    //     // --- B. Handle Breakdown Data (Incoming is array of objects: [{'item': 'name', 'price': 0}]) ---
    //     $breakdown = json_decode($request->breakdown_data, true) ?? [];

    //     // Loop over the array, STRICTLY define 'item' and 'price' keys, and sanitize values.
    //     $breakdownSanitized = collect($breakdown)
    //         ->map(function ($item) {
    //             // Get values safely, defaulting to empty string or zero
    //             $name = $item['item'] ?? '';
    //             $price = $item['price'] ?? 0;

    //             return [
    //                 // STRICTLY enforce the 'item' key and sanitize
    //                 'item' => Str::limit(strip_tags($name), 100),

    //                 // STRICTLY enforce the 'price' key and ensure it's a numeric float
    //                 'price' => is_numeric($price) ? (float) $price : 0.00,
    //             ];
    //         })
    //         // Remove any entry where the item name is now empty after sanitization
    //         ->filter(fn($item) => $item['item'] !== '')
    //         ->toArray();

    //     // Re-encode the structured data for database storage
    //     $breakdownJson = json_encode($breakdownSanitized);


    //     // --- 3. File and Signature Handling (Using helper methods) ---
    //     // NOTE: Assuming handleBase64Signature is defined elsewhere in the controller
    //     $signaturePath = $this->handleBase64Signature($request->confirmation_signature_data, $conversation->user_id);
    //     $receiveFilePath = $request->hasFile('receive_file')
    //         ? $request->file('receive_file')->store('uploads', 'public')
    //         : null;

    //     // --- 4. Create Repair Record ---
    //     $repair = Repair::create([
    //         'user_id'                       => $conversation->user_id,
    //         'technician_id'                 => $conversation->technician_id,
    //         'conversation_id'               => $validated['conversation_id'],
    //         'device'                        => $validated['device'],
    //         'device_type'                   => $validated['device_type'],
    //         'serial_number'                 => $request->serial_number,

    //         // Store the SECURELY formatted JSON strings
    //         'issues'                        => $issuesJson,
    //         'breakdown'                     => $breakdownJson,
    //         'estimated_cost'                => $validated['estimated_cost_data'],
    //         'completion_date'               => $request->completion_date,

    //         // Status & Confirmation Details
    //         'is_received'                   => $request->boolean('is_received'),
    //         'receive_notes'                 => $request->receive_notes,
    //         'receive_file_path'             => $receiveFilePath,
    //         'client_final_confirmation'     => $request->boolean('client_final_confirmation'),
    //         'confirmation_signature_path'   => $signaturePath,
    //     ]);

    //     // --- 5. Handle Confirmation (PDF, Email, Notification) ---
    //     if ($repair->client_final_confirmation) {
    //         // NOTE: Assuming 'processClientConfirmation' is defined elsewhere in the controller
    //         return $this->processClientConfirmation($repair);
    //     }

    //     // --- 6. Final JSON Response ---
    //     return response()->json([
    //         'message' => 'Repair request created successfully!',
    //         'repair' => $repair,
    //         'signature_path' => $signaturePath,
    //     ], 201);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Auth::user()->is_technician) {
            abort(403, 'Forbidden!');
        }
        // --- 1. Validation and Data Retrieval ---
        $validated = $request->validate([
            // 'conversation_id'                 => 'required|exists:conversations,id',
            'device'                          => 'required|string|max:100',
            'device_type'                     => 'required|string|max:100',

            // JSON data from frontend
            'issues_data'                     => 'required|string',
            'breakdown_data'                  => 'required|string',
            'estimated_cost_data'             => 'required|numeric',

            'serial_number'                   => 'nullable|string|max:100',
            'completion_date'                 => 'nullable|date',
            'is_received'                     => 'nullable|boolean',
            'receive_notes'                   => 'nullable|string',

            // Validation for the actual file upload
            'receive_file'                    => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',

            'client_final_confirmation'       => 'nullable|boolean',
            'confirmation_signature_data'     => 'nullable|string',

            'walk_in'                         => 'nullable|boolean',

            'disclaimer_enabled' => 'nullable|boolean',
            'disclaimer_agree'   => 'nullable|boolean',
            'repair_disclaimer'  => 'nullable|string',

        ]);

        if ($request->boolean('walk_in')) {
            // $validated['walk_in'] = true;
            Log::info('This is a walk-in repair request.');

            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'phone_number' => ['nullable', 'string', 'max:20', 'unique:' . User::class],
            ]);

            $user = User::create([
                'first_name'  => ucwords($request->first_name),
                'middle_name' => ucwords($request->middle_name),
                'last_name'   => ucwords($request->last_name),
                'email' => $request->email,
                'phone_number' => Crypt::encrypt($request->phone_number),
                'password' => Hash::make(' '),
                'is_technician' => false,
                'is_admin' => false,
                'is_disabled' => false,
                'is_banned' => false,
            ]);


            Log::info('------------------------------------------');
            Log::info('user_id' . $user->id);
            Log::info('technician_user_id' . Auth::user()->id);
            Log::info('technician_id' . Auth::user()->technician->id);
            Log::info('------------------------------------------');


            $conversation = Conversation::create([
                'conversation_code' => "TEMP",
                'user_id' => $user->id,
                'technician_user_id' => Auth::user()->id,
                'technician_id' => Auth::user()->technician->id,
            ]);

            $timestamp = strtotime($conversation->created_at); // integer timestamp
            $hashids = new Hashids();

            $code = $hashids->encode($conversation->id)
                . Str::upper(Str::random(4))
                . $hashids->encode($timestamp);

            $conversation->update(['conversation_code' => $code]);
        } else {
            Log::info('This is a typical repair request.');
            $conversation = Conversation::findOrFail($request->conversation_id);
        }



















        // --- 2. SECURE Data Preparation (Issues and Breakdown) ---

        // Handle Issues Data (sanitize and enforce structure)
        $issues = json_decode($request->issues_data, true) ?? [];
        $issuesSanitized = collect($issues)
            ->map(function ($item) {
                $issueName = $item['issue'] ?? '';
                return ['issue' => Str::limit(strip_tags($issueName), 100)];
            })
            ->filter(fn($item) => $item['issue'] !== '')
            ->toArray();
        $issuesJson = json_encode($issuesSanitized);


        // Handle Breakdown Data (sanitize and enforce structure)
        $breakdown = json_decode($request->breakdown_data, true) ?? [];
        $breakdownSanitized = collect($breakdown)
            ->map(function ($item) {
                $name = $item['item'] ?? '';
                $price = $item['price'] ?? 0;
                return [
                    'item' => Str::limit(strip_tags($name), 100),
                    'price' => is_numeric($price) ? (float) $price : 0.00,
                ];
            })
            ->filter(fn($item) => $item['item'] !== '')
            ->toArray();
        $breakdownJson = json_encode($breakdownSanitized);


        // --- 3. File and Signature Handling (THE FIX) ---

        // 🚨 CRITICAL FIX: Explicitly store the file and get the path.
        $receiveFilePath = null;
        if ($request->hasFile('receive_file')) {
            // The file() helper returns a UploadedFile instance
            $receiveFilePath = $request->file('receive_file')->store('repair_received_files', 'public');
        }

        // NOTE: Assuming handleBase64Signature is defined elsewhere
        $signaturePath = $this->handleBase64Signature($request->confirmation_signature_data, $conversation->user_id);

        $validated['disclaimer_agree'] = $request->boolean('disclaimer_agree');

        $disclaimerText = null;
        if ($request->boolean('disclaimer_enabled') && $validated['disclaimer_agree']) {
            $disclaimerText = $validated['repair_disclaimer'] ?? null;
        }


        // --- 4. Create Repair Record ---
        $repair = Repair::create([
            'user_id'                       => $conversation->user_id,
            'technician_id'                 => $conversation->technician_id,
            'conversation_id'               => $conversation->id,
            'device'                        => $validated['device'],
            'device_type'                   => $validated['device_type'],
            'serial_number'                 => $request->serial_number,

            'disclaimer'                   => $disclaimerText,
            'status'                       => 'in_progress',

            'issues'                        => $issuesJson,
            'breakdown'                     => $breakdownJson,
            'estimated_cost'                => $validated['estimated_cost_data'],
            'completion_date'               => $request->completion_date,

            // Status & Confirmation Details
            'is_received'                   => $request->boolean('is_received'),
            'receive_notes'                 => $request->receive_notes,

            // ✅ THE CORRECTED LINE: Assigining the file path to the database column
            'receive_file_path'             => $receiveFilePath,

            'client_final_confirmation'     => $request->boolean('client_final_confirmation'),
            'confirmation_signature_path'   => $signaturePath,
            'confirmation_date'             => now()
        ]);

        RepairRating::create([
            'user_id' => $conversation->user_id,
            'technician_id' => $conversation->technician_id,
            'repair_id' => $repair->id
        ]);


        ConversationMessage::create(
            [
                'conversation_id' => $conversation->id,
                'user_id' => $conversation->user_id,
                'technician_user_id' => $conversation->technician_user_id,
                'sender_id' => Auth::id(),
                'message' => Crypt::encryptString('*'),
                'repair_id' => $repair->id,
            ]
        );

        // --- 5. Handle Confirmation (PDF, Email, Notification) ---
        if ($repair->client_final_confirmation) {
            // NOTE: Assuming 'processClientConfirmation' is defined elsewhere in the controller
            return $this->processClientConfirmation($repair);
        }

        // --- 6. Final JSON Response ---
        return response()->json([
            'message' => 'Repair request created successfully!',
            'repair' => $repair,
            'signature_path' => $signaturePath,
        ], 201);
    }



// In App\Http\Controllers\Table\RepairController.php

// ... (Your existing methods like show, update, addBreakdown)

// --- Helper Methods ---

    /**
     * 🛠️ Helper method to handle Base64 decoding and storage.
     * @param string|null $base64Data
     * @param int $userId
     * @return string|null The relative path to the saved file or null.
     */
    protected function handleBase64Signature(?string $base64Data, int $userId): ?string
    {
        if (!$base64Data) {
            return null;
        }

        $base64ImageParts = explode(';base64,', $base64Data);

        // Check if the Base64 part exists
        if (count($base64ImageParts) < 2) {
            return null;
        }

        $base64Image = end($base64ImageParts);
        $imageBinary = base64_decode($base64Image);

        if ($imageBinary === false) {
            return null;
        }

        $directory = 'signatures';
        $filename = 'sig_u' . $userId . '_' . date('Ymd') . '_' . Str::random(10) . '.jpeg';
        $signaturePath = $directory . '/' . $filename;

        // Save the binary image data to the public disk
        Storage::disk('public')->put($signaturePath, $imageBinary);

        return $signaturePath;
    }


    /**
     * 🛠️ Helper method to handle PDF generation, notification, and email sending after confirmation.
     * @param Repair $repair
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function processClientConfirmation(Repair $repair)
    {
        // Define the path for the PDF in public storage
        $filePath = 'repair-orders/repair_order_' . $repair->id . '.pdf';

        // Create directory if not exists
        $directoryPath = storage_path('app/public/repair-orders');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0775, true);
        }

        // Generate and Save the PDF
        $pdf = $this->generateRepairOrderPdf($repair);
        $pdf->save(storage_path('app/public/' . $filePath));

        // Store the file path in the database
        $repair->order_slip_path = $filePath;
        $repair->save();

        // Notification and Email Logic
        $clientEmail = $repair->user->email;
        $subject = 'Repair Order Accepted!';
        $message = 'Your repair order has been confirmed by ' .
            $repair->user->first_name . ' ' .
            $repair->user->middle_name . ' ' .
            $repair->user->last_name . ' ' .
            '. Please find the attached PDF document for more details.';

        // Create notification for the technician
        Notification::create([
            'recipient' => $repair->technician->user->id,
            'subject' => $subject,
            'description' => $message,
            'notifiable_type' => 'App\Models\Repair',
            'notifiable_id' => $repair->id,
            'has_seen' => false,
        ]);

        // Send email to the client
        if (config('custom.enable_email_sender')) {
            $this->emailController->emailSendNotification(
                $clientEmail,
                $subject,
                $message,
                [
                    'attachment' => storage_path('app/public/' . $filePath),
                    'path' => $repair->order_slip_path,
                ],
                'mail.repair-order-confirmation-email'
            );
        }

        // Use a standard redirect response since this is usually called after the form submission
        return redirect()->back()->with('status', 'Repair order accepted successfully. PDF generated at: ' . Storage::url($filePath));
    }







































    public function store2(Request $request)
    {

        $validated = $request->validate([
            'device'           => 'required|string|max:100',
            'device_type'      => 'required|string|max:100',
        ]);

        $conversation = Conversation::find($request->conversation_id);

        $repair = Repair::create([
            'user_id'         =>  $conversation->user_id,
            'technician_id'   =>  $conversation->technician_id,
            'conversation_id' =>  $request->conversation_id,
            'issue'           => $request->issue,
            'description'     => $request->description,
            'device'          => $request->device,
            'device_type'     => $request->device_type,
            'estimated_cost' => $request->estimated_cost,
            'completion_date' => $request->completion_date,
        ]);

        return response()->json($repair);
    }

    /**
     * Display the specified resource.
     */
    public function show(Repair $repair)
    {

        if ($repair->status == 'pending') {
            abort(403, 'This is a pending repair initiation, it must be accepted first or declined.');
        }
        if ($repair->status == 'declined') {
            abort(403, 'This repair initiation has been declined.');
        }


        $technician = Technician::where('id', $repair->technician_id)->first();
        $technician_user = User::where('id', $technician->technician_user_id)->first();
        $client_user = User::where('id', $repair->user_id)->first();

        if (
            (Auth::user()->is_technician && $repair->technician_id != $technician->id)
            ||
            (!Auth::user()->is_technician && $repair->user_id != Auth::id())
        ) {
            abort(404);
        }

        // Load pending cancel requests and related requestor
        $repair->load(['pendingCancelRequests']);

        $repair_progress = RepairProgress::where('repair_id', $repair->id)
            ->orderBy('id', 'desc')->get();

        return view('repair.show', [
            'repair' => $repair,
            'repair_progress' => $repair_progress,
            'technician' => $technician,
            'technician_user' => $technician_user,
            'client_user' => $client_user
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repair $repair)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repair $repair)
    {
        $request->validate([
            'issue' => 'string|required|max:255',
            'description' => 'required|string',
            'device' => 'required|string|max:255',
            'device_type' => 'required|string|max:255',
            'status' => 'required|in:pending,in progress,completed',
        ]);


        $repair->update([
            'issue' => $request->issue,
            'description' => $request->description,
            'device' => $request->device,
            'device_type' => $request->device_type,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Repair updated successfully.');
    }


    public function addBreakdown(Request $request, Repair $repair)
    {
        $data = $request->validate([
            'serial_number' => 'required|string|max:50',
            'completion_date' => 'nullable|date',
            'issues' => 'required|array|min:1',
            'issues.*.issue' => 'required|string',
            'breakdown' => 'required|array|min:1',
            'breakdown.*.item' => 'required|string',  // <-- changed to "item"
            'breakdown.*.price' => 'required|numeric|min:0',
            'estimated_cost' => 'required|numeric|min:0',
        ]);

        $repair->serial_number = $data['serial_number'];
        $repair->completion_date = $data['completion_date'];
        $repair->issues = json_encode($data['issues']);
        $repair->breakdown = json_encode($data['breakdown']);
        $repair->estimated_cost = $data['estimated_cost'];
        $repair->status = 'in_progress';

        $repair->save();


        $client = User::find($repair->user_id);
        $technician = Technician::with('user')->find($repair->technician_id);
        $linkURL = route('repairs.show', $repair->id);

        Notification::create([
            'recipient' => $client->id,
            'subject' => 'Breakdown for your repair sent!',
            'description' => 'Your repair breakdown has been sent by ' .
                $technician->user->first_name . ' ' .
                $technician->user->middle_name . ' ' .
                $technician->user->last_name,
            'notifiable_type' => 'App\Models\Repair',
            'notifiable_id' => $repair->id,
            'has_seen' => false,
        ]);

        try {
            if (config('custom.enable_email_sender')) {
                $this->emailController->emailSendNotification(
                    $client->email,
                    'Breakdown for your repair sent!',
                    'Your repair breakdown has been sent by ' .
                        $technician->user->first_name . ' ' .
                        $technician->user->middle_name . ' ' .
                        $technician->user->last_name,
                    [
                        'link' => $linkURL,
                        'device' => $repair->device,
                        'device_type' => $repair->device_type
                    ],
                    'mail.repair-breakdown-email'
                );
            }
        } catch (Exception $e) {
            // Log the exception or handle it accordingly
            Log::error('Email send for breakdown failed: ' . $e->getMessage());
            // Optionally, you can add custom error handling here (e.g., notify the admin, retry logic, etc.)
        }

        return response()->json(['message' => 'Breakdown added successfully']);
    }


    // public function clientFinalConfirmation(Request $request, $id)
    // {
    //     $repair = Repair::findOrFail($id);

    //     // Optional: check if the user is allowed to confirm this repair
    //     if (auth()->id() !== $repair->user_id) {
    //         abort(403);
    //     }

    //     $request->validate([
    //         'client_final_confirmation' => 'required|boolean',
    //     ]);

    //     // Set the confirmation status
    //     $repair->client_final_confirmation = $request->client_final_confirmation;
    //     $repair->save();

    //     // If confirmation is true (accepted), generate the PDF and store it
    //     if ($repair->client_final_confirmation) {
    //         // Define the path for the PDF in public storage
    //         $filePath = 'repair-orders/repair_order_' . $repair->id . '.pdf';

    //         // Check if the directory exists and create it if not
    //         $directoryPath = storage_path('app/public/repair-orders');
    //         if (!file_exists($directoryPath)) {
    //             mkdir($directoryPath, 0775, true); // Create directory with appropriate permissions
    //         }

    //         // Generate the PDF
    //         $pdf = $this->generateRepairOrderPdf($repair);

    //         // Save the PDF to the storage/public folder
    //         $pdf->save(storage_path('app/public/' . $filePath));

    //         // Store the file path in the database
    //         $repair->order_slip_path = $filePath;
    //         $repair->save();

    //         // Return success message with PDF URL as a flash message
    //         return redirect()->back()->with('status', 'Repair order accepted successfully. PDF generated at: ' . Storage::url($filePath));
    //     }

    //     return redirect()->back()->with('status', 'Repair breakdown ' . ($request->client_final_confirmation ? 'accepted' : 'declined') . ' successfully.');
    // }
    public function clientFinalConfirmation(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        // Optional: check if the user is allowed to confirm this repair
        // if (auth()->id() !== $repair->user_id) {
        //     abort(403);
        // }

        $request->validate([
            'client_final_confirmation' => 'required|boolean',
        ]);

        // If user DID NOT confirm (false or 0)
        if (!$request->client_final_confirmation) {
            $repair->status = 'cancelled';
            $repair->is_cancelled = 1;
        } else {
            // If user confirmed
            $repair->status = 'in_progress'; // or whatever your normal status is
            $repair->confirmation_date = now();
            $repair->is_cancelled = 0; // optional
        }
        // Set the confirmation status
        $repair->client_final_confirmation = $request->client_final_confirmation;
        $repair->save();

        // If confirmation is true (accepted), generate the PDF and store it
        if ($repair->client_final_confirmation) {
            // Define the path for the PDF in public storage
            $filePath = 'repair-orders/repair_order_' . $repair->id . '.pdf';

            // Check if the directory exists and create it if not
            $directoryPath = storage_path('app/public/repair-orders');
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0775, true); // Create directory with appropriate permissions
            }

            // Generate the PDF
            $pdf = $this->generateRepairOrderPdf($repair);

            // Save the PDF to the storage/public folder
            $pdf->save(storage_path('app/public/' . $filePath));

            // Store the file path in the database
            $repair->order_slip_path = $filePath;
            $repair->save();


            $clientEmail = $repair->user->email;
            $technicianEmail = $repair->technician->user->email;
            $subject = 'Repair Order Accepted!';
            $message = 'Your repair order has been confirmed by ' .
                $repair->user->first_name . ' ' .
                $repair->user->middle_name . ' ' .
                $repair->user->last_name . ' ' .
                '. Please find the attached PDF document for more details.';

            // Create notification for the user
            Notification::create([
                'recipient' => $repair->technician->user->id,
                'subject' => $subject,
                'description' => $message,
                'notifiable_type' => 'App\Models\Repair',
                'notifiable_id' => $repair->id,
                'has_seen' => false,
            ]);

            // Send email to the client
            if (config('custom.enable_email_sender')) {
                $this->emailController->emailSendNotification(
                    $clientEmail,
                    $subject,
                    $message,
                    [
                        'attachment' => storage_path('app/public/' . $filePath),
                        'path' => $repair->order_slip_path,
                    ],
                    'mail.repair-order-confirmation-email' // Specify the view for the email template
                );
            }

            // Return success message with PDF URL as a flash message
            return redirect()->back()->with('status', 'Repair order accepted successfully. PDF generated at: ' . Storage::url($filePath));
        }

        return redirect()->back()->with('status', 'Repair breakdown ' . ($request->client_final_confirmation ? 'accepted' : 'declined') . ' successfully.');
    }







    public function generateRepairOrderPdf($repair)
    {
        // Pass the repair data to the view and generate the PDF
        return Pdf::loadView('repair.repair_order_pdf', compact('repair'))
            ->setPaper('a4')  // Set paper size to A4
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);  // Enable HTML5 and PHP parsing
    }



    // public function claimed(Request $request, Repair $repair)
    // {
    //     if (!$repair->is_cancelled) {
    //         $validated = $request->validate([
    //             'completion_confirmed' => 'required|boolean',
    //         ]);
    //         $completionConfirmed = $validated['completion_confirmed'];
    //     } else {
    //         $completionConfirmed = null;
    //     }

    //     $repair->update([
    //         'completion_confirmed' => $completionConfirmed,
    //         'is_claimed' => true, // force this to be true
    //     ]);

    //     return redirect()->back()->with('success', 'Repair marked as claimed.');
    // }

    /**
     * Handles the final step where the client confirms they have claimed the device.
     * Also validates and saves the required client e-signature.
     */
    public function claimed(Request $request, Repair $repair)
    {
        Log::info('CLAIMED METHOD START: Processing repair ID: ' . $repair->id);
        Log::info('Logged-in User ID: ' . Auth::id() . ' | Client User ID: ' . $repair->user_id . ' | Technician ID: ' . $repair->technician_id);


        // 1. **REVISED AUTHORIZATION CHECK**: Allow the client OR the assigned technician (via the technician->user relationship)
        // NOTE: This assumes your Technician model has a 'user' relationship pointing to the User model.
        $isAuthorized = (
            Auth::id() === $repair->user_id || // Is the logged-in user the client?
            (Auth::user()->is_technician && Auth::user()->technician->id === $repair->technician_id) // Is the logged-in user the assigned technician?
        );

        if (!$isAuthorized) {
            Log::warning('CLAIMED METHOD: Unauthorized attempt by user ID ' . Auth::id() . ' on repair ID ' . $repair->id . '. Access denied.');
            return redirect()->back()->with('error', 'Unauthorized action. You are not the client or the assigned technician for this repair.');
        }

        // Log success before proceeding
        Log::info('CLAIMED METHOD: Authorization passed for user ID: ' . Auth::id());

        $validationRules = [];
        $completionConfirmed = null;
        $signaturePath = null;

        // 2. Conditional Validation: Only require confirmation of success and signature if the repair was NOT cancelled.
        if (!$repair->is_cancelled) {
            $validationRules['completion_confirmed'] = 'required|boolean';
            $validationRules['confirmation_signature_data'] = 'required|string';
        }

        // Validate the request (execution stops here if validation fails)
        try {
            $validated = $request->validate($validationRules);
            Log::info('CLAIMED METHOD: Validation passed.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('CLAIMED METHOD: Validation failed: ' . json_encode($e->errors()));
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        // 3. Process data if the repair was not cancelled
        if (!$repair->is_cancelled) {
            $completionConfirmed = $validated['completion_confirmed'];

            // Handle and save the Base64 signature using the existing helper method.
            $signatureData = $validated['confirmation_signature_data'];
            Log::info('CLAIMED METHOD: Attempting to save signature...');
            $signaturePath = $this->handleBase64Signature($signatureData, $repair->user_id);

            Log::info('CLAIMED METHOD: Signature path returned: ' . ($signaturePath ?: 'NULL (Failed)'));

            if (!$signaturePath) {
                Log::error('CLAIMED METHOD: Signature saving failed for repair ID ' . $repair->id);
                return redirect()->back()->withErrors(['signature' => 'Failed to process signature data. Please ensure you have signed.']);
            }
        }

        // 4. Update the Repair record
        $updateData = [
            'completion_confirmed' => $completionConfirmed,
            'is_claimed' => true,
            'claim_signature_path' => $signaturePath, // <-- CONFIRMED: Correct column name
            'claim_date' => now(),
            'updated_at' => now(),
        ];

        Log::info('CLAIMED METHOD: Database update data: ' . json_encode($updateData));

        $repair->update($updateData);

        Log::info('CLAIMED METHOD: Database update successful for repair ID ' . $repair->id);

        // 5. Notify the technician (assuming models are imported)
        if ($repair->technician) {
            $technician = $repair->technician->user;
            Notification::create([
                'recipient' => $technician->id,
                'subject' => 'Device Claimed by Client',
                'description' => 'The client has claimed the device for repair ID ' . $repair->id . '.',
                'notifiable_type' => 'App\Models\Repair',
                'notifiable_id' => $repair->id,
                'has_seen' => false,
            ]);
            Log::info('CLAIMED METHOD: Notification sent to technician ' . $technician->id);
        }

        Log::info('CLAIMED METHOD END: Successfully claimed repair ID ' . $repair->id);

        return redirect()->back()->with('success', 'Repair marked as claimed and signature saved.');
    }



    public function received(Request $request, $repairId)
    {

        $request->validate([
            'is_received' => 'required|boolean',
            'receive_notes' => 'nullable|string',
            'receive_file' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,wmv,flv,mkv|max:25600', // 25MB max
        ]);

        $repair = Repair::findOrFail($repairId);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('receive_file')) {
            $file = $request->file('receive_file');
            $filePath = $file->store('repair_received_files', 'public');
        }

        // Update repair
        $repair->is_received = true;
        $repair->recieve_file_path = $filePath;
        $repair->receive_notes = $request->input('receive_notes');
        $repair->save();

        return redirect()->route('repairs.show', $repair->id)
            ->with('success', 'Device received successfully!');
    }

    // public function received(Request $request, $repairId)
    // {
    //     $repair = Repair::findOrFail($repairId);

    //     // Set 'is_received' to true
    //     $repair->is_received = true;
    //     $repair->save();

    //     return redirect()->route('repairs.show', $repair->id)
    //         ->with('success', 'Device received successfully!');
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repair $repair)
    {
        //
    }
}
