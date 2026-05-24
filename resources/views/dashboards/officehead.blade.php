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
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="users" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="totalSACount">—</div>
                            <div class="text-base text-slate-500 mt-1">Office Assistants</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="file-check" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="activeContractsCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Active Contracts</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="clipboard-check" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="pendingEvaluationsCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Pending Evaluations</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="alert-circle" class="report-box__icon text-danger"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="expiringContractsCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Expiring Contracts</div>
                        </div>
                    </div>
                </div>
                <!-- END: Stats Cards -->

                <!-- BEGIN: Analytics Charts -->
                <div class="col-span-12 grid grid-cols-12 gap-6 mt-8">
                    <!-- Donut Chart: Student Assistants Added by Month -->
                    <div class="col-span-12 lg:col-span-6 intro-y">
                        <div class="box p-5">
                            <div class="flex items-center h-10 mb-5">
                                <h2 class="text-lg font-medium truncate mr-5">Student Assistants Added by Month</h2>
                            </div>
                            <div class="h-[400px]">
                                <canvas id="sa-by-month-chart"></canvas>
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

            </div>
        </div>

        <!-- BEGIN: Side Panels -->
        <div class="col-span-12 2xl:col-span-3">
            <div class="2xl:border-l -mb-10 pb-10">
                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                    <!-- BEGIN: Total Pending Requests -->
                    <div class="col-span-12 mt-3 2xl:mt-8">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Total Pending Requests</h2>
                        </div>
                        <div class="mt-5 box p-5">
                            <a href="{{ route('requests.review') }}" class="block">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-3xl font-medium leading-8" id="pendingRequestsCount">0</div>
                                        <div class="text-base text-slate-500 mt-1">Requests Awaiting Review</div>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="file-text" class="w-12 h-12 text-warning"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <span class="text-sm text-slate-600 hover:text-primary">View all requests →</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- END: Total Pending Requests -->

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
<!-- Lucide Icons CDN -->
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
   window.reloadLucideIcons = function () {
    if (window.lucide && lucide.icons) {
        lucide.createIcons({ icons: lucide.icons });
    }
};

    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const totalSACountEl = document.getElementById('totalSACount');
        const activeContractsCountEl = document.getElementById('activeContractsCount');
        const pendingEvaluationsCountEl = document.getElementById('pendingEvaluationsCount');
        const expiringContractsCountEl = document.getElementById('expiringContractsCount');
        // Load office dashboard statistics (make it globally accessible)
        window.loadDashboardStats = async function() {
            // Get DOM elements dynamically (accessible from global scope)
            const totalSACountEl = document.getElementById('totalSACount');
            const activeContractsCountEl = document.getElementById('activeContractsCount');
            const pendingEvaluationsCountEl = document.getElementById('pendingEvaluationsCount');
            const expiringContractsCountEl = document.getElementById('expiringContractsCount');
            
            if (!totalSACountEl || !activeContractsCountEl || !pendingEvaluationsCountEl || !expiringContractsCountEl) {
                console.error('Dashboard stats elements not found');
                return;
            }
            
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
                    totalSACountEl.textContent = data.total_student_assistants || 0;
                    activeContractsCountEl.textContent = data.active_contracts || 0;
                    pendingEvaluationsCountEl.textContent = data.pending_evaluations || 0;
                    expiringContractsCountEl.textContent = data.expiring_contracts || 0;
                } else {
                    console.error('Failed to load dashboard stats:', result.message);
                    totalSACountEl.textContent = '—';
                    activeContractsCountEl.textContent = '—';
                    pendingEvaluationsCountEl.textContent = '—';
                    expiringContractsCountEl.textContent = '—';
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                if (totalSACountEl) totalSACountEl.textContent = '—';
                if (activeContractsCountEl) activeContractsCountEl.textContent = '—';
                if (pendingEvaluationsCountEl) pendingEvaluationsCountEl.textContent = '—';
                if (expiringContractsCountEl) expiringContractsCountEl.textContent = '—';
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

        // Chart variables (make them globally accessible)
        window.saByMonthChart = null;
        window.attendanceMonthChart = null;
        window.attendanceStatusChart = null;

        // Load analytics data and initialize charts (make it globally accessible)
        window.loadAnalytics = async function() {
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
                    
                    // Initialize Donut Chart: Student Assistants Added by Month
                    initSAByMonthChart(data.sa_by_month || []);
                    
                    // Initialize Bar Chart: Attendance by Month
                    initAttendanceMonthChart(data.attendance_by_month || []);
                    
                    // Initialize Pie Chart: Attendance Status
                    initAttendanceStatusChart(data.status_breakdown || {});
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
            }
        }

        // Initialize Student Assistants Added by Month Donut Chart
        function initSAByMonthChart(data) {
            const ctx = document.getElementById('sa-by-month-chart');
            if (!ctx) return;

            const labels = data.map(item => item.month || 'N/A');
            const values = data.map(item => item.count || 0);
            
            if (window.saByMonthChart) {
                window.saByMonthChart.destroy();
            }

            window.saByMonthChart = new Chart(ctx.getContext('2d'), {
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
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return `${label}: ${value} student assistant${value !== 1 ? 's' : ''}`;
                                }
                            }
                        }
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
            
            if (window.attendanceMonthChart) {
                window.attendanceMonthChart.destroy();
            }

            window.attendanceMonthChart = new Chart(ctx.getContext('2d'), {
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
            
            if (window.attendanceStatusChart) {
                window.attendanceStatusChart.destroy();
            }

            window.attendanceStatusChart = new Chart(ctx.getContext('2d'), {
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

        // Load pending requests count
        async function loadPendingRequestsCount() {
            try {
                const response = await fetch('/api/office-requests', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    const pendingCountEl = document.getElementById('pendingRequestsCount');
                    if (pendingCountEl) pendingCountEl.textContent = '0';
                    return;
                }
                
                const result = await response.json();
                
                if (result.success && result.data && Array.isArray(result.data)) {
                    const pendingCount = result.data.filter(request => request.status === 'Pending').length;
                    const pendingCountEl = document.getElementById('pendingRequestsCount');
                    if (pendingCountEl) pendingCountEl.textContent = pendingCount;
                } else {
                    const pendingCountEl = document.getElementById('pendingRequestsCount');
                    if (pendingCountEl) pendingCountEl.textContent = '0';
                }
            } catch (error) {
                console.error('Error loading pending requests count:', error);
                const pendingCountEl = document.getElementById('pendingRequestsCount');
                if (pendingCountEl) pendingCountEl.textContent = '0';
            }
        }

        // Create local references for use within the DOMContentLoaded scope (after all functions are defined)
        const loadDashboardStats = window.loadDashboardStats;
        const loadAnalytics = window.loadAnalytics;

        // Listen for evaluation updates from other tabs/pages
        function setupEvaluationUpdateListener() {
            if (typeof BroadcastChannel !== 'undefined') {
                const channel = new BroadcastChannel('dashboard-updates');
                channel.addEventListener('message', (event) => {
                    if (event.data.type === 'evaluation-submitted') {
                        // Refresh dashboard stats when evaluation is submitted
                        console.log('Evaluation submitted detected, refreshing dashboard stats...');
                        loadDashboardStats();
                    }
                });
            }
            
            // Also refresh when window regains focus (fallback)
            window.addEventListener('focus', () => {
                console.log('Window focused, refreshing dashboard stats...');
                loadDashboardStats();
            });
            
            // Refresh when page becomes visible (user switches back to tab)
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    console.log('Page visible, refreshing dashboard stats...');
                    loadDashboardStats();
                }
            });
        }

        // Initialize
        async function init() {
            await Promise.all([loadDashboardStats(), loadApprentices(), loadResignationRequests(), loadAnalytics(), loadPendingRequestsCount()]);
            setupEvaluationUpdateListener();
            reloadLucideIcons();
            
            // Refresh stats every 30 seconds
            setInterval(async () => {
                await loadDashboardStats();
                await loadApprentices();
                await loadResignationRequests();
                await loadAnalytics();
                await loadPendingRequestsCount();
            }, 30000);
        }

        // Initialize on page load
        init();
    });

    </script>
@endsection
