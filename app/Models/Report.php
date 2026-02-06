<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'user_id',
        'reported_user_id',
        'category_id',
        'description',
        'status',
        'admin_notes',
        'is_admin_report',
        'file_path',
    ];

    /**
     * The user who submitted the report.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The user who was reported.
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    /**
     * The category of the report.
     */
    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function adminActions()
    {
        return $this->hasMany(AdminAction::class)->orderBy('created_at', 'desc');
    }
}
