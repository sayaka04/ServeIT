<?php

namespace App\Http\Controllers;

use App\Events\Converse;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('recipient', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notification.index', compact('notifications'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notification.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notifiable_type' => 'required|string',
            'notifiable_id' => 'required|integer',
        ]);

        Notification::create([
            'recipient' => Auth::id(),
            'subject' => $data['subject'],
            'description' => $data['description'] ?? null,
            'notifiable_type' => $data['notifiable_type'],
            'notifiable_id' => $data['notifiable_id'],
            'has_seen' => false,
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notification = Notification::where('id', $id)
            ->where('recipient', Auth::id())
            ->firstOrFail();

        if (! $notification->has_seen) {
            $notification->has_seen = true;
            $notification->save();
        }

        return view('notification.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }






    public function markAsSeenAndRedirect(string $id)
    {
        $notification = Notification::where('id', $id)
            ->where('recipient', Auth::id())
            ->firstOrFail();

        // Mark the notification as seen
        if (!$notification->has_seen) {
            $notification->has_seen = true;
            $notification->save();
        }

        // Determine the resource route
        $resourceRoute = '';
        switch ($notification->notifiable_type) {
            case 'App\\Models\\Repair':
                $resourceRoute = route('repairs.show', $notification->notifiable_id);
                break;
            case 'App\\Models\\Conversation':
                $conversation = Conversation::find($notification->notifiable_id);
                $resourceRoute = route('conversations.show', $conversation->conversation_code);
                break;
                // Add more cases as needed
        }

        // Redirect to the resource if a route was found
        if ($resourceRoute) {
            return redirect($resourceRoute);
        }

        // Fallback if no resource route is found (e.g., redirect to the notifications list)
        return redirect()->route('notifications.index');
    }
}
