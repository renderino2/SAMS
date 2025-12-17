@extends('../layout/' . $layout)

@section('subhead')
    <title>Attendance - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                            Attendance Log & Time Tracker
                        </h1>
                        <p class="text-slate-500 mt-2">Use this page to Time In and Time Out daily. Track your working hours and attendance status.</p>
                    </div>
                </div>

                <!-- User Info -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h3 class="text-base font-medium mb-3 flex items-center">
                            <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                            User Information
                        </h3>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-4">
                                <strong>Name:</strong> 
                                <span id="studentName">{{ Auth::user()->full_name ?? Auth::user()->name }}</span>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <strong>ID Number:</strong> 
                                <span id="studentIdShow">{{ Auth::user()->student_id_number ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <strong>Office:</strong> 
                                <span id="assignedOffice">{{ Auth::user()->office ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status Card -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h3 class="text-base font-medium mb-3 flex items-center">
                            <i data-lucide="activity" class="w-4 h-4 mr-2"></i>
                            Current Status
                        </h3>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">Status:</span>
                            <span id="loginStatus" class="text-danger font-semibold">
                                <strong>Not Logged In</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Time In/Out Buttons -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h3 class="text-base font-medium mb-4 flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                            Time Actions
                        </h3>
                        <div class="flex gap-3">
                            <button id="timeInBtn" class="btn btn-primary">
                                <i data-lucide="log-in" class="w-4 h-4 mr-2"></i> 
                                Time In
                            </button>
                            <button id="timeOutBtn" class="btn btn-outline-secondary" disabled>
                                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> 
                                Time Out
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Today's Record Card -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h3 class="text-base font-medium mb-4 flex items-center">
                            <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                            Today's Record
                        </h3>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-3">
                                <div class="text-slate-600 text-sm mb-1">Time In:</div>
                                <div class="text-lg font-semibold" id="todayIn">--</div>
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <div class="text-slate-600 text-sm mb-1">Time Out:</div>
                                <div class="text-lg font-semibold" id="todayOut">--</div>
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <div class="text-slate-600 text-sm mb-1">Total Time Rendered:</div>
                                <div class="text-lg font-semibold text-primary" id="todayTotalTime">--</div>
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <div class="text-slate-600 text-sm mb-1">Status:</div>
                                <div id="todayStatus">
                                    <span class="badge bg-secondary text-white rounded p-1">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Indicator -->
                <div class="col-span-12 intro-y" id="statusIndicator" style="display: none;">
                    <div class="box p-5">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-success" id="statusIcon"></i>
                            <div>
                                <div class="font-medium" id="statusTitle">Status OK</div>
                                <div class="text-slate-500 text-sm" id="statusMessage">Your attendance is complete and within requirements.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DTR Summary Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-medium flex items-center mr-2">
                                <i data-lucide="clipboard-list" class="w-4 h-4 mr-2"></i>
                                Attendance History
                            </h3>
                            <button id="refreshBtn" class="btn btn-outline-secondary btn-sm">
                                <i data-lucide="refresh-cw" class="w-4 h-4 m"></i>
                                Refresh
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Total Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="dtrTable">
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">Loading attendance records...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Reminder -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5 flex items-center">
                        <i data-lucide="bell" class="w-4 h-4 mr-2 text-warning"></i>
                        <span><strong>Reminder:</strong> Don't forget to Time Out before leaving. Minimum required hours: 4 hours per day.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        (function () {
            const studentName = document.getElementById('studentName');
            const studentIdShow = document.getElementById('studentIdShow');
            const assignedOffice = document.getElementById('assignedOffice');
            const loginStatus = document.getElementById('loginStatus');
            const timeInBtn = document.getElementById('timeInBtn');
            const timeOutBtn = document.getElementById('timeOutBtn');
            const todayIn = document.getElementById('todayIn');
            const todayOut = document.getElementById('todayOut');
            const todayTotalTime = document.getElementById('todayTotalTime');
            const todayStatus = document.getElementById('todayStatus');
            const dtrTable = document.getElementById('dtrTable');
            const statusIndicator = document.getElementById('statusIndicator');
            const refreshBtn = document.getElementById('refreshBtn');

            // Helper function to re-initialize Lucide icons
            function reloadLucideIcons() {
                if (window.lucide && window.lucide.createIcons) {
                    window.lucide.createIcons({
                        icons: window.lucide.icons,
                        "stroke-width": 1.5,
                        nameAttr: "data-lucide",
                    });
                }
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Completed':
                        return 'bg-success text-white';
                    case 'Present':
                        return 'bg-primary text-white';
                    case 'Late':
                        return 'bg-warning text-white';
                    case 'Incomplete':
                        return 'bg-danger text-white';
                    case 'Absent':
                        return 'bg-secondary text-white';
                    default:
                        return 'bg-secondary text-white';
                }
            }

            // Calculate formatted time from minutes
            function formatTimeFromMinutes(minutes) {
                if (!minutes || minutes <= 0) {
                    return '--';
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

            // Store current attendance data and interval
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
                    const result = await response.json();
                    
                    if (result.success) {
                        if (result.data) {
                            const data = result.data;
                            currentAttendanceData = data;
                            
                            todayIn.textContent = data.time_in || '--';
                            todayOut.textContent = data.time_out || '--';
                            
                            // Calculate and display total time
                            if (data.time_in && data.time_out) {
                                // Both time in and out - use server calculated time
                                todayTotalTime.textContent = data.formatted_total_time || '--';
                                
                                // Clear interval if exists
                                if (totalTimeUpdateInterval) {
                                    clearInterval(totalTimeUpdateInterval);
                                    totalTimeUpdateInterval = null;
                                }
                            } else if (data.time_in && !data.time_out) {
                                // Timed in but not out - calculate real-time
                                const timeInRaw = data.time_in_raw || data.time_in;
                                const currentMinutes = calculateCurrentTotalTime(timeInRaw, data.date || new Date().toISOString().split('T')[0]);
                                if (currentMinutes !== null) {
                                    todayTotalTime.textContent = formatTimeFromMinutes(currentMinutes);
                                    
                                    // Start interval to update every minute
                                    if (!totalTimeUpdateInterval) {
                                        totalTimeUpdateInterval = setInterval(() => {
                                            if (currentAttendanceData && currentAttendanceData.time_in && !currentAttendanceData.time_out) {
                                                const timeInRaw = currentAttendanceData.time_in_raw || currentAttendanceData.time_in;
                                                const minutes = calculateCurrentTotalTime(
                                                    timeInRaw, 
                                                    currentAttendanceData.date || new Date().toISOString().split('T')[0]
                                                );
                                                if (minutes !== null) {
                                                    todayTotalTime.textContent = formatTimeFromMinutes(minutes);
                                                }
                                            }
                                        }, 60000); // Update every minute
                                    }
                                } else {
                                    todayTotalTime.textContent = '--';
                                }
                            } else {
                                todayTotalTime.textContent = '--';
                                // Clear interval if exists
                                if (totalTimeUpdateInterval) {
                                    clearInterval(totalTimeUpdateInterval);
                                    totalTimeUpdateInterval = null;
                                }
                            }
                            
                            // Update status badge
                            const statusBadge = `<span class="badge ${getStatusBadgeClass(data.status)} rounded p-1">${data.status}</span>`;
                            todayStatus.innerHTML = statusBadge;
                            
                            // Update login status
                            if (data.time_in && !data.time_out) {
                                loginStatus.innerHTML = '<strong class="text-success">✅ Logged In</strong>';
                                loginStatus.classList.remove('text-danger', 'text-primary');
                loginStatus.classList.add('text-success');
                timeOutBtn.disabled = false;
                                timeInBtn.disabled = true;
                                timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Already Timed In';
                            } else if (data.time_in && data.time_out) {
                                loginStatus.innerHTML = '<strong class="text-primary">✅ Completed</strong>';
                                loginStatus.classList.remove('text-danger', 'text-success');
                                loginStatus.classList.add('text-primary');
                                timeOutBtn.disabled = true;
                                timeInBtn.disabled = true;
                                timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Already Timed In';
                                timeOutBtn.innerHTML = '<i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Already Timed Out';
                                
                                // Show status indicator
                                updateStatusIndicator(data);
                            } else {
                                loginStatus.innerHTML = '<strong class="text-danger">Not Logged In</strong>';
                                loginStatus.classList.remove('text-success', 'text-primary');
                                loginStatus.classList.add('text-danger');
                                timeOutBtn.disabled = true;
                                timeInBtn.disabled = false;
                                timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                            }
                        } else {
                            // No attendance today
                            currentAttendanceData = null;
                            todayIn.textContent = '--';
                            todayOut.textContent = '--';
                            todayTotalTime.textContent = '--';
                            todayStatus.innerHTML = '<span class="badge bg-secondary text-white rounded p-1">--</span>';
                            loginStatus.innerHTML = '<strong class="text-danger">Not Logged In</strong>';
                            loginStatus.classList.remove('text-success', 'text-primary');
                            loginStatus.classList.add('text-danger');
                            timeOutBtn.disabled = true;
                            timeInBtn.disabled = false;
                            timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                            
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

            // Update status indicator
            function updateStatusIndicator(data) {
                const statusIcon = document.getElementById('statusIcon');
                const statusTitle = document.getElementById('statusTitle');
                const statusMessage = document.getElementById('statusMessage');
                
                if (data.status === 'Completed' && data.total_minutes >= 240) {
                    statusIndicator.style.display = 'block';
                    statusIcon.className = 'w-5 h-5 mr-2 text-success';
                    statusIcon.setAttribute('data-lucide', 'check-circle');
                    statusTitle.textContent = 'Status: OK ✓';
                    statusTitle.className = 'font-medium text-success';
                    statusMessage.textContent = `Your attendance is complete. You worked ${data.formatted_total_time} today.`;
                } else if (data.status === 'Incomplete') {
                    statusIndicator.style.display = 'block';
                    statusIcon.className = 'w-5 h-5 mr-2 text-warning';
                    statusIcon.setAttribute('data-lucide', 'alert-triangle');
                    statusTitle.textContent = 'Status: Incomplete ⚠';
                    statusTitle.className = 'font-medium text-warning';
                    statusMessage.textContent = `You worked ${data.formatted_total_time} today, which is less than the required 4 hours.`;
                } else {
                    statusIndicator.style.display = 'none';
                }
                
                reloadLucideIcons();
            }

            // Load attendance history
            async function loadAttendanceHistory() {
                try {
                    const response = await fetch('/api/attendance/history?limit=30', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        dtrTable.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error: Server returned non-JSON response</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data && result.data.length > 0) {
                        dtrTable.innerHTML = result.data.map(record => `
                            <tr>
                                <td>${record.formatted_date}</td>
                                <td>${record.time_in}</td>
                                <td>${record.time_out}</td>
                                <td>${record.formatted_total_time}</td>
                                <td>
                                    <span class="badge ${getStatusBadgeClass(record.status)} rounded p-1">
                                        ${record.status}
                                    </span>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        dtrTable.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">No attendance records found</td></tr>';
                    }
                    
                    reloadLucideIcons();
                } catch (error) {
                    console.error('Error loading attendance history:', error);
                    dtrTable.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error loading attendance records: ' + error.message + '</td></tr>';
                }
            }

            // Time In handler
            timeInBtn.addEventListener('click', async () => {
                if (timeInBtn.disabled) return;
                
                timeInBtn.disabled = true;
                timeInBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Processing...';
                reloadLucideIcons();
                
                try {
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     document.querySelector('input[name="_token"]')?.value ||
                                     '';
                    
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        showMessage('CSRF token not found. Please refresh the page.', 'error');
                        timeInBtn.disabled = false;
                        timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                        reloadLucideIcons();
                        return;
                    }

                    const response = await fetch('/api/attendance/time-in', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        await loadTodayAttendance();
                        await loadAttendanceHistory();
                        showMessage(result.message, 'success');
                    } else {
                        // Show detailed error message
                        let errorMsg = result.message || result.error || 'Failed to time in';
                        
                        // If user already timed in, show the existing time
                        if (result.existing_time_in) {
                            errorMsg += ` (You timed in at ${result.existing_time_in})`;
                        }
                        
                        console.error('Time in error:', result);
                        showMessage(errorMsg, 'error');
                        
                        // Reload today's attendance to update the UI
                        await loadTodayAttendance();
                        
                        timeInBtn.disabled = false;
                        timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                        reloadLucideIcons();
                    }
                } catch (error) {
                    console.error('Error timing in:', error);
                    showMessage('An error occurred while timing in: ' + error.message, 'error');
                    timeInBtn.disabled = false;
                    timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                    reloadLucideIcons();
                }
            });

            // Time Out handler
            timeOutBtn.addEventListener('click', async () => {
                if (timeOutBtn.disabled) return;
                
                timeOutBtn.disabled = true;
                timeOutBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Processing...';
                reloadLucideIcons();
                
                try {
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     document.querySelector('input[name="_token"]')?.value ||
                                     '';
                    
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        showMessage('CSRF token not found. Please refresh the page.', 'error');
                        timeOutBtn.disabled = false;
                        timeOutBtn.innerHTML = '<i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Time Out';
                        reloadLucideIcons();
                        return;
                    }

                    const response = await fetch('/api/attendance/time-out', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        await loadTodayAttendance();
                        await loadAttendanceHistory();
                        showMessage(result.message, 'success');
                    } else {
                        // Show detailed error message
                        const errorMsg = result.message || result.error || 'Failed to time out';
                        console.error('Time out error:', result);
                        showMessage(errorMsg, 'error');
                        timeOutBtn.disabled = false;
                        timeOutBtn.innerHTML = '<i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Time Out';
                        reloadLucideIcons();
                    }
                } catch (error) {
                    console.error('Error timing out:', error);
                    showMessage('An error occurred while timing out: ' + error.message, 'error');
                    timeOutBtn.disabled = false;
                    timeOutBtn.innerHTML = '<i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Time Out';
                    reloadLucideIcons();
                }
            });

            // Refresh button
            refreshBtn.addEventListener('click', async () => {
                refreshBtn.disabled = true;
                refreshBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-1 animate-spin"></i> Refreshing...';
                reloadLucideIcons();
                
                await Promise.all([loadTodayAttendance(), loadAttendanceHistory()]);
                
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 mr-1"></i> Refresh';
                reloadLucideIcons();
                showMessage('Attendance data refreshed', 'success');
            });

            // Show message
            function showMessage(message, type) {
                // Create a temporary message div
                const messageDiv = document.createElement('div');
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'error' ? 'danger' : 'info'} flex items-center fixed top-4 right-4 z-50 shadow-lg`;
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
                await Promise.all([loadTodayAttendance(), loadAttendanceHistory()]);
                reloadLucideIcons();
                
                // Update total hours display every 10 seconds if timed in but not out
                setInterval(() => {
                    if (currentAttendanceData && currentAttendanceData.time_in && !currentAttendanceData.time_out) {
                        const timeInRaw = currentAttendanceData.time_in_raw || currentAttendanceData.time_in;
                        const minutes = calculateCurrentTotalTime(
                            timeInRaw, 
                            currentAttendanceData.date || new Date().toISOString().split('T')[0]
                        );
                        if (minutes !== null) {
                            todayTotalTime.textContent = formatTimeFromMinutes(minutes);
                        }
                    }
                }, 10000); // Update every 10 seconds for smoother real-time display
                
                // Refresh attendance data every 30 seconds
                setInterval(async () => {
                    await loadTodayAttendance();
                }, 30000);
            }

            // Call init immediately (script is already loaded after DOM content)
            init();
        })();
    </script>
@endsection
