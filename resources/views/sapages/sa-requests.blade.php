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
                                </select>
                            </div>
                            <div class="col-span-12">
                                <label for="message" class="form-label">Message:</label>
                                <textarea name="message" id="message" rows="3" class="form-control" required></textarea>
                            </div>
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary"><i data-lucide="send" class="w-4 h-4 mr-2"></i>Submit Request</button>
                            </div>
                        </form>
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
            let requestsList = [];

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

            // Render requests table
            function renderRequestsTable() {
                if (requestsList.length === 0) {
                    requestsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">No requests submitted yet</td></tr>';
                    return;
                }

                requestsBody.innerHTML = requestsList.map((request, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${request.request_type}</td>
                        <td>${request.message}</td>
                        <td>
                            <span class="badge ${getStatusBadgeClass(request.status)} text-white rounded p-1">
                                ${request.status}
                            </span>
                        </td>
                        <td>${request.formatted_date}</td>
                    </tr>
                `).join('');
                
                reloadLucideIcons();
            }

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

            // Handle form submission
            requestForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(requestForm);
                const type = formData.get('request_type');
                const message = formData.get('message');
                
                if (!type || !message.trim()) {
                    showMessage('Please fill in all fields', 'error');
                    return;
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
                        await loadRequests();
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
        })();
    </script>
@endsection
