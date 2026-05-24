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
                                <div class="flex gap-2">
                                    <button class="btn btn-outline-danger flex-1 whitespace-nowrap" onclick="downloadReport('dtr', 'csv')">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                        Export CSV
                                    </button>
                                    <button class="btn btn-outline-primary flex-1 whitespace-nowrap" onclick="downloadReport('dtr', 'pdf')">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                        Export PDF
                                    </button>
                                </div>
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
                                <div class="flex gap-2">
                                    <button class="btn btn-outline-danger flex-1 whitespace-nowrap" onclick="downloadReport('evaluation', 'csv')">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                        Export CSV
                                    </button>
                                    <button class="btn btn-outline-primary flex-1 whitespace-nowrap" onclick="downloadReport('evaluation', 'pdf')">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                        Export PDF
                                    </button>
                                </div>
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
                                <div class="flex gap-2">
                                    <button class="btn btn-outline-danger flex-1 whitespace-nowrap" onclick="downloadReport('absent', 'csv')">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                        Export CSV
                                    </button>
                                    <button class="btn btn-outline-primary flex-1 whitespace-nowrap" onclick="downloadReport('absent', 'pdf')">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                        Export PDF
                                    </button>
                                </div>
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
                                <button class="btn btn-outline-danger whitespace-nowrap" onclick="exportTable('csv')">
                                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                    Export CSV
                                </button>
                                <button class="btn btn-outline-primary whitespace-nowrap" onclick="exportTable('pdf')">
                                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                                    Export PDF
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
            // Performance data from database
            let performanceData = [];
            let filteredData = [];
            let officesData = [];
            let currentPage = 1;
            const itemsPerPage = 10;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const reportTable = document.getElementById('reportTable');
            const totalStudents = document.getElementById('totalStudents');
            const avgAttendance = document.getElementById('avgAttendance');
            const totalLates = document.getElementById('totalLates');
            const totalAbsences = document.getElementById('totalAbsences');
            const avgEvaluation = document.getElementById('avgEvaluation');
            const officeBreakdown = document.getElementById('officeBreakdown');

            // Load performance data from API
            async function loadPerformanceData() {
                try {
                    const response = await fetch('/api/reports/performance', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        performanceData = result.data;
                        filteredData = [...performanceData];
                        updateTotalCount();
                        renderTable();
                        updateAnalytics();
                        renderOfficeBreakdown();
                    } else {
                        reportTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Failed to load performance data</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading performance data:', error);
                    reportTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading performance data. Please refresh the page.</td></tr>';
                }
            }

            // Load offices from database
            async function loadOffices() {
                try {
                    const response = await fetch('/api/offices');
                    const result = await response.json();
                    
                    if (result.success) {
                        // Filter to only active offices
                        officesData = result.data.filter(office => office.is_active);
                    } else {
                        console.error('Failed to load offices');
                        officesData = [];
                    }
                } catch (error) {
                    console.error('Error loading offices:', error);
                    officesData = [];
                }
            }

            // Initialize page
            async function init() {
                await Promise.all([loadOffices(), loadPerformanceData()]);
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

                // Reset to first page when filtering
                currentPage = 1;
                renderTable();
            }

            // Render the performance table with pagination
            function renderTable() {
                if (filteredData.length === 0) {
                    reportTable.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No performance records found</td></tr>';
                    document.getElementById('paginationContainer').style.display = 'none';
                    return;
                }

                // Calculate pagination
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
                const paginatedData = filteredData.slice(startIndex, endIndex);

                // Render table rows
                reportTable.innerHTML = paginatedData.map((student, index) => {
                    const globalIndex = startIndex + index + 1;
                    return `
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
                    `;
                }).join('');

                // Update pagination info
                document.getElementById('paginationStart').textContent = filteredData.length > 0 ? startIndex + 1 : 0;
                document.getElementById('paginationEnd').textContent = endIndex;
                document.getElementById('paginationTotal').textContent = filteredData.length;

                // Render pagination controls
                renderPagination(totalPages);
                
                // Show pagination container
                document.getElementById('paginationContainer').style.display = 'flex';
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
                        <a class="page-link" href="#" onclick="goToPage(1); return false;">
                            <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                        </a>
                    </li>
                `;

                // Previous page button
                paginationHTML += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToPage(${currentPage - 1}); return false;">
                            <i class="w-4 h-4" data-lucide="chevron-left"></i>
                        </a>
                    </li>
                `;

                // Page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, currentPage + 2);

                if (startPage > 1) {
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(1); return false;">1</a></li>`;
                    if (startPage > 2) {
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#">...</a></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
                        </li>
                    `;
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#">...</a></li>`;
                    }
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${totalPages}); return false;">${totalPages}</a></li>`;
                }

                // Next page button
                paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToPage(${currentPage + 1}); return false;">
                            <i class="w-4 h-4" data-lucide="chevron-right"></i>
                        </a>
                    </li>
                `;

                // Last page button
                paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="goToPage(${totalPages}); return false;">
                            <i class="w-4 h-4" data-lucide="chevrons-right"></i>
                        </a>
                    </li>
                `;

                paginationEl.innerHTML = paginationHTML;
                reloadLucideIcons();
            }

            // Go to specific page
            window.goToPage = function(page) {
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                if (page < 1 || page > totalPages || page === currentPage) {
                    return;
                }
                currentPage = page;
                renderTable();
                // Scroll to top of table
                document.querySelector('.table').scrollIntoView({ behavior: 'smooth', block: 'start' });
            };

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

            // Render office breakdown based on database offices
            function renderOfficeBreakdown() {
                // Initialize stats for all offices from database
                const officeStats = {};
                
                // Initialize all offices from database with zero values
                officesData.forEach(office => {
                    officeStats[office.name] = {
                        total: 0,
                        attendance: 0,
                        lates: 0,
                        absences: 0,
                        evaluation: 0,
                        officeName: office.name
                    };
                });
                
                // Aggregate performance data by office
                performanceData.forEach(student => {
                    const officeName = student.office || 'N/A';
                    
                    // Only process if office exists in database, or create entry for unmatched offices
                    if (!officeStats[officeName]) {
                        officeStats[officeName] = {
                            total: 0,
                            attendance: 0,
                            lates: 0,
                            absences: 0,
                            evaluation: 0,
                            officeName: officeName
                        };
                    }
                    
                    officeStats[officeName].total++;
                    officeStats[officeName].attendance += student.attendance;
                    officeStats[officeName].lates += student.lates;
                    officeStats[officeName].absences += student.absences;
                    officeStats[officeName].evaluation += student.evaluationScore;
                });

                // Render breakdown for all offices from database first, then any unmatched offices
                const sortedOffices = [
                    ...officesData.map(office => office.name),
                    ...Object.keys(officeStats).filter(office => !officesData.find(o => o.name === office))
                ];

                officeBreakdown.innerHTML = sortedOffices.map(officeName => {
                    const stats = officeStats[officeName] || {
                        total: 0,
                        attendance: 0,
                        lates: 0,
                        absences: 0,
                        evaluation: 0
                    };
                    
                    const avgAttendance = stats.total > 0 ? Math.round(stats.attendance / stats.total) : 0;
                    const avgEvaluation = stats.total > 0 ? (stats.evaluation / stats.total).toFixed(1) : '0.0';
                    
                    return `
                    <div class="intro-y box p-3">
                        <h3 class="text-md font-medium mb-2">${officeName}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Students:</span>
                                <span class="text-sm font-medium">${stats.total}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Avg Attendance:</span>
                                <span class="text-sm font-medium">${avgAttendance}%</span>
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
                                <span class="text-sm font-medium">${avgEvaluation}</span>
                            </div>
                        </div>
                    </div>
                `;
                }).join('');
            }

            // Download report function (global scope for onclick)
            window.downloadReport = async function(reportType, format = 'csv') {
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
                    if (format === 'pdf') {
                        // Generate PDF client-side
                        await generateReportPDF(reportType, period, type);
                    } else {
                        // Download CSV from server
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
                        
                        if (response.ok) {
                            // Get the filename from Content-Disposition header
                            const contentDisposition = response.headers.get('Content-Disposition');
                            let filename = 'report.csv';
                            if (contentDisposition) {
                                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                                if (filenameMatch) {
                                    filename = filenameMatch[1];
                                }
                            }
                            
                            // Get the blob and create download link
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                            
                            showMessage('CSV exported successfully!', 'success');
                        } else {
                            const result = await response.json();
                            showMessage(result.message || 'Failed to generate report', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error generating report:', error);
                    showMessage('An error occurred while generating report', 'error');
                }
            };

            // Generate PDF report
            async function generateReportPDF(reportType, period, type) {
                // Load jsPDF from CDN if not already loaded
                if (typeof window.jspdf === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
                    script.onload = () => {
                        fetchReportDataAndGeneratePDF(reportType, period, type);
                    };
                    document.head.appendChild(script);
                } else {
                    await fetchReportDataAndGeneratePDF(reportType, period, type);
                }
            }

            // Fetch report data and generate PDF
            async function fetchReportDataAndGeneratePDF(reportType, period, type) {
                try {
                    // Fetch data from server
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
                    
                    if (!response.ok) {
                        throw new Error('Failed to fetch report data');
                    }
                    
                    // Get CSV content
                    const csvText = await response.text();
                    const lines = csvText.split('\n').filter(line => line.trim());
                    
                    // Parse CSV to extract data
                    const headers = lines[0] ? lines[0].split(',').map(h => h.replace(/"/g, '')) : [];
                    const dataRows = lines.slice(1).map(line => {
                        // Simple CSV parsing (handles quoted fields)
                        const values = [];
                        let current = '';
                        let inQuotes = false;
                        for (let i = 0; i < line.length; i++) {
                            const char = line[i];
                            if (char === '"') {
                                inQuotes = !inQuotes;
                            } else if (char === ',' && !inQuotes) {
                                values.push(current.trim());
                                current = '';
                            } else {
                                current += char;
                            }
                        }
                        values.push(current.trim());
                        return values;
                    });
                    
                    // Generate PDF based on report type
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF();
                    
                    let title = '';
                    let subtitle = '';
                    
                    switch (reportType) {
                        case 'dtr':
                            title = 'DTR Summary Report';
                            subtitle = `Period: ${period ? period.charAt(0).toUpperCase() + period.slice(1) : 'Daily'}`;
                            generateDTRPDF(doc, headers, dataRows, subtitle);
                            break;
                        case 'evaluation':
                            title = 'Evaluation Report';
                            subtitle = `Type: ${type ? type.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'All'}`;
                            generateEvaluationPDF(doc, headers, dataRows, subtitle);
                            break;
                        case 'absent':
                            title = 'Absenteeism Trends Report';
                            subtitle = `Period: ${period ? period.charAt(0).toUpperCase() + period.slice(1) : 'Monthly'}`;
                            generateAbsenteeismPDF(doc, headers, dataRows, subtitle);
                            break;
                    }
                    
                    // Save PDF
                    const timestamp = new Date().toISOString().split('T')[0];
                    const fileName = `${title.replace(/\s+/g, '_')}_${timestamp}.pdf`;
                    doc.save(fileName);
                    showMessage('PDF exported successfully!', 'success');
                } catch (error) {
                    console.error('Error generating PDF report:', error);
                    showMessage('Failed to generate PDF report', 'error');
                }
            }

            // Generate DTR PDF
            function generateDTRPDF(doc, headers, dataRows, subtitle) {
                doc.setFontSize(18);
                doc.text('DTR Summary Report', 14, 20);
                
                doc.setFontSize(10);
                doc.text(`Generated: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`, 14, 30);
                doc.text(subtitle, 14, 36);
                
                let yPos = 50;
                doc.setFontSize(9);
                doc.setFont(undefined, 'bold');
                
                // Table headers
                const colPositions = [14, 54, 84, 109, 134, 159, 179];
                
                headers.forEach((header, index) => {
                    if (index < colPositions.length) {
                        doc.text(header.substring(0, 15), colPositions[index], yPos);
                    }
                });
                
                yPos += 8;
                doc.setFont(undefined, 'normal');
                doc.setFontSize(7);
                
                dataRows.slice(0, 30).forEach(row => {
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                        // Re-add headers
                        doc.setFont(undefined, 'bold');
                        doc.setFontSize(9);
                        headers.forEach((header, index) => {
                            if (index < colPositions.length) {
                                doc.text(header.substring(0, 15), colPositions[index], yPos);
                            }
                        });
                        yPos += 8;
                        doc.setFont(undefined, 'normal');
                        doc.setFontSize(7);
                    }
                    
                    row.forEach((cell, index) => {
                        if (index < colPositions.length) {
                            doc.text((cell || '').substring(0, 15), colPositions[index], yPos);
                        }
                    });
                    yPos += 6;
                });
                
                if (dataRows.length > 30) {
                    yPos += 4;
                    doc.setFont(undefined, 'italic');
                    doc.text(`... and ${dataRows.length - 30} more records`, 14, yPos);
                }
            }

            // Generate Evaluation PDF
            function generateEvaluationPDF(doc, headers, dataRows, subtitle) {
                doc.setFontSize(18);
                doc.text('Evaluation Report', 14, 20);
                
                doc.setFontSize(10);
                doc.text(`Generated: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`, 14, 30);
                doc.text(subtitle, 14, 36);
                
                let yPos = 50;
                doc.setFontSize(8);
                doc.setFont(undefined, 'bold');
                
                // Table headers (adjusted for evaluation data)
                const colPositions = [14, 49, 79, 114, 139, 164, 184, 204, 229];
                
                headers.forEach((header, index) => {
                    if (index < colPositions.length) {
                        doc.text(header.substring(0, 12), colPositions[index], yPos);
                    }
                });
                
                yPos += 8;
                doc.setFont(undefined, 'normal');
                doc.setFontSize(6);
                
                dataRows.slice(0, 25).forEach(row => {
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                        // Re-add headers
                        doc.setFont(undefined, 'bold');
                        doc.setFontSize(8);
                        headers.forEach((header, index) => {
                            if (index < colPositions.length) {
                                doc.text(header.substring(0, 12), colPositions[index], yPos);
                            }
                        });
                        yPos += 8;
                        doc.setFont(undefined, 'normal');
                        doc.setFontSize(6);
                    }
                    
                    row.forEach((cell, index) => {
                        if (index < colPositions.length) {
                            doc.text((cell || '').substring(0, 12), colPositions[index], yPos);
                        }
                    });
                    yPos += 6;
                });
                
                if (dataRows.length > 25) {
                    yPos += 4;
                    doc.setFont(undefined, 'italic');
                    doc.text(`... and ${dataRows.length - 25} more records`, 14, yPos);
                }
            }

            // Generate Absenteeism PDF
            function generateAbsenteeismPDF(doc, headers, dataRows, subtitle) {
                doc.setFontSize(18);
                doc.text('Absenteeism Trends Report', 14, 20);
                
                doc.setFontSize(10);
                doc.text(`Generated: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`, 14, 30);
                doc.text(subtitle, 14, 36);
                
                let yPos = 50;
                doc.setFontSize(9);
                doc.setFont(undefined, 'bold');
                
                // Table headers
                const colPositions = [14, 50, 80, 110, 140, 170, 200];
                
                headers.forEach((header, index) => {
                    if (index < colPositions.length) {
                        doc.text(header.substring(0, 18), colPositions[index], yPos);
                    }
                });
                
                yPos += 8;
                doc.setFont(undefined, 'normal');
                doc.setFontSize(7);
                
                dataRows.slice(0, 30).forEach(row => {
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                        // Re-add headers
                        doc.setFont(undefined, 'bold');
                        doc.setFontSize(9);
                        headers.forEach((header, index) => {
                            if (index < colPositions.length) {
                                doc.text(header.substring(0, 18), colPositions[index], yPos);
                            }
                        });
                        yPos += 8;
                        doc.setFont(undefined, 'normal');
                        doc.setFontSize(7);
                    }
                    
                    row.forEach((cell, index) => {
                        if (index < colPositions.length) {
                            doc.text((cell || '').substring(0, 18), colPositions[index], yPos);
                        }
                    });
                    yPos += 6;
                });
                
                if (dataRows.length > 30) {
                    yPos += 4;
                    doc.setFont(undefined, 'italic');
                    doc.text(`... and ${dataRows.length - 30} more records`, 14, yPos);
                }
            }

            // Export table function (global scope for onclick)
            window.exportTable = async function(format) {
                if (filteredData.length === 0) {
                    showMessage('No data to export', 'warning');
                    return;
                }
                
                try {
                    if (format === 'csv') {
                        exportToCSV();
                    } else if (format === 'pdf') {
                        await exportToPDF();
                    }
                } catch (error) {
                    console.error('Error exporting table:', error);
                    showMessage('An error occurred while exporting the table', 'error');
                }
            };

            // Export to CSV
            function exportToCSV() {
                // Create CSV content
                const headers = ['Name', 'Office', 'Attendance (%)', 'Late', 'Absences', 'Evaluation Score', 'Status'];
                const csvRows = [headers.join(',')];
                
                filteredData.forEach(student => {
                    const row = [
                        `"${student.name}"`,
                        `"${student.office}"`,
                        student.attendance,
                        student.lates,
                        student.absences,
                        student.evaluationScore.toFixed(1),
                        `"${student.status}"`
                    ];
                    csvRows.push(row.join(','));
                });
                
                const csvContent = csvRows.join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, -5);
                a.href = url;
                a.download = `performance_report_${timestamp}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                showMessage('CSV exported successfully!', 'success');
            }

            // Export to PDF using jsPDF
            async function exportToPDF() {
                // Load jsPDF from CDN if not already loaded
                if (typeof window.jspdf === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
                    script.onload = () => generatePerformancePDF();
                    document.head.appendChild(script);
                } else {
                    generatePerformancePDF();
                }
            }

            function generatePerformancePDF() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Add title
                doc.setFontSize(18);
                doc.text('Performance Report', 14, 20);
                
                // Add date
                doc.setFontSize(10);
                doc.text(`Generated: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`, 14, 30);
                
                // Add summary statistics
                doc.setFontSize(12);
                let yPos = 45;
                doc.setFont(undefined, 'bold');
                doc.text('Summary Statistics', 14, yPos);
                doc.setFont(undefined, 'normal');
                
                yPos += 8;
                doc.setFontSize(10);
                doc.text(`Total Students: ${filteredData.length}`, 20, yPos);
                yPos += 6;
                doc.text(`Average Attendance: ${avgAttendance.textContent}`, 20, yPos);
                yPos += 6;
                doc.text(`Total Lates: ${totalLates.textContent}`, 20, yPos);
                yPos += 6;
                doc.text(`Total Absences: ${totalAbsences.textContent}`, 20, yPos);
                yPos += 6;
                doc.text(`Average Evaluation: ${avgEvaluation.textContent}`, 20, yPos);
                
                // Add table headers
                yPos += 15;
                doc.setFont(undefined, 'bold');
                doc.setFontSize(9);
                doc.text('Performance Data', 14, yPos);
                
                // Table data
                yPos += 8;
                doc.setFontSize(7);
                doc.setFont(undefined, 'bold');
                doc.text('Name', 14, yPos);
                doc.text('Office', 55, yPos);
                doc.text('Attend %', 90, yPos);
                doc.text('Late', 110, yPos);
                doc.text('Abs', 125, yPos);
                doc.text('Eval', 140, yPos);
                doc.text('Status', 160, yPos);
                
                // Add table rows
                filteredData.slice(0, 25).forEach((student, index) => {
                    yPos += 6;
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                        // Re-add headers on new page
                        doc.setFont(undefined, 'bold');
                        doc.setFontSize(7);
                        doc.text('Name', 14, yPos);
                        doc.text('Office', 55, yPos);
                        doc.text('Attend %', 90, yPos);
                        doc.text('Late', 110, yPos);
                        doc.text('Abs', 125, yPos);
                        doc.text('Eval', 140, yPos);
                        doc.text('Status', 160, yPos);
                        yPos += 6;
                    }
                    doc.setFont(undefined, 'normal');
                    doc.text(student.name.substring(0, 20), 14, yPos);
                    doc.text((student.office || 'N/A').substring(0, 15), 55, yPos);
                    doc.text(student.attendance + '%', 90, yPos);
                    doc.text(student.lates.toString(), 110, yPos);
                    doc.text(student.absences.toString(), 125, yPos);
                    doc.text(student.evaluationScore.toFixed(1), 140, yPos);
                    doc.text(student.status.substring(0, 8), 160, yPos);
                });
                
                if (filteredData.length > 25) {
                    yPos += 8;
                    doc.setFont(undefined, 'italic');
                    doc.text(`... and ${filteredData.length - 25} more records`, 14, yPos);
                }
                
                // Save the PDF
                const fileName = `Performance_Report_${new Date().toISOString().split('T')[0]}.pdf`;
                doc.save(fileName);
                showMessage('PDF exported successfully!', 'success');
            }

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
