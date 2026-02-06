<?php

namespace App\Http\Controllers\Table;

use App\Events\Converse;
use App\Models\ConversationMessage;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Repair;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ConversationMessageController extends Controller
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
        $request->validate([
            'message' => ['required', 'string'],
            'images.*' => ['nullable', 'file', 'image', 'max:5120'],
            'files.*' => ['nullable', 'file', 'mimes:ppt,pptx,pdf,doc,docx,txt,xls,xlsx', 'max:10240'],
            'link_text' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'url', 'max:255'],
        ]);

        $conversation = Conversation::where('conversation_code', $request->conversation_code)->firstOrFail();

        $messageData = [
            'conversation_id' => $conversation->id,
            'user_id' => $conversation->user_id,
            'technician_user_id' => $conversation->technician_user_id,
            'sender_id' => Auth::id(),
            'message' => Crypt::encryptString($request->message),
        ];

        // Handle images (only save first)
        if ($request->hasFile('images')) {
            $image = $request->file('images')[0];
            $imagePath = $image->store('images', 'public');

            $messageData['image_name'] = $image->getClientOriginalName();
            $messageData['image_type'] = $image->getClientMimeType();
            $messageData['image_path'] = $imagePath;
        }

        // Handle files (only save first)
        if ($request->hasFile('files')) {
            $file = $request->file('files')[0];
            $filePath = $file->store('files', 'public');

            $messageData['file_name'] = $file->getClientOriginalName();
            $messageData['file_type'] = $file->getClientMimeType();
            $messageData['file_path'] = $filePath;
        }

        // Handle URL info
        if ($request->link_url && $request->link_text) {
            $messageData['url'] = $request->link_url;
            $messageData['url_name'] = $request->link_text;

            $host = parse_url($request->link_url, PHP_URL_HOST);
            $messageData['url_domain'] = $host;
        }



        $repair = null; // Define it as null in case it's not set in the request

        if ($request->repair_id) {
            $messageData['repair_id'] = $request->repair_id;
            $repair = Repair::find($request->repair_id);
        }


        $message = ConversationMessage::create($messageData);

        $message->is_technician = (Auth::id() == $message->technician_user_id);


        // Decrypt the message before sending the JSON response to the client
        // Use a try-catch block to handle the case where the message might not be encrypted
        try {
            $message->message = Crypt::decryptString($message->message);
        } catch (DecryptException $e) {
            // If decryption fails, the message was likely not encrypted, so we use the original
            Log::warning("Could not decrypt message for ConversationMessage ID: " . $message->id);
        }

        event(new Converse($conversation, $message, $repair));
        Log::info("\n----Event is passed!----\n");



        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully.',
            'data' => $message,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConversationMessage $conversationMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConversationMessage $conversationMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConversationMessage $conversationMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConversationMessage $conversationMessage)
    {
        //
    }
















    //-----------------------------------------------------INCASE----------------------------------------------------//
    // public function newMessage(Request $request)
    // {
    //     // Find the conversation by ID
    //     $conversation = Conversation::findOrFail($request->conversation_id);




    //     $user = User::where('id', Auth::id())->first();

    //     if ($user->is_technician) { // Corrected this line

    //         // Fetch messages that are greater than or equal to the last seen timestamp
    //         $messages = ConversationMessage::where('conversation_id', $conversation->id)
    //             ->where('created_at', '>=', $conversation->t_last_seen)
    //             ->get();

    //         // Update the last seen timestamp for the conversation
    //         $conversation->t_last_seen = now();
    //         $conversation->save();
    //     } else {

    //         // Fetch messages that are greater than or equal to the last seen timestamp
    //         $messages = ConversationMessage::where('conversation_id', $conversation->id)
    //             ->where('created_at', '>=', $conversation->c_last_seen)
    //             ->get();

    //         // Update the last seen timestamp for the conversation
    //         $conversation->c_last_seen = now();
    //         $conversation->save();
    //     }


    //     // Add the 'is_current_user' flag to each message to check if it's from the authenticated user
    //     $messagesWithUserCheck = $messages->map(function ($message) {
    //         // Add 'is_current_user' to the message if the user_id matches the authenticated user
    //         $message->is_current_user = $message->user_id == Auth::id();
    //         return $message;
    //     });

    //     // Return the response with the modified messages
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Messages fetched successfully.',
    //         'data' => $messagesWithUserCheck,
    //     ]);
    // }
}
