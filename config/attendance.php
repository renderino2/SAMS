<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Attendance photo required
    |--------------------------------------------------------------------------
    |
    | When false, Student Assistants can time in/out without capturing a photo.
    | Set ATTENDANCE_PHOTO_REQUIRED=false in .env for a temporary bypass.
    |
    */

    'photo_required' => env('ATTENDANCE_PHOTO_REQUIRED', true),

    /*
    |--------------------------------------------------------------------------
    | Grace period (minutes)
    |--------------------------------------------------------------------------
    |
    | Number of minutes after the scheduled time before marking as Late.
    | e.g. scheduled 7:30 + 5 min grace = Present until 7:35, Late at 7:36.
    |
    */

    'grace_minutes' => (int) env('ATTENDANCE_GRACE_MINUTES', 5),

    /*
    |--------------------------------------------------------------------------
    | Break window (minutes)
    |--------------------------------------------------------------------------
    |
    | How many minutes before a break's start time a time-out is still
    | considered "on schedule" (not flagged as unscheduled).
    |
    */

    'break_window_minutes' => (int) env('ATTENDANCE_BREAK_WINDOW_MINUTES', 10),

];
