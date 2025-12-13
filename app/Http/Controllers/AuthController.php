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
     * @return \Illuminate\Http\Response
     */
    public function getOfficeAttendances()
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
            $attendances = \App\Models\Attendance::where('office', $office)
                ->with('user')
                ->orderBy('date', 'desc')
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

                return [
                    'id' => $attendance->id,
                    'name' => $attendance->name,
                    'student_id' => $attendance->student_id_number,
                    'office' => $attendance->office,
                    'date' => $dateString,
                    'formatted_date' => $attendance->date->format('M d, Y'),
                    'time_in' => $timeInFormatted,
                    'time_out' => $timeOutFormatted,
                    'status' => $attendance->status,
                    'review_status' => $attendance->review_status ?? 'Pending',
                    'remarks' => $attendance->remarks,
                    'pending' => ($attendance->review_status === null || $attendance->review_status === 'Pending'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'office' => $office
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
            'head' => 'required|string|max:255'
        ]);

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

        $evaluation = \App\Models\Evaluation::create([
            'evaluator_id' => $user->id,
            'student_assistant_id' => $studentAssistant ? $studentAssistant->id : null,
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
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation submitted successfully!',
            'data' => $evaluation
        ]);
    }

    /**
     * Get evaluations for office head.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEvaluations()
    {
        $user = \Auth::user();
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $evaluations = \App\Models\Evaluation::where('evaluator_id', $user->id)
            ->orWhere('office', $user->office)
            ->orderBy('evaluation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $evaluations->map(function ($evaluation) {
            return [
                'id' => $evaluation->id,
                'student_name' => $evaluation->student_name,
                'nature_of_work' => $evaluation->nature_of_work,
                'office' => $evaluation->office,
                'total_score' => $evaluation->total_score,
                'average_score' => $evaluation->average_score,
                'overall_rating' => $evaluation->overall_rating,
                'evaluation_date' => $evaluation->evaluation_date->format('Y-m-d'),
                'formatted_date' => $evaluation->evaluation_date->format('M d, Y'),
                'rated_by' => $evaluation->rated_by,
                'head_of_office' => $evaluation->head_of_office,
                'status' => $evaluation->status,
                'created_at' => $evaluation->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
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
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can access this.'
            ], 403);
        }

        $evaluation = \App\Models\Evaluation::find($id);
        
        if (!$evaluation || ($evaluation->evaluator_id !== $user->id && $evaluation->office !== $user->office)) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found or unauthorized.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $evaluation->id,
                'student_name' => $evaluation->student_name,
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
                'evaluation_date' => $evaluation->evaluation_date->format('Y-m-d'),
                'rated_by' => $evaluation->rated_by,
                'head_of_office' => $evaluation->head_of_office,
                'status' => $evaluation->status,
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
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can update evaluations.'
            ], 403);
        }

        $evaluation = \App\Models\Evaluation::find($id);
        
        if (!$evaluation || $evaluation->evaluator_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found or unauthorized.'
            ], 404);
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
        
        if (!$user || $user->role !== 'Office Head') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Office Heads can delete evaluations.'
            ], 403);
        }

        $evaluation = \App\Models\Evaluation::find($id);
        
        if (!$evaluation || $evaluation->evaluator_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found or unauthorized.'
            ], 404);
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
            'request_type' => 'required|string|max:255',
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
        $request->validate([
            'fullName' => 'required|string|max:255',
            'studentId' => 'required|string|max:255|unique:users,student_id_number',
            'email' => 'required|email|max:255|unique:users,email',
            'contact' => 'required|string|max:20',
            'office' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive,Pending Assignment',
            'notes' => 'nullable|string|max:1000'
        ]);

        // In a real implementation, you would:
        // 1. Create a new user record in the database
        // 2. Set the role as 'Student Assistant'
        // 3. Store all the provided information
        // 4. Generate initial login credentials
        // 5. Send welcome email with login details

        $studentAssistantData = [
            'name' => $request->fullName,
            'full_name' => $request->fullName,
            'student_id_number' => $request->studentId,
            'email' => $request->email,
            'contact' => $request->contact,
            'office' => $request->office,
            'role' => 'Student Assistant',
            'status' => $request->status,
            'service_notes' => $request->notes,
            'password' => bcrypt('password123'), // Default password - should be changed on first login
            'password_hash' => bcrypt('password123'),
            'gender' => 'Not Specified',
            'active' => $request->status === 'Active' ? 1 : 0,
            'created_by' => \Auth::id()
        ];

        // For now, just return success
        // In real implementation, save to database:
        // User::create($studentAssistantData);

        return response()->json([
            'success' => true,
            'message' => 'Student assistant added successfully!',
            'data' => $studentAssistantData
        ]);
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
     * Upload contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadContract(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'uploadName' => 'required|string|max:255',
            'uploadID' => 'required|string|max:255',
            'uploadOffice' => 'required|string|max:255',
            'contractType' => 'required|string|in:New,Renewal,Amendment',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'pdfUpload' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'notes' => 'nullable|string|max:1000'
        ]);

        // In a real implementation, you would:
        // 1. Store the uploaded PDF file in a secure location
        // 2. Create a contract record in the database
        // 3. Link the contract to the student assistant
        // 4. Send notifications to relevant parties
        // 5. Generate contract reference numbers

        $contractData = [
            'student_name' => $request->uploadName,
            'student_id' => $request->uploadID,
            'office' => $request->uploadOffice,
            'contract_type' => $request->contractType,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'status' => 'Active',
            'notes' => $request->notes,
            'uploaded_by' => \Auth::id(),
            'uploaded_at' => now()->toISOString(),
            'file_path' => 'contracts/' . $request->uploadID . '-' . date('Y') . '.pdf'
        ];

        // Handle file upload
        if ($request->hasFile('pdfUpload')) {
            $file = $request->file('pdfUpload');
            $fileName = $request->uploadID . '-' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
            // In real implementation: $file->storeAs('contracts', $fileName);
            $contractData['file_name'] = $fileName;
            $contractData['file_size'] = $file->getSize();
        }

        // For now, just return success
        // In real implementation, save to database:
        // Contract::create($contractData);

        return response()->json([
            'success' => true,
            'message' => 'Contract uploaded successfully!',
            'data' => $contractData
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
        $request->validate([
            'evaluationId' => 'required|integer',
            'hrStatus' => 'required|string|in:Pending,Reviewed,Approved,Rejected',
            'hrRating' => 'nullable|string|in:Excellent,Good,Satisfactory,Needs Improvement,Poor',
            'hrComments' => 'nullable|string|max:1000'
        ]);

        // In a real implementation, you would:
        // 1. Find the evaluation record in the database
        // 2. Update the HR review fields
        // 3. Update the overall status
        // 4. Send notifications to relevant parties
        // 5. Log the review action for audit trail

        $reviewData = [
            'evaluation_id' => $request->evaluationId,
            'hr_status' => $request->hrStatus,
            'hr_rating' => $request->hrRating,
            'hr_comments' => $request->hrComments,
            'reviewed_by' => \Auth::id(),
            'reviewed_at' => now()->toISOString()
        ];

        // For now, just return success
        // In real implementation, save to database:
        // EvaluationReview::updateOrCreate(['evaluation_id' => $request->evaluationId], $reviewData);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation review saved successfully!',
            'data' => $reviewData
        ]);
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
        $request->validate([
            'reportType' => 'required|string|in:dtr,evaluation,absent',
            'period' => 'nullable|string',
            'type' => 'nullable|string'
        ]);

        // In a real implementation, you would:
        // 1. Generate the requested report based on type and parameters
        // 2. Create PDF/Excel file with the report data
        // 3. Store the file temporarily
        // 4. Return download URL or stream the file
        // 5. Clean up temporary files after download

        $reportData = [
            'report_type' => $request->reportType,
            'period' => $request->period,
            'type' => $request->type,
            'generated_by' => \Auth::id(),
            'generated_at' => now()->toISOString(),
            'file_name' => $this->generateReportFileName($request->reportType, $request->period, $request->type)
        ];

        // For now, just return success
        // In real implementation, generate actual report:
        // $reportPath = $this->generateReport($reportData);
        // return response()->download($reportPath);

        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully!',
            'data' => $reportData
        ]);
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
        
        return $baseName . '_' . $timestamp . '.pdf';
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
        $usersCount = \App\Models\User::where('office', $office->name)->count();
        
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
            ->orderBy('name')
            ->get();
        
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
        $officeHead = \App\Models\User::where('role', 'Office Head')->find($id);
        
        if (!$officeHead) {
            return response()->json([
                'success' => false,
                'message' => 'Office head not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $officeHead
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

        $passwordHash = \Hash::make($request->password);

        $officeHead = \App\Models\User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $passwordHash,
            'password_hash' => $passwordHash,
            'role' => 'Office Head',
            'office' => $request->office,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'active' => $request->active ?? 1,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Office head account created successfully!',
            'data' => $officeHead
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

        $updateData = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'office' => $request->office,
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
            'data' => $officeHead
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

        return response()->json([
            'success' => true,
            'data' => $studentAssistant
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
            'active' => 'nullable|boolean',
        ]);

        $passwordHash = \Hash::make($request->password);

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
            'active' => $request->active ?? 1,
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
            'active' => 'nullable|boolean',
        ]);

        $updateData = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'student_id_number' => $request->student_id_number,
            'email' => $request->email,
            'office' => $request->office,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'active' => $request->active ?? $studentAssistant->active,
        ];

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
     * Time In for student assistant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function timeIn(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated. Please log in again.'
            ], 401);
        }
        
        if ($user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can time in.',
                'user_role' => $user->role
            ], 403);
        }

        $today = now()->toDateString();
        
        // Check if already timed in today
        $existingAttendance = \App\Models\Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have already timed in today.',
                'existing_time_in' => $existingAttendance->time_in ? \Carbon\Carbon::parse($existingAttendance->date . ' ' . $existingAttendance->time_in)->format('g:i A') : null
            ], 422);
        }

        $timeIn = now()->toTimeString();
        
        // Determine status (Present or Late - you can customize the late threshold)
        $status = 'Present';
        $expectedTimeIn = '08:00:00'; // Expected time in (8 AM)
        if ($timeIn > $expectedTimeIn) {
            $status = 'Late';
        }

        try {
            $attendance = \App\Models\Attendance::create([
                'user_id' => $user->id,
                'student_id_number' => $user->student_id_number,
                'name' => $user->full_name ?? $user->name ?? 'Unknown',
                'office' => $user->office,
                'date' => $today,
                'time_in' => $timeIn,
                'status' => $status,
                'review_status' => null, // Pending review
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Time in recorded successfully!',
                'data' => [
                    'time_in' => $timeIn,
                    'formatted_time_in' => now()->format('g:i A'),
                    'status' => $status,
                    'date' => $today,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Time in error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'date' => $today,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to record time in. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Time Out for student assistant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function timeOut(\Illuminate\Http\Request $request)
    {
        $user = \Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated. Please log in again.'
            ], 401);
        }
        
        if ($user->role !== 'Student Assistant') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Student Assistants can time out.',
                'user_role' => $user->role
            ], 403);
        }

        $today = now()->toDateString();
        
        try {
            $attendance = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have not timed in today. Please time in first.'
                ], 422);
            }

            if ($attendance->time_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already timed out today.',
                    'existing_time_out' => $attendance->time_out ? \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->time_out)->format('g:i A') : null
                ], 422);
            }

            $timeOut = now()->toTimeString();
            
            // Calculate total minutes
            try {
                $dateString = $attendance->date->format('Y-m-d');
                $timeInCarbon = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_in);
                $timeOutCarbon = \Carbon\Carbon::parse($dateString . ' ' . $timeOut);
                $totalMinutes = $timeOutCarbon->diffInMinutes($timeInCarbon);
            } catch (\Exception $e) {
                \Log::error('Error calculating total minutes: ' . $e->getMessage(), [
                    'attendance_id' => $attendance->id,
                    'time_in' => $attendance->time_in,
                    'time_out' => $timeOut,
                    'date' => $dateString
                ]);
                $totalMinutes = 0;
            }

            // Update status to Completed
            $status = 'Completed';
            
            // Check if minimum hours worked (e.g., 4 hours = 240 minutes)
            $minimumMinutes = 240; // 4 hours
            if ($totalMinutes < $minimumMinutes) {
                $status = 'Incomplete';
            }

            $attendance->update([
                'time_out' => $timeOut,
                'total_minutes' => $totalMinutes,
                'status' => $status,
            ]);

            // Refresh the model to get updated formatted_total_time
            $attendance->refresh();

            // Calculate formatted total time manually to avoid accessor issues
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
                'message' => 'Time out recorded successfully!',
                'data' => [
                    'time_out' => $timeOut,
                    'formatted_time_out' => now()->format('g:i A'),
                    'total_minutes' => $totalMinutes,
                    'formatted_total_time' => $formattedTotalTime,
                    'status' => $status,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Time out error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'date' => $today,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to record time out. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get today's attendance for current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTodayAttendance()
    {
        $user = \Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        $today = now()->toDateString();

        try {
            $attendance = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No attendance record for today.'
                ]);
            }

            $dateString = $attendance->date instanceof \Carbon\Carbon 
                ? $attendance->date->format('Y-m-d') 
                : $attendance->date;

            $timeInFormatted = null;
            if ($attendance->time_in) {
                try {
                    $timeInFormatted = \Carbon\Carbon::parse($dateString . ' ' . $attendance->time_in)->format('g:i A');
                } catch (\Exception $e) {
                    $timeInFormatted = $attendance->time_in;
                }
            }

            $timeOutFormatted = null;
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

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $attendance->id,
                    'time_in' => $timeInFormatted,
                    'time_in_raw' => $attendance->time_in, // Raw time for real-time calculation
                    'time_out' => $timeOutFormatted,
                    'time_out_raw' => $attendance->time_out, // Raw time for calculations
                    'total_minutes' => $attendance->total_minutes,
                    'formatted_total_time' => $formattedTotalTime,
                    'status' => $attendance->status,
                    'date' => $dateString,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading today attendance: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'date' => $today,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load today attendance.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
        
        $limit = $request->get('limit', 30); // Default to last 30 records

        try {
            $attendances = \App\Models\Attendance::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit($limit)
                ->get();

            $data = $attendances->map(function ($attendance) {
                $dateString = $attendance->date->format('Y-m-d');
                
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
                
                return [
                    'id' => $attendance->id,
                    'date' => $dateString,
                    'formatted_date' => $attendance->date->format('M d, Y'),
                    'time_in' => $timeInFormatted,
                    'time_out' => $timeOutFormatted,
                    'total_minutes' => $attendance->total_minutes,
                    'formatted_total_time' => $attendance->formatted_total_time,
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
     * Get Student Assistants under office head's office.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfficeSAs()
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

        $studentAssistants = \App\Models\User::where('role', 'Student Assistant')
            ->where('office', $office)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $studentAssistants,
            'office' => $office
        ]);
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
        
        if (!$sa || $sa->role !== 'Student Assistant' || $sa->office !== $user->office) {
            return response()->json([
                'success' => false,
                'message' => 'Student Assistant not found or not under your office.'
            ], 404);
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
        
        // Count overtime (total_minutes > 240 minutes = 4 hours)
        $overtime = $attendances->filter(function ($attendance) {
            return $attendance->total_minutes && $attendance->total_minutes > 240;
        })->count();

        // Get attendance details
        $attendanceDetails = $attendances->map(function ($attendance) {
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

            return [
                'id' => $attendance->id,
                'date' => $dateString,
                'formatted_date' => $attendance->date->format('M d, Y'),
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
            ]
        ]);
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
