<?php

namespace App\Http\Controllers\Table;

use App\Models\Conversation;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\ConversationMessage;
use App\Models\Notification;
use App\Models\Repair;
use App\Models\Technician;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConversationController extends Controller
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
    // public function index()
    // {
    //     if (Auth::user()->is_technician) {
    //         $conversations = Conversation::where('technician_user_id', Auth::id())->get();
    //         $is_technician = true;
    //     } else {
    //         $conversations = Conversation::where('user_id', Auth::id())->get();
    //         $is_technician = false;
    //     }

    //     return view('conversation.lists', ['conversations' => $conversations, 'is_technician' => $is_technician]);
    // }

    public function index()
    {
        // Fetch the conversations for the logged-in user or technician
        if (Auth::user()->is_technician) {
            $conversations = Conversation::where('technician_user_id', Auth::id())->with('user', 'technician.user')->get();
            $is_technician = true;
        } else {
            $conversations = Conversation::where('user_id', Auth::id())->with('user', 'technician.user')->get();
            $is_technician = false;
        }

        // Get the online status for each conversation participant
        $onlineStatuses = $this->getOnlineStatuses($conversations, $is_technician);

        Log::info('Conversations:', $conversations->toArray());

        // Return the view with conversations and online statuses
        return view('conversation.lists', [
            'conversations' => $conversations,
            'is_technician' => $is_technician,
            'onlineStatuses' => $onlineStatuses
        ]);
    }


    private function getOnlineStatuses($conversations, $is_technician)
    {
        $onlineStatuses = [];

        foreach ($conversations as $conversation) {
            // Get the user_id or technician_user_id depending on whether you're the user or technician
            $userId = $is_technician ? $conversation->user_id : $conversation->technician_user_id;

            // Get the session for the given user (technician or user)
            $session = DB::table('sessions')->where('user_id', $userId)->first();

            // If a session is found, check the last activity time to determine online status
            if ($session) {
                $lastActivity = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                $threshold = now()->subMinutes(5);  // Define 5 minutes as the threshold

                // If the last activity was within 5 minutes, mark them as online
                $onlineStatuses[$conversation->id] = $lastActivity > $threshold;
            } else {
                // If no session is found, the user is considered offline
                $onlineStatuses[$conversation->id] = false;
            }
        }

        return $onlineStatuses;
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
        $technician = Technician::where('technician_code', $request->technician_code)->first();

        if (!$technician) {
            abort(404);
        }

        $user = User::where('id', Auth::id())->first();

        if ($user->is_technician) { // Corrected this line
            $tech = Technician::where('technician_user_id', Auth::id())->first();
            $conversation = Conversation::where('user_id', 13)->where('technician_id', $tech->id)->first();
        } else {
            $conversation = Conversation::where('user_id', Auth::id())->where('technician_id', $technician->id)->first();
        }


        if ($conversation) {
            return redirect()->route('conversations.show', ['conversation' => $conversation->conversation_code]);
        }

        $new_conversation = Conversation::create([
            'conversation_code' => "TEMP",
            'user_id' => Auth::id(),
            'technician_id' => $technician->id,
            'technician_user_id' => $technician->technician_user_id,
        ]);

        $timestamp = strtotime($new_conversation->created_at); // integer timestamp
        $hashids = new Hashids();

        $code = $hashids->encode($new_conversation->id)
            . Str::upper(Str::random(4))
            . $hashids->encode($timestamp);

        $new_conversation->update(['conversation_code' => $code]);

        if ($new_conversation) {
            return redirect()->route('conversations.show', ['conversation' => $code]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        //updates the notified status to seen and set it to default
        // $conversation = Conversation::find($conversation->id);

        if (Auth::user()->is_technician) {
            $conversation->t_last_seen = now();
            $conversation->t_is_notified = false;
            $conversation->save();
        } else {
            $conversation->c_last_seen = now();
            $conversation->c_is_notified = false;
            $conversation->save();
        }


        // --- Message Fetching ---
        $limit = 30; // Define your limit clearly

        // Fetch one MORE message than the limit to check if a "next page" exists
        $messages = ConversationMessage::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'desc')
            ->take($limit + 1) // 🔑 Fetch 26 messages if available
            ->get();

        // Check if the actual count exceeded the limit
        $has_more_messages = $messages->count() > $limit;

        // Remove the extra message to keep the view at the defined limit (25)
        if ($has_more_messages) {
            $messages = $messages->slice(0, $limit);
        }

        // Reverse the order to display the most recent message at the end
        $messages = $messages->reverse();

        $messages->map(function ($message) {
            // Attach the repair object if repair_id is present
            if ($message->repair_id) {
                $message->repair = Repair::find($message->repair_id);
            } else {
                $message->repair = null;
            }

            return $message;
        });

        $client = User::find($conversation->user_id);
        $technician = User::with('technician')->find($conversation->technician_user_id);


        if ($conversation->user_id == Auth::id() || $conversation->technician_user_id == Auth::id()) {
            return view('conversation.conversation', [
                'messages' => $messages,
                'conversation' => $conversation,
                'client' => $client,
                'technician' => $technician,
                // 🔑 Pass the boolean flag indicating if more messages exist
                'is_all' => !$has_more_messages,
                // NOTE: We are using a variable that tells us if we AREN'T viewing all.
                // If has_more_messages is TRUE, 'is_all' is FALSE, and the button shows.
                // If has_more_messages is FALSE, 'is_all' is TRUE, and the button hides. 
            ]);
        }

        abort(404, 'Conversation not found');
    }







    public function showAll(Conversation $conversation)
    {
        //updates the notified status to seen and set it to default
        // $conversation = Conversation::find($conversation->id);

        if (Auth::user()->is_technician) {
            $conversation->t_last_seen = now();
            $conversation->t_is_notified = false;
            $conversation->save();
        } else {
            $conversation->c_last_seen = now();
            $conversation->c_is_notified = false;
            $conversation->save();
        }


        $messages = ConversationMessage::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'desc')  // Get the latest messages first
            ->get();

        // Reverse the order to display the most recent message at the end
        $messages = $messages->reverse();

        $messages->map(function ($message) {
            // Attach the repair object if repair_id is present
            if ($message->repair_id) {
                $message->repair = Repair::find($message->repair_id);
            } else {
                $message->repair = null;
            }

            return $message;
        });

        $client = User::find($conversation->user_id);
        $technician = User::with('technician')->find($conversation->technician_user_id);


        if ($conversation->user_id == Auth::id() || $conversation->technician_user_id == Auth::id()) {
            return view('conversation.conversation', [
                'messages' => $messages,
                'conversation' => $conversation,
                'client' => $client,
                'technician' => $technician,
                'is_all' => true,
            ]);
        }

        abort(404, 'Conversation not found');
    }












    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }

    /**
     * Update the 'c_last_seen' or 't_last_seen' timestamp for the conversation.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLastSeen(string $id)
    {
        $conversation = Conversation::with(['user', 'technicianUser'])
            ->find($id);

        if (!$conversation) {
            return response()->json(['status' => 'error', 'message' => 'Conversation not found.'], 404);
        }

        $linkURL = route('conversations.show', $conversation->conversation_code);


        $user = Auth::user();

        // Check if the authenticated user is the customer
        if ($user->id === $conversation->user_id) {
            // Check if the customer has already been notified.
            if ($conversation->t_is_notified) {
                // Customer has been notified, so we log and do nothing.
                return response()->json(['status' => 'success', 'message' => 'Customer already notified. Log as temporary.']);
            }
            $conversation->t_last_seen = now();
            $conversation->t_is_notified = true;
            $conversation->save();


            Notification::create([
                'recipient' => $conversation->technician_user_id,
                'subject' => 'New Message from ' .
                    $conversation->user->first_name . ' ' .
                    $conversation->user->middle_name . ' ' .
                    $conversation->user->last_name,
                'description' => '',
                'notifiable_type' => 'App\Models\Conversation',
                'notifiable_id' => $id,
                'has_seen' => false,
            ]);
            if (config('custom.enable_email_sender')) {
                $this->emailController->emailSendNotification(
                    $conversation->technicianUser->email,
                    'New Message from -> ' .
                        $conversation->user->first_name . ' ' .
                        $conversation->user->middle_name . ' ' .
                        $conversation->user->last_name,
                    '',
                    ['link' => $linkURL],
                    'mail.new-message-email'
                );
            }

            return response()->json(['status' => 'success', 'updated_field' => 'c_last_seen', 'notification_status' => 'c_is_notified']);
        }

        // Check if the authenticated user is the technician
        if ($user->id === $conversation->technician_user_id) {
            // Check if the technician has already been notified.
            if ($conversation->c_is_notified) {
                // Technician has been notified, so we log and do nothing.
                return response()->json(['status' => 'success', 'message' => 'Technician already notified. Log as temporary.']);
            }
            $conversation->c_last_seen = now();
            $conversation->c_is_notified = true;
            $conversation->save();

            Notification::create([
                'recipient' => $conversation->user_id,
                'subject' => 'New Message from -> ' .
                    $conversation->technicianUser->first_name . ' ' .
                    $conversation->technicianUser->middle_name . ' ' .
                    $conversation->technicianUser->last_name,
                'description' => '',
                'notifiable_type' => 'App\Models\Conversation',
                'notifiable_id' => $id,
                'has_seen' => false,
            ]);

            Log::info("\n----updateLastSeen Technician is passed!----\n");


            if (config('custom.enable_email_sender')) {
                $this->emailController->emailSendNotification(
                    $conversation->user->email,
                    'New Message from -> ' .
                        $conversation->technicianUser->first_name . ' ' .
                        $conversation->technicianUser->middle_name . ' ' .
                        $conversation->technicianUser->last_name,
                    '',
                    ['link' => $linkURL],
                    'mail.new-message-email'
                );
            }

            return response()->json(['status' => 'success', 'updated_field' => 't_last_seen', 'notification_status' => 't_is_notified']);
        }

        Log::info("\n----updateLastSeen is passed!----\n");


        // If the user is neither the customer nor the technician
        return response()->json(['status' => 'error', 'message' => 'Unauthorized access.'], 403);
    }
}
