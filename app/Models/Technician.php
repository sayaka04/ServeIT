<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_user_id',
        'technician_code',
        'shop_id',
        'availability_start',
        'availability_end',
        'address',
        'longitude',
        'latitude',
        'tesda_verified',
        'home_service',
        'tesda_first_four',
        'tesda_last_four',
        'jobs_completed',
        'weighted_score_rating',
        'success_rate',
        'banner_picture',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            // Define casts if needed
        ];
    }

    public function getRouteKeyName()
    {
        return 'technician_code';
    }


    /**
     * A technician belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_user_id');
    }

    public function expertises(): BelongsToMany
    {
        return $this->belongsToMany(
            ExpertiseCategory::class,
            'technician_expertise', // The name of the pivot table
            'technician_id',        // The foreign key on the pivot table pointing to this model
            'expertise_category_id' // The foreign key on the pivot table pointing to the ExpertiseCategory model
        )->using(TechnicianExpertise::class) // Tell Eloquent to use your custom pivot model
            ->withTimestamps();
    }



    /**
     * A technician belongs to a shop.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * A technician can have many technician links.
     */
    public function technicianLinks(): HasMany
    {
        return $this->hasMany(TechnicianLink::class);
    }

    /**
     * A technician can have many technician files.
     */
    public function technicianFiles(): HasMany
    {
        return $this->hasMany(TechnicianFile::class);
    }

    /**
     * A technician can be part of many conversations.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'technician_id');
    }

    /**
     * A technician can be assigned many repairs.
     */
    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class, 'technician_id');
    }

    /**
     * A technician can receive many repair ratings.
     */
    public function repairRatings(): HasMany
    {
        return $this->hasMany(RepairRating::class, 'technician_id');
    }

    /**
     * A technician can have many expertise categories. (The new relationship!)
     */
    public function expertiseCategories(): BelongsToMany
    {
        return $this->belongsToMany(ExpertiseCategory::class, 'technician_expertise')
            ->withPivot('is_archived')
            ->withTimestamps();
    }

    public function activeExpertiseCategories(): BelongsToMany
    {
        return $this->belongsToMany(ExpertiseCategory::class, 'technician_expertise')
            ->withPivot('is_archived')
            ->wherePivot('is_archived', 0)
            ->withTimestamps();
    }
}
