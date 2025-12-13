@extends('../layout/' . $layout)

@section('subhead')
    <title>DTR Monitoring & Review - SAMS</title>
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
                            DTR Monitoring & Review
                        </h1>
                        <p class="text-slate-500 mt-2">Monitor and review student assistant attendance records.</p>
                        <div class="alert alert-info mt-3 flex items-center" id="pendingCount">
                            <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                            You have <span id="pendingCountNumber" class="mx-1">0</span> pending DTRs to review.
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="filter" class="w-5 h-5 mr-2"></i>
                            Filters
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="searchInput" class="form-label">Search:</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or ID" />
                            </div>
                            <div>
                                <label for="filterDate" class="form-label">Date:</label>
                                <input type="date" id="filterDate" class="form-control" />
                            </div>
                            <div>
                                <label for="filterOffice" class="form-label">Office:</label>
                                <select id="filterOffice" class="form-control">
                                    <option value="">All Offices</option>
                                </select>
                            </div>
                            <div>
                                <label for="filterStatus" class="form-label">Status:</label>
                                <select id="filterStatus" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="Present">Present</option>
                                    <option value="Late">Late</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Incomplete">Incomplete</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DTR Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            DTR Records
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Office</th>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="dtrTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- DTR Review Panel -->
                <div class="col-span-12 intro-y" id="dtrDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
                            DTR Review
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="font-medium text-slate-600">Name:</span>
                                <span id="detailName" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Office:</span>
                                <span id="detailOffice" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Date:</span>
                                <span id="detailDate" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Time In:</span>
                                <span id="detailTimeIn" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Time Out:</span>
                                <span id="detailTimeOut" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Status:</span>
                                <span id="detailStatus" class="text-slate-800"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="remarksText" class="form-label">Remarks (optional):</label>
                            <textarea id="remarksText" class="form-control" rows="3" placeholder="Enter any remarks or notes about this DTR record"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-success text-white" onclick="submitDTR('Approved')">
                                <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                Approve
                            </button>
                            <button class="btn btn-danger" onclick="submitDTR('Rejected')">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Reject
                            </button>
                            <button class="btn btn-secondary" onclick="closeDTRPanel()">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Cancel
                            </button>
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
            let dtrData = [];
            let filteredData = [];
            let currentDTRId = null;
            let currentOffice = '';

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const filterDate = document.getElementById('filterDate');
            const filterOffice = document.getElementById('filterOffice');
            const filterStatus = document.getElementById('filterStatus');
            const dtrTableBody = document.getElementById('dtrTableBody');
            const dtrDetailsPanel = document.getElementById('dtrDetailsPanel');
            const pendingCountNumber = document.getElementById('pendingCountNumber');

            // Initialize page
            async function init() {
                await loadDTRData();
                setupEventListeners();
                updatePendingCount();
                renderTable();
                reloadLucideIcons();
            }

            // Load DTR data from API
            async function loadDTRData() {
                try {
                    const response = await fetch('/api/office-attendances', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        dtrTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">Error loading data</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        dtrData = result.data.map(dtr => ({
                            id: dtr.id,
                            name: dtr.name,
                            studentId: dtr.student_id,
                            office: dtr.office,
                            date: dtr.date,
                            timeIn: dtr.time_in,
                            timeOut: dtr.time_out,
                            status: dtr.status,
                            review_status: dtr.review_status,
                            remarks: dtr.remarks,
                            pending: dtr.pending
                        }));
                        filteredData = [...dtrData];
                        currentOffice = result.office || '';
                        
                        // Populate office filter
                        populateOfficeFilter();
                    } else {
                        dtrTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">' + (result.message || 'No attendance records found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading DTR data:', error);
                    dtrTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">Error loading data</td></tr>';
                }
            }

            // Populate office filter
            function populateOfficeFilter() {
                const filterOffice = document.getElementById('filterOffice');
                const offices = [...new Set(dtrData.map(dtr => dtr.office).filter(Boolean))];
                
                // Clear existing options except "All Offices"
                filterOffice.innerHTML = '<option value="">All Offices</option>';
                
                // Add office options
                offices.forEach(office => {
                    const option = document.createElement('option');
                    option.value = office;
                    option.textContent = office;
                    if (office === currentOffice) {
                        option.selected = true;
                    }
                    filterOffice.appendChild(option);
                });
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                filterDate.addEventListener('change', filterData);
                filterOffice.addEventListener('change', filterData);
                filterStatus.addEventListener('change', filterData);
            }

            // Filter data based on search and filters
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedDate = filterDate.value;
                const selectedOffice = filterOffice.value;
                const selectedStatus = filterStatus.value;

                filteredData = dtrData.filter(dtr => {
                    const matchesSearch = !searchTerm || 
                        dtr.name.toLowerCase().includes(searchTerm) || 
                        dtr.studentId.toLowerCase().includes(searchTerm);
                    
                    const matchesDate = !selectedDate || dtr.date === selectedDate;
                    const matchesOffice = !selectedOffice || dtr.office === selectedOffice;
                    const matchesStatus = !selectedStatus || dtr.status === selectedStatus;

                    return matchesSearch && matchesDate && matchesOffice && matchesStatus;
                });

                renderTable();
            }

            // Render the DTR table
            function renderTable() {
                if (filteredData.length === 0) {
                    dtrTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">No DTR records found</td></tr>';
                    reloadLucideIcons();
                    return;
                }

                dtrTableBody.innerHTML = filteredData.map((dtr, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${dtr.name}</td>
                        <td>${dtr.studentId || 'N/A'}</td>
                        <td>${dtr.office || 'N/A'}</td>
                        <td>${formatDate(dtr.date)}</td>
                        <td>${dtr.timeIn}</td>
                        <td>${dtr.timeOut}</td>
                        <td>
                            <span class="badge ${getStatusBadgeClass(dtr.status)} text-white rounded p-1">${dtr.status}</span>
                            ${dtr.review_status && dtr.review_status !== 'Pending' ? 
                                `<span class="badge ${getReviewStatusBadgeClass(dtr.review_status)} text-white rounded p-1 ml-1">${dtr.review_status}</span>` 
                                : ''}
                        </td>
                        <td>
                            ${dtr.pending ? 
                                `<button class="btn btn-primary btn-sm text-white" onclick="reviewDTR(${dtr.id})">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Review
                                </button>` 
                                : 
                                `<button class="btn btn-secondary btn-sm text-white" onclick="reviewDTR(${dtr.id})" title="Already reviewed">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> View
                                </button>`}
                        </td>
                    </tr>
                `).join('');
                
                reloadLucideIcons();
            }

            // Get review status badge class
            function getReviewStatusBadgeClass(reviewStatus) {
                switch (reviewStatus) {
                    case 'Approved':
                        return 'bg-success text-white';
                    case 'Rejected':
                        return 'bg-danger text-white';
                    case 'Pending':
                        return 'bg-warning text-white';
                    default:
                        return 'bg-primary text-white';
                }
            }
            
            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Present': return 'bg-success text-white';
                    case 'Late': return 'bg-warning text-white';
                    case 'Absent': return 'bg-danger text-white';
                    case 'Overtime': return 'bg-pending text-white';
                    default: return 'bg-primary text-white';
                }
            }

            // Format date for display
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Update pending count
            function updatePendingCount() {
                const pendingCount = dtrData.filter(dtr => dtr.pending).length;
                pendingCountNumber.textContent = pendingCount;
            }

            // Review DTR function (global scope for onclick)
            window.reviewDTR = function(dtrId) {
                const dtr = dtrData.find(d => d.id === dtrId);
                if (!dtr) return;

                currentDTRId = dtrId;
                
                // Populate details
                document.getElementById('detailName').textContent = dtr.name;
                document.getElementById('detailOffice').textContent = dtr.office || 'N/A';
                document.getElementById('detailDate').textContent = formatDate(dtr.date);
                document.getElementById('detailTimeIn').textContent = dtr.timeIn;
                document.getElementById('detailTimeOut').textContent = dtr.timeOut;
                document.getElementById('detailStatus').textContent = dtr.status;
                document.getElementById('remarksText').value = dtr.remarks || '';

                // Show/hide action buttons based on review status
                const approveBtn = dtrDetailsPanel.querySelector('button[onclick*="Approved"]');
                const rejectBtn = dtrDetailsPanel.querySelector('button[onclick*="Rejected"]');
                
                if (dtr.review_status && dtr.review_status !== 'Pending') {
                    // Already reviewed - disable buttons
                    if (approveBtn) approveBtn.disabled = true;
                    if (rejectBtn) rejectBtn.disabled = true;
                } else {
                    // Pending - enable buttons
                    if (approveBtn) approveBtn.disabled = false;
                    if (rejectBtn) rejectBtn.disabled = false;
                }

                // Show panel
                dtrDetailsPanel.style.display = 'block';
                dtrDetailsPanel.scrollIntoView({ behavior: 'smooth' });
                reloadLucideIcons();
            };

            // Close DTR panel function (global scope for onclick)
            window.closeDTRPanel = function() {
                dtrDetailsPanel.style.display = 'none';
                currentDTRId = null;
            };

            // Submit DTR function (global scope for onclick)
            window.submitDTR = async function(action) {
                if (!currentDTRId) return;

                const remarks = document.getElementById('remarksText').value;
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch('/dtr-monitoring/review', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            dtr_id: currentDTRId,
                            action: action,
                            remarks: remarks
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        // Update local data
                        const dtrIndex = dtrData.findIndex(d => d.id === currentDTRId);
                        if (dtrIndex !== -1) {
                            dtrData[dtrIndex].pending = false;
                            dtrData[dtrIndex].review_status = action;
                            dtrData[dtrIndex].remarks = remarks;
                        }

                        // Update filtered data
                        const filteredIndex = filteredData.findIndex(d => d.id === currentDTRId);
                        if (filteredIndex !== -1) {
                            filteredData[filteredIndex].pending = false;
                            filteredData[filteredIndex].review_status = action;
                            filteredData[filteredIndex].remarks = remarks;
                        }

                        // Update UI
                        updatePendingCount();
                        renderTable();
                        closeDTRPanel();

                        // Show success message
                        showMessage(result.message || `DTR ${action.toLowerCase()} successfully!`, 'success');
                    } else {
                        showMessage(result.message || 'Failed to review DTR', 'error');
                    }
                } catch (error) {
                    console.error('Error submitting DTR review:', error);
                    showMessage('An error occurred while reviewing DTR', 'error');
                }
            };

            // Show message function
            function showMessage(message, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} flex items-center fixed top-4 right-4 z-50 shadow-lg`;
                messageDiv.innerHTML = `
                    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-4 h-4 mr-2"></i>
                    ${message}
                `;
                
                document.body.appendChild(messageDiv);
                reloadLucideIcons();
                
                // Remove message after 3 seconds
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

            // Initialize when DOM is loaded
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection
