<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepairProgress extends Model
{
    use HasFactory;

    protected $table = 'repair_progress'; // Explicitly define table name

    protected $fillable = [
        'repair_id',
        'description',
        'completion_rate',
        'progress_status',
        'progress_file_path',
        'created_at',
        'updated_at',


        // Add other fields as per your migration
    ];

    protected function casts(): array
    {
        return [
            // 'created_at' and 'updated_at' are handled by Laravel by default
        ];
    }

    /**
     * A repair progress update belongs to a repair.
     */
    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class);
    }
}
