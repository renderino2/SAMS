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
     * Get all segments for this attendance record.
     */
    public function segments()
    {
        return $this->hasMany(AttendanceSegment::class)->orderBy('segment_order');
    }

    /**
     * Sync the parent attendance record from its segments.
     * time_in / photo = first segment, time_out / photo = latest segment with a time_out.
     * total_minutes = sum of all segment minutes.
     */
    public function syncFromSegments()
    {
        $segments = $this->segments()->orderBy('segment_order')->get();

        if ($segments->isEmpty()) {
            return;
        }

        $first = $segments->first();
        $latestWithTimeOut = $segments->whereNotNull('time_out')->last();

        $this->time_in = $first->time_in;
        $this->time_in_photo = $first->time_in_photo;

        if ($latestWithTimeOut) {
            $this->time_out = $latestWithTimeOut->time_out;
            $this->time_out_photo = $latestWithTimeOut->time_out_photo;
        }

        $this->total_minutes = $segments->sum('total_minutes') ?: 0;
        $this->save();
    }

    /**
     * Build expected segments array from the user's schedule.
     * Returns an array of [{expected_time_in, expected_time_out}, ...].
     */
    public static function buildExpectedSegments($user)
    {
        $scheduledIn = $user->scheduled_time_in;
        $scheduledOut = $user->scheduled_time_out;

        if (!$scheduledIn || !$scheduledOut) {
            return [];
        }

        $breaks = $user->work_schedule;
        if (is_string($breaks)) {
            $breaks = json_decode($breaks, true);
        }

        if (!$breaks || !is_array($breaks) || count($breaks) === 0) {
            return [[
                'expected_time_in' => $scheduledIn,
                'expected_time_out' => $scheduledOut,
            ]];
        }

        usort($breaks, function ($a, $b) {
            return strcmp($a['time_in'], $b['time_in']);
        });

        $segments = [];
        $cursor = $scheduledIn;

        foreach ($breaks as $brk) {
            $breakStart = strlen($brk['time_in']) === 5 ? $brk['time_in'] . ':00' : $brk['time_in'];
            $breakEnd   = strlen($brk['time_out']) === 5 ? $brk['time_out'] . ':00' : $brk['time_out'];

            if ($cursor < $breakStart) {
                $segments[] = [
                    'expected_time_in'  => $cursor,
                    'expected_time_out' => $breakStart,
                ];
            }
            $cursor = $breakEnd;
        }

        if ($cursor < $scheduledOut) {
            $segments[] = [
                'expected_time_in'  => $cursor,
                'expected_time_out' => $scheduledOut,
            ];
        }

        return $segments;
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
