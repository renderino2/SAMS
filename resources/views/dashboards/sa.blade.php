@extends('../layout/' . $layout)

@section('subhead')
    <title>Student Assistant Dashboard - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: SA Dashboard Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium">Student Assistant Dashboard</h1>
                        <p class="text-slate-500 mt-2">Today: <span id="currentDate">—</span></p>
                    </div>
                </div>
                <!-- END: SA Dashboard Header -->

                <!-- BEGIN: Time Tracking Cards -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in h-full">
                        <div class="box p-5 h-full">
                            <div class="flex items-center">
                                <i data-lucide="log-in" class="report-box__icon text-primary mr-3"></i>
                                <div>
                                    <div class="text-base font-medium">Time In</div>
                                    <div class="text-xs text-slate-500">View and manage in Attendance page</div>
                                </div>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="timeIn">—</div>
                            <div class="text-xs text-slate-400 mt-1">Read-only summary</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in h-full">
                        <div class="box p-5 h-full">
                            <div class="flex items-center">
                                <i data-lucide="log-out" class="report-box__icon text-warning mr-3"></i>
                                <div>
                                    <div class="text-base font-medium">Time Out</div>
                                    <div class="text-xs text-slate-500">View and manage in Attendance page</div>
                                </div>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="timeOut">—</div>
                            <div class="text-xs text-slate-400 mt-1">Read-only summary</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="timer" class="report-box__icon text-success mr-3"></i>
                                <div>
                                    <div class="text-base font-medium">Total Time</div>
                                    <div class="text-xs text-slate-500">View and manage in Attendance page</div>
                                </div>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="totalHours">—</div>
                            <div class="text-base text-slate-500 mt-1">Total Time Rendered (Today)</div>
                            <div class="text-xs text-slate-400 mt-1" id="totalTimeAll" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <!-- END: Time Tracking Cards -->

                <!-- BEGIN: Scheduled Work Time -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Scheduled Work Time</h2>
                        </div>
                        <div class="mt-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="scheduledTimeContainer">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="clock" class="w-5 h-5 text-primary"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Scheduled Time In</div>
                                        <div class="text-slate-500 text-xs mt-0.5" id="scheduledTimeIn">—</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="clock" class="w-5 h-5 text-danger"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Scheduled Time Out</div>
                                        <div class="text-slate-500 text-xs mt-0.5" id="scheduledTimeOut">—</div>
                                    </div>
                                </div>
                                <div class="flex items-center col-span-2">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="layers" class="w-5 h-5 text-warning"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Work Schedule (Broken Schedule)</div>
                                        <div class="text-slate-500 text-xs mt-0.5" id="workSchedule">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Scheduled Work Time -->

                <!-- BEGIN: Contract & Requests Status -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Contract & Requests</h2>
                        </div>
                        <div class="mt-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-5 h-5 text-slate-500"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Contract Status</div>
                                        <div class="text-slate-500 text-xs mt-0.5">
                                            <span class="status" id="contractStatus">—</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="calendar" class="w-5 h-5 text-primary"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Contract Start Date</div>
                                        <div class="text-slate-500 text-xs mt-0.5" id="contractStartDate">—</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="calendar" class="w-5 h-5 text-danger"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Contract End Date</div>
                                        <div class="text-slate-500 text-xs mt-0.5" id="contractEndDate">—</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="send" class="w-5 h-5 text-slate-500"></i>
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Last Request</div>
                                        <div class="text-slate-500 text-xs mt-0.5" id="lastRequest">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Contract & Requests Status -->

                <!-- BEGIN: Attendance Summary -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10 mb-5">
                            <h2 class="text-lg font-medium truncate mr-5">Attendance Summary</h2>
                            <span class="text-xs text-slate-500" id="summaryPeriod">This Month</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Late Count -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="clock" class="report-box__icon text-warning mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Late</div>
                                            <div class="text-xs text-slate-500">Times late this month</div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6" id="lateCount">—</div>
                                    <div class="text-xs text-slate-400 mt-1" id="weekLateCount">This week: —</div>
                                </div>
                            </div>
                            
                            <!-- Overtime Count -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="timer" class="report-box__icon text-danger mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Overtime</div>
                                            <div class="text-xs text-slate-500">Days with overtime</div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6" id="overtimeCount">—</div>
                                    <div class="text-xs text-slate-400 mt-1" id="weekOvertimeCount">This week: —</div>
                                </div>
                            </div>
                            
                            <!-- Absences Count -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="x-circle" class="report-box__icon text-secondary mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Absences</div>
                                            <div class="text-xs text-slate-500">Days absent</div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6" id="absentCount">—</div>
                                    <div class="text-xs text-slate-400 mt-1">This month</div>
                                </div>
                            </div>
                            
                            <!-- Attendance Rate -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="trending-up" class="report-box__icon text-success mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Attendance Rate</div>
                                            <div class="text-xs text-slate-500">Overall percentage</div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6" id="attendanceRate">—</div>
                                    <div class="text-xs text-slate-400 mt-1" id="presentCount">Present: —</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Stats Row -->
                        <div class="grid grid-cols-3 md:grid-cols-3 gap-6 mt-6">
                            <!-- Total Hours -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="clock" class="report-box__icon text-primary mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Total Hours</div>
                                            <div class="text-xs text-slate-500">This month</div>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-8 mt-4" id="totalHoursSummary">—</div>
                                </div>
                            </div>
                            
                            <!-- Completed Days -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="check-circle" class="report-box__icon text-success mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Completed</div>
                                            <div class="text-xs text-slate-500">Full days worked</div>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-8 mt-4" id="completedCount">—</div>
                                </div>
                            </div>
                            
                            <!-- Incomplete Days -->
                            <div class="intro-y">
                                <div class="box p-5">
                                    <div class="flex items-center">
                                        <i data-lucide="alert-circle" class="report-box__icon text-warning mr-3"></i>
                                        <div>
                                            <div class="text-base font-medium">Incomplete</div>
                                            <div class="text-xs text-slate-500">Days below 5 hours</div>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-medium leading-8 mt-4" id="incompleteCount">—</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Attendance Summary -->
            </div>
        </div>

        <!-- BEGIN: Side Panels -->
        <div class="col-span-12 2xl:col-span-3">
            <div class="2xl:border-l -mb-10 pb-10">
                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                   

                    <!-- BEGIN: Recent Activity -->
                    <div class="col-span-12 mt-2">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Recent Activity</h2>
                        </div>
                        <div class="mt-5 relative before:block before:absolute before:w-px before:h-[85%] before:bg-slate-200 before:dark:bg-darkmode-400 before:ml-5 before:mt-5">
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Time In</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastTimeIn">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">Checked in for today's shift</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="send" class="w-5 h-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Request Submitted</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastRequestTime">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">Submitted a new request</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Recent Activity -->
                </div>
            </div>
        </div>
        <!-- END: Side Panels -->
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const currentDateEl = document.getElementById('currentDate');
        const timeInEl = document.getElementById('timeIn');
        const timeOutEl = document.getElementById('timeOut');
        const totalHoursEl = document.getElementById('totalHours');
        const lastTimeInEl = document.getElementById('lastTimeIn');
        const contractStatusEl = document.getElementById('contractStatus');
        const contractStartDateEl = document.getElementById('contractStartDate');
        const contractEndDateEl = document.getElementById('contractEndDate');
        const lastRequestEl = document.getElementById('lastRequest');
        const lastRequestTimeEl = document.getElementById('lastRequestTime');
        
        // Get user contract data from Laravel
        const user = @json(Auth::user());
        
        // Load scheduled work time
        function loadScheduledWorkTime() {
            if (!user) return;
            
            // Format time for display (HH:MM:SS to HH:MM AM/PM)
            const formatTime = (timeString) => {
                if (!timeString) return 'Not set';
                try {
                    const [hours, minutes] = timeString.split(':');
                    const hour = parseInt(hours);
                    const min = minutes || '00';
                    const period = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
                    return `${displayHour}:${min} ${period}`;
                } catch (e) {
                    return timeString;
                }
            };
            
            // Display scheduled time in/out
            const scheduledTimeInEl = document.getElementById('scheduledTimeIn');
            const scheduledTimeOutEl = document.getElementById('scheduledTimeOut');
            const workScheduleEl = document.getElementById('workSchedule');
            
            if (scheduledTimeInEl) {
                scheduledTimeInEl.textContent = formatTime(user.scheduled_time_in);
            }
            if (scheduledTimeOutEl) {
                scheduledTimeOutEl.textContent = formatTime(user.scheduled_time_out);
            }
            
            // Display work schedule (broken schedule)
            if (workScheduleEl) {
                // Handle both string (JSON) and array formats
                let workSchedule = user.work_schedule;
                if (typeof workSchedule === 'string') {
                    try {
                        workSchedule = JSON.parse(workSchedule);
                    } catch (e) {
                        console.error('Error parsing work_schedule:', e);
                        workSchedule = null;
                    }
                }
                
                if (workSchedule && Array.isArray(workSchedule) && workSchedule.length > 0) {
                    const scheduleText = workSchedule.map(slot => {
                        return `${formatTime(slot.time_in)} - ${formatTime(slot.time_out)}`;
                    }).join(', ');
                    workScheduleEl.textContent = scheduleText;
                } else {
                    workScheduleEl.textContent = 'No broken schedule set';
                }
            }
        }
        
        // Verify elements exist
        if (!timeInEl || !timeOutEl || !totalHoursEl) {
            console.error('Dashboard elements not found:', {
                timeInEl: !!timeInEl,
                timeOutEl: !!timeOutEl,
                totalHoursEl: !!totalHoursEl
            });
            return;
        }

        // Set current date
        const today = new Date();
        currentDateEl.textContent = today.toLocaleDateString('en-PH', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'Asia/Manila'
        });

        // Calculate formatted time from minutes
        function formatTimeFromMinutes(minutes) {
            if (!minutes || minutes <= 0) {
                return '—';
            }
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            
            if (hours > 0 && mins > 0) {
                return `${hours}h ${mins}m`;
            } else if (hours > 0) {
                return `${hours}h`;
            } else {
                return `${mins}m`;
            }
        }

        // Calculate current total time (real-time) when timed in but not out
        function calculateCurrentTotalTime(timeInRaw, dateStr) {
            if (!timeInRaw) return null;
            
            try {
                const now = new Date();
                // Parse time_in_raw (format: HH:MM:SS) and combine with date
                const [hours, minutes, seconds] = timeInRaw.split(':').map(Number);
                const timeInDate = new Date(dateStr);
                timeInDate.setHours(hours, minutes, seconds || 0, 0);
                
                // Calculate difference in minutes
                const diffMs = now - timeInDate;
                const diffMinutes = Math.max(0, Math.floor(diffMs / (1000 * 60)));
                
                return diffMinutes;
            } catch (error) {
                console.error('Error calculating current total time:', error);
                return null;
            }
        }

        // Store current attendance data
        let currentAttendanceData = null;
        let totalTimeUpdateInterval = null;

        // Load today's attendance
        async function loadTodayAttendance() {
            try {
                const response = await fetch('/api/attendance/today', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    return;
                }
                
                const result = await response.json();
                console.log('Attendance API response:', result);
                
                if (result.success) {
                    if (result.data) {
                        const data = result.data;
                        currentAttendanceData = data;
                        console.log('Updating dashboard with data:', data);
                        
                        // Update time cards - handle null, empty string, or "--" values
                        const timeInValue = (data.time_in && data.time_in !== '--' && data.time_in !== '') ? data.time_in : '—';
                        const timeOutValue = (data.time_out && data.time_out !== '--' && data.time_out !== '') ? data.time_out : '—';
                        
                        timeInEl.textContent = timeInValue;
                        timeOutEl.textContent = timeOutValue;
                        
                        // Calculate and display total time
                        const hasTimeIn = data.time_in && data.time_in !== '--' && data.time_in !== '';
                        const hasTimeOut = data.time_out && data.time_out !== '--' && data.time_out !== '';
                        
                        // Show total time for all sessions today if available
                        const totalTimeAllEl = document.getElementById('totalTimeAll');
                        if (data.formatted_total_time_today && data.all_records && data.all_records.length > 1) {
                            totalTimeAllEl.style.display = 'block';
                            totalTimeAllEl.textContent = `Total today: ${data.formatted_total_time_today} (${data.all_records.length} sessions)`;
                        } else if (data.formatted_total_time_today) {
                            totalTimeAllEl.style.display = 'none';
                        } else {
                            totalTimeAllEl.style.display = 'none';
                        }
                        
                        if (hasTimeIn && hasTimeOut) {
                            // Both time in and out - use server calculated time for current session
                            totalHoursEl.textContent = (data.formatted_total_time && data.formatted_total_time !== '--') ? data.formatted_total_time : '—';
                            
                            // Update total for today display if available
                            if (data.formatted_total_time_today) {
                                if (!totalTimeAllEl.textContent.includes('Total today:')) {
                                    totalTimeAllEl.style.display = 'block';
                                    totalTimeAllEl.textContent = `Total today: ${data.formatted_total_time_today}`;
                                }
                            }
                            
                            // Clear interval if exists
                            if (totalTimeUpdateInterval) {
                                clearInterval(totalTimeUpdateInterval);
                                totalTimeUpdateInterval = null;
                            }
                        } else if (hasTimeIn && !hasTimeOut) {
                            // Timed in but not out - calculate real-time for current session
                            const timeInRaw = data.time_in_raw || data.time_in;
                            if (timeInRaw && timeInRaw !== '--') {
                                const currentMinutes = calculateCurrentTotalTime(timeInRaw, data.date || new Date().toISOString().split('T')[0]);
                                if (currentMinutes !== null) {
                                    totalHoursEl.textContent = formatTimeFromMinutes(currentMinutes);
                                    
                                    // Start interval to update every minute
                                    if (!totalTimeUpdateInterval) {
                                        totalTimeUpdateInterval = setInterval(() => {
                                            if (currentAttendanceData && currentAttendanceData.time_in && !currentAttendanceData.time_out) {
                                                const timeInRaw = currentAttendanceData.time_in_raw || currentAttendanceData.time_in;
                                                if (timeInRaw && timeInRaw !== '--') {
                                                    const minutes = calculateCurrentTotalTime(
                                                        timeInRaw, 
                                                        currentAttendanceData.date || new Date().toISOString().split('T')[0]
                                                    );
                                                    if (minutes !== null) {
                                                        totalHoursEl.textContent = formatTimeFromMinutes(minutes);
                                                    }
                                                }
                                            }
                                        }, 60000); // Update every minute
                                    }
                                } else {
                                    totalHoursEl.textContent = '—';
                                }
                            } else {
                                totalHoursEl.textContent = '—';
                            }
                        } else {
                            // No current session - show total for today if available
                            if (data.formatted_total_time_today) {
                                totalHoursEl.textContent = data.formatted_total_time_today;
                                if (data.all_records && data.all_records.length > 1) {
                                    totalTimeAllEl.style.display = 'block';
                                    totalTimeAllEl.textContent = `(${data.all_records.length} sessions)`;
                                }
                            } else {
                                totalHoursEl.textContent = '—';
                            }
                            // Clear interval if exists
                            if (totalTimeUpdateInterval) {
                                clearInterval(totalTimeUpdateInterval);
                                totalTimeUpdateInterval = null;
                            }
                        }
                        
                        // Update recent activity
                        if (data.time_in) {
                            lastTimeInEl.textContent = 'Today at ' + data.time_in;
                        } else {
                            lastTimeInEl.textContent = '—';
                        }
                    } else {
                        // No attendance today
                        currentAttendanceData = null;
                        timeInEl.textContent = '—';
                        timeOutEl.textContent = '—';
                        totalHoursEl.textContent = '—';
                        lastTimeInEl.textContent = '—';
                        
                        // Hide total time all sessions display
                        const totalTimeAllEl = document.getElementById('totalTimeAll');
                        if (totalTimeAllEl) {
                            totalTimeAllEl.style.display = 'none';
                        }
                        
                        // Clear interval if exists
                        if (totalTimeUpdateInterval) {
                            clearInterval(totalTimeUpdateInterval);
                            totalTimeUpdateInterval = null;
                        }
                    }
                    
                    reloadLucideIcons();
                } else {
                    console.error('Failed to load today attendance:', result.message);
                }
            } catch (error) {
                console.error('Error loading today attendance:', error);
            }
        }

        // Load attendance history for recent activity
        async function loadRecentActivity() {
            try {
                const response = await fetch('/api/attendance/history?limit=5', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return;
                }
                
                const result = await response.json();
                
                if (result.success && result.data && result.data.length > 0) {
                    const latestRecord = result.data[0];
                    
                    // Update last time in if available
                    if (latestRecord.time_in && latestRecord.time_in !== '--') {
                        const recordDate = new Date(latestRecord.date);
                        const isToday = recordDate.toDateString() === today.toDateString();
                        
                        if (isToday) {
                            lastTimeInEl.textContent = 'Today at ' + latestRecord.time_in;
                        } else {
                            lastTimeInEl.textContent = latestRecord.formatted_date + ' at ' + latestRecord.time_in;
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading recent activity:', error);
            }
        }

        // Show message function
        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} flex items-center fixed top-4 right-4 z-50 shadow-lg`;
            messageDiv.innerHTML = `
                <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-4 h-4 mr-2"></i>
                ${message}
            `;
            
            document.body.appendChild(messageDiv);
            reloadLucideIcons();
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        // Load and display contract information
        function loadContractInfo() {
            if (!user) return;
            
            const contractStartDate = user.contract_start_date;
            const contractEndDate = user.contract_end_date;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Format and display contract dates
            if (contractStartDate) {
                const startDate = new Date(contractStartDate);
                contractStartDateEl.textContent = startDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } else {
                contractStartDateEl.textContent = 'Not set';
            }
            
            if (contractEndDate) {
                const endDate = new Date(contractEndDate);
                contractEndDateEl.textContent = endDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } else {
                contractEndDateEl.textContent = 'Not set';
            }
            
            // Calculate contract status
            if (!contractStartDate || !contractEndDate) {
                contractStatusEl.textContent = 'No Contract';
                contractStatusEl.className = 'status text-slate-500';
            } else {
                const startDate = new Date(contractStartDate);
                startDate.setHours(0, 0, 0, 0);
                const endDate = new Date(contractEndDate);
                endDate.setHours(0, 0, 0, 0);
                
                if (today < startDate) {
                    // Contract hasn't started yet
                    const daysUntilStart = Math.ceil((startDate - today) / (1000 * 60 * 60 * 24));
                    contractStatusEl.textContent = `Starts in ${daysUntilStart} day${daysUntilStart !== 1 ? 's' : ''}`;
                    contractStatusEl.className = 'status text-warning';
                } else if (today > endDate) {
                    // Contract has expired
                    const daysSinceExpiry = Math.ceil((today - endDate) / (1000 * 60 * 60 * 24));
                    contractStatusEl.textContent = `Expired ${daysSinceExpiry} day${daysSinceExpiry !== 1 ? 's' : ''} ago`;
                    contractStatusEl.className = 'status text-danger';
                } else {
                    // Contract is active
                    const daysUntilExpiry = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
                    if (daysUntilExpiry <= 30) {
                        contractStatusEl.textContent = `Active (${daysUntilExpiry} day${daysUntilExpiry !== 1 ? 's' : ''} left)`;
                        contractStatusEl.className = 'status text-warning';
                    } else {
                        contractStatusEl.textContent = 'Active';
                        contractStatusEl.className = 'status text-success';
                    }
                }
            }
        }

        // Load attendance statistics summary
        async function loadAttendanceStats() {
            try {
                const response = await fetch('/api/sa-attendance-stats', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return;
                }
                
                const result = await response.json();
                
                if (result.success && result.data) {
                    const data = result.data;
                    const summary = data.summary;
                    const week = data.this_week;
                    
                    // Update period
                    const summaryPeriodEl = document.getElementById('summaryPeriod');
                    if (summaryPeriodEl) {
                        summaryPeriodEl.textContent = data.period.period_type;
                    }
                    
                    // Update late count
                    const lateCountEl = document.getElementById('lateCount');
                    const weekLateCountEl = document.getElementById('weekLateCount');
                    if (lateCountEl) lateCountEl.textContent = summary.late || 0;
                    if (weekLateCountEl) weekLateCountEl.textContent = `This week: ${week.late || 0}`;
                    
                    // Update overtime count
                    const overtimeCountEl = document.getElementById('overtimeCount');
                    const weekOvertimeCountEl = document.getElementById('weekOvertimeCount');
                    if (overtimeCountEl) overtimeCountEl.textContent = summary.overtime || 0;
                    if (weekOvertimeCountEl) weekOvertimeCountEl.textContent = `This week: ${week.overtime || 0}`;
                    
                    // Update absent count
                    const absentCountEl = document.getElementById('absentCount');
                    if (absentCountEl) absentCountEl.textContent = summary.absent || 0;
                    
                    // Update attendance rate
                    const attendanceRateEl = document.getElementById('attendanceRate');
                    const presentCountEl = document.getElementById('presentCount');
                    if (attendanceRateEl) attendanceRateEl.textContent = (summary.attendance_rate || 0) + '%';
                    if (presentCountEl) presentCountEl.textContent = `Present: ${summary.present || 0}`;
                    
                    // Update total hours
                    const totalHoursSummaryEl = document.getElementById('totalHoursSummary');
                    if (totalHoursSummaryEl) {
                        totalHoursSummaryEl.textContent = summary.total_time_formatted || '—';
                    }
                    
                    // Update completed count
                    const completedCountEl = document.getElementById('completedCount');
                    if (completedCountEl) completedCountEl.textContent = summary.completed || 0;
                    
                    // Update incomplete count
                    const incompleteCountEl = document.getElementById('incompleteCount');
                    if (incompleteCountEl) incompleteCountEl.textContent = summary.incomplete || 0;
                    
                    reloadLucideIcons();
                }
            } catch (error) {
                console.error('Error loading attendance statistics:', error);
            }
        }

        // Load last request
        async function loadLastRequest() {
            try {
                const response = await fetch('/api/sa-requests', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return;
                }
                
                const result = await response.json();
                
                if (result.success && result.data && result.data.length > 0) {
                    // Get the most recent request (assuming results are ordered by date desc)
                    const lastRequest = result.data[0];
                    const requestDate = new Date(lastRequest.created_at);
                    const formattedDate = requestDate.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    
                    lastRequestEl.textContent = `${lastRequest.request_type || 'Request'} - ${lastRequest.status || 'Pending'} (${formattedDate})`;
                } else {
                    lastRequestEl.textContent = 'No requests';
                }
            } catch (error) {
                console.error('Error loading last request:', error);
                lastRequestEl.textContent = '—';
            }
        }

        // Initialize
        async function init() {
            await loadTodayAttendance();
            await loadRecentActivity();
            loadContractInfo();
            loadScheduledWorkTime();
            await loadLastRequest();
            await loadAttendanceStats();
            reloadLucideIcons();
            
            // Refresh attendance data every 30 seconds
            setInterval(async () => {
                await loadTodayAttendance();
            }, 30000);
            
            // Update total hours display every 10 seconds if timed in but not out
            setInterval(() => {
                if (currentAttendanceData && currentAttendanceData.time_in && !currentAttendanceData.time_out) {
                    const timeInRaw = currentAttendanceData.time_in_raw || currentAttendanceData.time_in;
                    const minutes = calculateCurrentTotalTime(
                        timeInRaw, 
                        currentAttendanceData.date || new Date().toISOString().split('T')[0]
                    );
                    if (minutes !== null) {
                        totalHoursEl.textContent = formatTimeFromMinutes(minutes);
                    }
                }
            }, 10000); // Update every 10 seconds for smoother real-time display
        }

        // Reload Lucide icons
        function reloadLucideIcons() {
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons({
                    icons: window.lucide.icons,
                    "stroke-width": 1.5,
                    nameAttr: "data-lucide",
                });
            }
        }

        // Initialize on page load
        init();
    });
</script>
@endsection