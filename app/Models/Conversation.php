<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_code',
        'user_id',
        'technician_user_id',
        'technician_id',
        'topic',
        'last_message_at',
        'created_at',
        'updated_at',
        'c_last_seen',
        't_last_seen',
        'c_is_notified',
        't_is_notified',
        // Add other fields as per your migration
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function getRouteKeyName()
    {
        return 'conversation_code';
    }

    /**
     * A conversation belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technicianUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_user_id');
    }


    /**
     * A conversation belongs to a technician.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * A conversation can have many messages.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class);
    }

    /**
     * A conversation can be associated with a repair.
     */
    public function repair(): HasOne
    {
        return $this->hasOne(Repair::class);
    }
}
