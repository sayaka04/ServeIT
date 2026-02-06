<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairRating extends Model
{
    use HasFactory;

    protected $table = 'repair_ratings'; // Explicitly define table name

    protected $fillable = [
        'user_id', // User who provided the rating (customer)
        'technician_id', // Technician being rated
        'repair_id', // Repair being rated
        'user_weighted_score', // Add user_weighted_score
        'technician_weighted_score', // Add technician_weighted_score
        'user_comment', // Add user_comment
        'technician_comment', // Add technician_comment
    ];

    /**
     * A repair rating belongs to a user (rater).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A repair rating belongs to a technician (rated).
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * A repair rating belongs to a repair.
     */
    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class);
    }

    protected $casts = [
        'user_weighted_score' => 'decimal:2',
        'technician_weighted_score' => 'decimal:2',
    ];
}
