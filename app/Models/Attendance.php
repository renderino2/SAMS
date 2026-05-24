<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'student_id_number',
        'name',
        'office',
        'date',
        'time_in',
        'time_in_photo',
        'time_out',
        'time_out_photo',
        'total_minutes',
        'status',
        'remarks',
        'review_status',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviewer (office head) who reviewed this attendance.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Calculate total minutes worked.
     *
     * @return int|null
     */
    public function calculateTotalMinutes()
    {
        if (!$this->time_in || !$this->time_out) {
            return null;
        }

        try {
            $dateString = $this->date instanceof \Carbon\Carbon 
                ? $this->date->format('Y-m-d') 
                : $this->date;
            
            $timeIn = \Carbon\Carbon::parse($dateString . ' ' . $this->time_in);
            $timeOut = \Carbon\Carbon::parse($dateString . ' ' . $this->time_out);
            
            return $timeOut->diffInMinutes($timeIn);
        } catch (\Exception $e) {
            \Log::error('Error calculating total minutes in model: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get formatted total time (e.g., "8h 30m").
     *
     * @return string
     */
    public function getFormattedTotalTimeAttribute()
    {
        if (!$this->total_minutes) {
            return '--';
        }

        $hours = floor($this->total_minutes / 60);
        $minutes = $this->total_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }
}
