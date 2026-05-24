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
                        <p class="text-slate-500 mt-2">Use this page to Time In and Time Out daily. Track your working hours and attendance status. <strong>Photo capture is required for time in/out.</strong></p>
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
                        <div class="flex gap-3 flex-wrap">
                            <button id="timeInBtn" class="btn btn-primary">
                                <i data-lucide="log-in" class="w-4 h-4 mr-2"></i> 
                                Time In
                            </button>
                            <button id="timeOutBtn" class="btn btn-outline-secondary" disabled>
                                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> 
                                Time Out
                            </button>
                            <button id="overtimeRequestBtn" class="btn btn-warning text-white" style="display: none;">
                                <i data-lucide="clock" class="w-4 h-4 mr-2"></i> 
                                Request Overtime
                            </button>
                        </div>
                        <div id="overtimeMessage" class="mt-3 text-sm" style="display: none;"></div>
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
                                <div class="text-slate-600 text-sm mb-1">Total Time Rendered (Today):</div>
                                <div class="text-lg font-semibold text-primary" id="todayTotalTime">--</div>
                                <div class="text-xs text-slate-400 mt-1" id="todayTotalTimeAll" style="display: none;"></div>
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
                            <h3 class="text-base font-medium flex items-center mr-auto">
                                <i data-lucide="clipboard-list" class="w-4 h-4 mr-2"></i>
                                Attendance History
                            </h3>
                            <button id="refreshBtn" class="btn btn-outline-secondary btn-sm">
                                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                Refresh
                            </button>
                        </div>
                        
                        <!-- Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="filterYear" class="form-label">Year:</label>
                                <select id="filterYear" class="form-control">
                                    <option value="">All Years</option>
                                </select>
                            </div>
                            <div>
                                <label for="filterMonth" class="form-label">Month:</label>
                                <select id="filterMonth" class="form-control">
                                    <option value="">All Months</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div>
                                <label for="filterDay" class="form-label">Day:</label>
                                <select id="filterDay" class="form-control">
                                    <option value="">All Days</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button id="clearFiltersBtn" class="btn btn-outline-secondary w-full">
                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time In Photo</th>
                                        <th>Time Out</th>
                                        <th>Time Out Photo</th>
                                        <th>Total Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="dtrTable">
                                    <tr>
                                        <td colspan="7" class="text-center text-slate-500">Loading attendance records...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-3" id="paginationContainer" style="display: none;">
                            <nav class="w-full sm:w-auto sm:mr-auto">
                                <ul class="pagination" id="pagination">
                                    <!-- Pagination items will be generated by JavaScript -->
                                </ul>
                            </nav>
                            <div class="hidden md:block text-slate-500">
                                Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> entries
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo View Modal -->
                <div id="photoViewModal" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto" id="photoViewTitle">Photo</h2>
                                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="modal-body p-5 text-center">
                                <img id="photoViewImage" src="" alt="Attendance Photo" style="max-width: 100%; border-radius: 8px;">
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-tw-dismiss="modal" class="btn btn-secondary w-20">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reminder -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5 flex items-center">
                        <i data-lucide="bell" class="w-4 h-4 mr-2 text-warning"></i>
                        <span><strong>Reminder:</strong> Don't forget to Time Out before leaving. Minimum required hours: 5 hours per day. If your total working hours exceed 5 hours, you will need to request overtime approval from HR before timing out.</span>
                    </div>
                </div>

                <!-- Photo Capture Modal -->
                <div id="photoCaptureModal" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto" id="photoModalTitle">Capture Photo</h2>
                                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="modal-body p-5">
                                <div class="text-center">
                                    <video id="video" autoplay playsinline style="width: 100%; max-width: 640px; border-radius: 8px; background: #000;"></video>
                                    <canvas id="canvas" style="display: none;"></canvas>
                                    <div id="photoPreview" class="mt-4" style="display: none;">
                                        <img id="capturedPhoto" src="" alt="Captured Photo" style="max-width: 100%; border-radius: 8px;">
                                    </div>
                                </div>
                                <div class="flex justify-center gap-3 mt-4">
                                    <button id="captureBtn" class="btn btn-primary">
                                        <i data-lucide="camera" class="w-4 h-4 mr-2"></i>
                                        Capture Photo
                                    </button>
                                    <button id="retakeBtn" class="btn btn-outline-secondary" style="display: none;">
                                        <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                        Retake
                                    </button>
                                    <button id="confirmPhotoBtn" class="btn btn-success" style="display: none;">
                                        <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                        Confirm & Submit
                                    </button>
                                </div>
                            </div>
                        </div>
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
            const overtimeRequestBtn = document.getElementById('overtimeRequestBtn');
            const overtimeMessage = document.getElementById('overtimeMessage');

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
            
            // Pagination variables
            let attendanceHistoryData = [];
            let currentPage = 1;
            const itemsPerPage = 5;
            
            // Photo capture variables
            let stream = null;
            let capturedPhotoBase64 = null;
            let currentAction = null; // 'timeIn' or 'timeOut'
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photoPreview = document.getElementById('photoPreview');
            const capturedPhoto = document.getElementById('capturedPhoto');
            const captureBtn = document.getElementById('captureBtn');
            const retakeBtn = document.getElementById('retakeBtn');
            const confirmPhotoBtn = document.getElementById('confirmPhotoBtn');
            const photoModal = document.getElementById('photoCaptureModal');
            const photoModalTitle = document.getElementById('photoModalTitle');

            // Initialize photo capture modal
            function initPhotoCapture() {
                // Open camera when modal is shown
                if (photoModal) {
                    photoModal.addEventListener('shown.tw.modal', async function() {
                        // Ensure aria-hidden is false when modal is shown
                        photoModal.setAttribute('aria-hidden', 'false');
                        
                        try {
                            stream = await navigator.mediaDevices.getUserMedia({ 
                                video: { 
                                    facingMode: 'user',
                                    width: { ideal: 1280 },
                                    height: { ideal: 720 }
                                } 
                            });
                            if (video) {
                                video.srcObject = stream;
                            }
                        } catch (error) {
                            console.error('Error accessing camera:', error);
                            alert('Unable to access camera. Please ensure camera permissions are granted.');
                            if (window.tailwind && window.tailwind.Modal) {
                                const modalInstance = tailwind.Modal.getOrCreateInstance(photoModal);
                                modalInstance.hide();
                            }
                        }
                    });

                    // Stop camera when modal is hidden
                    photoModal.addEventListener('hidden.tw.modal', function() {
                        // Ensure aria-hidden is true when modal is hidden
                        photoModal.setAttribute('aria-hidden', 'true');
                        stopCamera();
                        resetPhotoCapture();
                    });
                }

                // Capture photo
                if (captureBtn) {
                    captureBtn.addEventListener('click', function() {
                        if (video && canvas) {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(video, 0, 0);
                            
                            // Convert to base64
                            capturedPhotoBase64 = canvas.toDataURL('image/jpeg', 0.8);
                            
                            // Show preview
                            if (capturedPhoto) {
                                capturedPhoto.src = capturedPhotoBase64;
                            }
                            photoPreview.style.display = 'block';
                            captureBtn.style.display = 'none';
                            retakeBtn.style.display = 'inline-flex';
                            confirmPhotoBtn.style.display = 'inline-flex';
                            
                            // Stop camera stream
                            stopCamera();
                        }
                    });
                }

                // Retake photo
                if (retakeBtn) {
                    retakeBtn.addEventListener('click', function() {
                        resetPhotoCapture();
                        startCamera();
                    });
                }

                // Confirm and submit
                if (confirmPhotoBtn) {
                    confirmPhotoBtn.addEventListener('click', function() {
                        if (capturedPhotoBase64 && currentAction) {
                            submitTimeAction(currentAction, capturedPhotoBase64);
                        }
                    });
                }
            }

            function startCamera() {
                if (video) {
                    navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        } 
                    }).then(function(mediaStream) {
                        stream = mediaStream;
                        video.srcObject = stream;
                    }).catch(function(error) {
                        console.error('Error accessing camera:', error);
                        alert('Unable to access camera. Please ensure camera permissions are granted.');
                    });
                }
            }

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                if (video) {
                    video.srcObject = null;
                }
            }

            function resetPhotoCapture() {
                capturedPhotoBase64 = null;
                photoPreview.style.display = 'none';
                captureBtn.style.display = 'inline-flex';
                retakeBtn.style.display = 'none';
                confirmPhotoBtn.style.display = 'none';
            }

            function openPhotoCapture(action) {
                currentAction = action;
                if (photoModalTitle) {
                    photoModalTitle.textContent = action === 'timeIn' ? 'Capture Photo for Time In' : 'Capture Photo for Time Out';
                }
                
                if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                    const modal = tailwind.Modal.getOrCreateInstance(photoModal);
                    modal.show();
                    // Ensure aria-hidden is false when modal is shown
                    photoModal.setAttribute('aria-hidden', 'false');
                } else {
                    photoModal.style.display = 'block';
                    photoModal.classList.add('show');
                    // Ensure aria-hidden is false when modal is shown
                    photoModal.setAttribute('aria-hidden', 'false');
                }
            }

            async function submitTimeAction(action, photoBase64) {
                // Close modal
                if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                    const modal = tailwind.Modal.getOrCreateInstance(photoModal);
                    modal.hide();
                    // Ensure aria-hidden is true when modal is hidden
                    photoModal.setAttribute('aria-hidden', 'true');
                } else {
                    photoModal.style.display = 'none';
                    photoModal.classList.remove('show');
                    // Ensure aria-hidden is true when modal is hidden
                    photoModal.setAttribute('aria-hidden', 'true');
                }
                stopCamera();
                resetPhotoCapture();

                // Disable button and show loading
                const btn = action === 'timeIn' ? timeInBtn : timeOutBtn;
                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Processing...';
                reloadLucideIcons();

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     document.querySelector('input[name="_token"]')?.value || '';
                    
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        showMessage('CSRF token not found. Please refresh the page.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                        reloadLucideIcons();
                        return;
                    }

                    const url = action === 'timeIn' ? '/api/attendance/time-in' : '/api/attendance/time-out';
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            photo_base64: photoBase64
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        await loadTodayAttendance();
                        await loadAttendanceHistory();
                        showMessage(result.message, 'success');
                    } else {
                        let errorMsg = result.message || result.error || `Failed to ${action === 'timeIn' ? 'time in' : 'time out'}`;
                        if (result.existing_time_in) {
                            const existingDate = result.existing_date ? new Date(result.existing_date).toLocaleDateString() : 'today';
                            errorMsg = `You have an open time-in session. Please time out first before timing in again. (You timed in at ${result.existing_time_in} on ${existingDate})`;
                        }
                        console.error(`${action} error:`, result);
                        showMessage(errorMsg, 'error');
                        
                        // Reload attendance data even on error to ensure UI reflects current state
                        // This is important when there's an existing session that the UI might not be showing
                        await loadTodayAttendance();
                        
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                        reloadLucideIcons();
                    }
                } catch (error) {
                    console.error(`Error ${action}:`, error);
                    showMessage(`An error occurred while ${action === 'timeIn' ? 'timing in' : 'timing out'}: ` + error.message, 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                    reloadLucideIcons();
                }
            }

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
                            
                            // Show total time for all sessions today if available
                            const todayTotalTimeAllEl = document.getElementById('todayTotalTimeAll');
                            if (data.formatted_total_time_today && data.all_records && data.all_records.length > 1) {
                                todayTotalTimeAllEl.style.display = 'block';
                                todayTotalTimeAllEl.textContent = `Total today: ${data.formatted_total_time_today} (${data.all_records.length} sessions)`;
                            } else if (data.formatted_total_time_today) {
                                todayTotalTimeAllEl.style.display = 'none';
                            } else {
                                todayTotalTimeAllEl.style.display = 'none';
                            }
                            
                            // Calculate and display total time for current session
                            if (data.time_in && data.time_out) {
                                // Both time in and out - use server calculated time
                                todayTotalTime.textContent = data.formatted_total_time || '--';
                                
                                // Update total for today display
                                if (data.formatted_total_time_today) {
                                    if (!todayTotalTimeAllEl.textContent.includes('Total today:')) {
                                        todayTotalTimeAllEl.style.display = 'block';
                                        todayTotalTimeAllEl.textContent = `Total today: ${data.formatted_total_time_today}`;
                                    }
                                }
                                
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
                            
                            // Check and update overtime status
                            checkAndUpdateOvertimeStatus(data);
                            
                            // Update login status / Time Actions buttons
                            // Rules:
                            // - If there is an open session (time_in && !time_out): disable Time In, enable Time Out
                            // - If latest session is closed (time_in && time_out): enable Time In again (allow multiple sessions), disable Time Out
                            // - If no session: enable Time In, disable Time Out
                            if (data.time_in && !data.time_out) {
                                loginStatus.innerHTML = '<strong class="text-success">Logged In</strong>';
                                loginStatus.classList.remove('text-danger', 'text-primary');
                                loginStatus.classList.add('text-success');
                                // Always enable time out button - students can time out anytime
                                // Server will validate if after 6 PM without approval
                                timeOutBtn.disabled = false;
                                timeInBtn.disabled = true;
                                timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Already Timed In';
                            } else if (data.time_in && data.time_out) {
                                // Latest session is completed — allow another Time In (multiple sessions per day)
                                loginStatus.innerHTML = '<strong class="text-primary">Timed Out</strong>';
                                loginStatus.classList.remove('text-danger', 'text-success');
                                loginStatus.classList.add('text-primary');

                                timeOutBtn.disabled = true;
                                timeOutBtn.innerHTML = '<i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Time Out';

                                timeInBtn.disabled = false;
                                timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                                
                                // Show status indicator
                                updateStatusIndicator(data);
                            } else {
                                loginStatus.innerHTML = '<strong class="text-danger">Not Logged In</strong>';
                                loginStatus.classList.remove('text-success', 'text-primary');
                                loginStatus.classList.add('text-danger');
                                timeOutBtn.disabled = true;
                                timeInBtn.disabled = false;
                                timeInBtn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Time In';
                                overtimeRequestBtn.style.display = 'none';
                                overtimeMessage.style.display = 'none';
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
                            overtimeRequestBtn.style.display = 'none';
                            overtimeMessage.style.display = 'none';
                            
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

            // Check and update overtime status (automatic detection, no approval needed)
            function checkAndUpdateOvertimeStatus(data) {
                // Hide overtime request button - overtime is now automatic
                overtimeRequestBtn.style.display = 'none';
                
                if (!data || !data.time_in || data.time_out) {
                    // Not timed in or already timed out - no overtime check needed
                    overtimeMessage.style.display = 'none';
                    return;
                }
                
                // Calculate total minutes worked today
                let totalMinutesToday = 0;
                const REQUIRED_HOURS_MINUTES = 300; // 5 hours
                
                if (data.total_minutes_today !== undefined && data.total_minutes_today !== null) {
                    // Use backend provided total
                    totalMinutesToday = data.total_minutes_today;
                } else if (data.time_in && !data.time_out) {
                    // Currently timed in - calculate real-time total including current session
                    const timeInRaw = data.time_in_raw || data.time_in;
                    const currentMinutes = calculateCurrentTotalTime(timeInRaw, data.date || new Date().toISOString().split('T')[0]);
                    if (currentMinutes !== null) {
                        // Add previous sessions if any
                        const previousMinutes = (data.total_minutes || 0) - currentMinutes;
                        if (previousMinutes > 0) {
                            totalMinutesToday = previousMinutes + currentMinutes;
                        } else {
                            totalMinutesToday = currentMinutes;
                        }
                    }
                }
                
                // Check if total hours worked exceeds 5 hours (300 minutes) - automatic overtime detection
                const isOvertime = totalMinutesToday > REQUIRED_HOURS_MINUTES;
                
                // Show informational message if overtime detected (no approval needed)
                if (isOvertime) {
                    const overtimeHours = Math.floor(totalMinutesToday / 60);
                    const overtimeMinutes = totalMinutesToday % 60;
                    const overtimeText = overtimeHours > 0 && overtimeMinutes > 0 
                        ? `${overtimeHours}h ${overtimeMinutes}m` 
                        : overtimeHours > 0 ? `${overtimeHours}h` : `${overtimeMinutes}m`;
                    
                    overtimeMessage.style.display = 'block';
                    overtimeMessage.innerHTML = `<span class="text-info"><i data-lucide="info" class="w-4 h-4 inline mr-1"></i> Overtime detected: You have worked ${overtimeText} today (exceeds 5 hours). Overtime will be automatically logged.</span>`;
                    reloadLucideIcons();
                } else {
                    overtimeMessage.style.display = 'none';
                }
            }

            // Update status indicator
            function updateStatusIndicator(data) {
                const statusIcon = document.getElementById('statusIcon');
                const statusTitle = document.getElementById('statusTitle');
                const statusMessage = document.getElementById('statusMessage');
                
                if (!statusIcon || !statusTitle || !statusMessage) {
                    return; // Elements not found, skip update
                }
                
                if (data.status === 'Completed' && data.total_minutes >= 300) {
                    statusIndicator.style.display = 'block';
                    // Use setAttribute for SVG elements instead of className
                    statusIcon.setAttribute('class', 'w-5 h-5 mr-2 text-success');
                    statusIcon.setAttribute('data-lucide', 'check-circle');
                    statusTitle.textContent = 'Status: OK ✓';
                    statusTitle.className = 'font-medium text-success';
                    statusMessage.textContent = `Your attendance is complete. You worked ${data.formatted_total_time} today.`;
                } else if (data.status === 'Incomplete') {
                    statusIndicator.style.display = 'block';
                    // Use setAttribute for SVG elements instead of className
                    statusIcon.setAttribute('class', 'w-5 h-5 mr-2 text-warning');
                    statusIcon.setAttribute('data-lucide', 'alert-triangle');
                    statusTitle.textContent = 'Status: Incomplete ⚠';
                    statusTitle.className = 'font-medium text-warning';
                    statusMessage.textContent = `You worked ${data.formatted_total_time} today, which is less than the required 5 hours.`;
                } else {
                    statusIndicator.style.display = 'none';
                }
                
                reloadLucideIcons();
            }

            // Filter elements
            const filterYear = document.getElementById('filterYear');
            const filterMonth = document.getElementById('filterMonth');
            const filterDay = document.getElementById('filterDay');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');

            // Initialize year dropdown with available years
            function initializeYearFilter() {
                const currentYear = new Date().getFullYear();
                const years = [];
                for (let i = currentYear; i >= currentYear - 5; i--) {
                    years.push(i);
                }
                filterYear.innerHTML = '<option value="">All Years</option>' + 
                    years.map(year => `<option value="${year}">${year}</option>`).join('');
            }

            // Update day dropdown based on selected month
            function updateDayFilter() {
                const year = filterYear.value;
                const month = filterMonth.value;
                
                filterDay.innerHTML = '<option value="">All Days</option>';
                
                if (year && month) {
                    const daysInMonth = new Date(year, month, 0).getDate();
                    for (let i = 1; i <= daysInMonth; i++) {
                        const day = i.toString().padStart(2, '0');
                        filterDay.innerHTML += `<option value="${day}">${day}</option>`;
                    }
                }
            }

            // Load attendance history with filters
            async function loadAttendanceHistory() {
                try {
                    // Build query string with filters
                    const params = new URLSearchParams();
                    params.append('limit', '100');
                    if (filterYear.value) params.append('year', filterYear.value);
                    if (filterMonth.value) params.append('month', filterMonth.value);
                    if (filterDay.value) params.append('day', filterDay.value);

                    const response = await fetch('/api/attendance/history?' + params.toString(), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        dtrTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error: Server returned non-JSON response</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data && result.data.length > 0) {
                        attendanceHistoryData = result.data;
                        // Reset to first page when loading new data
                        currentPage = 1;
                        renderAttendanceTable();
                    } else {
                        attendanceHistoryData = [];
                        dtrTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No attendance records found</td></tr>';
                        document.getElementById('paginationContainer').style.display = 'none';
                    }
                    
                    reloadLucideIcons();
                } catch (error) {
                    console.error('Error loading attendance history:', error);
                    dtrTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading attendance records: ' + error.message + '</td></tr>';
                    attendanceHistoryData = [];
                    document.getElementById('paginationContainer').style.display = 'none';
                }
            }

            // Render attendance table with pagination
            function renderAttendanceTable() {
                if (attendanceHistoryData.length === 0) {
                    dtrTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No attendance records found</td></tr>';
                    document.getElementById('paginationContainer').style.display = 'none';
                    return;
                }

                // Calculate pagination
                const totalPages = Math.ceil(attendanceHistoryData.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, attendanceHistoryData.length);
                const paginatedData = attendanceHistoryData.slice(startIndex, endIndex);

                // Render table rows
                dtrTable.innerHTML = paginatedData.map(record => {
                    const timeInPhoto = record.time_in_photo 
                        ? `<button class="btn btn-sm btn-outline-primary" onclick="viewPhoto('${record.time_in_photo}', 'Time In Photo - ${record.formatted_date}')">
                            <i data-lucide="image" class="w-4 h-4 mr-1"></i>View
                           </button>`
                        : '<span class="text-slate-400">—</span>';
                    
                    const timeOutPhoto = record.time_out_photo 
                        ? `<button class="btn btn-sm btn-outline-primary" onclick="viewPhoto('${record.time_out_photo}', 'Time Out Photo - ${record.formatted_date}')">
                            <i data-lucide="image" class="w-4 h-4 mr-1"></i>View
                           </button>`
                        : '<span class="text-slate-400">—</span>';
                    
                    return `
                        <tr>
                            <td class="whitespace-nowrap">${record.formatted_date}</td>
                            <td class="whitespace-nowrap">${record.time_in}</td>
                            <td class="whitespace-nowrap">${timeInPhoto}</td>
                            <td class="whitespace-nowrap">${record.time_out}</td>
                            <td class="whitespace-nowrap">${timeOutPhoto}</td>
                            <td class="whitespace-nowrap">${record.formatted_total_time}</td>
                            <td class="whitespace-nowrap">
                                <span class="badge ${getStatusBadgeClass(record.status)} rounded p-1">
                                    ${record.status}
                                </span>
                            </td>
                        </tr>
                    `;
                }).join('');

                // Update pagination info
                document.getElementById('paginationStart').textContent = attendanceHistoryData.length > 0 ? startIndex + 1 : 0;
                document.getElementById('paginationEnd').textContent = endIndex;
                document.getElementById('paginationTotal').textContent = attendanceHistoryData.length;

                // Render pagination controls
                renderPagination(totalPages);
                
                // Show pagination container
                document.getElementById('paginationContainer').style.display = 'flex';
                
                reloadLucideIcons();
            }

            // Render pagination controls
            function renderPagination(totalPages) {
                const paginationEl = document.getElementById('pagination');
                
                if (totalPages <= 1) {
                    paginationEl.innerHTML = '';
                    document.getElementById('paginationContainer').style.display = 'none';
                    return;
                }

                let paginationHTML = '';

                // First page button
                paginationHTML += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToAttendancePage(1); return false;">
                            <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                        </a>
                    </li>
                `;

                // Previous page button
                paginationHTML += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToAttendancePage(${currentPage - 1}); return false;">
                            <i class="w-4 h-4" data-lucide="chevron-left"></i>
                        </a>
                    </li>
                `;

                // Page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, currentPage + 2);

                if (startPage > 1) {
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToAttendancePage(1); return false;">1</a></li>`;
                    if (startPage > 2) {
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#">...</a></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="goToAttendancePage(${i}); return false;">${i}</a>
                        </li>
                    `;
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#">...</a></li>`;
                    }
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToAttendancePage(${totalPages}); return false;">${totalPages}</a></li>`;
                }

                // Next page button
                paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToAttendancePage(${currentPage + 1}); return false;">
                            <i class="w-4 h-4" data-lucide="chevron-right"></i>
                        </a>
                    </li>
                `;

                // Last page button
                paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToAttendancePage(${totalPages}); return false;">
                            <i class="w-4 h-4" data-lucide="chevrons-right"></i>
                        </a>
                    </li>
                `;

                paginationEl.innerHTML = paginationHTML;
                reloadLucideIcons();
            }

            // Go to specific page
            window.goToAttendancePage = function(page) {
                const totalPages = Math.ceil(attendanceHistoryData.length / itemsPerPage);
                if (page < 1 || page > totalPages || page === currentPage) {
                    return;
                }
                currentPage = page;
                renderAttendanceTable();
                // Scroll to top of table
                document.querySelector('.table').scrollIntoView({ behavior: 'smooth', block: 'start' });
            };

            // View photo function (global scope for onclick)
            window.viewPhoto = function(photoUrl, title) {
                const photoViewModal = document.getElementById('photoViewModal');
                const photoViewImage = document.getElementById('photoViewImage');
                const photoViewTitle = document.getElementById('photoViewTitle');
                
                if (photoViewImage) {
                    photoViewImage.src = photoUrl;
                }
                if (photoViewTitle) {
                    photoViewTitle.textContent = title || 'Attendance Photo';
                }
                
                if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                    const modal = tailwind.Modal.getOrCreateInstance(photoViewModal);
                    modal.show();
                    // Ensure aria-hidden is false when modal is shown
                    photoViewModal.setAttribute('aria-hidden', 'false');
                } else {
                    photoViewModal.style.display = 'block';
                    photoViewModal.classList.add('show');
                    // Ensure aria-hidden is false when modal is shown
                    photoViewModal.setAttribute('aria-hidden', 'false');
                }
            };

            // Setup filter event listeners
            if (filterYear) {
                filterYear.addEventListener('change', function() {
                    updateDayFilter();
                    currentPage = 1; // Reset to first page when filtering
                    loadAttendanceHistory();
                });
            }
            
            if (filterMonth) {
                filterMonth.addEventListener('change', function() {
                    updateDayFilter();
                    currentPage = 1; // Reset to first page when filtering
                    loadAttendanceHistory();
                });
            }
            
            if (filterDay) {
                filterDay.addEventListener('change', function() {
                    currentPage = 1; // Reset to first page when filtering
                    loadAttendanceHistory();
                });
            }
            
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    filterYear.value = '';
                    filterMonth.value = '';
                    filterDay.value = '';
                    updateDayFilter();
                    currentPage = 1; // Reset to first page when clearing filters
                    loadAttendanceHistory();
                });
            }

            // Time In handler
            timeInBtn.addEventListener('click', async () => {
                if (timeInBtn.disabled) return;
                openPhotoCapture('timeIn');
            });

            // Time Out handler
            timeOutBtn.addEventListener('click', async () => {
                if (timeOutBtn.disabled) return;
                openPhotoCapture('timeOut');
            });

            // Overtime Request handler
            overtimeRequestBtn.addEventListener('click', async () => {
                const reason = prompt('Please provide a reason for overtime request:');
                if (!reason || reason.trim() === '') {
                    return;
                }
                
                overtimeRequestBtn.disabled = true;
                overtimeRequestBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Submitting...';
                reloadLucideIcons();
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    if (!csrfToken) {
                        showMessage('CSRF token not found. Please refresh the page.', 'error');
                        overtimeRequestBtn.disabled = false;
                        overtimeRequestBtn.innerHTML = '<i data-lucide="clock" class="w-4 h-4 mr-2"></i> Request Overtime';
                        reloadLucideIcons();
                        return;
                    }

                    const response = await fetch('/api/requests', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            request_type: 'Overtime Request',
                            message: `Overtime Request: ${reason.trim()}`
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        showMessage('Overtime request submitted successfully! Please wait for HR approval.', 'success');
                        overtimeRequestBtn.style.display = 'none';
                        overtimeMessage.style.display = 'block';
                        overtimeMessage.innerHTML = '<span class="text-info"><i data-lucide="info" class="w-4 h-4 inline mr-1"></i> Overtime request submitted. Waiting for HR approval.</span>';
                        // Reload attendance to check for approval
                        await loadTodayAttendance();
                    } else {
                        const errorMsg = result.message || 'Failed to submit overtime request';
                        showMessage(errorMsg, 'error');
                        overtimeRequestBtn.disabled = false;
                        overtimeRequestBtn.innerHTML = '<i data-lucide="clock" class="w-4 h-4 mr-2"></i> Request Overtime';
                        reloadLucideIcons();
                    }
                } catch (error) {
                    console.error('Error submitting overtime request:', error);
                    showMessage('An error occurred while submitting overtime request: ' + error.message, 'error');
                    overtimeRequestBtn.disabled = false;
                    overtimeRequestBtn.innerHTML = '<i data-lucide="clock" class="w-4 h-4 mr-2"></i> Request Overtime';
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
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} flex items-center fixed top-4 right-4 z-50 shadow-lg max-w-md`;
                messageDiv.innerHTML = `
                    <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                    <span class="flex-1">${message}</span>
                `;
                
                document.body.appendChild(messageDiv);
                reloadLucideIcons();
                
                // Show error messages longer (5 seconds) than success messages (3 seconds)
                const duration = type === 'error' ? 5000 : 3000;
                setTimeout(() => {
                    messageDiv.remove();
                }, duration);
            }

            // Initialize
            async function init() {
                initPhotoCapture();
                initializeYearFilter();
                updateDayFilter();
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
                            
                            // Update overtime status check with real-time data
                            const updatedData = { ...currentAttendanceData };
                            // Calculate total minutes today for overtime check
                            if (currentAttendanceData.total_minutes_today !== undefined && currentAttendanceData.total_minutes_today !== null) {
                                // If we have previous sessions, add current session
                                const previousMinutes = currentAttendanceData.total_minutes_today - (currentAttendanceData.total_minutes || 0);
                                updatedData.total_minutes_today = previousMinutes + minutes;
                            } else {
                                updatedData.total_minutes_today = minutes;
                            }
                            checkAndUpdateOvertimeStatus(updatedData);
                        }
                    }
                }, 10000); // Update every 10 seconds for smoother real-time display
                
                // Refresh attendance data every 30 seconds
                setInterval(async () => {
                    await loadTodayAttendance();
                }, 30000);
            }

            // Handle photo view modal close
            document.addEventListener('DOMContentLoaded', function() {
                const photoViewModal = document.getElementById('photoViewModal');
                
                // Add event listeners for modal shown/hidden to manage aria-hidden
                if (photoViewModal) {
                    photoViewModal.addEventListener('shown.tw.modal', function() {
                        // Ensure aria-hidden is false when modal is shown
                        photoViewModal.setAttribute('aria-hidden', 'false');
                    });
                    
                    photoViewModal.addEventListener('hidden.tw.modal', function() {
                        // Ensure aria-hidden is true when modal is hidden
                        photoViewModal.setAttribute('aria-hidden', 'true');
                    });
                }
                
                const closeButtons = photoViewModal ? photoViewModal.querySelectorAll('[data-tw-dismiss="modal"]') : [];
                
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                            const modalInstance = tailwind.Modal.getOrCreateInstance(photoViewModal);
                            modalInstance.hide();
                            // Ensure aria-hidden is true when modal is hidden
                            photoViewModal.setAttribute('aria-hidden', 'true');
                        } else {
                            photoViewModal.style.display = 'none';
                            photoViewModal.classList.remove('show');
                            // Ensure aria-hidden is true when modal is hidden
                            photoViewModal.setAttribute('aria-hidden', 'true');
                        }
                    });
                });

                // Close modal when clicking backdrop
                if (photoViewModal) {
                    photoViewModal.addEventListener('click', function(e) {
                        if (e.target === photoViewModal) {
                            if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                                const modalInstance = tailwind.Modal.getOrCreateInstance(photoViewModal);
                                modalInstance.hide();
                                // Ensure aria-hidden is true when modal is hidden
                                photoViewModal.setAttribute('aria-hidden', 'true');
                            } else {
                                photoViewModal.style.display = 'none';
                                photoViewModal.classList.remove('show');
                                // Ensure aria-hidden is true when modal is hidden
                                photoViewModal.setAttribute('aria-hidden', 'true');
                            }
                        }
                    });
                }
            });

            // Call init immediately (script is already loaded after DOM content)
            init();
        })();
    </script>
@endsection
