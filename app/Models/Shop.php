<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'email',
        'longitude',
        'latitude',
        'is_archived',
        'created_at',
        'updated_at',
        // Add other shop-specific fields as per your migration
    ];

    protected function casts(): array
    {
        return [
            // Define casts if needed (e.g., 'is_active' => 'boolean')
        ];
    }

    /**
     * A shop can have many technicians.
     */
    public function technicians(): HasMany
    {
        return $this->hasMany(Technician::class);
    }
}
