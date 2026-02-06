<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicianExpertise extends Model
{
    use HasFactory;

    // Explicitly define the pivot table name
    protected $table = 'technician_expertise';

    /**
     * The attributes that are mass assignable.
     * Only the foreign keys are needed since we decided against the 'level' column for now.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'technician_id',
        'expertise_category_id',
        'is_archived',
    ];

    // Note: The timestamps are automatically handled by the Model base class.

    /**
     * This record belongs to a specific Technician.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }

    /**
     * This record points to a specific ExpertiseCategory.
     */
    public function expertiseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpertiseCategory::class, 'expertise_category_id');
    }
}
