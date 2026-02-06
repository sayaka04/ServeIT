<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

Broadcast::channel('users.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('converse.{id}', function ($user, $id) {
    $conversation = Conversation::find($id);

    Log::info('Broadcast Auth Attempt:', [
        'user_id' => $user->id,
        'channel_id' => $id,
        'conversation_user' => $conversation?->user_id,
        'conversation_tech' => $conversation?->technician_user_id,
    ]);

    if (!$conversation) {
        return false;
    }

    return $user->id === $conversation->user_id || $user->id === $conversation->technician_user_id;
});
