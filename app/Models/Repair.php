<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Customer who requested the repair
        'technician_id', // Technician assigned to the repair
        'conversation_id', // Related conversation


        //--------------------------------------------------------------------------------------
        //The changes in this major changing below: 3 columns
        'issues',    // NEW: JSON array of multiple issues
        'breakdown', // NEW: JSON for breakdown info
        'serial_number',
        'client_final_confirmation',
        'order_slip_path',
        //--------------------------------------------------------------------------------------
        'confirmation_signature_path', // <--- MUST BE PRESENT!
        'confirmation_date',

        'claim_signature_path', // <--- MUST BE PRESENT!
        'claim_date',

        'disclaimer',
        'device',
        'device_type',
        'status',
        'estimated_cost',
        'is_cancelled',
        'is_completed',

        'is_received',
        'receive_file_path',
        'receive_notes',

        'is_claimed',
        // 'claim_file_path',
        // 'claim_notes',

        'completion_confirmed',
        'completion_date',
        'created_at',
        'updated_at',
        // Add other fields as per your migration
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'price' => 'float',
        ];
    }

    /**
     * A repair belongs to a user (customer).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A repair belongs to a technician.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * A repair belongs to a conversation.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * A repair can have many progress updates.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(RepairProgress::class);
    }

    /**
     * A repair can have one repair rating.
     */
    public function repairRating(): HasOne
    {
        return $this->hasOne(RepairRating::class);
    }

    public function cancelRequests()
    {
        return $this->hasMany(RepairCancelRequest::class);
    }

    // public function pendingCancelRequests()
    // {
    //     return $this->hasMany(RepairCancelRequest::class)
    //         ->whereNull('is_accepted');
    // }

    public function pendingCancelRequests()
    {
        return $this->hasMany(RepairCancelRequest::class)
            ->whereNull('is_accepted')
            ->with('requestor'); // optional, eager load requestor
    }
}
