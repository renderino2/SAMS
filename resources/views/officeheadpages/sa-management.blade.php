@extends('../layout/' . $layout)

@section('subhead')
    <title>SA Management & Performance - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                            Student Assistant Management & Performance
                        </h1>
                        <p class="text-slate-500 mt-2">Manage and monitor student assistants under your office.</p>
                        <div class="mt-3">
                            <span class="text-sm text-slate-600">Office: <strong id="officeName">Loading...</strong></span>
                        </div>
                    </div>
                </div>

                <!-- SA List -->
                <div class="col-span-12 lg:col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="user-check" class="w-5 h-5 mr-2"></i>
                            Student Assistants
                        </h2>
                        <div class="mb-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or student ID..." />
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="saTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Performance Panel -->
                <div class="col-span-12 lg:col-span-12 intro-y" id="performancePanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="bar-chart-2" class="w-5 h-5 mr-2"></i>
                            Performance Metrics
                        </h2>
                        
                        <!-- SA Info -->
                        <div class="mb-4 p-3 bg-slate-50 dark:bg-slate-800 rounded">
                            <h3 class="font-medium" id="saName">--</h3>
                            <p class="text-sm text-slate-500" id="saStudentId">--</p>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="mb-4">
                            <label class="form-label">Date Range:</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" id="startDate" class="form-control" />
                                <input type="date" id="endDate" class="form-control" />
                            </div>
                            <button id="applyDateRange" class="btn btn-primary w-full mt-2">
                                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Apply Filter
                            </button>
                        </div>

                        <!-- Metrics Cards -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="p-3 bg-primary/10 rounded">
                                <div class="text-xs text-slate-500">Total Time</div>
                                <div class="text-lg font-medium" id="metricTotalTime">--</div>
                            </div>
                            <div class="p-3 bg-warning/10 rounded">
                                <div class="text-xs text-slate-500">Lates</div>
                                <div class="text-lg font-medium" id="metricLates">0</div>
                            </div>
                            <div class="p-3 bg-danger/10 rounded">
                                <div class="text-xs text-slate-500">Absents</div>
                                <div class="text-lg font-medium" id="metricAbsents">0</div>
                            </div>
                            <div class="p-3 bg-success/10 rounded">
                                <div class="text-xs text-slate-500">Overtime</div>
                                <div class="text-lg font-medium" id="metricOvertime">0</div>
                            </div>
                        </div>

                        <!-- Additional Metrics -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600">Work Days:</span>
                                <span class="text-sm font-medium" id="metricWorkDays">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Expected Days:</span>
                                <span class="text-sm font-medium" id="metricExpectedDays">0</span>
                            </div>
                        </div>

                        <!-- Attendance Details -->
                        <div class="mt-4">
                            <h3 class="font-medium mb-2">Recent Attendance</h3>
                            <div class="overflow-y-auto max-h-64">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceDetailsBody">
                                        <tr>
                                            <td colspan="3" class="text-center text-slate-500 text-xs">No records</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            let saList = [];
            let filteredSAList = [];
            let currentSAId = null;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const saTableBody = document.getElementById('saTableBody');
            const performancePanel = document.getElementById('performancePanel');
            const officeName = document.getElementById('officeName');
            const saName = document.getElementById('saName');
            const saStudentId = document.getElementById('saStudentId');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const applyDateRange = document.getElementById('applyDateRange');
            const attendanceDetailsBody = document.getElementById('attendanceDetailsBody');

            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            startDate.value = thirtyDaysAgo.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];

            // Initialize
            async function init() {
                await loadSAs();
                setupEventListeners();
                reloadLucideIcons();
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterSAs);
                applyDateRange.addEventListener('click', () => {
                    if (currentSAId) {
                        loadSAPerformance(currentSAId);
                    }
                });
            }

            // Load Student Assistants
            async function loadSAs() {
                try {
                    const response = await fetch('/api/office-sas', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        saTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-slate-500">Error loading data</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        saList = result.data;
                        filteredSAList = [...saList];
                        officeName.textContent = result.office || 'N/A';
                        renderSATable();
                    } else {
                        saTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-slate-500">' + (result.message || 'No student assistants found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading SAs:', error);
                    saTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-slate-500">Error loading data</td></tr>';
                }
            }

            // Filter SAs
            function filterSAs() {
                const searchTerm = searchInput.value.toLowerCase();
                filteredSAList = saList.filter(sa => {
                    const name = (sa.full_name || sa.name || '').toLowerCase();
                    const studentId = (sa.student_id_number || '').toLowerCase();
                    return name.includes(searchTerm) || studentId.includes(searchTerm);
                });
                renderSATable();
            }

            // Render SA Table
            function renderSATable() {
                if (filteredSAList.length === 0) {
                    saTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-slate-500">No student assistants found</td></tr>';
                    return;
                }

                saTableBody.innerHTML = filteredSAList.map((sa, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${sa.full_name || sa.name}</td>
                        <td>${sa.student_id_number || 'N/A'}</td>
                        <td>${sa.email || 'N/A'}</td>
                        <td>
                            <span class="badge ${sa.active ? 'bg-success text-white' : 'bg-danger text-white'} rounded p-1">
                                ${sa.active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="viewPerformance(${sa.id})">
                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View Performance
                            </button>
                        </td>
                    </tr>
                `).join('');
                
                reloadLucideIcons();
            }

            // View Performance
            window.viewPerformance = async function(saId) {
                currentSAId = saId;
                performancePanel.style.display = 'block';
                attendanceDetailsBody.innerHTML = '<tr><td colspan="3" class="text-center text-slate-500 text-xs">Loading performance data...</td></tr>';
                performancePanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                await loadSAPerformance(saId);
            };

            // Load SA Performance
            async function loadSAPerformance(saId) {
                try {
                    const start = startDate.value;
                    const end = endDate.value;
                    const url = `/api/sa-performance/${saId}?start_date=${start}&end_date=${end}`;
                    
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        showMessage('Error loading performance data', 'error');
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        const data = result.data;
                        const sa = data.sa;
                        const metrics = data.metrics;
                        const attendanceDetails = data.attendance_details;

                        // Update SA Info
                        saName.textContent = sa.name;
                        saStudentId.textContent = sa.student_id_number;

                        // Update Metrics
                        document.getElementById('metricTotalTime').textContent = metrics.total_time_formatted || '--';
                        document.getElementById('metricLates').textContent = metrics.lates || 0;
                        document.getElementById('metricAbsents').textContent = metrics.absents || 0;
                        document.getElementById('metricOvertime').textContent = metrics.overtime || 0;
                        document.getElementById('metricWorkDays').textContent = metrics.total_days || 0;
                        document.getElementById('metricExpectedDays').textContent = metrics.expected_work_days || 0;

                        // Update Attendance Details
                        if (attendanceDetails && attendanceDetails.length > 0) {
                            attendanceDetailsBody.innerHTML = attendanceDetails.slice(0, 10).map(record => `
                                <tr>
                                    <td class="text-xs">${record.formatted_date}</td>
                                    <td class="text-xs">${record.time_in} - ${record.time_out}</td>
                                    <td>
                                        <span class="badge ${getStatusBadgeClass(record.status)} rounded p-1 text-xs">
                                            ${record.status}
                                        </span>
                                    </td>
                                </tr>
                            `).join('');
                        } else {
                            attendanceDetailsBody.innerHTML = '<tr><td colspan="3" class="text-center text-slate-500 text-xs">No attendance records</td></tr>';
                        }

                        // Show performance panel
                        performancePanel.style.display = 'block';
                        reloadLucideIcons();
                    } else {
                        performancePanel.style.display = 'block';
                        attendanceDetailsBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger text-xs">Failed to load performance records.</td></tr>';
                        showMessage(result.message || 'Failed to load performance data', 'error');
                    }
                } catch (error) {
                    console.error('Error loading SA performance:', error);
                    performancePanel.style.display = 'block';
                    attendanceDetailsBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger text-xs">Error loading performance records.</td></tr>';
                    showMessage('Error loading performance data: ' + error.message, 'error');
                }
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Present':
                    case 'Completed':
                        return 'bg-success text-white';
                    case 'Late':
                        return 'bg-warning text-white';
                    case 'Absent':
                    case 'Incomplete':
                        return 'bg-danger text-white';
                    case 'Overtime':
                        return 'bg-primary text-white';
                    default:
                        return 'bg-secondary text-white';
                }
            }

            // Show message
            function showMessage(message, type) {
                const messageDiv = document.createElement('div');
                const alertType = type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info');
                messageDiv.className = `alert alert-${alertType} flex items-center fixed top-4 right-4 z-50 shadow-lg`;
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

            // Initialize
            init();
        });
</script>
@endsection

