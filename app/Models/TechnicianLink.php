<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TechnicianLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_id',
        'url',
        'type',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            // Define casts if needed
        ];
    }

    /**
     * A technician link belongs to a technician.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
