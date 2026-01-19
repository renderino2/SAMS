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
                    <div class="report-box zoom-in">
                        <div class="box p-5">
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
                    <div class="report-box zoom-in">
                        <div class="box p-5">
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
                            <div class="text-base text-slate-500 mt-1">Total Hours</div>
                        </div>
                    </div>
                </div>
                <!-- END: Time Tracking Cards -->

                <!-- BEGIN: Contract & Requests Status -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Contract & Requests</h2>
                        </div>
                        <div class="mt-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
        const lastRequestEl = document.getElementById('lastRequest');
        const lastRequestTimeEl = document.getElementById('lastRequestTime');
        
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
                        
                        if (hasTimeIn && hasTimeOut) {
                            // Both time in and out - use server calculated time
                            totalHoursEl.textContent = (data.formatted_total_time && data.formatted_total_time !== '--') ? data.formatted_total_time : '—';
                            
                            // Clear interval if exists
                            if (totalTimeUpdateInterval) {
                                clearInterval(totalTimeUpdateInterval);
                                totalTimeUpdateInterval = null;
                            }
                        } else if (hasTimeIn && !hasTimeOut) {
                            // Timed in but not out - calculate real-time
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
                            totalHoursEl.textContent = '—';
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

        // Initialize
        async function init() {
            await loadTodayAttendance();
            await loadRecentActivity();
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