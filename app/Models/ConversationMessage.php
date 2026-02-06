<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConversationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'technician_user_id',
        'sender_id',
        'repair_id',
        'repair_accepted',
        'message',
        'image_name',
        'image_type',
        'image_path',
        'file_name',
        'file_type',
        'file_path',
        'url',
        'url_name',
        'url_domain',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            // 'created_at' and 'updated_at' are handled by Laravel by default
        ];
    }

    /**
     * A conversation message belongs to a conversation.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * A conversation message belongs to a user (sender).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
