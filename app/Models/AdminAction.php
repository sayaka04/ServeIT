<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $fillable = [
        'admin_id',
        'target_user_id',
        'report_id',
        'action_taken',
        'notes',
    ];

    // Relationships

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Optional: Scopes or helpers

    public static function availableActions(): array
    {
        return ['ban', 'unban', 'warn', 'suspend', 'dismiss'];
    }
}
