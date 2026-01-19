@extends('../layout/' . $layout)

@section('subhead')
    <title>Requests Review - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Review Student Assistant Requests
                        </h1>
                        <p class="text-slate-500 mt-2">Review and manage student assistant requests for overtime, absence, and official assignments.</p>
                        <div class="alert alert-info mt-3 flex items-center" id="pendingCount">
                            <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                            You have <span id="pendingCountNumber" class="mx-1">0</span> pending requests to review.
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
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="searchInput" class="form-label">Search:</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or ID" />
                            </div>
                            <div>
                                <label for="typeFilter" class="form-label">Request Type:</label>
                                <select id="typeFilter" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="Schedule Adjustment">Schedule Adjustment</option>
                                    <option value="Change Office">Change Office</option>
                                    <option value="Leave Request">Leave Request</option>
                                </select>
                            </div>
                            <div>
                                <label for="statusFilter" class="form-label">Status:</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Student Assistant Requests
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Request Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="requestBody">
                                    <tr>
                                        <td colspan="8" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Request Review Panel -->
                <div class="col-span-12 intro-y" id="requestDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
                            Request Review
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="font-medium text-slate-600">Student Name:</span>
                                <span id="detailName" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Student ID:</span>
                                <span id="detailStudentId" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Request Type:</span>
                                <span id="detailType" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Request Date:</span>
                                <span id="detailDate" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Current Status:</span>
                                <span id="detailStatus" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Office:</span>
                                <span id="detailOffice" class="text-slate-800"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="font-medium text-slate-600">Reason:</span>
                            <p id="detailReason" class="text-slate-800 mt-1"></p>
                        </div>
                        <div class="mb-4">
                            <label for="reviewComments" class="form-label">Review Comments (optional):</label>
                            <textarea id="reviewComments" class="form-control" rows="3" placeholder="Enter any comments or notes about this request"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-success text-white" onclick="submitRequest('Approved')">
                                <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                Approve
                            </button>
                            <button class="btn btn-danger" onclick="submitRequest('Rejected')">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Reject
                            </button>
                            <button class="btn btn-secondary" onclick="closeRequestPanel()">
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
            let requestData = [];
            let filteredData = [];
            let currentRequestId = null;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const requestBody = document.getElementById('requestBody');
            const requestDetailsPanel = document.getElementById('requestDetailsPanel');
            const pendingCountNumber = document.getElementById('pendingCountNumber');

            // Initialize page
            async function init() {
                await loadRequests();
                setupEventListeners();
                updatePendingCount();
                renderTable();
                reloadLucideIcons();
            }

            // Load requests from API
            async function loadRequests() {
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
                        requestBody.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">Error loading data</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        requestData = result.data.map(request => ({
                            id: request.id,
                            name: request.name,
                            studentId: request.student_id,
                            type: request.request_type,
                            date: request.created_at,
                            status: request.status,
                            reason: request.message,
                            remarks: request.remarks,
                            office: result.office || 'N/A',
                            reviewed_at: request.reviewed_at,
                            reviewed_by: request.reviewed_by
                        }));
                        filteredData = [...requestData];
                    } else {
                        requestBody.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">' + (result.message || 'No requests found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading requests:', error);
                    requestBody.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">Error loading data</td></tr>';
                }
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                typeFilter.addEventListener('change', filterData);
                statusFilter.addEventListener('change', filterData);
            }

            // Filter data based on search and filters
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedType = typeFilter.value;
                const selectedStatus = statusFilter.value;

                filteredData = requestData.filter(request => {
                    const matchesSearch = !searchTerm || 
                        request.name.toLowerCase().includes(searchTerm) || 
                        request.studentId.toLowerCase().includes(searchTerm);
                    
                    const matchesType = !selectedType || request.type === selectedType;
                    const matchesStatus = !selectedStatus || request.status === selectedStatus;

                    return matchesSearch && matchesType && matchesStatus;
                });

                renderTable();
            }

            // Render the request table
            function renderTable() {
                if (filteredData.length === 0) {
                    requestBody.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">No requests found</td></tr>';
                    reloadLucideIcons();
                    return;
                }

                requestBody.innerHTML = filteredData.map((request, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${request.name}</td>
                        <td>${request.studentId || 'N/A'}</td>
                        <td><span class="badge ${getTypeBadgeClass(request.type)} text-white rounded p-1">${request.type}</span></td>
                        <td>${formatDate(request.date)}</td>
                        <td><span class="badge ${getStatusBadgeClass(request.status)} text-white rounded p-1">${request.status}</span></td>
                        <td class="max-w-xs truncate" title="${request.reason}">${request.reason}</td>
                        <td>
                            ${request.status === 'Pending' ? 
                                `<button class="btn btn-primary btn-sm text-white" onclick="reviewRequest(${request.id})">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Review
                                </button>` 
                                : 
                                `<button class="btn btn-secondary btn-sm text-white" onclick="reviewRequest(${request.id})" title="Already reviewed">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> View
                                </button>`}
                        </td>
                    </tr>
                `).join('');
                
                reloadLucideIcons();
            }

            // Get type badge class
            function getTypeBadgeClass(type) {
                switch (type) {
                    case 'Schedule Adjustment':
                        return 'bg-primary text-white whitespace-nowrap';
                    case 'Change Office':
                        return 'bg-warning text-white whitespace-nowrap';
                    case 'Leave Request':
                        return 'bg-danger text-white whitespace-nowrap';
                    default:
                        return 'bg-pending text-white whitespace-nowrap';
                }
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Pending': return 'bg-warning text-white whitespace-nowrap';
                    case 'Approved': return 'bg-success text-white whitespace-nowrap';
                    case 'Rejected': return 'bg-danger text-white whitespace-nowrap';
                    default: return 'bg-danger text-white whitespace-nowrap';
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
                const pendingCount = requestData.filter(request => request.status === 'Pending').length;
                pendingCountNumber.textContent = pendingCount;
            }

            // Review request function (global scope for onclick)
            window.reviewRequest = async function(requestId) {
                const request = requestData.find(r => r.id === requestId);
                if (!request) return;

                currentRequestId = requestId;
                
                // Populate details
                document.getElementById('detailName').textContent = request.name;
                document.getElementById('detailStudentId').textContent = request.studentId || 'N/A';
                document.getElementById('detailType').textContent = request.type;
                document.getElementById('detailDate').textContent = formatDate(request.date);
                document.getElementById('detailStatus').textContent = request.status;
                document.getElementById('detailOffice').textContent = request.office;
                document.getElementById('detailReason').textContent = request.reason;
                document.getElementById('reviewComments').value = request.remarks || '';

                // Show/hide action buttons based on status
                const approveBtn = requestDetailsPanel.querySelector('button[onclick*="Approved"]');
                const rejectBtn = requestDetailsPanel.querySelector('button[onclick*="Rejected"]');
                
                if (request.status !== 'Pending') {
                    // Already reviewed - disable buttons
                    if (approveBtn) approveBtn.disabled = true;
                    if (rejectBtn) rejectBtn.disabled = true;
                } else {
                    // Pending - enable buttons
                    if (approveBtn) approveBtn.disabled = false;
                    if (rejectBtn) rejectBtn.disabled = false;
                }

                // Show panel
                requestDetailsPanel.style.display = 'block';
                requestDetailsPanel.scrollIntoView({ behavior: 'smooth' });
                reloadLucideIcons();
            };

            // Close request panel function (global scope for onclick)
            window.closeRequestPanel = function() {
                requestDetailsPanel.style.display = 'none';
                currentRequestId = null;
            };

            // Submit request function (global scope for onclick)
            window.submitRequest = async function(action) {
                if (!currentRequestId) return;

                const comments = document.getElementById('reviewComments').value;
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch(`/api/requests/${currentRequestId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            action: action,
                            remarks: comments
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        // Update local data
                        const requestIndex = requestData.findIndex(r => r.id === currentRequestId);
                        if (requestIndex !== -1) {
                            requestData[requestIndex].status = action;
                            requestData[requestIndex].remarks = comments;
                            requestData[requestIndex].reviewed_at = result.data.reviewed_at;
                            requestData[requestIndex].reviewed_by = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                        }

                        // Update filtered data
                        const filteredIndex = filteredData.findIndex(r => r.id === currentRequestId);
                        if (filteredIndex !== -1) {
                            filteredData[filteredIndex].status = action;
                            filteredData[filteredIndex].remarks = comments;
                            filteredData[filteredIndex].reviewed_at = result.data.reviewed_at;
                            filteredData[filteredIndex].reviewed_by = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                        }

                        // Update UI
                        updatePendingCount();
                        renderTable();
                        closeRequestPanel();

                        // Show success message
                        showMessage(result.message || `Request ${action.toLowerCase()} successfully!`, 'success');
                    } else {
                        showMessage(result.message || 'Failed to process request', 'error');
                    }
                } catch (error) {
                    console.error('Error processing request:', error);
                    showMessage('An error occurred while processing request', 'error');
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
