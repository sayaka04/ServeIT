<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExpertiseCategory extends Model
{
    use HasFactory;

    // Defines the table name if it doesn't match the model name convention
    protected $table = 'expertise_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_archived',
    ];

    /**
     * An expertise category can be held by many technicians.
     */
    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(
            Technician::class,
            'technician_expertise', // The pivot table name
            'expertise_category_id', // Foreign key on the pivot table pointing to this model
            'technician_id' // Foreign key on the pivot table pointing to the Technician model
        )->withTimestamps();
        // ->withPivot('level'); // You can add this if you decide to add the 'level' column later
    }
}
