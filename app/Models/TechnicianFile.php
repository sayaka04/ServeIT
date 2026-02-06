<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TechnicianFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_id',
        'file_name',
        'file_description',
        'file_type',
        'file_path',
        'is_deleted',
        'created_at',
        'updated_at',
        // Add other fields as per your migration
    ];

    protected function casts(): array
    {
        return [
            // Define casts if needed
        ];
    }

    /**
     * A technician file belongs to a technician.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
