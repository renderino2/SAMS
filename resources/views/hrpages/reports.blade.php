@extends('../layout/' . $layout)

@section('subhead')
    <title>Reports & Performance - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="folder" class="w-5 h-5 mr-2"></i>
                            Reports & Performance
                        </h1>
                        <p class="text-slate-500 mt-2">Generate comprehensive reports and analyze student assistant performance metrics.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Total Student Assistants:</span>
                            <span class="ml-2" id="totalStudents">0</span>
                        </div>
                    </div>
                </div>

                <!-- Report Cards -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Report Generation
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- DTR Summary Report -->
                            <div class="intro-y box p-5">
                                <h3 class="text-md font-medium mb-3 flex items-center">
                                    <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                                    DTR Summary
                                </h3>
                                <div class="mb-3">
                                    <label for="dtrPeriod" class="form-label">Period:</label>
                                    <select id="dtrPeriod" class="form-control">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-full" onclick="downloadReport('dtr')">
                                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                    Download PDF
                                </button>
                            </div>

                            <!-- Evaluation Reports -->
                            <div class="intro-y box p-5">
                                <h3 class="text-md font-medium mb-3 flex items-center">
                                    <i data-lucide="clipboard-check" class="w-4 h-4 mr-2"></i>
                                    Evaluation Reports
                                </h3>
                                <div class="mb-3">
                                    <label for="evalType" class="form-label">Report Type:</label>
                                    <select id="evalType" class="form-control">
                                        <option value="all">All</option>
                                        <option value="by-office">By Office</option>
                                        <option value="by-student">By Student</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-full" onclick="downloadReport('evaluation')">
                                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                    Download PDF
                                </button>
                            </div>

                            <!-- Absenteeism Trends -->
                            <div class="intro-y box p-5">
                                <h3 class="text-md font-medium mb-3 flex items-center">
                                    <i data-lucide="trending-down" class="w-4 h-4 mr-2"></i>
                                    Absenteeism Trends
                                </h3>
                                <div class="mb-3">
                                    <label for="absentPeriod" class="form-label">Period:</label>
                                    <select id="absentPeriod" class="form-control">
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-full" onclick="downloadReport('absent')">
                                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                    Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                                Performance Table
                            </h2>
                            <div class="flex items-center gap-2">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search student name..." />
                                <button class="btn btn-secondary" onclick="exportTable()">
                                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                    Export
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Office</th>
                                        <th>Attendance (%)</th>
                                        <th>Late</th>
                                        <th>Absences</th>
                                        <th>Evaluation Score</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="reportTable">
                                    <tr>
                                        <td colspan="7" class="text-center text-slate-500">Loading performance data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Performance Analytics -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="pie-chart" class="w-5 h-5 mr-2"></i>
                            Performance Analytics
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center p-3 bg-slate-50 rounded">
                                <div class="text-2xl font-bold text-success" id="avgAttendance">0%</div>
                                <div class="text-sm text-slate-600">Average Attendance</div>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded">
                                <div class="text-2xl font-bold text-warning" id="totalLates">0</div>
                                <div class="text-sm text-slate-600">Total Lates</div>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded">
                                <div class="text-2xl font-bold text-danger" id="totalAbsences">0</div>
                                <div class="text-sm text-slate-600">Total Absences</div>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded">
                                <div class="text-2xl font-bold text-primary" id="avgEvaluation">0.0</div>
                                <div class="text-sm text-slate-600">Average Evaluation</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Office Performance Breakdown -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="building" class="w-5 h-5 mr-2"></i>
                            Office Performance Breakdown
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="officeBreakdown">
                            <!-- Office performance cards will be populated by JavaScript -->
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
            // Sample performance data - in real implementation, this would come from the server
            let performanceData = [
                {
                    id: 1,
                    name: "John Doe",
                    office: "Registrar",
                    attendance: 95,
                    lates: 2,
                    absences: 1,
                    evaluationScore: 4.2,
                    status: "Active"
                },
                {
                    id: 2,
                    name: "Jane Smith",
                    office: "Library",
                    attendance: 98,
                    lates: 0,
                    absences: 0,
                    evaluationScore: 4.8,
                    status: "Active"
                },
                {
                    id: 3,
                    name: "Mike Johnson",
                    office: "Guidance",
                    attendance: 87,
                    lates: 5,
                    absences: 3,
                    evaluationScore: 3.1,
                    status: "Active"
                },
                {
                    id: 4,
                    name: "Sarah Wilson",
                    office: "Clinic",
                    attendance: 92,
                    lates: 3,
                    absences: 2,
                    evaluationScore: 4.5,
                    status: "Active"
                },
                {
                    id: 5,
                    name: "David Brown",
                    office: "IT",
                    attendance: 96,
                    lates: 1,
                    absences: 1,
                    evaluationScore: 4.7,
                    status: "Active"
                },
                {
                    id: 6,
                    name: "Lisa Garcia",
                    office: "Registrar",
                    attendance: 89,
                    lates: 4,
                    absences: 2,
                    evaluationScore: 3.8,
                    status: "Active"
                },
                {
                    id: 7,
                    name: "Robert Lee",
                    office: "Library",
                    attendance: 94,
                    lates: 2,
                    absences: 1,
                    evaluationScore: 4.3,
                    status: "Active"
                },
                {
                    id: 8,
                    name: "Maria Santos",
                    office: "Guidance",
                    attendance: 91,
                    lates: 3,
                    absences: 2,
                    evaluationScore: 4.0,
                    status: "Active"
                },
                {
                    id: 9,
                    name: "Alex Chen",
                    office: "Clinic",
                    attendance: 97,
                    lates: 1,
                    absences: 0,
                    evaluationScore: 4.6,
                    status: "Active"
                },
                {
                    id: 10,
                    name: "Emma Davis",
                    office: "IT",
                    attendance: 93,
                    lates: 2,
                    absences: 1,
                    evaluationScore: 4.1,
                    status: "Active"
                }
            ];

            let filteredData = [...performanceData];

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const reportTable = document.getElementById('reportTable');
            const totalStudents = document.getElementById('totalStudents');
            const avgAttendance = document.getElementById('avgAttendance');
            const totalLates = document.getElementById('totalLates');
            const totalAbsences = document.getElementById('totalAbsences');
            const avgEvaluation = document.getElementById('avgEvaluation');
            const officeBreakdown = document.getElementById('officeBreakdown');

            // Initialize page
            function init() {
                updateTotalCount();
                renderTable();
                updateAnalytics();
                renderOfficeBreakdown();
                setupEventListeners();
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
            }

            // Filter data based on search
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();

                filteredData = performanceData.filter(student => {
                    return !searchTerm || student.name.toLowerCase().includes(searchTerm);
                });

                renderTable();
            }

            // Render the performance table
            function renderTable() {
                if (filteredData.length === 0) {
                    reportTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No performance records found</td></tr>';
                    return;
                }

                reportTable.innerHTML = filteredData.map((student, index) => `
                    <tr>
                        <td>${student.name}</td>
                        <td>${student.office}</td>
                        <td>
                            <span class="badge ${getAttendanceBadgeClass(student.attendance)} text-white rounded p-1">
                                ${student.attendance}%
                            </span>
                        </td>
                        <td>
                            <span class="badge ${getLateBadgeClass(student.lates)} text-white rounded p-1">
                                ${student.lates}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${getAbsenceBadgeClass(student.absences)} text-white rounded p-1">
                                ${student.absences}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${getEvaluationBadgeClass(student.evaluationScore)} text-white rounded p-1">
                                ${student.evaluationScore.toFixed(1)}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${getStatusBadgeClass(student.status)} text-white rounded p-1">
                                ${student.status}
                            </span>
                        </td>
                    </tr>
                `).join('');
            }

            // Get attendance badge class
            function getAttendanceBadgeClass(attendance) {
                if (attendance >= 95) return 'bg-success text-white';
                if (attendance >= 90) return 'bg-warning text-white';
                if (attendance >= 85) return 'bg-pending text-white';
                return 'bg-danger text-white';
            }

            // Get late badge class
            function getLateBadgeClass(lates) {
                if (lates === 0) return 'bg-success text-white';
                if (lates <= 2) return 'bg-warning text-white';
                return 'bg-danger text-white';
            }

            // Get absence badge class
            function getAbsenceBadgeClass(absences) {
                if (absences === 0) return 'bg-success text-white';
                if (absences <= 2) return 'bg-warning text-white';
                return 'bg-danger text-white';
            }

            // Get evaluation badge class
            function getEvaluationBadgeClass(score) {
                if (score >= 4.5) return 'bg-success text-white';
                if (score >= 3.5) return 'bg-warning text-white';
                if (score >= 2.5) return 'bg-pending text-white';
                return 'bg-danger text-white';
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Active': return 'bg-success text-white';
                    case 'Inactive': return 'bg-secondary text-white';
                    case 'Suspended': return 'bg-danger text-white';
                    default: return 'bg-secondary text-white';
                }
            }

            // Update total count
            function updateTotalCount() {
                totalStudents.textContent = performanceData.length;
            }

            // Update analytics
            function updateAnalytics() {
                const totalStudents = performanceData.length;
                const totalAttendance = performanceData.reduce((sum, student) => sum + student.attendance, 0);
                const totalLatesCount = performanceData.reduce((sum, student) => sum + student.lates, 0);
                const totalAbsencesCount = performanceData.reduce((sum, student) => sum + student.absences, 0);
                const totalEvaluation = performanceData.reduce((sum, student) => sum + student.evaluationScore, 0);

                avgAttendance.textContent = totalStudents > 0 ? Math.round(totalAttendance / totalStudents) + '%' : '0%';
                totalLates.textContent = totalLatesCount;
                totalAbsences.textContent = totalAbsencesCount;
                avgEvaluation.textContent = totalStudents > 0 ? (totalEvaluation / totalStudents).toFixed(1) : '0.0';
            }

            // Render office breakdown
            function renderOfficeBreakdown() {
                const officeStats = {};
                
                performanceData.forEach(student => {
                    if (!officeStats[student.office]) {
                        officeStats[student.office] = {
                            total: 0,
                            attendance: 0,
                            lates: 0,
                            absences: 0,
                            evaluation: 0
                        };
                    }
                    
                    officeStats[student.office].total++;
                    officeStats[student.office].attendance += student.attendance;
                    officeStats[student.office].lates += student.lates;
                    officeStats[student.office].absences += student.absences;
                    officeStats[student.office].evaluation += student.evaluationScore;
                });

                officeBreakdown.innerHTML = Object.entries(officeStats).map(([office, stats]) => `
                    <div class="intro-y box p-3">
                        <h3 class="text-md font-medium mb-2">${office}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Students:</span>
                                <span class="text-sm font-medium">${stats.total}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Avg Attendance:</span>
                                <span class="text-sm font-medium">${Math.round(stats.attendance / stats.total)}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Total Lates:</span>
                                <span class="text-sm font-medium">${stats.lates}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Total Absences:</span>
                                <span class="text-sm font-medium">${stats.absences}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Avg Evaluation:</span>
                                <span class="text-sm font-medium">${(stats.evaluation / stats.total).toFixed(1)}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // Download report function (global scope for onclick)
            window.downloadReport = async function(reportType) {
                let period = '';
                let type = '';
                
                switch (reportType) {
                    case 'dtr':
                        period = document.getElementById('dtrPeriod').value;
                        break;
                    case 'evaluation':
                        type = document.getElementById('evalType').value;
                        break;
                    case 'absent':
                        period = document.getElementById('absentPeriod').value;
                        break;
                }
                
                try {
                    const response = await fetch('/reports/download', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            reportType: reportType,
                            period: period,
                            type: type
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        showMessage(result.message || 'Report generated successfully!', 'success');
                        // In real implementation, this would trigger a file download
                        // window.open(result.downloadUrl, '_blank');
                    } else {
                        showMessage(result.message || 'Failed to generate report', 'error');
                    }
                } catch (error) {
                    console.error('Error generating report:', error);
                    showMessage('An error occurred while generating report', 'error');
                }
            };

            // Export table function (global scope for onclick)
            window.exportTable = function() {
                // In real implementation, this would export the current table data
                showMessage('Table export functionality will be implemented in the next version', 'info');
            };

            // Show message function
            function showMessage(message, type) {
                // Create a temporary message element
                const messageDiv = document.createElement('div');
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} flex items-center fixed top-4 right-4 z-50`;
                messageDiv.innerHTML = `
                    <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-4 h-4 mr-2"></i>
                    ${message}
                `;
                
                document.body.appendChild(messageDiv);
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.remove();
                }, 5000);
            }

            // Initialize when DOM is loaded
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection
