<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairCancelRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'requestor_id',
        'approver_id',
        'reason',
        'is_accepted',
    ];

    // Relationships
    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
