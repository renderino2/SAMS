<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSegment extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'segment_order',
        'segment_type',
        'expected_time_in',
        'expected_time_out',
        'time_in',
        'time_in_photo',
        'time_out',
        'time_out_photo',
        'total_minutes',
        'status',
        'remarks',
        'covering_for',
        'covering_request_id',
    ];

    protected $casts = [
        'segment_order' => 'integer',
        'total_minutes' => 'integer',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function coveringForUser()
    {
        return $this->belongsTo(User::class, 'covering_for');
    }
}
