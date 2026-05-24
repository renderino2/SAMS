<?php

namespace App\Http\Controllers;

use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginView()
    {
        return view('authpages.login', [
            'layout' => 'login',
            'pageTitle' => 'Login'
        ]);
    }

    /**
     * Show register view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerView()
    {
        return view('authpages.register', [
            'layout' => 'login',
            'pageTitle' => 'Register'
        ]);
    }

    /**
     * Authenticate login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // Look up the user by email only
        $user = \App\Models\User::where('email', $request->email)
            ->first();

        if (!$user) {
            return response()->json([   
                'message' => 'User not found.'
            ], 401);
        }

        // Verify password (check both password_hash and password fields)
        $storedPassword = $user->password_hash ?? $user->password;
        if (!password_verify($request->password, $storedPassword)) {
            return response()->json([
                'message' => 'Invalid password.'
            ], 401);
        }
        // 6.751149763924785, 125.35272821270365 sa CJC
        // 6.7561110397199915, 125.35915687266676 samong balay
        // 6.642508532651743, 125.34272271225116 samong legit na balay 
    // 6.756111484588946, 125.35907044729782
    //6.749561718048088, 125.35485563531775 la miggy
       // 6.757634529179423, 125.35345807583727 Grand Mall
        // Geofencing check for Student Assistants only
        // TODO: Re-enable geofencing when location detection is fixed
        
        if ($user->role === 'Student Assistant') {
            $schoolLatitude = 6.642508532651743;
            $schoolLongitude = 125.34272271225116;
            $allowedRadius = 350000000000; // meters

            // Get location from requestage
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // Check if location is provided and valid
            if ($latitude === null || $longitude === null || 
                !is_numeric($latitude) || !is_numeric($longitude)) {
                \Log::warning('Login attempt without valid location', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);
                return response()->json([
                    'message' => 'Location access is required for Student Assistants. Please enable location services and try again.',
                    'debug' => [
                        'latitude_received' => $latitude,
                        'longitude_received' => $longitude
                    ]
                ], 403);
            }

            // Validate coordinate ranges
            if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                \Log::warning('Login attempt with invalid coordinates', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);
                return response()->json([
                    'message' => 'Invalid location coordinates. Please try again.',
                    'debug' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ]
                ], 403);
            }

            // Calculate distance from school
            $distance = $this->calculateDistance(
                (float) $latitude,
                (float) $longitude,
                $schoolLatitude,
                $schoolLongitude
            );

            \Log::info('Login location check', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_lat' => $latitude,
                'user_lon' => $longitude,
                'school_lat' => $schoolLatitude,
                'school_lon' => $schoolLongitude,
                'distance' => round($distance, 2),
                'allowed_radius' => $allowedRadius,
                'within_radius' => $distance <= $allowedRadius
            ]);

            // Check if user is within allowed radius
            if ($distance > $allowedRadius) {
                return response()->json([
                    'message' => 'You are outside the campus area. Please be within 350 meters of the school to log in.',
                    'distance' => round($distance, 2),
                    'allowed_radius' => $allowedRadius,
                    'debug' => [
                        'your_location' => [
                            'latitude' => $latitude,
                            'longitude' => $longitude
                        ],
                        'school_location' => [
                            'latitude' => $schoolLatitude,
                            'longitude' => $schoolLongitude
                        ]
                    ]
                ], 403);
            }
        }
        

        // Login the user
        \Auth::login($user);

        // Set redirect based on role
        $redirect = match ($user->role) {
            'Student Assistant' => '/sa-dashboard',
            'Office Head' => '/officehead-dashboard',
            'HR' => '/hr-dashboard',
            default => '/'
        };

        return response()->json([
            'success' => true,
            'redirect' => $redirect
        ]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     * Returns distance in meters.
     *
     * @param float $lat1 Latitude of first point
     * @param float $lon1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lon2 Longitude of second point
     * @return float Distance in meters
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Handle user registration.
     *
     * @param  \App\Http\Request\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        // Check if student ID or email already exists (additional check)
        $existingUser = \App\Models\User::where('student_id_number', $request->studentId)
            ->orWhere('email', $request->email)
            ->first();

        if ($existingUser) {
            if ($existingUser->student_id_number === $request->studentId) {
                return response()->json([
                    'message' => 'Student ID already registered.'
                ], 422);
            }
            if ($existingUser->email === $request->email) {
                return response()->json([
                    'message' => 'Email already registered.'
                ], 422);
            }
        }

        // Hash the password
        $passwordHash = password_hash($request->password, PASSWORD_DEFAULT);

        // Create new user
        $user = \App\Models\User::create([
            'name' => $request->fullName, // Laravel's default name field
            'student_id_number' => $request->studentId,
            'full_name' => $request->fullName,
            'email' => $request->email,
            'password' => $passwordHash, // Laravel's default password field
            'password_hash' => $passwordHash,
            'role' => $request->role,
            'office' => $request->office,
            'gender' => 'Not Specified', // Default value for required gender field
            'active' => 1, // Default value for required active field
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful!'
            ]);
        } else {
            return response()->json([
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Show Student Assistant Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function saDashboard()
    {
        return view('dashboards.sa', [
            'layout' => 'side-menu',
            'pageTitle' => 'Student Assistant Dashboard'
        ]);
    }

    /**
     * Show Student Assistant Attendance page.
     *
     * @return \Illuminate\Http\Response
     */
    public function saAttendance()
    {
        return view('sapages.sa-attendance', [
            'layout' => 'side-menu',
            'pageTitle' => 'Attendance'
        ]);
    }

    /**
     * Show Student Assistant Requests page.
     *
     * @return \Illuminate\Http\Response
     */
    public function saRequests()
    {
        return view('sapages.sa-requests', [
            'layout' => 'side-menu',
            'pageTitle' => 'Requests'
        ]);
    }

    /**
     * Show Student Assistant Skill Inventory page.
     *
     * @return \Illuminate\Http\Response
     */
    public function saSkillInventory()
    {
        return view('sapages.sa-skill-inventory', [
            'layout' => 'side-menu',
            'pageTitle' => 'Skill Inventory'
        ]);
    }

    /**
     * Show Office Head Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function officeheadDashboard()
    {
        return view('dashboards.officehead', [
            'layout' => 'side-menu',
            'pageTitle' => 'Office Head Dashboard'
        ]);
    }

    /**
     * Show HR Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function hrDashboard()
    {
        return view('dashboards.hr', [
            'layout' => 'side-menu',
            'pageTitle' => 'HR Dashboard'
        ]);
    }

    /**
     * Show Profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('accountpages.profile', [
            'layout' => 'side-menu',
            'pageTitle' => 'Profile'
        ]);
    }

    /**
     * Update user contact information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateContact(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        $request->validate([
            'contact' => 'nullable|string|regex:/^09\d{9}$/',
            'address' => 'nullable|string|max:500',
            'guardian_address' => 'nullable|string|max:500'
        ]);

        // Only Student Assistants can update guardian_address
        if ($request->has('guardian_address') && $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Only Student Assistants can update guardian address.'
            ], 403);
        }

        if ($request->has('contact')) {
        $user->contact = $request->contact;
        }
        
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        
        if ($request->has('guardian_address') && $user->role === 'Student Assistant') {
            $user->guardian_address = $request->guardian_address;
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile information updated successfully!',
            'user' => [
                'contact' => $user->contact,
                'address' => $user->address,
                'guardian_address' => $user->guardian_address
            ]
        ]);
    }

    /**
     * Show Account Settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('sapages.sa-settings', [
            'layout' => 'side-menu',
            'pageTitle' => 'Account Settings'
        ]);
    }

    /**
     * Update user account information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        // Validate basic fields
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'contact' => 'nullable|string|regex:/^09\d{9}$/',
            'address' => 'nullable|string|max:500',
            'guardian_address' => 'nullable|string|max:500',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'section' => 'nullable|string|max:50',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|same:new_password',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Only Student Assistants can update guardian_address, course, year_level, and section
        if (($request->has('guardian_address') || $request->has('course') || $request->has('year_level') || $request->has('section')) && $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Only Student Assistants can update academic information.'
            ], 403);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                $oldPhotoPath = storage_path('app/public/profiles/' . $user->profile_photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            
            // Generate unique filename
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store file in storage/app/public/profiles
            $file->storeAs('public/profiles', $filename);
            
            // Update user's profile_photo field
            $user->profile_photo = $filename;
        }

        // Check if password change is requested
        if ($request->filled('current_password') || $request->filled('new_password')) {
            // Validate current password
            if (!$request->filled('current_password')) {
                return response()->json([
                    'message' => 'Current password is required to change password'
                ], 422);
            }

            if (!$request->filled('new_password')) {
                return response()->json([
                    'message' => 'New password is required'
                ], 422);
            }

            // Verify current password
            if (!password_verify($request->current_password, $user->password_hash ?? $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 422);
            }

            // Update password
            $newPasswordHash = password_hash($request->new_password, PASSWORD_DEFAULT);
            $user->password = $newPasswordHash;
            $user->password_hash = $newPasswordHash;
        }

        // Update other fields
        $user->full_name = $request->full_name;
        $user->name = $request->full_name; // Also update Laravel's default name field
        $user->email = $request->email;
        
        if ($request->filled('contact')) {
            $user->contact = $request->contact;
        }
        
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        
        if ($request->has('guardian_address') && $user->role === 'Student Assistant') {
            $user->guardian_address = $request->guardian_address;
        }
        
        if ($request->has('course') && $user->role === 'Student Assistant') {
            $user->course = $request->course;
        }
        
        if ($request->has('year_level') && $user->role === 'Student Assistant') {
            $user->year_level = $request->year_level;
        }
        
        if ($request->has('section') && $user->role === 'Student Assistant') {
            $user->section = $request->section;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully!',
            'user' => [
                'full_name' => $user->full_name,
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->contact,
                'address' => $user->address,
                'guardian_address' => $user->guardian_address,
                'course' => $user->course,
                'year_level' => $user->year_level,
                'section' => $user->section,
                'profile_photo' => $user->profile_photo ? asset('storage/profiles/' . $user->profile_photo) : null
            ]
        ]);
    }

    /**
     * Show DTR Monitoring page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dtrMonitoring()
    {
        return view('officeheadpages.dtr-monitoring', [
            'layout' => 'side-menu',
            'pageTitle' => 'DTR Monitoring'
        ]);
    }

    /**
     * Get attendance records for office head's office.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getOfficeAttendances(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can access this.'
            ], 403);
        }

        // Get office information - try office_id first, fall back to office name
        $officeId = $user->office_id;
        $office = $user->office;
        
        if (!$officeId && !$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            // Get date filter if provided
            $date = $request->get('date', now('Asia/Manila')->toDateString());
            
            // Get attendance records for the office, filtered by date and ensure user belongs to office
            // Check both office_id and office name to catch all SAs
            $attendancesQuery = \App\Models\Attendance::whereDate('date', $date);
            
            if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                // Use office_id if available, but also check office name for backward compatibility
                $attendancesQuery->where(function($q) use ($officeId, $office) {
                    $q->where('office', $office)
                      ->orWhereHas('user', function($userQuery) use ($officeId, $office) {
                          $userQuery->where('role', 'Student Assistant')
                                    ->where(function($uq) use ($officeId, $office) {
                                        if ($officeId) {
                                            $uq->where('office_id', $officeId);
                                        }
                                        $uq->orWhereRaw('LOWER(TRIM(office)) = LOWER(TRIM(?))', [$office]);
                                    });
                      });
                });
            } else {
                // Fall back to office name only
                $attendancesQuery->where('office', $office)
                    ->whereHas('user', function($query) use ($office) {
                        $query->where('role', 'Student Assistant')
                              ->where('office', $office);
                    });
            }
            
            $attendances = $attendancesQuery
                ->with(['user' => function($query) use ($officeId, $office) {
                    $query->where('role', 'Student Assistant');
                    if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                        $query->where(function($q) use ($officeId, $office) {
                            $q->where('office_id', $officeId)
                              ->orWhereRaw('LOWER(TRIM(office)) = LOWER(TRIM(?))', [$office]);
                        });
                    } else {
                        $query->where('office', $office);
                    }
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $attendances->map(function ($attendance) {
                $dateString = $attendance->date instanceof \Carbon\Carbon 
                    ? $attendance->date->format('Y-m-d') 
                    : $attendance->date;

                $timeInFormatted = '--';
                if ($attendance->time_in) {
                    try {
                        $timeInFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_in)->format('g:i A');
                    } catch (\Exception $e) {
                        $timeInFormatted = $attendance->time_in;
                    }
                }

                $timeOutFormatted = '--';
                if ($attendance->time_out) {
                    try {
                        $timeOutFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_out)->format('g:i A');
                    } catch (\Exception $e) {
                        $timeOutFormatted = $attendance->time_out;
                    }
                }

                // Get photo URLs
                $timeInPhotoUrl = null;
                if ($attendance->time_in_photo) {
                    $timeInPhotoUrl = asset('storage/' . $attendance->time_in_photo);
                }
                
                $timeOutPhotoUrl = null;
                if ($attendance->time_out_photo) {
                    $timeOutPhotoUrl = asset('storage/' . $attendance->time_out_photo);
                }

                return [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'name' => $attendance->user ? ($attendance->user->full_name ?? $attendance->user->name) : $attendance->name,
                    'student_id' => $attendance->user ? ($attendance->user->student_id_number ?? null) : $attendance->student_id_number,
                    'office' => $attendance->office,
                    'date' => $dateString,
                    'formatted_date' => $attendance->date->format('M d, Y'),
                    'time_in' => $timeInFormatted,
                    'time_out' => $timeOutFormatted,
                    'status' => $attendance->status,
                    'review_status' => $attendance->review_status ?? 'Pending',
                    'remarks' => $attendance->remarks,
                    'pending' => ($attendance->review_status === null || $attendance->review_status === 'Pending'),
                    'has_overtime_approval' => $attendance->has_overtime_approval ?? false,
                    'time_in_photo' => $timeInPhotoUrl,
                    'time_out_photo' => $timeOutPhotoUrl,
                ];
            });

            // Group by user_id to show one row per student assistant
            $groupedData = [];
            foreach ($data as $record) {
                $userId = $record['user_id'];
                if (!isset($groupedData[$userId])) {
                    $groupedData[$userId] = [
                        'user_id' => $userId,
                        'name' => $record['name'],
                        'student_id' => $record['student_id'],
                        'office' => $record['office'],
                        'sessions' => [],
                        'total_time' => 0,
                        'status' => 'Absent',
                        'has_attendance' => false,
                    ];
                }
                
                // Add session if there's time in
                if ($record['time_in'] !== '--') {
                    $groupedData[$userId]['has_attendance'] = true;
                    $groupedData[$userId]['status'] = $record['status'] ?? 'Present';
                    $groupedData[$userId]['sessions'][] = [
                        'id' => $record['id'],
                        'time_in' => $record['time_in'],
                        'time_out' => $record['time_out'],
                        'status' => $record['status'],
                        'review_status' => $record['review_status'],
                        'has_overtime_approval' => $record['has_overtime_approval'],
                    ];
                }
            }
            
            // Convert to array and calculate total time for each student
            $finalData = array_values($groupedData);
            foreach ($finalData as &$student) {
                if (count($student['sessions']) > 0) {
                    // Get the latest session for display
                    $latestSession = $student['sessions'][0];
                    $student['time_in'] = $latestSession['time_in'];
                    $student['time_out'] = $latestSession['time_out'];
                    $student['session_count'] = count($student['sessions']);
                } else {
                    $student['time_in'] = '--';
                    $student['time_out'] = '--';
                    $student['session_count'] = 0;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $finalData,
                'office' => $office,
                'date' => $date
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading office attendances: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance records.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Review DTR record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reviewDTR(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can review DTRs.'
            ], 403);
        }

        $request->validate([
            'dtr_id' => 'required|integer',
            'action' => 'required|in:Approved,Rejected',
            'remarks' => 'nullable|string|max:500'
        ]);

        try {
            $attendance = \App\Models\Attendance::find($request->dtr_id);
            
            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found.'
                ], 404);
            }

            // Check if attendance belongs to office head's office
            if ($attendance->office !== $user->office) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. This attendance record does not belong to your office.'
                ], 403);
            }

            $attendance->update([
                'review_status' => $request->action,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'remarks' => $request->remarks ?? $attendance->remarks,
            ]);

        return response()->json([
            'success' => true,
                'message' => 'DTR record ' . strtolower($request->action) . ' successfully!',
                'data' => [
                    'id' => $attendance->id,
                    'review_status' => $attendance->review_status,
                    'reviewed_at' => $attendance->reviewed_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error reviewing DTR: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to review DTR record.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show Evaluation Form page.
     *
     * @return \Illuminate\Http\Response
     */
    public function evaluationForm()
    {
        return view('officeheadpages.evaluation-form', [
            'layout' => 'side-menu',
            'pageTitle' => 'Evaluation Form'
        ]);
    }

    /**
     * Submit evaluation form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitEvaluation(\Illuminate\Http\Request $request)
    {
        try {
            $request->validate([
                'studentName' => 'required|string|max:255',
                'office' => 'required|string|max:255',
                'rate1' => 'required|integer|min:1|max:10',
                'rate2' => 'required|integer|min:1|max:10',
                'rate3' => 'required|integer|min:1|max:10',
                'rate4' => 'required|integer|min:1|max:10',
                'rate5' => 'required|integer|min:1|max:10',
                'rate6' => 'required|integer|min:1|max:10',
                'rate7' => 'required|integer|min:1|max:10',
                'rate8' => 'required|integer|min:1|max:10',
                'rate9' => 'required|integer|min:1|max:10',
                'rate10' => 'required|integer|min:1|max:10',
                'comments' => 'nullable|string|max:1000',
                'date' => 'required|date',
                'ratedBy' => 'required|string|max:255',
                'head' => 'required|string|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check your input.',
                'errors' => $e->errors()
            ], 422);
        }

        // In a real implementation, you would:
        // 1. Create an evaluation record in the database
        // 2. Store all the rating data
        // 3. Calculate and store the total and average scores
        // 4. Send notifications if needed
        // 5. Generate evaluation reports

        $user = \Auth::user();
        
        // Try to find student assistant by name or student ID
        $studentAssistant = null;
        if ($request->studentName) {
            $studentAssistant = \App\Models\User::where('role', 'Student Assistant')
                ->where(function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->studentName . '%')
                          ->orWhere('full_name', 'like', '%' . $request->studentName . '%');
                })
                ->where('office', $request->office)
                ->first();
        }

        // Get current criteria questions from settings or use default
        $criteriaQuestions = [];
        if ($request->has('criteriaQuestions') && is_array($request->criteriaQuestions)) {
            $criteriaQuestions = $request->criteriaQuestions;
        } else {
            // Get default questions from settings
            $criteriaSettings = \App\Models\EvaluationCriteriaSetting::where('is_active', true)
                ->orderBy('order')
                ->get();
            foreach ($criteriaSettings as $setting) {
                $criteriaQuestions[$setting->criterion_key] = $setting->question_text;
            }
        }

        try {
            $evaluation = \App\Models\Evaluation::create([
                'evaluator_id' => $user->id,
                'student_assistant_id' => $studentAssistant ? $studentAssistant->id : null,
                'student_name' => $request->studentName,
                'nature_of_work' => $request->natureOfWork ?? 'N/A', // Default value since field was removed from form
                'office' => $request->office,
                'rate1' => $request->rate1,
                'rate2' => $request->rate2,
                'rate3' => $request->rate3,
                'rate4' => $request->rate4,
                'rate5' => $request->rate5,
                'rate6' => $request->rate6,
                'rate7' => $request->rate7,
                'rate8' => $request->rate8,
                'rate9' => $request->rate9,
                'rate10' => $request->rate10,
                'total_score' => $request->totalScore,
                'average_score' => $request->averageScore,
                'overall_rating' => $request->overallRating,
                'comments' => $request->comments,
                'evaluation_date' => $request->date,
                'rated_by' => $request->ratedBy,
                'head_of_office' => $request->head,
                'status' => 'Pending',
                'criteria_questions' => $criteriaQuestions,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evaluation submitted successfully!',
                'data' => $evaluation
            ]);
        } catch (\Exception $e) {
            \Log::error('Error submitting evaluation: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit evaluation: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred while submitting the evaluation.'
            ], 500);
        }
    }

    /**
     * Get evaluations for office head or HR.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEvaluations()
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can access this.'
            ], 403);
        }

        try {
            // HR can see all evaluations, Office Head only sees their office's evaluations
            if ($user->role === 'HR') {
                $evaluations = \App\Models\Evaluation::orderBy('evaluation_date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Office Head role - see evaluations from their office
                $evaluations = \App\Models\Evaluation::where(function($query) use ($user) {
                        $query->where('evaluator_id', $user->id)
                              ->orWhere('office', $user->office);
                    })
            ->orderBy('evaluation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            }

        $data = $evaluations->map(function ($evaluation) {
            // Get student assistant info if available
            $studentId = null;
            if ($evaluation->student_assistant_id) {
                $studentAssistant = \App\Models\User::find($evaluation->student_assistant_id);
                if ($studentAssistant) {
                    $studentId = $studentAssistant->student_id_number;
                }
            }
            
            return [
                'id' => $evaluation->id,
                'student_name' => $evaluation->student_name,
                'student_id_number' => $studentId,
                'student_assistant_id' => $evaluation->student_assistant_id,
                'nature_of_work' => $evaluation->nature_of_work,
                'office' => $evaluation->office,
                'total_score' => $evaluation->total_score,
                'average_score' => $evaluation->average_score,
                'overall_rating' => $evaluation->overall_rating,
                'evaluation_date' => $evaluation->evaluation_date ? $evaluation->evaluation_date->format('Y-m-d') : null,
                'formatted_date' => $evaluation->evaluation_date ? $evaluation->evaluation_date->format('M d, Y') : 'N/A',
                'rated_by' => $evaluation->rated_by,
                'head_of_office' => $evaluation->head_of_office,
                'status' => $evaluation->status,
                'created_at' => $evaluation->created_at ? $evaluation->created_at->format('Y-m-d H:i:s') : null,
                'rate1' => $evaluation->rate1,
                'rate2' => $evaluation->rate2,
                'rate3' => $evaluation->rate3,
                'rate4' => $evaluation->rate4,
                'rate5' => $evaluation->rate5,
                'rate6' => $evaluation->rate6,
                'rate7' => $evaluation->rate7,
                'rate8' => $evaluation->rate8,
                'rate9' => $evaluation->rate9,
                'rate10' => $evaluation->rate10,
                'comments' => $evaluation->comments,
                'hr_rating' => $evaluation->hr_rating ?? null,
                'hr_comments' => $evaluation->hr_comments ?? null,
                'criteria_questions' => $evaluation->criteria_questions ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
        } catch (\Exception $e) {
            \Log::error('Error getting evaluations: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'user_role' => $user->role
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading evaluations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data for dashboards.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAnalytics()
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can access this.'
            ], 403);
        }

        // Use real database data
        try {
            // Get office information - try office_id first, fall back to office name
            $officeId = $user->office_id;
            $office = null;
            
            if ($officeId) {
                // Use office relationship if office_id exists
                $officeRelation = $user->officeRelation;
                $office = $officeRelation ? $officeRelation->name : $user->office;
            } else {
                // Fall back to office name column and try to find office_id
                $office = $user->office;
                if ($office) {
                    $officeModel = \App\Models\Office::where('name', $office)->first();
                    if ($officeModel) {
                        $officeId = $officeModel->id;
                        // Update user's office_id if it was null
                        if (\Schema::hasColumn('users', 'office_id')) {
                            $user->update(['office_id' => $officeId]);
                        }
                    }
                }
            }
            
            // Student Assistants by Department/Office
            $saByOffice = [];
            if ($user->role === 'HR') {
                // Check if office_id column exists
                if (\Schema::hasColumn('users', 'office_id')) {
                    $query = \App\Models\User::where('role', 'Student Assistant')
                        ->where(function($q) {
                            $q->whereNotNull('office_id')
                              ->orWhereNotNull('office');
                        })
                        ->with('officeRelation');
                } else {
                    // Fallback if office_id column doesn't exist yet
                    $query = \App\Models\User::where('role', 'Student Assistant')
                        ->whereNotNull('office');
                }
                
                // Check if status column exists before filtering
                if (\Schema::hasColumn('users', 'status')) {
                    $query->where(function($q) {
                        $q->whereIn('status', ['Active', 'Apprentice'])
                          ->orWhereNull('status');
                    });
                } else {
                    // Use active column if status doesn't exist
                    $query->where('active', 1);
                }
                
                if (\Schema::hasColumn('users', 'office_id')) {
                    $saByOffice = $query
                        ->get()
                        ->groupBy(function($user) {
                            return $user->office_id ?? 'office_' . $user->office;
                        })
                        ->map(function($group) {
                            $firstUser = $group->first();
                            $officeName = null;
                            
                            if ($firstUser->officeRelation) {
                                $officeName = $firstUser->officeRelation->name;
                            } elseif ($firstUser->office) {
                                $officeName = $firstUser->office;
                            } else {
                                $officeName = 'Unknown';
                            }
                            
                            return [
                                'office' => $officeName,
                                'count' => $group->count()
                            ];
                        })
                        ->values()
                        ->sortByDesc('count')
                        ->values()
                        ->toArray();
                } else {
                    // Fallback: group by office name
                    $saByOffice = $query
                        ->selectRaw('office, COUNT(*) as count')
                        ->groupBy('office')
                        ->orderBy('count', 'desc')
                        ->get()
                        ->map(function($item) {
                            return [
                                'office' => $item->office,
                                'count' => (int)$item->count
                            ];
                        })
                        ->toArray();
                }
            } else {
                // Office Head sees only their office
                if (!$office) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No office assigned to your account.'
                    ], 422);
                }
                
                $query = \App\Models\User::where('role', 'Student Assistant');
                
                // Use office_id if available, otherwise use office name
                if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                    $query->where('office_id', $officeId);
                } else {
                    $query->where('office', $office);
                }
                
                // Check if status column exists before filtering
                if (\Schema::hasColumn('users', 'status')) {
                    $query->where(function($q) {
                        $q->whereIn('status', ['Active', 'Apprentice'])
                          ->orWhereNull('status');
                    });
                } else {
                    // Use active column if status doesn't exist
                    $query->where('active', 1);
                }
                
                $count = $query->count();
                $saByOffice = [['office' => $office, 'count' => $count]];
            }

            // Attendance by Month/Week/Today
            $now = \Carbon\Carbon::now('Asia/Manila');
            $today = $now->toDateString();
            $thisWeekStart = $now->copy()->startOfWeek()->toDateString();
            
            $baseQuery = \App\Models\Attendance::query();
            if ($user->role === 'Office Head' && $office) {
                $baseQuery->where('office', $office);
            }
            
            $attendanceToday = (clone $baseQuery)
                ->whereDate('date', $today)
                ->whereNotNull('time_in')
                ->count();
            
            // Count attendance records for this week (total check-ins)
            $attendanceThisWeek = (clone $baseQuery)
                ->whereBetween('date', [$thisWeekStart, $today])
                ->whereNotNull('time_in')
                ->count();
            
            $attendanceByMonth = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $monthStart = $month->copy()->startOfMonth()->toDateString();
                $monthEnd = $month->copy()->endOfMonth()->toDateString();
                
                // Count attendance records for this month (total check-ins)
                $monthCount = (clone $baseQuery)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->whereNotNull('time_in')
                    ->count();
                
                $attendanceByMonth[] = [
                    'month' => $month->format('M'),
                    'count' => (int)$monthCount
                ];
            }

            // Attendance Status Breakdown
            $statusData = [
                'Present' => 0,
                'Late' => 0,
                'Absent' => 0,
                'Completed' => 0,
                'Incomplete' => 0,
            ];
            
            try {
                $statusQuery = clone $baseQuery;
                $statusBreakdown = $statusQuery
                    ->whereDate('date', $today)
                    ->get()
                    ->groupBy('status')
                    ->map(function($group) {
                        return $group->count();
                    });
                
                foreach ($statusBreakdown as $status => $count) {
                    $statusKey = $status ?? 'Unknown';
                    if (isset($statusData[$statusKey])) {
                        $statusData[$statusKey] = (int)$count;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Error getting status breakdown, using defaults: ' . $e->getMessage());
            }

            // Calculate absent: Total SAs in office - (Present + Late)
            $totalSAsQuery = \App\Models\User::where('role', 'Student Assistant');
            
            // Check if status column exists before filtering
            if (\Schema::hasColumn('users', 'status')) {
                $totalSAsQuery->where(function($query) {
                    $query->whereIn('status', ['Active', 'Apprentice'])
                          ->orWhereNull('status');
                });
            } else {
                // Use active column if status doesn't exist
                $totalSAsQuery->where('active', 1);
            }
            
            if ($user->role === 'Office Head' && $office) {
                if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                    $totalSAsQuery->where('office_id', $officeId);
                } else {
                    $totalSAsQuery->where('office', $office);
                }
            }
            
            $totalSAs = $totalSAsQuery->count();
            
            $statusData['Absent'] = max(0, $totalSAs - $statusData['Present'] - $statusData['Late']);

            // Student Assistants Added by Month
            $saByMonth = [];
            $saQuery = \App\Models\User::where('role', 'Student Assistant');
            
            // Check if status column exists before filtering
            if (\Schema::hasColumn('users', 'status')) {
                $saQuery->where(function($q) {
                    $q->whereIn('status', ['Active', 'Apprentice'])
                      ->orWhereNull('status');
                });
            } else {
                // Use active column if status doesn't exist
                $saQuery->where('active', 1);
            }
            
            if ($user->role === 'Office Head' && $office) {
                if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                    $saQuery->where('office_id', $officeId);
                } else {
                    $saQuery->where('office', $office);
                }
            }
            
            // Get last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $monthStart = $month->copy()->startOfMonth()->toDateString();
                $monthEnd = $month->copy()->endOfMonth()->toDateString();
                
                $monthCount = (clone $saQuery)
                    ->whereBetween('created_at', [$monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])
                    ->count();
                
                $saByMonth[] = [
                    'month' => $month->format('M Y'),
                    'count' => (int)$monthCount
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'sa_by_office' => $saByOffice,
                    'sa_by_month' => $saByMonth,
                    'attendance_today' => $attendanceToday,
                    'attendance_this_week' => $attendanceThisWeek,
                    'attendance_by_month' => $attendanceByMonth,
                    'attendance_by_week' => [
                        ['week' => 'Mon', 'count' => 0],
                        ['week' => 'Tue', 'count' => 0],
                        ['week' => 'Wed', 'count' => 0],
                        ['week' => 'Thu', 'count' => 0],
                        ['week' => 'Fri', 'count' => 0],
                        ['week' => 'Sat', 'count' => 0],
                        ['week' => 'Sun', 'count' => 0],
                    ],
                    'status_breakdown' => $statusData
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting analytics: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'user_role' => $user->role ?? null,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading analytics: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }

    /**
     * Get a single evaluation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEvaluation($id)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can access this.'
            ], 403);
        }

        $evaluation = \App\Models\Evaluation::find($id);
        
        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found.'
            ], 404);
        }

        // Office Head can only see their office's evaluations, HR can see all
        if ($user->role === 'Office Head' && $evaluation->evaluator_id !== $user->id && $evaluation->office !== $user->office) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only access evaluations from your office.'
            ], 403);
        }

        // Get student assistant info if available
        $studentId = null;
        if ($evaluation->student_assistant_id) {
            $studentAssistant = \App\Models\User::find($evaluation->student_assistant_id);
            if ($studentAssistant) {
                $studentId = $studentAssistant->student_id_number;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $evaluation->id,
                'student_name' => $evaluation->student_name,
                'student_id_number' => $studentId,
                'nature_of_work' => $evaluation->nature_of_work,
                'office' => $evaluation->office,
                'rate1' => $evaluation->rate1,
                'rate2' => $evaluation->rate2,
                'rate3' => $evaluation->rate3,
                'rate4' => $evaluation->rate4,
                'rate5' => $evaluation->rate5,
                'rate6' => $evaluation->rate6,
                'rate7' => $evaluation->rate7,
                'rate8' => $evaluation->rate8,
                'rate9' => $evaluation->rate9,
                'rate10' => $evaluation->rate10,
                'total_score' => $evaluation->total_score,
                'average_score' => $evaluation->average_score,
                'overall_rating' => $evaluation->overall_rating,
                'comments' => $evaluation->comments,
                'evaluation_date' => $evaluation->evaluation_date ? $evaluation->evaluation_date->format('Y-m-d') : null,
                'rated_by' => $evaluation->rated_by,
                'head_of_office' => $evaluation->head_of_office,
                'status' => $evaluation->status,
                'hr_rating' => $evaluation->hr_rating ?? null,
                'hr_comments' => $evaluation->hr_comments ?? null,
            ]
        ]);
    }

    /**
     * Update an evaluation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateEvaluation(\Illuminate\Http\Request $request, $id)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can update evaluations.'
            ], 403);
        }

        $evaluation = \App\Models\Evaluation::find($id);
        
        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found.'
            ], 404);
        }

        // Office Head can only update their own evaluations, HR can update any
        if ($user->role === 'Office Head' && $evaluation->evaluator_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only update evaluations you created.'
            ], 403);
        }

        $request->validate([
            'studentName' => 'required|string|max:255',
            'natureOfWork' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'rate1' => 'required|integer|min:1|max:10',
            'rate2' => 'required|integer|min:1|max:10',
            'rate3' => 'required|integer|min:1|max:10',
            'rate4' => 'required|integer|min:1|max:10',
            'rate5' => 'required|integer|min:1|max:10',
            'rate6' => 'required|integer|min:1|max:10',
            'rate7' => 'required|integer|min:1|max:10',
            'rate8' => 'required|integer|min:1|max:10',
            'rate9' => 'required|integer|min:1|max:10',
            'rate10' => 'required|integer|min:1|max:10',
            'comments' => 'nullable|string|max:1000',
            'date' => 'required|date',
            'ratedBy' => 'required|string|max:255',
            'head' => 'required|string|max:255',
            'totalScore' => 'required|integer',
            'averageScore' => 'required|numeric',
            'overallRating' => 'required|string',
        ]);

        $evaluation->update([
            'student_name' => $request->studentName,
            'nature_of_work' => $request->natureOfWork,
            'office' => $request->office,
            'rate1' => $request->rate1,
            'rate2' => $request->rate2,
            'rate3' => $request->rate3,
            'rate4' => $request->rate4,
            'rate5' => $request->rate5,
            'rate6' => $request->rate6,
            'rate7' => $request->rate7,
            'rate8' => $request->rate8,
            'rate9' => $request->rate9,
            'rate10' => $request->rate10,
            'total_score' => $request->totalScore,
            'average_score' => $request->averageScore,
            'overall_rating' => $request->overallRating,
            'comments' => $request->comments,
            'evaluation_date' => $request->date,
            'rated_by' => $request->ratedBy,
            'head_of_office' => $request->head,
        ]);

        // Update criteria questions if provided
        if ($request->has('criteriaQuestions') && is_array($request->criteriaQuestions)) {
            $evaluation->criteria_questions = $request->criteriaQuestions;
            $evaluation->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Evaluation updated successfully!',
            'data' => $evaluation
        ]);
    }

    /**
     * Delete an evaluation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteEvaluation($id)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can delete evaluations.'
            ], 403);
        }

        $evaluation = \App\Models\Evaluation::find($id);
        
        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found.'
            ], 404);
        }

        // Office Head can only delete their own evaluations, HR can delete any
        if ($user->role === 'Office Head' && $evaluation->evaluator_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only delete evaluations you created.'
            ], 403);
        }

        $evaluation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evaluation deleted successfully!'
        ]);
    }

    /**
     * Get requests for Student Assistant.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSARequests()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can access this.'
            ], 403);
        }

        try {
            $requests = \App\Models\Request::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'request_type' => $request->request_type,
                    'message' => $request->message,
                    'status' => $request->status,
                    'remarks' => $request->remarks,
                    'created_at' => $request->created_at->format('Y-m-d H:i:s'),
                    'formatted_date' => $request->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $request->reviewed_at ? $request->reviewed_at->format('M d, Y h:i A') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading SA requests: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load requests.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Create a new request (Student Assistant).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createRequest(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can create requests.'
            ], 403);
        }

        $request->validate([
            'request_type' => 'required|string|max:255|in:Schedule Adjustment,Change Office,Leave Request,Resignation,Overtime Request',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $newRequest = \App\Models\Request::create([
                'user_id' => $user->id,
                'student_id_number' => $user->student_id_number,
                'request_type' => $request->request_type,
                'message' => $request->message,
                'status' => 'Pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Request submitted successfully!',
                'data' => [
                    'id' => $newRequest->id,
                    'request_type' => $newRequest->request_type,
                    'message' => $newRequest->message,
                    'status' => $newRequest->status,
                    'created_at' => $newRequest->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Create a new request from Office Head to HR asking for additional SAs (with quantity).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createOfficeHeadSARequest(\Illuminate\Http\Request $request)
    {
        try {
            $user = \Auth::user();

            if (!$user || $user->role !== 'Office Head') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only Office Heads can create this request.'
                ], 403);
            }

            if (!$user->office) {
                return response()->json([
                    'success' => false,
                    'message' => 'No office assigned to your account.'
                ], 422);
            }

            // Validate request
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:50',
                'message' => 'nullable|string|max:2000',
            ]);

            $qty = (int) $validated['quantity'];
            $note = isset($validated['message']) ? trim((string) $validated['message']) : '';

            // Store details in message so HR can review without a new DB column
            $finalMessage = "Requesting {$qty} Student Assistant(s) for Office: {$user->office}.";
            if ($note !== '') {
                $finalMessage .= " Note: {$note}";
            }

            $newRequest = \App\Models\Request::create([
                'user_id' => $user->id,                 // Office Head
                'student_id_number' => null,            // Not applicable
                'request_type' => 'SA Request',
                'message' => $finalMessage,
                'status' => 'Pending',
            ]);

            \Log::info('Office Head SA Request created', [
                'request_id' => $newRequest->id,
                'office_head_user_id' => $user->id,
                'office' => $user->office,
                'quantity' => $qty,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SA request submitted successfully!',
                'data' => [
                    'id' => $newRequest->id,
                    'request_type' => $newRequest->request_type,
                    'message' => $newRequest->message,
                    'status' => $newRequest->status,
                    'created_at' => $newRequest->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation error in Office Head SA request', [
                'errors' => $e->errors(),
                'user_id' => \Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check your input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating Office Head SA request: ' . $e->getMessage(), [
                'user_id' => \Auth::id(),
                'office' => \Auth::user()->office ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit SA request: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred while processing your request.'
            ], 500);
        }
    }

    /**
     * Get requests for Office Head's office.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfficeRequests()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $office = $user->office;
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            $requests = \App\Models\Request::with('user')
                ->whereHas('user', function($query) use ($office) {
                    $query->where('office', $office);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'name' => $request->user->full_name ?? $request->user->name,
                    'student_id' => $request->user->student_id_number ?? $request->student_id_number,
                    'request_type' => $request->request_type,
                    'message' => $request->message,
                    'status' => $request->status,
                    'remarks' => $request->remarks,
                    'created_at' => $request->created_at->format('Y-m-d'),
                    'formatted_date' => $request->created_at->format('M d, Y'),
                    'reviewed_at' => $request->reviewed_at ? $request->reviewed_at->format('M d, Y h:i A') : null,
                    'reviewed_by' => $request->reviewer ? ($request->reviewer->full_name ?? $request->reviewer->name) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'office' => $office
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading office requests: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load requests.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get all requests for HR.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRequests()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can access this.'
            ], 403);
        }

        try {
            $requests = \App\Models\Request::with(['user', 'reviewer'])
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'name' => $request->user->full_name ?? $request->user->name,
                    'student_id' => $request->user->student_id_number ?? $request->student_id_number,
                    'office' => $request->user->office,
                    'request_type' => $request->request_type,
                    'message' => $request->message,
                    'status' => $request->status,
                    'remarks' => $request->remarks,
                    'created_at' => $request->created_at->format('Y-m-d'),
                    'formatted_date' => $request->created_at->format('M d, Y'),
                    'reviewed_at' => $request->reviewed_at ? $request->reviewed_at->format('M d, Y h:i A') : null,
                    'reviewed_by' => $request->reviewer ? ($request->reviewer->full_name ?? $request->reviewer->name) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading all requests: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load requests.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Process a request (Office Head or HR).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can process requests.'
            ], 403);
        }

        $request->validate([
            'action' => 'required|string|in:Approved,Rejected',
            'remarks' => 'nullable|string|max:1000'
        ]);

        try {
            $requestRecord = \App\Models\Request::find($id);
            
            if (!$requestRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found.'
                ], 404);
            }

            // Office Head can only process requests from their office
            if ($user->role === 'Office Head') {
                if ($requestRecord->user->office !== $user->office) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized. This request does not belong to your office.'
                    ], 403);
                }
            }

            $requestRecord->update([
                'status' => $request->action,
                'remarks' => $request->remarks,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Request ' . strtolower($request->action) . ' successfully!',
                'data' => [
                    'id' => $requestRecord->id,
                    'status' => $requestRecord->status,
                    'reviewed_at' => $requestRecord->reviewed_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error processing request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process request.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show Requests Review page (Office Head).
     *
     * @return \Illuminate\Http\Response
     */
    public function requestsReview()
    {
        return view('officeheadpages.requests-review', [
            'layout' => 'side-menu',
            'pageTitle' => 'Requests Review'
        ]);
    }

    /**
     * Show HR Requests Review page.
     *
     * @return \Illuminate\Http\Response
     */
    public function hrRequestsReview()
    {
        return view('hrpages.requests-review', [
            'layout' => 'side-menu',
            'pageTitle' => 'Requests Review'
        ]);
    }

    /**
     * Submit request review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitRequestReview(\Illuminate\Http\Request $request)
    {
        return $this->processRequest($request, $request->requestId);
    }

    /**
     * Show Student Assistants Management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentAssistants()
    {
        return view('hrpages.student-assistants', [
            'layout' => 'side-menu',
            'pageTitle' => 'Student Assistant Management'
        ]);
    }

    /**
     * Add new student assistant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addStudentAssistant(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can add student assistants.'
            ], 403);
        }

        $request->validate([
            'fullName' => 'required|string|max:255',
            'studentId' => 'required|string|max:255|unique:users,student_id_number',
            'email' => 'required|email|max:255|unique:users,email',
            'contact' => 'required|string|max:20',
            'office' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive,Pending Assignment,Apprentice',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $defaultPassword = bcrypt('password123'); // Default password - should be changed on first login
            
            $studentAssistant = \App\Models\User::create([
            'name' => $request->fullName,
            'full_name' => $request->fullName,
            'student_id_number' => $request->studentId,
            'email' => $request->email,
            'contact' => $request->contact,
            'office' => $request->office,
            'role' => 'Student Assistant',
            'status' => $request->status,
                'password' => $defaultPassword,
                'password_hash' => $defaultPassword,
            'gender' => 'Not Specified',
                'active' => $request->status === 'Active' ? 1 : ($request->status === 'Apprentice' ? 1 : 0),
                'email_verified_at' => now(),
            ]);

            // Store service notes if provided (you may need to create a service_records table for this)
            // For now, we'll skip this as it's not in the users table

        return response()->json([
            'success' => true,
            'message' => 'Student assistant added successfully!',
                'data' => [
                    'id' => $studentAssistant->id,
                    'name' => $studentAssistant->name,
                    'full_name' => $studentAssistant->full_name,
                    'student_id_number' => $studentAssistant->student_id_number,
                    'email' => $studentAssistant->email,
                    'contact' => $studentAssistant->contact,
                    'office' => $studentAssistant->office,
                    'status' => $studentAssistant->status,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding student assistant: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add student assistant. ' . ($e->getCode() === 23000 ? 'Email or Student ID already exists.' : 'Please try again.'),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show Contract Management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function contractManagement()
    {
        return view('hrpages.contract-management', [
            'layout' => 'side-menu',
            'pageTitle' => 'Contract Management'
        ]);
    }

    /**
     * Get all contracts (student assistants with contract dates).
     *
     * @return \Illuminate\Http\Response
     */
    public function getContracts()
    {
        $studentAssistants = \App\Models\User::where('role', 'Student Assistant')
            ->whereNotNull('contract_start_date')
            ->whereNotNull('contract_end_date')
            ->orderBy('contract_end_date', 'desc')
            ->get();

        $contracts = $studentAssistants->map(function ($sa) {
            $today = now();
            $startDate = \Carbon\Carbon::parse($sa->contract_start_date);
            $endDate = \Carbon\Carbon::parse($sa->contract_end_date);
            
            // Determine contract status
            $status = 'Active';
            if ($today->lt($startDate)) {
                $status = 'Pending';
            } elseif ($today->gt($endDate)) {
                $status = 'Expired';
            }

            return [
                'id' => $sa->id,
                'name' => $sa->full_name ?? $sa->name,
                'studentId' => $sa->student_id_number,
                'office' => $sa->office,
                'startDate' => $sa->contract_start_date,
                'endDate' => $sa->contract_end_date,
                'status' => $status,
                'uploadDate' => $sa->created_at->format('Y-m-d'),
                'notes' => null, // Can be extended if needed
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Upload/Update contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadContract(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'studentId' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'pdfUpload' => 'nullable|file|mimes:pdf|max:10240', // 10MB max, optional
        ]);

        // Find student assistant by student ID
        $studentAssistant = \App\Models\User::where('role', 'Student Assistant')
            ->where('student_id_number', $request->studentId)
            ->first();

        if (!$studentAssistant) {
            return response()->json([
                'success' => false,
                'message' => 'Student assistant not found.'
            ], 404);
        }

        // Update contract dates
        $studentAssistant->contract_start_date = $request->startDate;
        $studentAssistant->contract_end_date = $request->endDate;
        $studentAssistant->save();

        // Handle file upload if provided
        $filePath = null;
        if ($request->hasFile('pdfUpload')) {
            $file = $request->file('pdfUpload');
            $fileName = $request->studentId . '-' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('contracts', $fileName, 'public');
        }

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully!',
            'data' => [
                'id' => $studentAssistant->id,
                'name' => $studentAssistant->full_name ?? $studentAssistant->name,
                'studentId' => $studentAssistant->student_id_number,
                'office' => $studentAssistant->office,
                'startDate' => $studentAssistant->contract_start_date,
                'endDate' => $studentAssistant->contract_end_date,
                'filePath' => $filePath
            ]
        ]);
    }

    /**
     * Show Skill Inventory Management page (HR).
     *
     * @return \Illuminate\Http\Response
     */
    public function skillInventory()
    {
        return view('hrpages.skill-inventory', [
            'layout' => 'side-menu',
            'pageTitle' => 'Skill Inventory Management'
        ]);
    }

    /**
     * Show Skill Review page (Office Head).
     *
     * @return \Illuminate\Http\Response
     */
    public function skillReview()
    {
        return view('officeheadpages.skill-review', [
            'layout' => 'side-menu',
            'pageTitle' => 'Skill Review'
        ]);
    }

    /**
     * Get skills for Student Assistant.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSASkills()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can access this.'
            ], 403);
        }

        try {
            $skills = \App\Models\SkillInventory::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'skill_name' => $skill->skill_name,
                    'note' => $skill->note,
                    'proof_file' => $skill->proof_file ? asset('storage/skills/' . $skill->proof_file) : null,
                    'status' => $skill->status,
                    'remarks' => $skill->remarks,
                    'created_at' => $skill->created_at->format('Y-m-d H:i:s'),
                    'formatted_date' => $skill->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $skill->reviewed_at ? $skill->reviewed_at->format('M d, Y h:i A') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading SA skills: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load skills.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Create a new skill request (Student Assistant).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSkillRequest(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can create skill requests.'
            ], 403);
        }

        $request->validate([
            'skillName' => 'required|string|max:255',
            'skillNote' => 'nullable|string|max:2000',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240', // 10MB max
        ]);

        try {
            $proofFile = null;
            
            // Handle proof file upload
            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                
                // Generate unique filename
                $filename = 'skill_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store file in storage/app/public/skills
                $file->storeAs('public/skills', $filename);
                
                $proofFile = $filename;
            }

            $skill = \App\Models\SkillInventory::create([
                'user_id' => $user->id,
                'student_id_number' => $user->student_id_number,
                'skill_name' => $request->skillName,
                'note' => $request->skillNote,
                'proof_file' => $proofFile,
                'status' => 'Pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Skill request submitted successfully!',
                'data' => [
                    'id' => $skill->id,
                    'skill_name' => $skill->skill_name,
                    'status' => $skill->status,
                    'created_at' => $skill->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating skill request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit skill request.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get skills for Office Head's office.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfficeSkills()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $office = $user->office;
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            $skills = \App\Models\SkillInventory::with('user')
                ->whereHas('user', function($query) use ($office) {
                    $query->where('office', $office);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->user->full_name ?? $skill->user->name,
                    'student_id' => $skill->user->student_id_number ?? $skill->student_id_number,
                    'skill_name' => $skill->skill_name,
                    'note' => $skill->note,
                    'proof_file' => $skill->proof_file ? asset('storage/skills/' . $skill->proof_file) : null,
                    'status' => $skill->status,
                    'remarks' => $skill->remarks,
                    'created_at' => $skill->created_at->format('Y-m-d'),
                    'formatted_date' => $skill->created_at->format('M d, Y'),
                    'reviewed_at' => $skill->reviewed_at ? $skill->reviewed_at->format('M d, Y h:i A') : null,
                    'reviewed_by' => $skill->reviewer ? ($skill->reviewer->full_name ?? $skill->reviewer->name) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'office' => $office
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading office skills: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load skills.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get all skills for HR.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSkills()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can access this.'
            ], 403);
        }

        try {
            $skills = \App\Models\SkillInventory::with(['user', 'reviewer'])
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->user->full_name ?? $skill->user->name,
                    'student_id' => $skill->user->student_id_number ?? $skill->student_id_number,
                    'office' => $skill->user->office,
                    'skill_name' => $skill->skill_name,
                    'note' => $skill->note,
                    'proof_file' => $skill->proof_file ? asset('storage/skills/' . $skill->proof_file) : null,
                    'status' => $skill->status,
                    'remarks' => $skill->remarks,
                    'created_at' => $skill->created_at->format('Y-m-d'),
                    'formatted_date' => $skill->created_at->format('M d, Y'),
                    'reviewed_at' => $skill->reviewed_at ? $skill->reviewed_at->format('M d, Y h:i A') : null,
                    'reviewed_by' => $skill->reviewer ? ($skill->reviewer->full_name ?? $skill->reviewer->name) : null,
                ];
            });

        return response()->json([
            'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading all skills: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load skills.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Process a skill request (Office Head or HR).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processSkillRequest(\Illuminate\Http\Request $request, $id)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can process skill requests.'
            ], 403);
        }

        $request->validate([
            'action' => 'required|string|in:Approved,Rejected',
            'remarks' => 'nullable|string|max:1000'
        ]);

        try {
            $skill = \App\Models\SkillInventory::find($id);
            
            if (!$skill) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skill request not found.'
                ], 404);
            }

            // Office Head can only process skills from their office
            if ($user->role === 'Office Head') {
                if ($skill->user->office !== $user->office) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized. This skill request does not belong to your office.'
                    ], 403);
                }
            }

            $skill->update([
                'status' => $request->action,
                'remarks' => $request->remarks,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Skill request ' . strtolower($request->action) . ' successfully!',
                'data' => [
                    'id' => $skill->id,
                    'status' => $skill->status,
                    'reviewed_at' => $skill->reviewed_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error processing skill request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process skill request.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Add new skill to inventory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSkill(\Illuminate\Http\Request $request)
    {
        return $this->createSkillRequest($request);
    }

    /**
     * Show Evaluation Review page.
     *
     * @return \Illuminate\Http\Response
     */
    public function evaluationReview()
    {
        return view('hrpages.evaluation-review', [
            'layout' => 'side-menu',
            'pageTitle' => 'Evaluation Review'
        ]);
    }

    /**
     * Save evaluation review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveEvaluationReview(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can review evaluations.'
            ], 403);
        }

        $request->validate([
            'evaluationId' => 'required|integer',
            'hrStatus' => 'required|string|in:Pending,Reviewed,Approved,Rejected',
            'hrRating' => 'nullable|string|in:Excellent,Good,Satisfactory,Needs Improvement,Poor',
            'hrComments' => 'nullable|string|max:1000'
        ]);

        try {
            $evaluation = \App\Models\Evaluation::find($request->evaluationId);
            
            if (!$evaluation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation not found.'
                ], 404);
            }

            // Update evaluation with HR review data
            $updateData = [
                'status' => $request->hrStatus,
            ];

            // Only update fields if the columns exist
            // Note: If hr_rating and hr_comments columns don't exist, add them via migration
            if (\Schema::hasColumn('evaluations', 'hr_rating')) {
                $updateData['hr_rating'] = $request->hrRating;
            }
            if (\Schema::hasColumn('evaluations', 'hr_comments')) {
                $updateData['hr_comments'] = $request->hrComments;
            }

            $evaluation->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation review saved successfully!',
                'data' => [
                    'id' => $evaluation->id,
                    'status' => $evaluation->status,
                    'hr_rating' => $evaluation->hr_rating ?? $request->hrRating,
                    'hr_comments' => $evaluation->hr_comments ?? $request->hrComments,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving evaluation review: ' . $e->getMessage(), [
                'evaluation_id' => $request->evaluationId,
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save evaluation review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Reports & Performance page.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports()
    {
        return view('hrpages.reports', [
            'layout' => 'side-menu',
            'pageTitle' => 'Reports & Performance'
        ]);
    }

    /**
     * Download report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadReport(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can download reports.'
            ], 403);
        }

        $request->validate([
            'reportType' => 'required|string|in:dtr,evaluation,absent',
            'period' => 'nullable|string',
            'type' => 'nullable|string'
        ]);

        try {
            $reportType = $request->reportType;
            $period = $request->period;
            $type = $request->type;
            
            $fileName = $this->generateReportFileName($reportType, $period, $type);
            $csvContent = $this->generateReportCSV($reportType, $period, $type);
            
            // Return CSV file download
            return response($csvContent, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate CSV content for reports.
     *
     * @param  string  $reportType
     * @param  string  $period
     * @param  string  $type
     * @return string
     */
    private function generateReportCSV($reportType, $period, $type)
    {
        $csv = [];
        
        switch ($reportType) {
            case 'dtr':
                $csv = $this->generateDTRReport($period);
                break;
            case 'evaluation':
                $csv = $this->generateEvaluationReport($type);
                break;
            case 'absent':
                $csv = $this->generateAbsenteeismReport($period);
                break;
        }
        
        return $csv;
    }

    /**
     * Generate DTR Summary Report.
     *
     * @param  string  $period
     * @return string
     */
    private function generateDTRReport($period)
    {
        $today = now();
        $startDate = null;
        $endDate = $today->toDateString();
        
        switch ($period) {
            case 'daily':
                $startDate = $today->toDateString();
                break;
            case 'weekly':
                $startDate = $today->copy()->startOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = $today->copy()->startOfMonth()->toDateString();
                break;
        }
        
        // Get all student assistants
        $query = \App\Models\User::where('role', 'Student Assistant');
        if (\Schema::hasColumn('users', 'status')) {
            $query->where(function($q) {
                $q->whereIn('status', ['Active', 'Apprentice'])
                  ->orWhereNull('status');
            });
        } else {
            $query->where('active', 1);
        }
        
        $studentAssistants = $query->get();
        
        $csv = [];
        $csv[] = ['DTR Summary Report - ' . ucfirst($period)];
        $csv[] = ['Generated: ' . now()->format('Y-m-d H:i:s')];
        $csv[] = ['Period: ' . ($startDate ? $startDate . ' to ' . $endDate : $endDate)];
        $csv[] = [];
        $csv[] = ['Name', 'Office', 'Date', 'Time In', 'Time Out', 'Total Hours', 'Status'];
        
        foreach ($studentAssistants as $sa) {
            $attendances = \App\Models\Attendance::where('user_id', $sa->id);
            
            if ($startDate) {
                $attendances->whereBetween('date', [$startDate, $endDate]);
            } else {
                $attendances->whereDate('date', $endDate);
            }
            
            $attendances = $attendances->orderBy('date', 'desc')->get();
            
            if ($attendances->isEmpty()) {
                $csv[] = [
                    $sa->full_name ?? $sa->name,
                    $sa->office ?? 'N/A',
                    $startDate ?? $endDate,
                    'N/A',
                    'N/A',
                    '0',
                    'Absent'
                ];
            } else {
                foreach ($attendances as $attendance) {
                    $totalHours = $attendance->total_minutes ? round($attendance->total_minutes / 60, 2) : 0;
                    $csv[] = [
                        $sa->full_name ?? $sa->name,
                        $sa->office ?? 'N/A',
                        $attendance->date,
                        $attendance->time_in ? date('H:i:s', strtotime($attendance->time_in)) : 'N/A',
                        $attendance->time_out ? date('H:i:s', strtotime($attendance->time_out)) : 'N/A',
                        $totalHours,
                        $attendance->status ?? 'Present'
                    ];
                }
            }
        }
        
        return $this->arrayToCSV($csv);
    }

    /**
     * Generate Evaluation Report.
     *
     * @param  string  $type
     * @return string
     */
    private function generateEvaluationReport($type)
    {
        $query = \App\Models\Evaluation::query();
        
        switch ($type) {
            case 'by-office':
                $query->orderBy('office')->orderBy('evaluation_date', 'desc');
                break;
            case 'by-student':
                $query->orderBy('student_name')->orderBy('evaluation_date', 'desc');
                break;
            default:
                $query->orderBy('evaluation_date', 'desc');
        }
        
        $evaluations = $query->get();
        
        $csv = [];
        $csv[] = ['Evaluation Report - ' . ucfirst(str_replace('-', ' ', $type))];
        $csv[] = ['Generated: ' . now()->format('Y-m-d H:i:s')];
        $csv[] = [];
        $csv[] = ['Student Name', 'Office', 'Nature of Work', 'Evaluation Date', 'Rated By', 'Total Score', 'Average Score', 'Overall Rating', 'Status'];
        
        foreach ($evaluations as $eval) {
            $csv[] = [
                $eval->student_name ?? 'N/A',
                $eval->office ?? 'N/A',
                $eval->nature_of_work ?? 'N/A',
                $eval->evaluation_date ?? 'N/A',
                $eval->rated_by ?? 'N/A',
                $eval->total_score ?? 0,
                $eval->average_score ?? 0,
                $eval->overall_rating ?? 'N/A',
                $eval->status ?? 'Pending'
            ];
        }
        
        return $this->arrayToCSV($csv);
    }

    /**
     * Generate Absenteeism Trends Report.
     *
     * @param  string  $period
     * @return string
     */
    private function generateAbsenteeismReport($period)
    {
        $today = now();
        $startDate = null;
        $endDate = $today->toDateString();
        
        switch ($period) {
            case 'monthly':
                $startDate = $today->copy()->startOfMonth()->toDateString();
                break;
            case 'quarterly':
                $startDate = $today->copy()->startOfQuarter()->toDateString();
                break;
            case 'yearly':
                $startDate = $today->copy()->startOfYear()->toDateString();
                break;
        }
        
        // Get all student assistants
        $query = \App\Models\User::where('role', 'Student Assistant');
        if (\Schema::hasColumn('users', 'status')) {
            $query->where(function($q) {
                $q->whereIn('status', ['Active', 'Apprentice'])
                  ->orWhereNull('status');
            });
        } else {
            $query->where('active', 1);
        }
        
        $studentAssistants = $query->get();
        
        $csv = [];
        $csv[] = ['Absenteeism Trends Report - ' . ucfirst($period)];
        $csv[] = ['Generated: ' . now()->format('Y-m-d H:i:s')];
        $csv[] = ['Period: ' . ($startDate ? $startDate . ' to ' . $endDate : $endDate)];
        $csv[] = [];
        $csv[] = ['Name', 'Office', 'Total Days', 'Present Days', 'Absent Days', 'Late Count', 'Absence Rate (%)'];
        
        foreach ($studentAssistants as $sa) {
            $attendances = \App\Models\Attendance::where('user_id', $sa->id);
            
            if ($startDate) {
                $attendances->whereBetween('date', [$startDate, $endDate]);
            } else {
                $attendances->whereDate('date', $endDate);
            }
            
            $allAttendances = $attendances->get();
            $presentDays = $allAttendances->where('status', '!=', 'Absent')->count();
            $absentDays = $allAttendances->where('status', 'Absent')->count();
            $lateCount = $allAttendances->where('status', 'Late')->count();
            $totalDays = $presentDays + $absentDays;
            $absenceRate = $totalDays > 0 ? round(($absentDays / $totalDays) * 100, 2) : 0;
            
            $csv[] = [
                $sa->full_name ?? $sa->name,
                $sa->office ?? 'N/A',
                $totalDays,
                $presentDays,
                $absentDays,
                $lateCount,
                $absenceRate
            ];
        }
        
        return $this->arrayToCSV($csv);
    }

    /**
     * Convert array to CSV string.
     *
     * @param  array  $data
     * @return string
     */
    private function arrayToCSV($data)
    {
        $output = fopen('php://temp', 'r+');
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    /**
     * Generate report file name.
     *
     * @param  string  $reportType
     * @param  string  $period
     * @param  string  $type
     * @return string
     */
    private function generateReportFileName($reportType, $period, $type)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $baseName = ucfirst($reportType) . '_Report';
        
        if ($period) {
            $baseName .= '_' . ucfirst($period);
        }
        
        if ($type) {
            $baseName .= '_' . ucfirst(str_replace('-', '_', $type));
        }
        
        return $baseName . '_' . $timestamp . '.csv';
    }

    /**
     * Get performance data for reports page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPerformanceData()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can access this.'
            ], 403);
        }

        try {
            // Get all student assistants with office relationship loaded
            $query = \App\Models\User::where('role', 'Student Assistant')->with('officeRelation');
            if (\Schema::hasColumn('users', 'status')) {
                $query->where(function($q) {
                    $q->whereIn('status', ['Active', 'Apprentice'])
                      ->orWhereNull('status');
                });
            } else {
                $query->where('active', 1);
            }
            
            $studentAssistants = $query->get();
            $performanceData = [];
            
            // Calculate performance metrics for each student assistant
            foreach ($studentAssistants as $sa) {
                // Get attendance records for the last 30 days
                $startDate = now()->subDays(30)->toDateString();
                $endDate = now()->toDateString();
                
                $attendances = \App\Models\Attendance::where('user_id', $sa->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();
                
                $totalDays = 30;
                $presentDays = $attendances->where('status', '!=', 'Absent')->count();
                $lateCount = $attendances->where('status', 'Late')->count();
                $absentCount = max(0, $totalDays - $presentDays);
                $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
                
                // Get average evaluation score
                $evaluations = \App\Models\Evaluation::where(function($q) use ($sa) {
                    $q->where('student_name', $sa->full_name ?? $sa->name);
                    if (\Schema::hasColumn('evaluations', 'student_assistant_id')) {
                        $q->orWhere('student_assistant_id', $sa->id);
                    }
                })->get();
                
                $avgEvaluation = 0;
                if ($evaluations->count() > 0) {
                    $avgEvaluation = round($evaluations->avg('average_score') ?? 0, 1);
                }
                
                // Get status
                $status = 'Active';
                if (\Schema::hasColumn('users', 'status')) {
                    $status = $sa->status ?? 'Active';
                } else {
                    $status = $sa->active ? 'Active' : 'Inactive';
                }
                
                // Get office name from relationship (current name from database) or fallback to office field
                $officeName = $sa->officeRelation ? $sa->officeRelation->name : ($sa->office ?? 'N/A');
                
                $performanceData[] = [
                    'id' => $sa->id,
                    'name' => $sa->full_name ?? $sa->name,
                    'office' => $officeName,
                    'attendance' => $attendanceRate,
                    'lates' => $lateCount,
                    'absences' => $absentCount,
                    'evaluationScore' => $avgEvaluation,
                    'status' => $status
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $performanceData
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting performance data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load performance data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Office Management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function officeManagement()
    {
        return view('hrpages.office-management', [
            'layout' => 'side-menu',
            'pageTitle' => 'Office Management'
        ]);
    }

    /**
     * Get all offices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOffices()
    {
        $offices = \App\Models\Office::orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $offices
        ]);
    }

    /**
     * Get a single office.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOffice($id)
    {
        $office = \App\Models\Office::find($id);
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $office
        ]);
    }

    /**
     * Create a new office.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createOffice(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name',
            'code' => 'nullable|string|max:50|unique:offices,code',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $office = \App\Models\Office::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'location' => $request->location,
            'contact_person' => $request->contact_person,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Office created successfully!',
            'data' => $office
        ]);
    }

    /**
     * Update an office.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOffice(\Illuminate\Http\Request $request, $id)
    {
        $office = \App\Models\Office::find($id);
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name,' . $id,
            'code' => 'nullable|string|max:50|unique:offices,code,' . $id,
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $oldName = $office->name;
        
        $office->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'location' => $request->location,
            'contact_person' => $request->contact_person,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'is_active' => $request->is_active ?? $office->is_active,
        ]);

        // Update office name in users table for backward compatibility
        if ($oldName !== $request->name) {
            \App\Models\User::where('office_id', $office->id)
                ->update(['office' => $request->name]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Office updated successfully!',
            'data' => $office
        ]);
    }

    /**
     * Delete an office.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteOffice($id)
    {
        $office = \App\Models\Office::find($id);
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found.'
            ], 404);
        }

        // Check if office is being used by any users
        $usersCount = \App\Models\User::where('office_id', $office->id)->count();
        
        if ($usersCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete office. It is currently assigned to ' . $usersCount . ' user(s).'
            ], 422);
        }

        $office->delete();

        return response()->json([
            'success' => true,
            'message' => 'Office deleted successfully!'
        ]);
    }

    /**
     * Show Office Head Account Management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function officeHeadManagement()
    {
        return view('hrpages.officehead-management', [
            'layout' => 'side-menu',
            'pageTitle' => 'Office Head Account Management'
        ]);
    }

    /**
     * Get all office heads.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfficeHeads()
    {
        $officeHeads = \App\Models\User::where('role', 'Office Head')
            ->with('officeRelation')
            ->orderBy('name')
            ->get()
            ->map(function ($officeHead) {
                return [
                    'id' => $officeHead->id,
                    'name' => $officeHead->name,
                    'full_name' => $officeHead->full_name,
                    'email' => $officeHead->email,
                    'office' => $officeHead->officeRelation ? $officeHead->officeRelation->name : $officeHead->office,
                    'office_id' => $officeHead->office_id,
                    'contact' => $officeHead->contact,
                    'gender' => $officeHead->gender,
                    'active' => $officeHead->active,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $officeHeads
        ]);
    }

    /**
     * Get a single office head.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOfficeHead($id)
    {
        $officeHead = \App\Models\User::where('role', 'Office Head')
            ->with('officeRelation')
            ->find($id);
        
        if (!$officeHead) {
            return response()->json([
                'success' => false,
                'message' => 'Office head not found.'
            ], 404);
        }

        $data = [
            'id' => $officeHead->id,
            'name' => $officeHead->name,
            'full_name' => $officeHead->full_name,
            'email' => $officeHead->email,
            'office' => $officeHead->officeRelation ? $officeHead->officeRelation->name : $officeHead->office,
            'office_id' => $officeHead->office_id,
            'contact' => $officeHead->contact,
            'gender' => $officeHead->gender,
            'active' => $officeHead->active,
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Create a new office head account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createOfficeHead(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'office' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20|regex:/^09\d{9}$/',
            'gender' => 'required|string|in:male,female,other',
            'active' => 'nullable|boolean',
        ]);

        // Find office by name
        $office = \App\Models\Office::where('name', $request->office)->first();
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found.'
            ], 422);
        }

        $passwordHash = \Hash::make($request->password);

        $officeHead = \App\Models\User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $passwordHash,
            'password_hash' => $passwordHash,
            'role' => 'Office Head',
            'office' => $request->office, // Keep for backward compatibility
            'office_id' => $office->id,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'active' => $request->active ?? 1,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Office head account created successfully!',
            'data' => $officeHead->load('officeRelation')
        ]);
    }

    /**
     * Update an office head account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOfficeHead(\Illuminate\Http\Request $request, $id)
    {
        $officeHead = \App\Models\User::where('role', 'Office Head')->find($id);
        
        if (!$officeHead) {
            return response()->json([
                'success' => false,
                'message' => 'Office head not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'office' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20|regex:/^09\d{9}$/',
            'gender' => 'required|string|in:male,female,other',
            'active' => 'nullable|boolean',
        ]);

        // Find office by name
        $office = \App\Models\Office::where('name', $request->office)->first();
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found.'
            ], 422);
        }

        $updateData = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'office' => $request->office, // Keep for backward compatibility
            'office_id' => $office->id,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'active' => $request->active ?? $officeHead->active,
        ];

        if ($request->filled('password')) {
            $passwordHash = \Hash::make($request->password);
            $updateData['password'] = $passwordHash;
            $updateData['password_hash'] = $passwordHash;
        }

        $officeHead->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Office head account updated successfully!',
            'data' => $officeHead->load('officeRelation')
        ]);
    }

    /**
     * Delete an office head account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteOfficeHead($id)
    {
        $officeHead = \App\Models\User::where('role', 'Office Head')->find($id);
        
        if (!$officeHead) {
            return response()->json([
                'success' => false,
                'message' => 'Office head not found.'
            ], 404);
        }

        $officeHead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Office head account deleted successfully!'
        ]);
    }

    /**
     * Show Student Assistant Account & Profile Management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function saAccountManagement()
    {
        return view('hrpages.sa-account-management', [
            'layout' => 'side-menu',
            'pageTitle' => 'Student Assistant Account & Profile Management'
        ]);
    }

    /**
     * Get all student assistants with full profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSAAccounts()
    {
        $studentAssistants = \App\Models\User::where('role', 'Student Assistant')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $studentAssistants
        ]);
    }

    /**
     * Get a single student assistant account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSAAccount($id)
    {
        $studentAssistant = \App\Models\User::where('role', 'Student Assistant')->find($id);
        
        if (!$studentAssistant) {
            return response()->json([
                'success' => false,
                'message' => 'Student assistant not found.'
            ], 404);
        }

        // Decode work_schedule if it exists
        $data = $studentAssistant->toArray();
        if (isset($data['work_schedule']) && is_string($data['work_schedule'])) {
            $data['work_schedule'] = json_decode($data['work_schedule'], true);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Create a new student assistant account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSAAccount(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'student_id_number' => 'required|string|max:255|unique:users,student_id_number',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'office' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20|regex:/^09\d{9}$/',
            'gender' => 'required|string|in:male,female,other',
            'status' => 'nullable|string|in:Active,Inactive,Apprentice,Pending Assignment',
            'active' => 'nullable|boolean',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'scheduled_time_in' => 'nullable|date_format:H:i',
            'scheduled_time_out' => 'nullable|date_format:H:i',
            'work_schedule' => 'nullable|array',
            'work_schedule.*.time_in' => 'required_with:work_schedule|date_format:H:i',
            'work_schedule.*.time_out' => 'required_with:work_schedule|date_format:H:i',
        ]);

        $passwordHash = \Hash::make($request->password);
        
        // Set active based on status if status is provided, otherwise use request active or default to 1
        $status = $request->status ?? 'Active';
        $active = $request->has('active') ? $request->active : 
                  ($status === 'Active' || $status === 'Apprentice' ? 1 : 0);

        // Format scheduled times
        $scheduledTimeIn = null;
        if ($request->scheduled_time_in && !empty($request->scheduled_time_in)) {
            $timeValue = $request->scheduled_time_in;
            if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                $scheduledTimeIn = $timeValue . ':00';
            } else {
                $scheduledTimeIn = $timeValue ?: null;
            }
        }
        
        $scheduledTimeOut = null;
        if ($request->scheduled_time_out && !empty($request->scheduled_time_out)) {
            $timeValue = $request->scheduled_time_out;
            if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                $scheduledTimeOut = $timeValue . ':00';
            } else {
                $scheduledTimeOut = $timeValue ?: null;
            }
        }
        
        // Format work schedule slots
        $workSchedule = null;
        if ($request->work_schedule && is_array($request->work_schedule) && count($request->work_schedule) > 0) {
            $workSchedule = array_map(function($slot) {
                $timeIn = $slot['time_in'];
                $timeOut = $slot['time_out'];
                if (strlen($timeIn) === 5) $timeIn .= ':00';
                if (strlen($timeOut) === 5) $timeOut .= ':00';
                return [
                    'time_in' => $timeIn,
                    'time_out' => $timeOut
                ];
            }, $request->work_schedule);
        }

        $studentAssistant = \App\Models\User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'student_id_number' => $request->student_id_number,
            'email' => $request->email,
            'password' => $passwordHash,
            'password_hash' => $passwordHash,
            'role' => 'Student Assistant',
            'office' => $request->office,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'status' => $status,
            'active' => $active,
            'contract_start_date' => $request->contract_start_date ?: null,
            'contract_end_date' => $request->contract_end_date ?: null,
            'scheduled_time_in' => $scheduledTimeIn,
            'scheduled_time_out' => $scheduledTimeOut,
            'work_schedule' => $workSchedule ? json_encode($workSchedule) : null,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student assistant account created successfully!',
            'data' => $studentAssistant
        ]);
    }

    /**
     * Update a student assistant account and profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSAAccount(\Illuminate\Http\Request $request, $id)
    {
        $studentAssistant = \App\Models\User::where('role', 'Student Assistant')->find($id);
        
        if (!$studentAssistant) {
            return response()->json([
                'success' => false,
                'message' => 'Student assistant not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'student_id_number' => 'required|string|max:255|unique:users,student_id_number,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'office' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20|regex:/^09\d{9}$/',
            'gender' => 'required|string|in:male,female,other',
            'status' => 'nullable|string|in:Active,Inactive,Apprentice,Pending Assignment',
            'active' => 'nullable|boolean',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'scheduled_time_in' => 'nullable|date_format:H:i',
            'scheduled_time_out' => 'nullable|date_format:H:i',
            'work_schedule' => 'nullable|array',
            'work_schedule.*.time_in' => 'required_with:work_schedule|date_format:H:i',
            'work_schedule.*.time_out' => 'required_with:work_schedule|date_format:H:i',
        ]);

        // Set active based on status if status is provided, otherwise use request active or keep existing
        $status = $request->status ?? $studentAssistant->status ?? 'Active';
        $active = $request->has('active') ? $request->active : 
                  ($request->has('status') ? ($status === 'Active' || $status === 'Apprentice' ? 1 : 0) : $studentAssistant->active);

        $updateData = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'student_id_number' => $request->student_id_number,
            'email' => $request->email,
            'office' => $request->office,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'status' => $status,
            'active' => $active,
            'contract_start_date' => $request->contract_start_date ?: null,
            'contract_end_date' => $request->contract_end_date ?: null,
        ];
        
        // Format scheduled times
        if ($request->has('scheduled_time_in')) {
            if ($request->scheduled_time_in && !empty($request->scheduled_time_in)) {
                $timeValue = $request->scheduled_time_in;
                if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                    $updateData['scheduled_time_in'] = $timeValue . ':00';
                } else {
                    $updateData['scheduled_time_in'] = $timeValue ?: null;
                }
            } else {
                $updateData['scheduled_time_in'] = null;
            }
        }
        
        if ($request->has('scheduled_time_out')) {
            if ($request->scheduled_time_out && !empty($request->scheduled_time_out)) {
                $timeValue = $request->scheduled_time_out;
                if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                    $updateData['scheduled_time_out'] = $timeValue . ':00';
                } else {
                    $updateData['scheduled_time_out'] = $timeValue ?: null;
                }
            } else {
                $updateData['scheduled_time_out'] = null;
            }
        }
        
        // Format work schedule
        if ($request->has('work_schedule')) {
            if ($request->work_schedule && is_array($request->work_schedule) && count($request->work_schedule) > 0) {
                $workSchedule = array_map(function($slot) {
                    $timeIn = $slot['time_in'];
                    $timeOut = $slot['time_out'];
                    if (strlen($timeIn) === 5) $timeIn .= ':00';
                    if (strlen($timeOut) === 5) $timeOut .= ':00';
                    return [
                        'time_in' => $timeIn,
                        'time_out' => $timeOut
                    ];
                }, $request->work_schedule);
                $updateData['work_schedule'] = json_encode($workSchedule);
            } else {
                $updateData['work_schedule'] = null;
            }
        }

        if ($request->filled('password')) {
            $passwordHash = \Hash::make($request->password);
            $updateData['password'] = $passwordHash;
            $updateData['password_hash'] = $passwordHash;
        }

        $studentAssistant->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Student assistant account updated successfully!',
            'data' => $studentAssistant
        ]);
    }

    /**
     * Delete a student assistant account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSAAccount($id)
    {
        $studentAssistant = \App\Models\User::where('role', 'Student Assistant')->find($id);
        
        if (!$studentAssistant) {
            return response()->json([
                'success' => false,
                'message' => 'Student assistant not found.'
            ], 404);
        }

        $studentAssistant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student assistant account deleted successfully!'
        ]);
    }

    /**
     * Process photo from request (file upload or base64).
     * Returns the stored path or null.
     */
    private function processAttendancePhoto(\Illuminate\Http\Request $request, $userId, $today, $prefix = 'time_in')
    {
        try {
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = $prefix . '_' . $userId . '_' . $today . '_' . time() . '.' . $photo->getClientOriginalExtension();
                return $photo->storeAs('attendance_photos', $photoName, 'public');
            }

            if ($request->has('photo_base64') && !empty($request->input('photo_base64'))) {
                $base64Image = $request->input('photo_base64');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $image = substr($base64Image, strpos($base64Image, ',') + 1);
                    $image = base64_decode($image, true);
                    if ($image === false) return null;

                    $type = strtolower($type[1]);
                    if (!in_array($type, ['jpg', 'jpeg', 'png'])) return null;

                    $directory = storage_path('app/public/attendance_photos');
                    if (!\File::exists($directory)) {
                        \File::makeDirectory($directory, 0755, true);
                    }

                    $photoName = $prefix . '_' . $userId . '_' . $today . '_' . time() . '.' . $type;
                    $photoPath = 'attendance_photos/' . $photoName;

                    if (\Storage::disk('public')->put($photoPath, $image)) {
                        return $photoPath;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error processing {$prefix} photo: " . $e->getMessage(), [
                'user_id' => $userId,
                'error' => $e->getTraceAsString()
            ]);
        }
        return null;
    }

    /**
     * Resolve the user's office name.
     */
    private function resolveOfficeName($user)
    {
        $officeName = $user->office;
        try {
            if (method_exists($user, 'officeRelation') && $user->relationLoaded('officeRelation') && $user->officeRelation) {
                $officeName = $user->officeRelation->name;
            } elseif (!$officeName && \Schema::hasColumn('users', 'office_id') && $user->office_id) {
                $officeModel = \App\Models\Office::find($user->office_id);
                if ($officeModel) {
                    $officeName = $officeModel->name;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Error resolving office name', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
        return $officeName;
    }

    /**
     * Format a time string to 12-hour format for display.
     */
    private function formatTime($dateString, $timeString)
    {
        if (!$timeString) return null;
        try {
            return \Carbon\Carbon::parse($dateString . ' ' . $timeString, 'Asia/Manila')
                ->setTimezone('Asia/Manila')
                ->format('g:i A');
        } catch (\Exception $e) {
            return $timeString;
        }
    }

    /**
     * Format minutes into human-readable string.
     */
    private function formatMinutes($minutes)
    {
        if (!$minutes || $minutes <= 0) return '--';
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        if ($h > 0 && $m > 0) return "{$h}h {$m}m";
        if ($h > 0) return "{$h}h";
        return "{$m}m";
    }

    /**
     * Determine Present/Late status by comparing actual time against expected + grace.
     * Returns ['status' => string, 'remarks' => string|null, 'late_minutes' => int].
     */
    private function determineTimeInStatus($actualTimeString, $expectedTimeString, $dateString, $contextLabel = '')
    {
        $grace = config('attendance.grace_minutes', 5);

        if (!$expectedTimeString) {
            return ['status' => 'Present', 'remarks' => null, 'late_minutes' => 0];
        }

        try {
            $actual   = \Carbon\Carbon::parse($dateString . ' ' . $actualTimeString, 'Asia/Manila');
            $expected = \Carbon\Carbon::parse($dateString . ' ' . $expectedTimeString, 'Asia/Manila');
            $deadline = $expected->copy()->addMinutes($grace);

            if ($actual->lte($deadline)) {
                return ['status' => 'Present', 'remarks' => null, 'late_minutes' => 0];
            }

            $lateMinutes = $actual->diffInMinutes($deadline);
            $expectedFormatted = $expected->format('g:i A');
            $label = $contextLabel ? "{$contextLabel}: " : '';
            $remarks = "{$label}Late by {$lateMinutes} min (scheduled: {$expectedFormatted}, grace: {$grace} min)";

            return ['status' => 'Late', 'remarks' => $remarks, 'late_minutes' => $lateMinutes];
        } catch (\Exception $e) {
            \Log::warning('Error in determineTimeInStatus', ['error' => $e->getMessage()]);
            return ['status' => 'Present', 'remarks' => null, 'late_minutes' => 0];
        }
    }

    /**
     * Time In for student assistant (segment-aware).
     */
    public function timeIn(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated. Please log in again.'], 401);
        }
        if ($user->role !== 'Student Assistant') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only Student Assistants can time in.'], 403);
        }

        if (method_exists($user, 'officeRelation')) {
            $user->load('officeRelation');
        }

        $nowManila = now('Asia/Manila');
        $today     = $nowManila->toDateString();
        $timeIn    = $nowManila->toTimeString();

        // Check for an open segment (timed in but not out)
        $todayAttendance = \App\Models\Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->orderBy('time_in', 'desc')
            ->first();

        if ($todayAttendance) {
            $openSegment = \App\Models\AttendanceSegment::where('attendance_id', $todayAttendance->id)
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->first();
            ]);

            // Update parent attendance status based on first segment
            if ($isFirstTimeIn) {
                $todayAttendance->update(['status' => $statusResult['status']]);
            }
            // Sync parent from segments (time_in stays as first, time_out stays as latest)
            $todayAttendance->syncFromSegments();

            return response()->json([
                'success' => true,
                'message' => 'Time in recorded successfully!',
                'data' => [
                    'time_in'           => $timeIn,
                    'formatted_time_in' => $nowManila->format('g:i A'),
                    'status'            => $statusResult['status'],
                    'date'              => $today,
                    'photo_captured'    => !empty($timeInPhoto),
                    'segment_order'     => $segmentOrder,
                    'segment_type'      => $segmentType,
                    'late_minutes'      => $statusResult['late_minutes'],
                    'remarks'           => $statusResult['remarks'],
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Time in error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'date'    => $today,
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to record time in. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Time Out for student assistant (segment-aware).
     */
    public function timeOut(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated. Please log in again.'], 401);
        }
        if ($user->role !== 'Student Assistant') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only Student Assistants can time out.'], 403);
        }

        $nowManila = now('Asia/Manila');
        $today     = $nowManila->toDateString();
        $timeOut   = $nowManila->toTimeString();

        try {
            // Find today's attendance
            $attendance = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->orderBy('time_in', 'desc')
                ->first();

            if (!$attendance) {
                return response()->json(['success' => false, 'message' => 'You have no open time-in session. Please time in first.'], 422);
            }

            // Find the open segment
            $openSegment = \App\Models\AttendanceSegment::where('attendance_id', $attendance->id)
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->first();

            if (!$openSegment) {
                return response()->json(['success' => false, 'message' => 'You have no open time-in session. Please time in first.'], 422);
            }

            $timeOutPhoto = $this->processAttendancePhoto($request, $user->id, $today, 'time_out');

            // Calculate segment minutes
            $segmentMinutes = 0;
            try {
                $tIn  = \Carbon\Carbon::parse($today . ' ' . $openSegment->time_in, 'Asia/Manila');
                $tOut = \Carbon\Carbon::parse($today . ' ' . $timeOut, 'Asia/Manila');
                $segmentMinutes = $tOut->diffInMinutes($tIn);
            } catch (\Exception $e) {
                \Log::error('Error calculating segment minutes', ['error' => $e->getMessage()]);
            }
            
            // Calculate total minutes worked today (including all completed sessions + current session)
            $totalMinutesToday = $totalMinutes; // Start with current session
            $completedSessions = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->whereNotNull('time_in')
                ->whereNotNull('time_out')
                ->where('id', '!=', $attendance->id) // Exclude current session
                ->get();
            
            foreach ($completedSessions as $session) {
                if ($session->total_minutes) {
                    $totalMinutesToday += $session->total_minutes;
                }
            }
            
            // Automatic overtime detection (no blocking - just log it)
            $isOvertime = false;
            $minimumMinutes = 300; // 5 hours
            if ($totalMinutesToday > $minimumMinutes) {
                $isOvertime = true;
                // Log overtime session
                \Log::info('Overtime detected for Student Assistant', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'date' => $today,
                    'total_minutes_today' => $totalMinutesToday,
                    'overtime_minutes' => $totalMinutesToday - $minimumMinutes,
                    'sessions_count' => $completedSessions->count() + 1
                ]);
            }

            // Detect if this time-out is off-schedule
            $isOffSchedule = false;
            $warningMessage = null;
            $breakWindow = config('attendance.break_window_minutes', 10);

            $scheduledTimeOut = $user->scheduled_time_out;
            $expectedSegmentEnd = $openSegment->expected_time_out;

            if ($expectedSegmentEnd && $scheduledTimeOut) {
                try {
                    $actualOut    = \Carbon\Carbon::parse($today . ' ' . $timeOut, 'Asia/Manila');
                    $expectedEnd  = \Carbon\Carbon::parse($today . ' ' . $expectedSegmentEnd, 'Asia/Manila');
                    $schedEnd     = \Carbon\Carbon::parse($today . ' ' . $scheduledTimeOut, 'Asia/Manila');
                    $windowStart  = $expectedEnd->copy()->subMinutes($breakWindow);

                    $isNearExpectedEnd = $actualOut->between($windowStart, $expectedEnd->copy()->addMinutes($breakWindow));
                    $isEndOfDay        = $actualOut->gte($schedEnd->copy()->subMinutes($breakWindow));

                    if (!$isNearExpectedEnd && !$isEndOfDay) {
                        $isOffSchedule = true;
                        $expectedFmt = $expectedEnd->format('g:i A');
                        $warningMessage = "Unscheduled early time-out. Expected segment end: {$expectedFmt}. This has been logged but you are not restricted.";
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error checking off-schedule time-out', ['error' => $e->getMessage()]);
                }
            }
            
            // Log session (time in and time out)
            \Log::info('Attendance session completed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'attendance_id' => $attendance->id,
                'date' => $today,
                'time_in' => $attendance->time_in,
                'time_out' => $timeOut,
                'total_minutes' => $totalMinutes,
                'total_minutes_today' => $totalMinutesToday,
                'status' => $status,
                'is_overtime' => $isOvertime,
                'session_number' => $completedSessions->count() + 1
            ]);

            // Handle photo upload if provided
            $timeOutPhoto = null;
            try {
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = 'time_out_' . $user->id . '_' . $today . '_' . time() . '.' . $photo->getClientOriginalExtension();
                    $photoPath = $photo->storeAs('attendance_photos', $photoName, 'public');
                    $timeOutPhoto = $photoPath;
                } elseif ($request->has('photo_base64') && !empty($request->input('photo_base64'))) {
                    // Handle base64 image from camera
                    $base64Image = $request->input('photo_base64');
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                        $image = substr($base64Image, strpos($base64Image, ',') + 1);
                        $image = base64_decode($image, true);
                        
                        if ($image === false) {
                            \Log::warning('Failed to decode base64 image for time out', ['user_id' => $user->id]);
                        } else {
                            $type = strtolower($type[1]);
                            
                            if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                                \Log::warning('Invalid image type for time out', ['type' => $type, 'user_id' => $user->id]);
                            } else {
                                // Ensure directory exists
                                $directory = storage_path('app/public/attendance_photos');
                                if (!\File::exists($directory)) {
                                    \File::makeDirectory($directory, 0755, true);
                                }
                                
                                $photoName = 'time_out_' . $user->id . '_' . $today . '_' . time() . '.' . $type;
                                $photoPath = 'attendance_photos/' . $photoName;
                                
                                if (\Storage::disk('public')->put($photoPath, $image)) {
                                    $timeOutPhoto = $photoPath;
                                } else {
                                    \Log::error('Failed to save time out photo', ['user_id' => $user->id, 'path' => $photoPath]);
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $photoError) {
                // Log photo error but don't block time out
                \Log::error('Error processing time out photo: ' . $photoError->getMessage(), [
                    'user_id' => $user->id,
                    'error' => $photoError->getTraceAsString()
                ]);
                // Continue without photo - photo is optional for now
            }

            // Build remarks
            $segmentRemarks = $openSegment->remarks;
            if ($isOffSchedule && $warningMessage) {
                $segmentRemarks = $segmentRemarks ? $segmentRemarks . ' | ' . $warningMessage : $warningMessage;
            }

            // Close the segment
            $openSegment->update([
                'time_out'       => $timeOut,
                'time_out_photo' => $timeOutPhoto,
                'total_minutes'  => $segmentMinutes,
                'status'         => 'Completed',
                'remarks'        => $segmentRemarks,
            ]);

            // Sync parent attendance from all segments
            $attendance->syncFromSegments();

            // Calculate total minutes across all segments today
            $totalMinutesToday = \App\Models\AttendanceSegment::where('attendance_id', $attendance->id)
                ->sum('total_minutes') ?: 0;

            // Overtime detection
            $minimumMinutes = 300;
            $isOvertime = $totalMinutesToday > $minimumMinutes;

            // Determine overall attendance status
            $expectedSegments = \App\Models\Attendance::buildExpectedSegments($user);
            $completedCount   = \App\Models\AttendanceSegment::where('attendance_id', $attendance->id)
                ->where('status', 'Completed')->count();
            $totalExpected    = max(count($expectedSegments), 1);

            $overallStatus = $attendance->status;
            if ($completedCount >= $totalExpected && $totalMinutesToday >= $minimumMinutes) {
                $overallStatus = 'Completed';
            }
            $attendance->update(['status' => $overallStatus]);

            // Get all segments for response
            $allSegments = \App\Models\AttendanceSegment::where('attendance_id', $attendance->id)
                ->orderBy('segment_order')
                ->get()
                ->map(function ($seg) use ($today) {
                    return [
                        'id'             => $seg->id,
                        'segment_order'  => $seg->segment_order,
                        'segment_type'   => $seg->segment_type,
                        'time_in'        => $this->formatTime($today, $seg->time_in),
                        'time_out'       => $this->formatTime($today, $seg->time_out),
                        'expected_in'    => $this->formatTime($today, $seg->expected_time_in),
                        'expected_out'   => $this->formatTime($today, $seg->expected_time_out),
                        'total_minutes'  => $seg->total_minutes,
                        'status'         => $seg->status,
                        'remarks'        => $seg->remarks,
                    ];
                });

            // Format total time for today
            $formattedTotalTimeToday = '--';
            if ($totalMinutesToday > 0) {
                $hours = floor($totalMinutesToday / 60);
                $minutes = $totalMinutesToday % 60;
                if ($hours > 0 && $minutes > 0) {
                    $formattedTotalTimeToday = "{$hours}h {$minutes}m";
                } elseif ($hours > 0) {
                    $formattedTotalTimeToday = "{$hours}h";
                } else {
                    $formattedTotalTimeToday = "{$minutes}m";
                }
            }
            
            // Get all sessions for today for logging
            $allSessionsToday = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->whereNotNull('time_in')
                ->orderBy('time_in', 'asc')
                ->get()
                ->map(function($session) use ($dateString) {
                    $timeInFmt = null;
                    $timeOutFmt = null;
                    if ($session->time_in) {
                        try {
                            $timeInFmt = \Carbon\Carbon::parse($dateString . ' ' . $session->time_in, 'Asia/Manila')
                                ->setTimezone('Asia/Manila')
                                ->format('g:i A');
                        } catch (\Exception $e) {
                            $timeInFmt = $session->time_in;
                        }
                    }
                    if ($session->time_out) {
                        try {
                            $timeOutFmt = \Carbon\Carbon::parse($dateString . ' ' . $session->time_out, 'Asia/Manila')
                                ->setTimezone('Asia/Manila')
                                ->format('g:i A');
                        } catch (\Exception $e) {
                            $timeOutFmt = $session->time_out;
                        }
                    }
                    return [
                        'id' => $session->id,
                        'time_in' => $timeInFmt,
                        'time_out' => $timeOutFmt,
                        'total_minutes' => $session->total_minutes,
                        'status' => $session->status,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => $isOffSchedule
                    ? 'Time out recorded. Warning: this was an unscheduled time-out and has been logged.'
                    : ($isOvertime ? 'Time out recorded successfully! Overtime detected and logged.' : 'Time out recorded successfully!'),
                'data' => [
                    'time_out'                  => $timeOut,
                    'formatted_time_out'        => $nowManila->format('g:i A'),
                    'total_minutes'             => $segmentMinutes,
                    'formatted_total_time'      => $this->formatMinutes($segmentMinutes),
                    'total_minutes_today'       => $totalMinutesToday,
                    'formatted_total_time_today'=> $this->formatMinutes($totalMinutesToday),
                    'status'                    => $overallStatus,
                    'is_overtime'               => $isOvertime,
                    'is_off_schedule'           => $isOffSchedule,
                    'warning_message'           => $warningMessage,
                    'segments'                  => $allSegments,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Time out error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'date'    => $today,
                'error'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to record time out. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get today's attendance for current user (segment-aware).
     */
    public function getTodayAttendance()
    {
        $user = \Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }

        $today = now('Asia/Manila')->toDateString();

        try {
            // Get all attendance records for today, ordered by time_in desc
            $attendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->orderBy('time_in', 'desc')
                ->get();

            if ($attendances->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No attendance record for today.',
                    'schedule' => $this->buildScheduleInfo($user, $today),
                ]);
            }

            $dateString = $attendance->date instanceof \Carbon\Carbon
                ? $attendance->date->format('Y-m-d')
                : ($attendance->date ?? $today);

            // Load segments
            $segments = \App\Models\AttendanceSegment::where('attendance_id', $attendance->id)
                ->orderBy('segment_order')
                ->get();

            $openSegment = $segments->whereNull('time_out')->whereNotNull('time_in')->first();
            $totalMinutesToday = $segments->sum('total_minutes') ?: 0;
            $minimumMinutes = 300;
            $isOvertime = $totalMinutesToday > $minimumMinutes;

            // Determine if there's an open session via segments
            $hasOpenSession = $openSegment !== null;

            $formattedSegments = $segments->map(function ($seg) use ($dateString) {
                return [
                    'id'             => $seg->id,
                    'segment_order'  => $seg->segment_order,
                    'segment_type'   => $seg->segment_type,
                    'time_in'        => $this->formatTime($dateString, $seg->time_in),
                    'time_in_raw'    => $seg->time_in,
                    'time_out'       => $this->formatTime($dateString, $seg->time_out),
                    'time_out_raw'   => $seg->time_out,
                    'expected_in'    => $this->formatTime($dateString, $seg->expected_time_in),
                    'expected_out'   => $this->formatTime($dateString, $seg->expected_time_out),
                    'total_minutes'  => $seg->total_minutes,
                    'status'         => $seg->status,
                    'remarks'        => $seg->remarks,
                ];
            });

            // Build warnings from segments
            $warnings = $segments->filter(function ($seg) {
                return $seg->remarks && (
                    str_contains($seg->remarks, 'Late') ||
                    str_contains($seg->remarks, 'Unscheduled') ||
                    str_contains($seg->remarks, 'unscheduled')
                );
            })->map(function ($seg) {
                return $seg->remarks;
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'id'                         => $attendance->id,
                    'time_in'                    => $this->formatTime($dateString, $attendance->time_in),
                    'time_in_raw'                => $attendance->time_in,
                    'time_out'                   => $this->formatTime($dateString, $attendance->time_out),
                    'time_out_raw'               => $attendance->time_out,
                    'total_minutes'              => $attendance->total_minutes,
                    'total_minutes_today'        => $totalMinutesToday,
                    'formatted_total_time'       => $this->formatMinutes($attendance->total_minutes),
                    'formatted_total_time_today' => $this->formatMinutes($totalMinutesToday),
                    'status'                     => $attendance->status,
                    'date'                       => $dateString,
                    'is_overtime'                => $isOvertime,
                    'has_open_session'           => $hasOpenSession,
                    'segments'                   => $formattedSegments,
                    'current_segment'            => $openSegment ? [
                        'id'            => $openSegment->id,
                        'segment_order' => $openSegment->segment_order,
                        'segment_type'  => $openSegment->segment_type,
                        'time_in'       => $this->formatTime($dateString, $openSegment->time_in),
                        'time_in_raw'   => $openSegment->time_in,
                        'expected_out'  => $this->formatTime($dateString, $openSegment->expected_time_out),
                        'status'        => $openSegment->status,
                    ] : null,
                    'warnings'                   => $warnings,
                ],
                'schedule' => $this->buildScheduleInfo($user, $today),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading today attendance: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'date'    => $today,
                'error'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load today attendance.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Build schedule info for the frontend.
     */
    private function buildScheduleInfo($user, $today)
    {
        $scheduledIn  = $user->scheduled_time_in;
        $scheduledOut = $user->scheduled_time_out;

        $breaks = $user->work_schedule;
        if (is_string($breaks)) {
            $breaks = json_decode($breaks, true);
        }

        $formattedBreaks = null;
        if ($breaks && is_array($breaks)) {
            $formattedBreaks = array_map(function ($brk) use ($today) {
                return [
                    'start' => $this->formatTime($today, strlen($brk['time_in']) === 5 ? $brk['time_in'] . ':00' : $brk['time_in']),
                    'end'   => $this->formatTime($today, strlen($brk['time_out']) === 5 ? $brk['time_out'] . ':00' : $brk['time_out']),
                ];
            }, $breaks);
        }

        $expectedSegments = \App\Models\Attendance::buildExpectedSegments($user);
        $formattedExpected = array_map(function ($seg) use ($today) {
            return [
                'expected_in'  => $this->formatTime($today, $seg['expected_time_in']),
                'expected_out' => $this->formatTime($today, $seg['expected_time_out']),
            ];
        }, $expectedSegments);

        return [
            'scheduled_time_in'  => $this->formatTime($today, $scheduledIn),
            'scheduled_time_out' => $this->formatTime($today, $scheduledOut),
            'breaks'             => $formattedBreaks,
            'expected_segments'  => $formattedExpected,
            'grace_minutes'      => config('attendance.grace_minutes', 5),
        ];
    }

    /**
     * Get attendance history for current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAttendanceHistory(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        $limit = $request->get('limit', 100); // Increased limit for filtering
        $year = $request->get('year');
        $month = $request->get('month');
        $day = $request->get('day');

        try {
            // Build query with filters
            $query = \App\Models\Attendance::where('user_id', $user->id);
            
            // Apply year filter
            if ($year) {
                $query->whereYear('date', $year);
            }
            
            // Apply month filter
            if ($month) {
                $query->whereMonth('date', $month);
            }
            
            // Apply day filter
            if ($day) {
                $query->whereDay('date', $day);
            }
            
            // Get all attendance records, ordered by date and time_in descending
            $attendances = $query
                ->orderBy('date', 'desc')
                ->orderBy('time_in', 'desc')
                ->limit($limit)
                ->get();

            $data = $attendances->map(function ($attendance) {
                $dateString = $attendance->date->format('Y-m-d');
                
                $timeInFormatted = '--';
                if ($attendance->time_in) {
                    try {
                        // Parse time assuming it's in Asia/Manila timezone
                        $timeInFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_in, 'Asia/Manila')
                            ->setTimezone('Asia/Manila')
                            ->format('g:i A');
                    } catch (\Exception $e) {
                        $timeInFormatted = $attendance->time_in;
                    }
                }
                
                $timeOutFormatted = '--';
                if ($attendance->time_out) {
                    try {
                        // Parse time assuming it's in Asia/Manila timezone
                        $timeOutFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_out, 'Asia/Manila')
                            ->setTimezone('Asia/Manila')
                            ->format('g:i A');
                    } catch (\Exception $e) {
                        $timeOutFormatted = $attendance->time_out;
                    }
                }
                
                // Format total time
                $formattedTotalTime = '--';
                if ($attendance->total_minutes) {
                    $hours = floor($attendance->total_minutes / 60);
                    $minutes = $attendance->total_minutes % 60;
                    if ($hours > 0 && $minutes > 0) {
                        $formattedTotalTime = "{$hours}h {$minutes}m";
                    } elseif ($hours > 0) {
                        $formattedTotalTime = "{$hours}h";
                    } else {
                        $formattedTotalTime = "{$minutes}m";
                    }
                }
                
                // Get photo URLs
                $timeInPhotoUrl = null;
                $timeOutPhotoUrl = null;
                if ($attendance->time_in_photo) {
                    if (\Storage::disk('public')->exists($attendance->time_in_photo)) {
                        // Use asset() for proper URL generation
                        $timeInPhotoUrl = asset('storage/' . $attendance->time_in_photo);
                    }
                }
                if ($attendance->time_out_photo) {
                    if (\Storage::disk('public')->exists($attendance->time_out_photo)) {
                        // Use asset() for proper URL generation
                        $timeOutPhotoUrl = asset('storage/' . $attendance->time_out_photo);
                    }
                }
                
                return [
                    'id' => $attendance->id,
                    'date' => $dateString,
                    'formatted_date' => $attendance->date->format('M d, Y'),
                    'year' => $attendance->date->format('Y'),
                    'month' => $attendance->date->format('m'),
                    'day' => $attendance->date->format('d'),
                    'time_in' => $timeInFormatted,
                    'time_out' => $timeOutFormatted,
                    'time_in_photo' => $timeInPhotoUrl,
                    'time_out_photo' => $timeOutPhotoUrl,
                    'total_minutes' => $attendance->total_minutes,
                    'formatted_total_time' => $formattedTotalTime,
                    'status' => $attendance->status,
                    'office' => $attendance->office,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => $data->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading attendance history: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance history.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get attendance details for a specific user (for office heads).
     *
     * @param  int  $userId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserAttendanceDetails($userId, \Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $office = $user->officeRelation ? $user->officeRelation->name : $user->office;
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            // Get the student assistant
            $sa = \App\Models\User::find($userId);
            
            if (!$sa || $sa->role !== 'Student Assistant') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student Assistant not found.'
                ], 404);
            }

            // Verify the SA belongs to the office head's office
            $saOffice = $sa->officeRelation ? $sa->officeRelation->name : $sa->office;
            if ($saOffice !== $office) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student assistant does not belong to your office.'
                ], 403);
            }

            // Get date filter (default to today only for DTR Summary)
            $date = $request->get('date', now()->toDateString());
            $startDate = $request->get('start_date', $date);
            $endDate = $request->get('end_date', $date);

            // Get attendance records
            $attendances = \App\Models\Attendance::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->orderBy('time_in', 'desc')
                ->get();

            $data = $attendances->map(function ($attendance) {
                $dateString = $attendance->date instanceof \Carbon\Carbon 
                    ? $attendance->date->format('Y-m-d') 
                    : $attendance->date;

                $timeInFormatted = '--';
                if ($attendance->time_in) {
                    try {
                        $timeInFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_in)->format('g:i A');
                    } catch (\Exception $e) {
                        $timeInFormatted = $attendance->time_in;
                    }
                }

                $timeOutFormatted = '--';
                if ($attendance->time_out) {
                    try {
                        $timeOutFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_out)->format('g:i A');
                    } catch (\Exception $e) {
                        $timeOutFormatted = $attendance->time_out;
                    }
                }

                // Format total time
                $formattedTotalTime = '--';
                if ($attendance->total_minutes) {
                    $hours = floor($attendance->total_minutes / 60);
                    $minutes = $attendance->total_minutes % 60;
                    if ($hours > 0 && $minutes > 0) {
                        $formattedTotalTime = "{$hours}h {$minutes}m";
                    } elseif ($hours > 0) {
                        $formattedTotalTime = "{$hours}h";
                    } else {
                        $formattedTotalTime = "{$minutes}m";
                    }
                }

                // Get photo URLs
                $timeInPhotoUrl = null;
                if ($attendance->time_in_photo) {
                    $timeInPhotoUrl = asset('storage/' . $attendance->time_in_photo);
                }
                
                $timeOutPhotoUrl = null;
                if ($attendance->time_out_photo) {
                    $timeOutPhotoUrl = asset('storage/' . $attendance->time_out_photo);
                }

                return [
                    'id' => $attendance->id,
                    'date' => $dateString,
                    'formatted_date' => $attendance->date->format('M d, Y'),
                    'time_in' => $timeInFormatted,
                    'time_out' => $timeOutFormatted,
                    'total_minutes' => $attendance->total_minutes,
                    'formatted_total_time' => $formattedTotalTime,
                    'status' => $attendance->status,
                    'remarks' => $attendance->remarks,
                    'time_in_photo' => $timeInPhotoUrl,
                    'time_out_photo' => $timeOutPhotoUrl,
                    'review_status' => $attendance->review_status ?? 'Pending',
                    'reviewed_by' => $attendance->reviewed_by,
                    'reviewed_at' => $attendance->reviewed_at ? $attendance->reviewed_at->format('Y-m-d H:i:s') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $sa->id,
                        'name' => $sa->full_name ?? $sa->name,
                        'student_id' => $sa->student_id_number,
                        'email' => $sa->email,
                    ],
                    'attendances' => $data,
                    'total' => $data->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading user attendance details: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'target_user_id' => $userId,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance details.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show Office Head SA Management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function officeHeadSAManagement()
    {
        return view('officeheadpages.sa-management', [
            'layout' => 'side-menu',
            'pageTitle' => 'SA Management & Performance'
        ]);
    }

    /**
     * Show SA Account Management page for Office Heads.
     *
     * @return \Illuminate\Http\Response
     */
    public function officeHeadSAAccountManagement()
    {
        return view('officeheadpages.sa-account-management', [
            'layout' => 'side-menu',
            'pageTitle' => 'SA Account Management'
        ]);
    }

    /**
     * Get Student Assistants under office head's office.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfficeSAs()
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads and HR can access this.'
            ], 403);
        }

        try {
        $office = $user->office;
        
            // HR can see all student assistants, Office Head only sees their office
            if ($user->role === 'HR') {
                $studentAssistants = \App\Models\User::where('role', 'Student Assistant')
                    ->orderBy('name')
                    ->get();
                
                return response()->json([
                    'success' => true,
                    'data' => $studentAssistants->map(function($sa) {
                        return [
                            'id' => $sa->id,
                            'name' => $sa->name,
                            'full_name' => $sa->full_name,
                            'student_id_number' => $sa->student_id_number,
                            'email' => $sa->email,
                            'contact' => $sa->contact,
                            'course' => $sa->course,
                            'year_level' => $sa->year_level,
                            'section' => $sa->section,
                            'office' => $sa->office,
                            'status' => $sa->status,
                            'active' => $sa->active,
                            'scheduled_time_in' => $sa->scheduled_time_in,
                            'scheduled_time_out' => $sa->scheduled_time_out,
                            'work_schedule' => $sa->work_schedule ? json_decode($sa->work_schedule, true) : null,
                        ];
                    }),
                    'office' => null // HR doesn't have a specific office
                ]);
            } else {
                // Office Head role
                $officeId = $user->office_id;
                $office = $user->office;
                
                if (!$officeId && !$office) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No office assigned to your account.'
                    ], 422);
                }

                // Query student assistants - check both office_id and office name
                // This ensures we find all SAs regardless of whether they have office_id set or not
                $query = \App\Models\User::where('role', 'Student Assistant');
                
                if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                    // Check both office_id OR office name to catch all SAs
                    $query->where(function($q) use ($officeId, $office) {
                        $q->where('office_id', $officeId)
                          ->orWhereRaw('LOWER(TRIM(office)) = LOWER(TRIM(?))', [$office]);
                    });
                } else {
                    // Fall back to office name comparison only
                    $query->whereRaw('LOWER(TRIM(office)) = LOWER(TRIM(?))', [$office]);
                }
                
                $studentAssistants = $query->orderBy('name')->get();

                \Log::info('Office Head SA query', [
                    'office_head_id' => $user->id,
                    'office_head_office_id' => $officeId,
                    'office_head_office' => $office,
                    'student_assistants_count' => $studentAssistants->count(),
                    'student_assistants' => $studentAssistants->map(function($sa) {
                        return [
                            'id' => $sa->id,
                            'name' => $sa->name,
                            'full_name' => $sa->full_name,
                            'office_id' => $sa->office_id,
                            'office' => $sa->office,
                            'status' => $sa->status
                        ];
                    })
                ]);

        return response()->json([
            'success' => true,
            'data' => $studentAssistants->map(function($sa) {
                return [
                    'id' => $sa->id,
                    'name' => $sa->name,
                    'full_name' => $sa->full_name,
                    'student_id_number' => $sa->student_id_number,
                    'email' => $sa->email,
                    'contact' => $sa->contact,
                    'course' => $sa->course,
                    'year_level' => $sa->year_level,
                    'section' => $sa->section,
                    'office' => $sa->office,
                    'status' => $sa->status,
                    'active' => $sa->active,
                    'scheduled_time_in' => $sa->scheduled_time_in,
                    'scheduled_time_out' => $sa->scheduled_time_out,
                    'work_schedule' => $sa->work_schedule ? json_decode($sa->work_schedule, true) : null,
                ];
            }),
            'office' => $office
        ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error getting office student assistants: ' . $e->getMessage(), [
                'office' => $user->office ?? null,
                'user_id' => $user->id,
                'user_role' => $user->role
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading student assistants: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a student assistant account (Office Head only).
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOfficeSA($id, \Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can update student assistant accounts.'
            ], 403);
        }

        // Get office information - try office_id first, fall back to office name
        $officeId = $user->office_id;
        $office = $user->office;
        
        if (!$officeId && !$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            $studentAssistant = \App\Models\User::where('role', 'Student Assistant')
                ->where('id', $id)
                ->first();

            if (!$studentAssistant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student assistant not found.'
                ], 404);
            }

            // Check if SA belongs to office head's office (check both office_id and office name)
            $saBelongsToOffice = false;
            $saOfficeId = $studentAssistant->office_id;
            $saOffice = $studentAssistant->office;
            
            // Log for debugging
            \Log::info('Office comparison check', [
                'office_head_id' => $user->id,
                'office_head_office_id' => $officeId,
                'office_head_office' => $office,
                'sa_id' => $studentAssistant->id,
                'sa_office_id' => $saOfficeId,
                'sa_office' => $saOffice
            ]);
            
            // Priority 1: Compare office_id if both have it
            if ($officeId && $saOfficeId && \Schema::hasColumn('users', 'office_id')) {
                $saBelongsToOffice = ($saOfficeId == $officeId);
            }
            // Priority 2: Compare office names if both have them
            elseif ($office && $saOffice) {
                $saBelongsToOffice = (strtolower(trim($saOffice)) === strtolower(trim($office)));
            }
            // Priority 3: Cross-check office_id with office name
            elseif ($officeId && $saOffice && \Schema::hasColumn('users', 'office_id')) {
                // Try to find office by name and compare IDs
                $saOfficeModel = \App\Models\Office::where('name', $saOffice)->first();
                if ($saOfficeModel) {
                    $saBelongsToOffice = ($saOfficeModel->id == $officeId);
                }
            }
            elseif ($saOfficeId && $office && \Schema::hasColumn('users', 'office_id')) {
                // Try to find office head's office by name and compare IDs
                $officeHeadOfficeModel = \App\Models\Office::where('name', $office)->first();
                if ($officeHeadOfficeModel) {
                    $saBelongsToOffice = ($officeHeadOfficeModel->id == $saOfficeId);
                }
            }
            
            if (!$saBelongsToOffice) {
                \Log::warning('Office head tried to update SA from different office', [
                    'office_head_id' => $user->id,
                    'office_head_office_id' => $officeId,
                    'office_head_office' => $office,
                    'sa_id' => $studentAssistant->id,
                    'sa_office_id' => $saOfficeId,
                    'sa_office' => $saOffice
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. This student assistant does not belong to your office.'
                ], 403);
            }

            // Validate request
            $validationRules = [
                'full_name' => 'required|string|max:255',
                'student_id_number' => 'required|string|max:255|unique:users,student_id_number,' . $id,
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'contact' => 'nullable|string|max:20',
                'course' => 'nullable|string|max:255',
                'year_level' => 'nullable|string|max:50',
                'section' => 'nullable|string|max:50',
                'scheduled_time_in' => 'nullable|date_format:H:i',
                'scheduled_time_out' => 'nullable|date_format:H:i',
                'work_schedule' => 'nullable|array',
                'work_schedule.*.time_in' => 'required_with:work_schedule|date_format:H:i',
                'work_schedule.*.time_out' => 'required_with:work_schedule|date_format:H:i',
            ];
            
            // Only validate status if the column exists
            if (\Schema::hasColumn('users', 'status')) {
                $validationRules['status'] = 'required|string|in:Active,Inactive,Pending Assignment,Apprentice';
            }
            
            $request->validate($validationRules);

            // Prepare update data
            $updateData = [
                'full_name' => $request->full_name,
                'name' => $request->full_name, // Also update name field
                'student_id_number' => $request->student_id_number,
                'email' => $request->email,
            ];
            
            // Only update status if column exists
            if (\Schema::hasColumn('users', 'status') && $request->has('status')) {
                $updateData['status'] = $request->status;
            }
            
            // Update active based on status if status exists, otherwise use request value
            if (\Schema::hasColumn('users', 'status') && $request->has('status')) {
                $updateData['active'] = $request->status === 'Active' ? 1 : ($request->status === 'Apprentice' ? 1 : 0);
            } elseif ($request->has('status')) {
                // If status doesn't exist but was provided, just use it for active calculation
                $updateData['active'] = $request->status === 'Active' ? 1 : ($request->status === 'Apprentice' ? 1 : 0);
            }

            // Optional fields
            if ($request->has('contact')) {
                $updateData['contact'] = $request->contact;
            }
            if ($request->has('course')) {
                $updateData['course'] = $request->course;
            }
            if ($request->has('year_level')) {
                $updateData['year_level'] = $request->year_level;
            }
            if ($request->has('section')) {
                $updateData['section'] = $request->section;
            }
            if ($request->has('scheduled_time_in')) {
                if ($request->scheduled_time_in && !empty($request->scheduled_time_in)) {
                    // If time is already in HH:MM:SS format, use it; otherwise add :00
                    $timeValue = $request->scheduled_time_in;
                    if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                        // Format is HH:MM, add seconds
                        $updateData['scheduled_time_in'] = $timeValue . ':00';
                    } else {
                        // Already in correct format or empty
                        $updateData['scheduled_time_in'] = $timeValue ?: null;
                    }
                } else {
                    $updateData['scheduled_time_in'] = null;
                }
            }
            
            if ($request->has('scheduled_time_out')) {
                if ($request->scheduled_time_out && !empty($request->scheduled_time_out)) {
                    $timeValue = $request->scheduled_time_out;
                    if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                        $updateData['scheduled_time_out'] = $timeValue . ':00';
                    } else {
                        $updateData['scheduled_time_out'] = $timeValue ?: null;
                    }
                } else {
                    $updateData['scheduled_time_out'] = null;
                }
            }
            
            if ($request->has('work_schedule')) {
                if ($request->work_schedule && is_array($request->work_schedule) && count($request->work_schedule) > 0) {
                    $workSchedule = array_map(function($slot) {
                        $timeIn = $slot['time_in'];
                        $timeOut = $slot['time_out'];
                        if (strlen($timeIn) === 5) $timeIn .= ':00';
                        if (strlen($timeOut) === 5) $timeOut .= ':00';
                        return [
                            'time_in' => $timeIn,
                            'time_out' => $timeOut
                        ];
                    }, $request->work_schedule);
                    $updateData['work_schedule'] = json_encode($workSchedule);
                } else {
                    $updateData['work_schedule'] = null;
                }
            }

            \Log::info('Updating SA account', [
                'sa_id' => $id,
                'office_head_id' => $user->id,
                'update_data' => $updateData
            ]);

            $studentAssistant->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Student assistant account updated successfully!',
                'data' => $studentAssistant->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed for SA update', [
                'sa_id' => $id,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating SA account: ' . $e->getMessage(), [
                'sa_id' => $id,
                'office_head_id' => $user->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student assistant account.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Approve apprentice to become full-pledged student assistant.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveApprentice($id)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can approve apprentices.'
            ], 403);
        }

        $office = $user->office;
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            $apprentice = \App\Models\User::where('role', 'Student Assistant')
                ->where('id', $id)
                ->first();

            if (!$apprentice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student assistant not found.'
                ], 404);
            }

            // Check if apprentice belongs to office head's office
            if ($apprentice->office !== $office) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. This apprentice does not belong to your office.'
                ], 403);
            }

            // Check if apprentice status
            if ($apprentice->status !== 'Apprentice') {
                return response()->json([
                    'success' => false,
                    'message' => 'This student assistant is not an apprentice.',
                    'current_status' => $apprentice->status
                ], 422);
            }

            // Update status to Active
            $apprentice->update([
                'status' => 'Active',
                'active' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Apprentice approved successfully! Student assistant is now full-pledged.',
                'data' => [
                    'id' => $apprentice->id,
                    'name' => $apprentice->name,
                    'status' => $apprentice->status
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error approving apprentice: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'apprentice_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve apprentice.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get office head dashboard statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfficeDashboardStats()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $office = $user->office;
        
        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'No office assigned to your account.'
            ], 422);
        }

        try {
            $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();
            $thirtyDaysFromNow = \Carbon\Carbon::now('Asia/Manila')->addDays(30)->toDateString();
            
            // Count total student assistants in this office
            $totalSACount = \App\Models\User::where('role', 'Student Assistant')
                ->where('office', $office)
                ->where('active', 1)
                ->count();

            // Count active contracts (contract_start_date <= today AND contract_end_date >= today)
            $activeContractsCount = \App\Models\User::where('role', 'Student Assistant')
                ->where('office', $office)
                ->where('active', 1)
                ->whereNotNull('contract_start_date')
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_start_date', '<=', $today)
                ->whereDate('contract_end_date', '>=', $today)
                ->count();

            // Count pending evaluations: Active student assistants who haven't been evaluated yet
            // Get active student assistants in this office
            $activeSAs = \App\Models\User::where('role', 'Student Assistant')
                ->where('office', $office)
                ->where('active', 1)
                ->whereNotNull('contract_start_date')
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_start_date', '<=', $today)
                ->whereDate('contract_end_date', '>=', $today)
                ->get();
            
            // Count how many don't have any evaluation yet
            $pendingEvaluationsCount = 0;
            foreach ($activeSAs as $sa) {
                $hasEvaluation = \App\Models\Evaluation::where(function($query) use ($sa) {
                    $query->where('student_name', $sa->full_name ?? $sa->name)
                          ->orWhere('student_name', $sa->name);
                    if (\Schema::hasColumn('evaluations', 'student_assistant_id')) {
                        $query->orWhere('student_assistant_id', $sa->id);
                    }
                })
                ->where('office', $office)
                ->exists();
                
                if (!$hasEvaluation) {
                    $pendingEvaluationsCount++;
                }
            }

            // Count expiring contracts (contract_end_date between today and 30 days from now)
            $expiringContractsCount = \App\Models\User::where('role', 'Student Assistant')
                ->where('office', $office)
                ->where('active', 1)
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_end_date', '>=', $today)
                ->whereDate('contract_end_date', '<=', $thirtyDaysFromNow)
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_student_assistants' => $totalSACount,
                    'active_contracts' => $activeContractsCount,
                    'pending_evaluations' => $pendingEvaluationsCount,
                    'expiring_contracts' => $expiringContractsCount,
                    'office' => $office
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading office dashboard stats: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'office' => $office,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get HR dashboard statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHRDashboardStats()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can access this.'
            ], 403);
        }

        try {
            $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();
            $thirtyDaysFromNow = \Carbon\Carbon::now('Asia/Manila')->addDays(30)->toDateString();
            
            // Count total student assistants across all offices
            $totalSACount = \App\Models\User::where('role', 'Student Assistant')
                ->where('active', 1)
                ->count();

            // Count active contracts across all offices
            // Active contracts: contract_start_date <= today AND contract_end_date >= today
            $activeContractsCount = \App\Models\User::where('role', 'Student Assistant')
                ->where('active', 1)
                ->whereNotNull('contract_start_date')
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_start_date', '<=', $today)
                ->whereDate('contract_end_date', '>=', $today)
                ->count();

            // Count pending evaluations across all offices
            $pendingEvaluationsCount = \App\Models\Evaluation::where('status', 'Pending')
                ->count();

            // Count expiring contracts across all offices
            // Expiring contracts: Currently active contracts (started) that will expire within 30 days
            $expiringContractsCount = \App\Models\User::where('role', 'Student Assistant')
                ->where('active', 1)
                ->whereNotNull('contract_start_date')
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_start_date', '<=', $today) // Contract must have started
                ->whereDate('contract_end_date', '>=', $today) // Contract must still be active
                ->whereDate('contract_end_date', '<=', $thirtyDaysFromNow) // Expiring within 30 days
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_student_assistants' => $totalSACount,
                    'active_contracts' => $activeContractsCount,
                    'pending_evaluations' => $pendingEvaluationsCount,
                    'expiring_contracts' => $expiringContractsCount
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading HR dashboard stats: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get SA performance metrics.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSAPerformance($id, \Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $sa = \App\Models\User::find($id);
        
        if (!$sa || $sa->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Student Assistant not found.'
            ], 404);
        }
        
        // HR can view any SA performance; Office Head is restricted to their office
        if ($user->role === 'Office Head') {
            $officeId = $user->office_id;
            $office = $user->office;

            if (!$officeId && !$office) {
                return response()->json([
                    'success' => false,
                    'message' => 'No office assigned to your account.'
                ], 422);
            }

            // Use the same office-scoping logic as getOfficeSAs
            $scopeQuery = \App\Models\User::where('role', 'Student Assistant');
            if ($officeId && \Schema::hasColumn('users', 'office_id')) {
                $scopeQuery->where(function ($q) use ($officeId, $office) {
                    $q->where('office_id', $officeId);
                    if ($office) {
                        $q->orWhereRaw('LOWER(TRIM(office)) = LOWER(TRIM(?))', [$office]);
                    }
                });
            } else {
                $scopeQuery->whereRaw('LOWER(TRIM(office)) = LOWER(TRIM(?))', [$office]);
            }

            $isAccessible = $scopeQuery->where('id', $sa->id)->exists();
            if (!$isAccessible) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student assistant does not belong to your office.'
                ], 403);
            }
        }

        // Get date range (default to last 30 days)
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        // Get all attendance records for this SA
        $attendances = \App\Models\Attendance::where('user_id', $sa->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate metrics
        $totalDays = $attendances->count();
        $totalMinutes = $attendances->sum('total_minutes') ?? 0;
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        
        // Count lates (status = 'Late')
        $lates = $attendances->where('status', 'Late')->count();
        
        // Count absents (no attendance record on expected days)
        // For simplicity, we'll count days with status 'Absent' if it exists, or calculate based on expected work days
        $absents = 0;
        $expectedWorkDays = $this->calculateWorkDays($startDate, $endDate);
        $absents = max(0, $expectedWorkDays - $totalDays);
        
        // Count overtime (total_minutes > 300 minutes = 5 hours)
        $overtime = $attendances->filter(function ($attendance) {
            return $attendance->total_minutes && $attendance->total_minutes > 300;
        })->count();

        try {
            // Get attendance details
            $attendanceDetails = $attendances->map(function ($attendance) {
                $dateString = $attendance->date instanceof \Carbon\Carbon
                    ? $attendance->date->format('Y-m-d')
                    : ($attendance->date ? (string) $attendance->date : now()->toDateString());

            $timeInFormatted = '--';
            if ($attendance->time_in) {
                try {
                    $timeInFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_in)->format('g:i A');
                } catch (\Exception $e) {
                    $timeInFormatted = $attendance->time_in;
                }
            }

            $timeOutFormatted = '--';
            if ($attendance->time_out) {
                try {
                    $timeOutFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_out)->format('g:i A');
                } catch (\Exception $e) {
                    $timeOutFormatted = $attendance->time_out;
                }
            }

            // Calculate formatted total time
            $formattedTotalTime = '--';
            if ($attendance->total_minutes) {
                $hours = floor($attendance->total_minutes / 60);
                $minutes = $attendance->total_minutes % 60;
                if ($hours > 0 && $minutes > 0) {
                    $formattedTotalTime = "{$hours}h {$minutes}m";
                } elseif ($hours > 0) {
                    $formattedTotalTime = "{$hours}h";
                } else {
                    $formattedTotalTime = "{$minutes}m";
                }
            }

                $formattedDate = $dateString;
                try {
                    $formattedDate = \Carbon\Carbon::parse($dateString)->format('M d, Y');
                } catch (\Exception $e) {
                    // Keep raw date string if parsing fails.
                }

                return [
                    'id' => $attendance->id,
                    'date' => $dateString,
                    'formatted_date' => $formattedDate,
                    'time_in' => $timeInFormatted,
                    'time_out' => $timeOutFormatted,
                    'total_minutes' => $attendance->total_minutes,
                    'formatted_total_time' => $formattedTotalTime,
                    'status' => $attendance->status,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'sa' => [
                        'id' => $sa->id,
                        'name' => $sa->full_name ?? $sa->name,
                        'student_id_number' => $sa->student_id_number,
                        'email' => $sa->email,
                        'office' => $sa->office,
                    ],
                    'metrics' => [
                        'total_days' => $totalDays,
                        'total_hours' => $totalHours,
                        'total_minutes' => $remainingMinutes,
                        'total_time_formatted' => $totalHours > 0 ? "{$totalHours}h {$remainingMinutes}m" : "{$remainingMinutes}m",
                        'lates' => $lates,
                        'absents' => $absents,
                        'overtime' => $overtime,
                        'expected_work_days' => $expectedWorkDays,
                    ],
                    'attendance_details' => $attendanceDetails,
                    'date_range' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading SA performance: ' . $e->getMessage(), [
                'office_head_id' => $user->id ?? null,
                'sa_id' => $id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load performance data.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Calculate work days between two dates (excluding weekends).
     *
     * @param  string  $startDate
     * @param  string  $endDate
     * @return int
     */
    private function calculateWorkDays($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        $workDays = 0;

        while ($start <= $end) {
            // Count only weekdays (Monday = 1, Sunday = 7)
            if ($start->dayOfWeek !== \Carbon\Carbon::SATURDAY && $start->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                $workDays++;
            }
            $start->addDay();
        }

        return $workDays;
    }

    /**
     * Get attendance summary by office for HR dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAttendanceSummary()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'HR') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only HR can access this.'
            ], 403);
        }

        try {
            $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();
            
            // Get all offices with student assistants using office_id
            $query = \App\Models\User::where('role', 'Student Assistant')
                ->whereNotNull('office_id')
                ->with('officeRelation');
            
            // Check if status column exists before filtering
            if (\Schema::hasColumn('users', 'status')) {
                $query->where(function($q) {
                    $q->whereIn('status', ['Active', 'Apprentice'])
                      ->orWhereNull('status');
                });
            } else {
                // Use active column if status doesn't exist
                $query->where('active', 1);
            }
            
            // Get distinct offices with their IDs
            $offices = $query->get()
                ->groupBy('office_id')
                ->map(function($group) {
                    $office = $group->first()->officeRelation;
                    return [
                        'id' => $office ? $office->id : null,
                        'name' => $office ? $office->name : ($group->first()->office ?? 'Unknown')
                    ];
                })
                ->filter(function($office) {
                    return $office['id'] !== null;
                })
                ->values();
            
            $summary = [];
            
            foreach ($offices as $officeData) {
                $officeId = $officeData['id'];
                $officeName = $officeData['name'];
                
                // Get total student assistants in this office
                $query = \App\Models\User::where('role', 'Student Assistant')
                    ->where('office_id', $officeId);
                
                // Check if status column exists before filtering
                if (\Schema::hasColumn('users', 'status')) {
                    $query->where(function($q) {
                        $q->whereIn('status', ['Active', 'Apprentice'])
                          ->orWhereNull('status');
                    });
                } else {
                    // Use active column if status doesn't exist
                    $query->where('active', 1);
                }
                
                $totalSAs = $query->count();
                
                // Get today's attendance records for this office (using office name for attendance table compatibility)
                $todayAttendances = \App\Models\Attendance::where('office', $officeName)
                    ->whereDate('date', $today)
                    ->get();
                
                // Count by status
                $present = $todayAttendances->where('status', 'Present')->count();
                $late = $todayAttendances->where('status', 'Late')->count();
                $absent = max(0, $totalSAs - $present - $late);
                
                // Count overtime (status = 'Overtime' or total_minutes > 300)
                $overtime = 0;
                foreach ($todayAttendances as $attendance) {
                    if ($attendance->status === 'Overtime' || 
                        ($attendance->total_minutes && (int)$attendance->total_minutes > 300)) {
                        $overtime++;
                    }
                }
                
                $summary[] = [
                    'office' => $officeName,
                    'total' => $totalSAs,
                    'present' => $present,
                    'late' => $late,
                    'absent' => $absent,
                    'overtime' => $overtime,
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting attendance summary: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading attendance summary: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }

    /**
     * Get attendance statistics summary for logged-in Student Assistant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSAAttendanceStats()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can access this.'
            ], 403);
        }

        try {
            // Get date range (default to last 30 days, or this month)
            $now = \Carbon\Carbon::now('Asia/Manila');
            $startDate = $now->copy()->startOfMonth()->toDateString();
            $endDate = $now->toDateString();
            
            // Get all attendance records for this SA
            $attendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();
            
            // Calculate metrics
            $totalRecords = $attendances->count();
            $totalMinutes = $attendances->sum('total_minutes') ?? 0;
            $totalHours = floor($totalMinutes / 60);
            $remainingMinutes = $totalMinutes % 60;
            
            // Count by status
            $present = $attendances->where('status', 'Present')->count();
            $late = $attendances->where('status', 'Late')->count();
            $completed = $attendances->where('status', 'Completed')->count();
            $incomplete = $attendances->where('status', 'Incomplete')->count();
            
            // Count overtime (total_minutes > 300 minutes = 5 hours)
            $overtime = $attendances->filter(function ($attendance) {
                return $attendance->total_minutes && $attendance->total_minutes > 300;
            })->count();
            
            // Calculate absences (expected work days - present days)
            // For simplicity, we'll count days with status 'Absent' if it exists
            $absent = $attendances->where('status', 'Absent')->count();
            
            // If no absent status, calculate based on expected work days
            if ($absent === 0) {
                $expectedWorkDays = $this->calculateWorkDays($startDate, $endDate);
                $absent = max(0, $expectedWorkDays - $totalRecords);
            }
            
            // Calculate attendance rate
            $totalWorkDays = $present + $late + $completed + $incomplete;
            $attendanceRate = $totalWorkDays > 0 ? round(($totalWorkDays / ($totalWorkDays + $absent)) * 100, 1) : 0;
            
            // Get this week's stats
            $weekStart = $now->copy()->startOfWeek()->toDateString();
            $weekAttendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$weekStart, $endDate])
                ->get();
            
            $weekLate = $weekAttendances->where('status', 'Late')->count();
            $weekOvertime = $weekAttendances->filter(function ($attendance) {
                return $attendance->total_minutes && $attendance->total_minutes > 300;
            })->count();
            $weekTotalMinutes = $weekAttendances->sum('total_minutes') ?? 0;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'period_type' => 'This Month'
                    ],
                    'summary' => [
                        'total_days' => $totalRecords,
                        'present' => $present,
                        'late' => $late,
                        'absent' => $absent,
                        'completed' => $completed,
                        'incomplete' => $incomplete,
                        'overtime' => $overtime,
                        'attendance_rate' => $attendanceRate,
                        'total_hours' => $totalHours,
                        'total_minutes' => $remainingMinutes,
                        'total_time_formatted' => $totalHours > 0 && $remainingMinutes > 0 
                            ? "{$totalHours}h {$remainingMinutes}m" 
                            : ($totalHours > 0 ? "{$totalHours}h" : "{$remainingMinutes}m")
                    ],
                    'this_week' => [
                        'late' => $weekLate,
                        'overtime' => $weekOvertime,
                        'total_minutes' => $weekTotalMinutes,
                        'total_hours' => floor($weekTotalMinutes / 60),
                        'total_time_formatted' => floor($weekTotalMinutes / 60) > 0 && ($weekTotalMinutes % 60) > 0
                            ? floor($weekTotalMinutes / 60) . 'h ' . ($weekTotalMinutes % 60) . 'm'
                            : (floor($weekTotalMinutes / 60) > 0 ? floor($weekTotalMinutes / 60) . 'h' : ($weekTotalMinutes % 60) . 'm')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting SA attendance stats: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading attendance statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update attendance record (for Office Head to edit attendance).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAttendance(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can update attendance records.'
            ], 403);
        }

        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string|max:500',
            'attendance_id' => 'nullable|integer|exists:attendances,id'
        ]);

        try {
            $targetUserId = $request->input('user_id');
            $date = $request->input('date');
            $timeIn = $request->input('time_in');
            $timeOut = $request->input('time_out');
            $remarks = $request->input('remarks');
            $attendanceId = $request->input('attendance_id');

            // Verify the target user is a Student Assistant in the office head's office
            $targetUser = \App\Models\User::find($targetUserId);
            if (!$targetUser || $targetUser->role !== 'Student Assistant') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user. Only Student Assistants can have their attendance updated.'
                ], 422);
            }

            // Check if the SA belongs to the office head's office
            $officeHeadOffice = $user->office_id ?? $user->office;
            $saOffice = $targetUser->office_id ?? $targetUser->office;
            
            if ($officeHeadOffice !== $saOffice) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student assistant does not belong to your office.'
                ], 403);
            }

            // Find or create attendance record
            $attendance = null;
            if ($attendanceId) {
                $attendance = \App\Models\Attendance::where('id', $attendanceId)
                    ->where('user_id', $targetUserId)
                    ->whereDate('date', $date)
                    ->first();
            }
            
            if (!$attendance) {
                $attendance = \App\Models\Attendance::where('user_id', $targetUserId)
                    ->whereDate('date', $date)
                    ->first();
            }

            // If no attendance record exists and we have time in/out, create one
            if (!$attendance && ($timeIn || $timeOut)) {
                $attendance = \App\Models\Attendance::create([
                    'user_id' => $targetUserId,
                    'student_id_number' => $targetUser->student_id_number,
                    'name' => $targetUser->full_name ?? $targetUser->name,
                    'office' => $targetUser->office ?? ($targetUser->officeRelation ? $targetUser->officeRelation->name : null),
                    'date' => $date,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                ]);
            } elseif (!$attendance) {
                // No attendance and no time in/out - mark as absent
                $attendance = \App\Models\Attendance::create([
                    'user_id' => $targetUserId,
                    'student_id_number' => $targetUser->student_id_number,
                    'name' => $targetUser->full_name ?? $targetUser->name,
                    'office' => $targetUser->office ?? ($targetUser->officeRelation ? $targetUser->officeRelation->name : null),
                    'date' => $date,
                    'status' => 'Absent',
                ]);
            }

            // Calculate total minutes and status
            $totalMinutes = null;
            $status = 'Absent';

            if ($timeIn && $timeOut) {
                try {
                    $dateString = $date;
                    $timeInCarbon = \Carbon\Carbon::parse($dateString . ' ' . $timeIn, 'Asia/Manila')
                        ->setTimezone('Asia/Manila');
                    $timeOutCarbon = \Carbon\Carbon::parse($dateString . ' ' . $timeOut, 'Asia/Manila')
                        ->setTimezone('Asia/Manila');
                    $totalMinutes = $timeOutCarbon->diffInMinutes($timeInCarbon);
                    
                    // Determine status
                    $minimumMinutes = 300; // 5 hours
                    $scheduledTimeIn = $targetUser->scheduled_time_in ?? '08:00:00';
                    $scheduledTimeInCarbon = \Carbon\Carbon::parse($dateString . ' ' . $scheduledTimeIn, 'Asia/Manila')
                        ->setTimezone('Asia/Manila');
                    
                    // Check if late (time in is after scheduled time + 15 minutes grace period)
                    $gracePeriod = 15; // minutes
                    $isLate = $timeInCarbon->diffInMinutes($scheduledTimeInCarbon) > $gracePeriod;
                    
                    if ($totalMinutes >= $minimumMinutes) {
                        $status = $isLate ? 'Late' : 'Completed';
                    } else {
                        $status = $isLate ? 'Late' : 'Incomplete';
                    }
                } catch (\Exception $e) {
                    \Log::error('Error calculating total minutes in updateAttendance: ' . $e->getMessage());
                    $status = 'Present';
                }
            } elseif ($timeIn) {
                // Only time in - mark as Present or Late
                try {
                    $dateString = $date;
                    $scheduledTimeIn = $targetUser->scheduled_time_in ?? '08:00:00';
                    $scheduledTimeInCarbon = \Carbon\Carbon::parse($dateString . ' ' . $scheduledTimeIn, 'Asia/Manila')
                        ->setTimezone('Asia/Manila');
                    $timeInCarbon = \Carbon\Carbon::parse($dateString . ' ' . $timeIn, 'Asia/Manila')
                        ->setTimezone('Asia/Manila');
                    
                    $gracePeriod = 15; // minutes
                    $isLate = $timeInCarbon->diffInMinutes($scheduledTimeInCarbon) > $gracePeriod;
                    $status = $isLate ? 'Late' : 'Present';
                } catch (\Exception $e) {
                    $status = 'Present';
                }
            }

            // Update attendance record
            $updateData = [
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'total_minutes' => $totalMinutes,
                'status' => $status,
            ];

            if ($remarks) {
                $updateData['remarks'] = ($attendance->remarks ? $attendance->remarks . "\n\n" : '') . 
                    '[' . now('Asia/Manila')->format('Y-m-d H:i:s') . '] Office Head Edit: ' . $remarks;
            }

            $attendance->update($updateData);

            // Format response
            $formattedTimeIn = $timeIn ? \Carbon\Carbon::parse($date . ' ' . $timeIn, 'Asia/Manila')
                ->format('g:i A') : null;
            $formattedTimeOut = $timeOut ? \Carbon\Carbon::parse($date . ' ' . $timeOut, 'Asia/Manila')
                ->format('g:i A') : null;

            $formattedTotalTime = '--';
            if ($totalMinutes) {
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                if ($hours > 0 && $minutes > 0) {
                    $formattedTotalTime = "{$hours}h {$minutes}m";
                } elseif ($hours > 0) {
                    $formattedTotalTime = "{$hours}h";
                } else {
                    $formattedTotalTime = "{$minutes}m";
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully!',
                'data' => [
                    'id' => $attendance->id,
                    'date' => $date,
                    'time_in' => $formattedTimeIn,
                    'time_out' => $formattedTimeOut,
                    'total_minutes' => $totalMinutes,
                    'formatted_total_time' => $formattedTotalTime,
                    'status' => $status,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating attendance: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'target_user_id' => $request->input('user_id'),
                'date' => $request->input('date'),
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attendance. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get evaluation criteria settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEvaluationCriteriaSettings()
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        try {
            $criteria = \App\Models\EvaluationCriteriaSetting::where('is_active', true)
                ->orderBy('order')
                ->get();

            $criteriaData = [];
            foreach ($criteria as $criterion) {
                $criteriaData[$criterion->criterion_key] = $criterion->question_text;
            }

            return response()->json([
                'success' => true,
                'data' => $criteriaData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load criteria settings.'
            ], 500);
        }
    }

    /**
     * Update evaluation criteria settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEvaluationCriteriaSettings(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user || !in_array($user->role, ['Office Head', 'HR'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $request->validate([
            'criteria' => 'required|array',
            'criteria.*' => 'required|string|max:500',
        ]);

        try {
            foreach ($request->criteria as $key => $questionText) {
                \App\Models\EvaluationCriteriaSetting::updateOrCreate(
                    ['criterion_key' => $key],
                    [
                        'question_text' => $questionText,
                        'is_active' => true,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Criteria settings updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update criteria settings.'
            ], 500);
        }
    }

    /**
     * Verify attendance photos (approve/reject).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyAttendancePhotos(\Illuminate\Http\Request $request, $id)
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can verify attendance photos.'
            ], 403);
        }

        $request->validate([
            'review_status' => 'required|in:Approved,Rejected',
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            $attendance = \App\Models\Attendance::find($id);
            
            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found.'
                ], 404);
            }

            // Verify the attendance belongs to the office head's office
            $office = $user->officeRelation ? $user->officeRelation->name : $user->office;
            $saOffice = $attendance->user && $attendance->user->officeRelation 
                ? $attendance->user->officeRelation->name 
                : ($attendance->user ? $attendance->user->office : $attendance->office);
            
            if ($saOffice !== $office) {
                return response()->json([
                    'success' => false,
                    'message' => 'This attendance does not belong to your office.'
                ], 403);
            }

            // Update review status
            $attendance->review_status = $request->review_status;
            $attendance->reviewed_by = $user->id;
            $attendance->reviewed_at = now();
            
            if ($request->remarks) {
                $existingRemarks = $attendance->remarks ? $attendance->remarks . "\n" : '';
                $attendance->remarks = $existingRemarks . '[Photo Verification ' . $request->review_status . '] ' . $request->remarks;
            } else {
                $existingRemarks = $attendance->remarks ? $attendance->remarks . "\n" : '';
                $attendance->remarks = $existingRemarks . '[Photo Verification ' . $request->review_status . ']';
            }
            
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance photos ' . strtolower($request->review_status) . ' successfully!',
                'data' => [
                    'id' => $attendance->id,
                    'review_status' => $attendance->review_status,
                    'reviewed_by' => $attendance->reviewed_by,
                    'reviewed_at' => $attendance->reviewed_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error verifying attendance photos: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'attendance_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify attendance photos.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Logout user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        \Auth::logout();
        return redirect('login');
    }
}
