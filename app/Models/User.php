<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',    // From your migration
        'middle_name',   // From your migration
        'last_name',     // From your migration
        'email',
        'phone_number',
        'password',
        'is_admin',
        'is_technician',
        'is_admin_supervisor', // From your migration
        'is_disabled',   // From your migration
        'is_banned',     // From your migration
        'banned_by',
        'banned_at',
        'profile_picture',
        'created_at',
        'updated_at',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_technician' => 'boolean',
            'is_disabled' => 'boolean', // Added cast for new field
            'is_banned' => 'boolean',   // Added cast for new field
        ];
    }

    public function getPhoneNumberAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return unserialize(Crypt::decryptString($value));
        } catch (\Exception $e) {
            return null; // prevents crashes if bad data exists
        }
    }

    /**
     * Check if the user is a technician.
     *
     * @return bool
     */
    public function isTechnician(): bool
    {
        return $this->is_technician; // Checks the 'is_technician' field in the User model
    }



    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * A user can be a technician.
     */
    public function technician(): HasOne
    {
        return $this->hasOne(Technician::class, 'technician_user_id');
    }

    /**
     * A user can initiate or be part of many conversations.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_id'); // Assuming 'user_id' as the foreign key
    }

    /**
     * A user can send many conversation messages.
     */
    public function conversationMessages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class);
    }

    /**
     * A user can create many repairs.
     */
    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class, 'user_id'); // Assuming 'user_id' as the foreign key for the customer
    }

    /**
     * A user can leave many repair ratings.
     */
    public function repairRatings(): HasMany
    {
        return $this->hasMany(RepairRating::class, 'user_id'); // Assuming 'user_id' as the foreign key for the rater
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }


    public function adminActionsTaken()
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }

    public function adminActionsReceived()
    {
        return $this->hasMany(AdminAction::class, 'target_user_id');
    }
}
