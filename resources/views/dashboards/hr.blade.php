@extends('../layout/' . $layout)

@section('subhead')
    <title>HR Dashboard - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: HR Overview Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium">HR Dashboard Overview</h1>
                        <p class="text-slate-500 mt-2">Monitor contract activity, student assistant attendance, and HR actions.</p>
                    </div>
                </div>
                <!-- END: HR Overview Header -->

                <!-- BEGIN: KPI Cards -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="users" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="totalSAs">0</div>
                            <div class="text-base text-slate-500 mt-1">Student Assistants</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="file-check-2" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="activeContracts">0</div>
                            <div class="text-base text-slate-500 mt-1">Active Contracts</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="clipboard-list" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="pendingEvals">0</div>
                            <div class="text-base text-slate-500 mt-1">Pending Evaluations</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="alarm-clock" class="report-box__icon text-danger"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="expiringContracts">0</div>
                            <div class="text-base text-slate-500 mt-1">Expiring Contracts</div>
                        </div>
                    </div>
                </div>
                <!-- END: KPI Cards -->

                <!-- BEGIN: Analytics Charts -->
                <div class="col-span-12 grid grid-cols-12 gap-6 mt-8">
                    <!-- Donut Chart: Student Assistants by Department -->
                    <div class="col-span-12 lg:col-span-4 intro-y">
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
                    <div class="col-span-12 lg:col-span-8 intro-y">
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
                    <div class="col-span-12 lg:col-span-4 intro-y">
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

                <!-- BEGIN: Attendance Summary -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5"> Attendance Summary</h2>
                        </div>
                        <div class="overflow-x-auto mt-5">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">OFFICE</th>
                                        <th class="whitespace-nowrap">TOTAL</th>
                                        <th class="whitespace-nowrap">PRESENT</th>
                                        <th class="whitespace-nowrap">LATE</th>
                                        <th class="whitespace-nowrap">ABSENT</th>
                                        <th class="whitespace-nowrap">OVERTIME</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-slate-500">Loading attendance data...</td>
                                    </tr>
                                </tbody>
                            </table>
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
                    <div class="col-span-12 mt-3 2xl:mt-8">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Recent Activity</h2>
                        </div>
                        <div class="mt-5 box p-5">
                            <ul id="notificationsList" class="list-disc pl-5 text-slate-600">
                                <li>Loading notifications...</li>
                            </ul>
                        </div>
                    </div>
                    <!-- END: Recent Activity -->

                    <!-- BEGIN: Quick Actions -->
                    <div class="col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Quick Actions</h2>
                        </div>
                        <div class="mt-5 intro-y grid grid-cols-1 gap-3">
                            <a href="{{ route('student.assistants') }}" class="btn box text-left">Manage Student Assistants</a>
                            <a href="{{ route('contract.management') }}" class="btn box text-left">Manage Contracts</a>
                            <a href="{{ route('reports') }}" class="btn box text-left">View Reports</a>
                            <a href="{{ route('evaluation.review') }}" class="btn box text-left">Review Evaluations</a>
                        </div>
                    </div>
                    <!-- END: Quick Actions -->
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
            
            // Destroy existing chart if it exists
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

        // Load analytics on page load
        loadAnalytics();
    });
</script>
@endsection

