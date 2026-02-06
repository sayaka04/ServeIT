<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Repair;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Converse implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Conversation $conversation,
        public ConversationMessage $message,
        public ?Repair $repair = null // 👈 allow null
    ) {

        Log::info('🔥 Converse event triggered for conversation: ' . $conversation->id);

        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info('📡 Broadcasting on: converse.' . $this->conversation->id);

        return [
            new PrivateChannel('converse.' . $this->conversation->id),
        ];
    }



    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'user_id' => $this->message->user_id,
                'sender_id' => $this->message->sender_id,
                'repair_id' => $this->message->repair_id,
                'repair_accepted' => $this->message->repair_accepted,
                'message' => $this->message->message,
                'created_at' => $this->message->created_at->toDateTimeString(),

                // Include these if you want them available in realtime updates
                'image_name' => $this->message->image_name,
                'image_path' => $this->message->image_path,
                'file_name' => $this->message->file_name,
                'file_path' => $this->message->file_path,
                'file_type' => $this->message->file_type,
                'url' => $this->message->url,
                'url_name' => $this->message->url_name,
                'url_domain' => $this->message->url_domain,
                'is_technician' => $this->message->is_technician,

            ],

            'repair' => $this->repair ? [
                'id' => $this->repair->id,
                'technician_id' => $this->repair->technician_id,
                'user_id' => $this->repair->user_id,
                'conversation_id' => $this->repair->conversation_id,
                'issue' => $this->repair->issue,
                'description' => $this->repair->description,
                'device' => $this->repair->device,
                'device_type' => $this->repair->device_type,
                'status' => $this->repair->status,
                'estimated_cost' => $this->repair->estimated_cost,
                'is_cancelled' => $this->repair->is_cancelled,
                'is_completed' => $this->repair->is_completed,
                'completion_date' => $this->repair->completion_date,
                'created_at' => $this->repair->created_at,
                'updated_at' => $this->repair->updated_at,
            ] : null,


        ];
    }
}
