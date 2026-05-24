@extends('../layout/' . $layout)

@section('subhead')
    <title>Requests Page - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            {{-- <i data-lucide="send" class="w-5 h-5 mr-2"></i> --}}
                            Requests
                        </h1>
                        
                        <p class="text-slate-500 mt-2">Submit and track your student assistant-related requests.</p>
                    </div>
                </div>

                <!-- Submitted Requests Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                     {{-- <h2 class="text-lg font-medium mb-4 flex items-center">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Submitted Requests
                    </h2> --}}
                    <h2 class="text-lg font-medium mb-4 flex items-center">
                        {{-- <i data-lucide="send" class="w-5 h-5 mr-2"></i> --}}
                        Submitted Requests
                    </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Submitted On</th>
                                    </tr>
                                </thead>
                                <tbody id="requestsBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">No requests submitted yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div id="paginationContainer" class="flex items-center justify-between mt-4" style="display: none;">
                            <div class="text-slate-600">
                                Showing <span id="paginationInfo">0-0 of 0</span> requests
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="prevPageBtn" class="btn btn-outline-secondary" disabled>
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </button>
                                <span class="text-slate-600">
                                    Page <span id="currentPageDisplay">1</span> of <span id="totalPagesDisplay">1</span>
                                </span>
                                <button id="nextPageBtn" class="btn btn-outline-secondary" disabled>
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Request Form -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                            New Request
                        </h2>
                        
                        <form id="requestForm" class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label for="request_type" class="form-label">Request Type:</label>
                                <select name="request_type" id="request_type" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="Schedule Adjustment">Schedule Adjustment</option>
                                    <option value="Change Office">Change Office</option>
                                    <option value="Leave Request">Leave Request</option>
                                    <option value="Resignation">Resignation</option>
                                </select>
                            </div>
                            <!-- Resignation-specific fields (shown when Resignation is selected) -->
                            <div class="col-span-12 md:col-span-6" id="resignationReasonField" style="display: none;">
                                <label for="resignationReason" class="form-label">Reason for Resignation <span class="text-danger">*</span></label>
                                <select id="resignationReason" class="form-control">
                                    <option value="">Select reason...</option>
                                    <option value="Graduated">Graduated</option>
                                    <option value="Transferring to Another School">Transferring to Another School</option>
                                    <option value="Personal Reasons">Personal Reasons</option>
                                    <option value="Health Reasons">Health Reasons</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-span-12">
                                <label for="message" class="form-label">Message:</label>
                                <textarea name="message" id="message" rows="3" class="form-control" placeholder="Enter your message or additional details..."></textarea>
                                <div id="resignationMessageHint" class="text-xs text-slate-500 mt-1" style="display: none;">
                                    Additional message (optional). The reason selected above will be included automatically.
                                </div>
                            </div>
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary"><i data-lucide="send" class="w-4 h-4 mr-2"></i>Submit Request</button>
                            </div>
                        </form>
                        
                        <!-- Current Resignation Status (if any) -->
                        <div id="resignationStatus" class="mt-4" style="display: none;">
                            <div class="alert" id="resignationStatusAlert">
                                <div class="flex items-center">
                                    <i data-lucide="info" class="w-4 h-4 mr-4"></i>
                                    <div>
                                        <div class="font-medium" id="resignationStatusTitle">Resignation Request Status</div>
                                        <div class="text-sm mt-1" id="resignationStatusMessage"></div>
                                    </div>
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
            const requestForm = document.getElementById('requestForm');
            const requestsBody = document.getElementById('requestsBody');
            const paginationContainer = document.getElementById('paginationContainer');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const currentPageDisplay = document.getElementById('currentPageDisplay');
            const totalPagesDisplay = document.getElementById('totalPagesDisplay');
            const paginationInfo = document.getElementById('paginationInfo');
            
            let requestsList = [];
            let currentPage = 1;
            const itemsPerPage = 6;

            // Load requests
            async function loadRequests() {
                try {
                    const response = await fetch('/api/sa-requests', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        requestsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error loading data</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        requestsList = result.data;
                        renderRequestsTable();
                    } else {
                        requestsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">' + (result.message || 'No requests found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading requests:', error);
                    requestsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error loading data</td></tr>';
                }
            }

            // Render requests table with pagination
            function renderRequestsTable() {
                if (requestsList.length === 0) {
                    requestsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">No requests submitted yet</td></tr>';
                    paginationContainer.style.display = 'none';
                    return;
                }

                // Calculate pagination
                const totalPages = Math.ceil(requestsList.length / itemsPerPage);
                currentPage = Math.min(currentPage, Math.max(1, totalPages));
                
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, requestsList.length);
                const paginatedRequests = requestsList.slice(startIndex, endIndex);

                // Render table rows
                requestsBody.innerHTML = paginatedRequests.map((request, index) => {
                    const globalIndex = startIndex + index + 1;
                    return `
                        <tr>
                            <td>${globalIndex}</td>
                            <td>${request.request_type}</td>
                            <td>${request.message}</td>
                            <td>
                                <span class="badge ${getStatusBadgeClass(request.status)} text-white rounded p-1">
                                    ${request.status}
                                </span>
                            </td>
                            <td>${request.formatted_date}</td>
                        </tr>
                    `;
                }).join('');
                
                // Update pagination controls
                updatePaginationControls(totalPages, startIndex + 1, endIndex, requestsList.length);
                
                reloadLucideIcons();
            }

            // Update pagination controls
            function updatePaginationControls(totalPages, startItem, endItem, totalItems) {
                if (totalPages <= 1) {
                    paginationContainer.style.display = 'none';
                    return;
                }

                paginationContainer.style.display = 'flex';
                currentPageDisplay.textContent = currentPage;
                totalPagesDisplay.textContent = totalPages;
                paginationInfo.textContent = `${startItem}-${endItem} of ${totalItems}`;

                // Enable/disable navigation buttons
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;
            }

            // Pagination event listeners
            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderRequestsTable();
                }
            });

            nextPageBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(requestsList.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderRequestsTable();
                }
            });

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Approved':
                        return 'bg-success';
                    case 'Rejected':
                        return 'bg-danger';
                    case 'Pending':
                        return 'bg-warning';
                    default:
                        return 'bg-secondary';
                }
            }

            // Show/hide resignation-specific fields
            const requestTypeSelect = document.getElementById('request_type');
            const resignationReasonField = document.getElementById('resignationReasonField');
            const resignationReason = document.getElementById('resignationReason');
            const messageField = document.getElementById('message');
            const resignationMessageHint = document.getElementById('resignationMessageHint');
            
            requestTypeSelect.addEventListener('change', function() {
                if (this.value === 'Resignation') {
                    resignationReasonField.style.display = 'block';
                    resignationReason.required = true;
                    messageField.required = false;
                    resignationMessageHint.style.display = 'block';
                } else {
                    resignationReasonField.style.display = 'none';
                    resignationReason.required = false;
                    messageField.required = true;
                    resignationMessageHint.style.display = 'none';
                }
            });

            // Load resignation status on page load
            async function loadResignationStatus() {
                try {
                    const response = await fetch('/api/sa-requests', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        // Filter for resignation requests and get most recent
                        const resignationRequests = result.data.filter(req => req.request_type === 'Resignation');
                        if (resignationRequests.length > 0) {
                            const resignation = resignationRequests[0]; // Get most recent resignation request
                            showResignationStatus(resignation);
                        }
                    }
                } catch (error) {
                    console.error('Error loading resignation status:', error);
                }
            }

            // Show resignation status (informational only - doesn't block new requests)
            function showResignationStatus(resignation) {
                const statusDiv = document.getElementById('resignationStatus');
                const statusAlert = document.getElementById('resignationStatusAlert');
                const statusTitle = document.getElementById('resignationStatusTitle');
                const statusMessage = document.getElementById('resignationStatusMessage');
                
                let alertClass = 'alert-info';
                let statusText = 'Pending Review';
                
                if (resignation.status === 'Approved') {
                    alertClass = 'alert-success';
                    statusText = 'Approved';
                } else if (resignation.status === 'Rejected') {
                    alertClass = 'alert-danger';
                    statusText = 'Rejected';
                }
                
                statusAlert.className = `alert ${alertClass} flex items-center`;
                statusTitle.textContent = `Most Recent Resignation Request - ${statusText}`;
                
                let message = '';
                if (resignation.message) {
                    message = resignation.message.replace(/\n/g, '<br>');
                }
                if (resignation.remarks) {
                    message += `<br><strong>Remarks:</strong> ${resignation.remarks}`;
                }
                if (resignation.reviewed_at) {
                    const reviewedDate = new Date(resignation.reviewed_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    message += `<br><strong>Reviewed on:</strong> ${reviewedDate}`;
                }
                if (resignation.formatted_date) {
                    message += `<br><strong>Submitted on:</strong> ${resignation.formatted_date}`;
                }
                
                statusMessage.innerHTML = message || 'No additional information available.';
                
                statusDiv.style.display = 'block';
                
                // Note: Form remains enabled to allow multiple resignation requests
            }

            // Handle form submission
            requestForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(requestForm);
                const type = formData.get('request_type');
                let message = formData.get('message') || '';
                
                if (!type) {
                    showMessage('Please select a request type', 'error');
                    return;
                }
                
                // For resignation requests, include reason in message
                if (type === 'Resignation') {
                    const reason = resignationReason.value;
                    if (!reason) {
                        showMessage('Please select a reason for resignation', 'error');
                        return;
                    }
                    // Build full message with reason
                    message = `Reason: ${reason}${message ? '\n\nAdditional Message: ' + message : ''}`;
                } else {
                    // For other requests, message is required
                    if (!message.trim()) {
                        showMessage('Please enter a message', 'error');
                        return;
                    }
                }
                
                const submitBtn = requestForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Submitting...';
                reloadLucideIcons();
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch('/api/requests', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            request_type: type,
                            message: message
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        showMessage(result.message || 'Request submitted successfully!', 'success');
                        requestForm.reset();
                        resignationReasonField.style.display = 'none';
                        resignationMessageHint.style.display = 'none';
                        currentPage = 1; // Reset to first page
                        await loadRequests();
                        await loadResignationStatus();
                    } else {
                        showMessage(result.message || 'Failed to submit request', 'error');
                    }
                } catch (error) {
                    console.error('Error submitting request:', error);
                    showMessage('An error occurred while submitting request', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    reloadLucideIcons();
                }
            });

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
            loadRequests();
            loadResignationStatus();
        })();
    </script>
@endsection
