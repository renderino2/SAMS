@extends('../layout/' . $layout)

@section('subhead')
    <title>Office Head Dashboard - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Office Head Dashboard Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <div class="flex items-center">
                            <h1 class="text-xl md:text-2xl font-medium">Office Head Dashboard</h1>
                            <div class="ml-auto">
                                <div class="w-5 h-5 bg-success rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Office Head Dashboard Header -->

                <!-- BEGIN: Stats Cards -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="users" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="assignedCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Assigned Student Assistants</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="attendanceCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Attendance Today</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="clipboard-check" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="evalStatus">—</div>
                            <div class="text-base text-slate-500 mt-1">Evaluation Status</div>
                        </div>
                    </div>
                </div>
                <!-- END: Stats Cards -->

                <!-- BEGIN: Analytics Charts -->
                <div class="col-span-12 grid grid-cols-12 gap-6 mt-8">
                    <!-- Donut Chart: Student Assistants by Department -->
                    <div class="col-span-12 lg:col-span-6 intro-y">
                        <div class="box p-5">
                            <div class="flex items-center h-10 mb-5">
                                <h2 class="text-lg font-medium truncate mr-5">Student Assistants by Department</h2>
                            </div>
                            <div class="h-[400px]">
                                <canvas id="sa-by-department-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Vertical Bar Chart: Attendance by Month -->
                    <div class="col-span-12 lg:col-span-6 intro-y">
                        <div class="box p-5">
                            <div class="flex items-center h-10 mb-5">
                                <h2 class="text-lg font-medium truncate mr-5">Attendance by Month</h2>
                            </div>
                            <div class="h-[400px]">
                                <canvas id="attendance-by-month-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart: Attendance Status -->
                    <div class="col-span-12 lg:col-span-12 intro-y">
                        <div class="box p-5">
                            <div class="flex items-center h-10 mb-5">
                                <h2 class="text-lg font-medium truncate mr-5">Attendance Status Today</h2>
                            </div>
                            <div class="h-[400px]">
                                <canvas id="attendance-status-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Analytics Charts -->

                <!-- BEGIN: DTR Summary Table -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">DTR Summary per Assistant</h2>
                        </div>
                        <div class="overflow-x-auto mt-5">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">NAME</th>
                                        <th class="whitespace-nowrap">ATTENDANCE</th>
                                        <th class="whitespace-nowrap">TIME IN / OUT</th>
                                        <th class="whitespace-nowrap">STATUS</th>
                                        <th class="whitespace-nowrap">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody id="dtrTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">Loading DTR data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center mt-6 gap-3">
                            <button class="btn btn-success" onclick="approveOvertime()">
                                <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Approve Overtime
                            </button>
                            <button class="btn btn-danger" onclick="markAbsence()">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Mark Absence
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END: DTR Summary Table -->
            </div>
        </div>

        <!-- BEGIN: Side Panels -->
        <div class="col-span-12 2xl:col-span-3">
            <div class="2xl:border-l -mb-10 pb-10">
                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                    <!-- BEGIN: Quick Actions -->
                    
                    <!-- END: Quick Actions -->

                    <!-- BEGIN: Apprentice Approvals -->
                    <div class="col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Apprentice Approvals</h2>
                        </div>
                        <div class="mt-5" id="apprenticeApprovals">
                            <div class="text-center text-slate-500 text-sm">Loading apprentices...</div>
                        </div>
                    </div>
                    <!-- END: Apprentice Approvals -->

                    <!-- BEGIN: Resignation Requests -->
                    <div class="col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Resignation Requests</h2>
                        </div>
                        <div class="mt-5" id="resignationRequests">
                            <div class="text-center text-slate-500 text-sm">Loading resignation requests...</div>
                        </div>
                    </div>
                    <!-- END: Resignation Requests -->

                    <!-- BEGIN: Recent Activities -->
                    <div class="col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Recent Activities</h2>
                        </div>
                        <div class="mt-5 relative before:block before:absolute before:w-px before:h-[85%] before:bg-slate-200 before:dark:bg-darkmode-400 before:ml-5 before:mt-5">
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="user-check" class="w-5 h-5 text-success"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Student Assistant Check-in</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastCheckIn">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">New student assistant checked in</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Evaluation Submitted</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastEvaluation">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">New evaluation form submitted</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="send" class="w-5 h-5 text-warning"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Request Review</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastRequest">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">New request pending review</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Recent Activities -->
                </div>
            </div>
        </div>
        <!-- END: Side Panels -->
    </div>
    @endsection
@section('script')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const assignedCountEl = document.getElementById('assignedCount');
        const attendanceCountEl = document.getElementById('attendanceCount');
        const evalStatusEl = document.getElementById('evalStatus');
        const dtrTableBody = document.getElementById('dtrTableBody');

        // Load office dashboard statistics
        async function loadDashboardStats() {
            try {
                const response = await fetch('/api/office-dashboard-stats', {
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
                console.log('Dashboard stats response:', result);
                
                if (result.success && result.data) {
                    const data = result.data;
                    
                    // Update stats cards
                    assignedCountEl.textContent = data.assigned_count || 0;
                    attendanceCountEl.textContent = data.attendance_today || 0;
                    evalStatusEl.textContent = data.evaluation_count || 0;
                } else {
                    console.error('Failed to load dashboard stats:', result.message);
                    assignedCountEl.textContent = '—';
                    attendanceCountEl.textContent = '—';
                    evalStatusEl.textContent = '—';
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                assignedCountEl.textContent = '—';
                attendanceCountEl.textContent = '—';
                evalStatusEl.textContent = '—';
            }
        }

        // Load apprentices for approval
        async function loadApprentices() {
            try {
                const response = await fetch('/api/office-sas', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return;
                }
                
                const result = await response.json();
                const apprenticeContainer = document.getElementById('apprenticeApprovals');
                
                if (result.success && result.data) {
                    const apprentices = result.data.filter(sa => sa.status === 'Apprentice');
                    
                    if (apprentices.length > 0) {
                        apprenticeContainer.innerHTML = apprentices.slice(0, 3).map(apprentice => {
                            return `
                                <div class="box p-4 mb-3">
                                    <div class="flex items-start">
                                        <div class="flex-1">
                                            <div class="font-medium">${apprentice.full_name || apprentice.name || 'Student Assistant'}</div>
                                            <div class="text-xs text-slate-500 mt-1">${apprentice.student_id_number || 'N/A'}</div>
                                            <div class="text-xs text-slate-400 mt-1">${apprentice.email || ''}</div>
                                        </div>
                                        <div class="ml-2">
                                            <button class="btn btn-sm btn-success" onclick="approveApprentice(${apprentice.id})" title="Approve as Full-pledged">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                Approve
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        
                        if (apprentices.length > 3) {
                            apprenticeContainer.innerHTML += `<div class="text-center text-slate-500 text-xs mt-2">+ ${apprentices.length - 3} more apprentices</div>`;
                        }
                    } else {
                        apprenticeContainer.innerHTML = '<div class="text-center text-slate-500 text-sm">No apprentices pending approval</div>';
                    }
                } else {
                    apprenticeContainer.innerHTML = '<div class="text-center text-slate-500 text-sm">No apprentices pending approval</div>';
                }
                
                reloadLucideIcons();
            } catch (error) {
                console.error('Error loading apprentices:', error);
                document.getElementById('apprenticeApprovals').innerHTML = '<div class="text-center text-slate-500 text-sm">Error loading apprentices</div>';
            }
        }

        // Approve apprentice (global scope for onclick)
        window.approveApprentice = async function(apprenticeId) {
            if (!confirm('Are you sure you want to approve this apprentice as a full-pledged student assistant?')) {
                return;
            }
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                const response = await fetch(`/api/apprentices/${apprenticeId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    alert(result.message || 'Apprentice approved successfully!');
                    await loadApprentices();
                    await loadDashboardStats(); // Refresh stats
                } else {
                    alert(result.message || 'Failed to approve apprentice. Please try again.');
                }
            } catch (error) {
                console.error('Error approving apprentice:', error);
                alert('An error occurred while approving the apprentice. Please try again.');
            }
        };

        // Load resignation requests
        async function loadResignationRequests() {
            try {
                const response = await fetch('/api/office-requests?type=Resignation&status=Pending', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return;
                }
                
                const result = await response.json();
                const resignationContainer = document.getElementById('resignationRequests');
                
                if (result.success && result.data && result.data.length > 0) {
                    const resignations = result.data.filter(req => req.request_type === 'Resignation' && req.status === 'Pending');
                    
                    if (resignations.length > 0) {
                        resignationContainer.innerHTML = resignations.slice(0, 3).map(resignation => {
                            // Extract reason from message
                            const messageLines = resignation.message ? resignation.message.split('\n') : [];
                            const reason = messageLines[0] ? messageLines[0].replace('Reason: ', '') : 'N/A';
                            
                            return `
                                <div class="box p-4 mb-3">
                                    <div class="flex items-start">
                                        <div class="flex-1">
                                            <div class="font-medium">${resignation.name || 'Student Assistant'}</div>
                                            <div class="text-xs text-slate-500 mt-1">${reason}</div>
                                            <div class="text-xs text-slate-400 mt-1">${formatDate(resignation.created_at)}</div>
                                        </div>
                                        <div class="flex gap-1 ml-2">
                                            <button class="btn btn-sm btn-success" onclick="processResignation(${resignation.id}, 'Approved')" title="Approve">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="processResignation(${resignation.id}, 'Rejected')" title="Reject">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        
                        if (resignations.length > 3) {
                            resignationContainer.innerHTML += `<div class="text-center text-slate-500 text-xs mt-2">+ ${resignations.length - 3} more pending</div>`;
                        }
                    } else {
                        resignationContainer.innerHTML = '<div class="text-center text-slate-500 text-sm">No pending resignation requests</div>';
                    }
                } else {
                    resignationContainer.innerHTML = '<div class="text-center text-slate-500 text-sm">No pending resignation requests</div>';
                }
                
                reloadLucideIcons();
            } catch (error) {
                console.error('Error loading resignation requests:', error);
                document.getElementById('resignationRequests').innerHTML = '<div class="text-center text-slate-500 text-sm">Error loading resignation requests</div>';
            }
        }

        // Format date helper
        function formatDate(dateString) {
            if (!dateString) return '—';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        // Process resignation request (global scope for onclick)
        window.processResignation = async function(requestId, action) {
            if (!confirm(`Are you sure you want to ${action.toLowerCase()} this resignation request?`)) {
                return;
            }
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                const response = await fetch(`/api/requests/${requestId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: action,
                        remarks: action === 'Approved' ? 'Resignation approved' : 'Resignation rejected'
                    })
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    alert(`Resignation request ${action.toLowerCase()} successfully!`);
                    await loadResignationRequests();
                    await loadDashboardStats(); // Refresh stats
                } else {
                    alert(result.message || `Failed to ${action.toLowerCase()} resignation request. Please try again.`);
                }
            } catch (error) {
                console.error('Error processing resignation:', error);
                alert('An error occurred while processing the resignation request. Please try again.');
            }
        };

        // Load DTR summary table
        async function loadDTRSummary() {
            try {
                const response = await fetch('/api/office-attendances?date=' + new Date().toISOString().split('T')[0], {
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
                    dtrTableBody.innerHTML = result.data.map(record => {
                        const timeIn = record.time_in ? record.time_in : '—';
                        const timeOut = record.time_out ? record.time_out : '—';
                        const statusBadge = getStatusBadge(record.status || 'Present');
                        
                        return `
                            <tr>
                                <td>${record.name || record.student_name || '—'}</td>
                                <td>${record.status || 'Present'}</td>
                                <td>${timeIn} / ${timeOut}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(${record.user_id || record.id})">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    dtrTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">No attendance records for today</td></tr>';
                }
            } catch (error) {
                console.error('Error loading DTR summary:', error);
                dtrTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error loading attendance data</td></tr>';
            }
        }

        // Get status badge HTML
        function getStatusBadge(status) {
            const badges = {
                'Present': '<span class="badge bg-success text-white rounded p-1">Present</span>',
                'Late': '<span class="badge bg-warning text-white rounded p-1">Late</span>',
                'Absent': '<span class="badge bg-danger text-white rounded p-1">Absent</span>',
                'Completed': '<span class="badge bg-primary text-white rounded p-1">Completed</span>',
                'Incomplete': '<span class="badge bg-secondary text-white rounded p-1">Incomplete</span>'
            };
            return badges[status] || '<span class="badge bg-secondary text-white rounded p-1">' + status + '</span>';
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

        // Chart variables
        let saDepartmentChart = null;
        let attendanceMonthChart = null;
        let attendanceStatusChart = null;

        // Load analytics data and initialize charts
        async function loadAnalytics() {
            try {
                const response = await fetch('/api/analytics', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success && result.data) {
                    const data = result.data;
                    
                    // Initialize Donut Chart: Student Assistants by Department
                    initSADepartmentChart(data.sa_by_office || []);
                    
                    // Initialize Bar Chart: Attendance by Month
                    initAttendanceMonthChart(data.attendance_by_month || []);
                    
                    // Initialize Pie Chart: Attendance Status
                    initAttendanceStatusChart(data.status_breakdown || {});
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
            }
        }

        // Initialize Student Assistants by Department Donut Chart
        function initSADepartmentChart(data) {
            const ctx = document.getElementById('sa-by-department-chart');
            if (!ctx) return;

            const labels = data.map(item => item.office || 'N/A');
            const values = data.map(item => item.count || 0);
            
            if (saDepartmentChart) {
                saDepartmentChart.destroy();
            }

            saDepartmentChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899',
                        ],
                        hoverBackgroundColor: [
                            '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed', '#db2777',
                        ],
                        borderWidth: 5,
                        borderColor: document.documentElement.classList.contains('dark') 
                            ? '#1e293b' 
                            : '#ffffff',
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                            },
                        },
                    },
                    cutout: '80%',
                },
            });
        }

        // Initialize Attendance by Month Bar Chart
        function initAttendanceMonthChart(data) {
            const ctx = document.getElementById('attendance-by-month-chart');
            if (!ctx) return;

            const labels = data.map(item => item.month || 'N/A');
            const values = data.map(item => item.count || 0);
            
            if (attendanceMonthChart) {
                attendanceMonthChart.destroy();
            }

            attendanceMonthChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Attendance Count',
                        data: values,
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },
                    },
                },
            });
        }

        // Initialize Attendance Status Pie Chart
        function initAttendanceStatusChart(data) {
            const ctx = document.getElementById('attendance-status-chart');
            if (!ctx) return;

            const labels = ['Present', 'Late', 'Absent', 'Completed', 'Incomplete'];
            const values = [
                data.Present || 0,
                data.Late || 0,
                data.Absent || 0,
                data.Completed || 0,
                data.Incomplete || 0,
            ];
            
            if (attendanceStatusChart) {
                attendanceStatusChart.destroy();
            }

            attendanceStatusChart = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#6b7280',
                        ],
                        hoverBackgroundColor: [
                            '#059669', '#d97706', '#dc2626', '#2563eb', '#4b5563',
                        ],
                        borderWidth: 5,
                        borderColor: document.documentElement.classList.contains('dark') 
                            ? '#1e293b' 
                            : '#ffffff',
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                            },
                        },
                    },
                },
            });
        }

        // Initialize
        async function init() {
            await Promise.all([loadDashboardStats(), loadDTRSummary(), loadApprentices(), loadResignationRequests(), loadAnalytics()]);
            reloadLucideIcons();
            
            // Refresh stats every 30 seconds
            setInterval(async () => {
                await loadDashboardStats();
                await loadDTRSummary();
                await loadApprentices();
                await loadResignationRequests();
                await loadAnalytics();
            }, 30000);
        }

        // Initialize on page load
        init();
    });

        function approveOvertime() {
            // Implement approve overtime functionality
            console.log('Approving overtime...');
        alert('Overtime approval functionality coming soon!');
        }

        function markAbsence() {
            // Implement mark absence functionality
            console.log('Marking absence...');
        alert('Mark absence functionality coming soon!');
    }

    function viewDetails(userId) {
        // Implement view details functionality
        console.log('Viewing details for user:', userId);
        // Could redirect to a detail page or open a modal
        }
    </script>
@endsection
