@extends('../layout/' . $layout)

@section('subhead')
    <title>My Profile - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                            My Profile
                        </h1>
                        <p class="text-slate-500 mt-2">View and manage your personal and academic details.</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Basic Information
                        </h2>
                        <div class="profile-info" id="profileData">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Student ID:</span>
                                    <span id="sid" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Full Name:</span>
                                    <span id="fullName" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Email:</span>
                                    <span id="email" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Contact:</span>
                                    <span id="contact" class="text-slate-800">—</span>
                                </div>
                                @if(Auth::user()->role === 'Student Assistant')
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Course:</span>
                                    <span id="course" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Year Level:</span>
                                    <span id="yearLevel" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Section:</span>
                                    <span id="section" class="text-slate-800">—</span>
                                </div>
                                @endif
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Role:</span>
                                    <span id="role" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Office:</span>
                                    <span id="office" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Address:</span>
                                    <span id="address" class="text-slate-800">—</span>
                                </div>
                                @if(Auth::user()->role === 'Student Assistant')
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Guardian Address:</span>
                                    <span id="guardianAddress" class="text-slate-800">—</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->role === 'Student Assistant')
                <!-- Resignation Request -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="file-x" class="w-5 h-5 mr-2"></i>
                            Resignation Request
                        </h2>
                        <p class="text-slate-500 mb-4">Submit a resignation request with your reason. Your Office Head will review and respond to your request.</p>
                        
                        <!-- Resignation Form -->
                        <div id="resignationForm">
                            <div class="mb-4">
                                <label for="resignationReason" class="form-label">Reason for Resignation <span class="text-danger">*</span></label>
                                <select id="resignationReason" class="form-control" required>
                                    <option value="">Select reason...</option>
                                    <option value="Graduated">Graduated</option>
                                    <option value="Transferring to Another School">Transferring to Another School</option>
                                    <option value="Personal Reasons">Personal Reasons</option>
                                    <option value="Health Reasons">Health Reasons</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="resignationMessage" class="form-label">Additional Message (Optional)</label>
                                <textarea id="resignationMessage" class="form-control" rows="3" placeholder="Please provide any additional details or comments about your resignation..."></textarea>
                            </div>
                            <button id="submitResignationBtn" class="btn btn-danger" onclick="submitResignation()">
                                <i data-lucide="file-x" class="w-4 h-4 mr-2"></i>
                                Submit Resignation Request
                            </button>
                        </div>

                        <!-- Current Resignation Status -->
                        <div id="resignationStatus" class="mt-4" style="display: none;">
                            <div class="alert" id="resignationStatusAlert">
                                <div class="flex items-center">
                                    <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                                    <div>
                                        <div class="font-medium" id="resignationStatusTitle">Resignation Request Status</div>
                                        <div class="text-sm mt-1" id="resignationStatusMessage"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        (function () {
            // Load profile data on page load
            function loadProfileData() {
                // Get user data from Laravel
                const user = @json(Auth::user());
                
                if (user) {
                    document.getElementById('sid').textContent = user.student_id_number || '—';
                    document.getElementById('fullName').textContent = user.full_name || user.name || '—';
                    document.getElementById('email').textContent = user.email || '—';
                    document.getElementById('contact').textContent = user.contact || '—';
                    document.getElementById('role').textContent = user.role || '—';
                    document.getElementById('office').textContent = user.office || '—';
                    document.getElementById('address').textContent = user.address || '—';
                    
                    @if(Auth::user()->role === 'Student Assistant')
                    document.getElementById('course').textContent = user.course || '—';
                    document.getElementById('yearLevel').textContent = user.year_level || '—';
                    document.getElementById('section').textContent = user.section || '—';
                    document.getElementById('guardianAddress').textContent = user.guardian_address || '—';
                    @endif
                }
            }

            // Load resignation status
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

            // Show resignation status
            function showResignationStatus(resignation) {
                const statusDiv = document.getElementById('resignationStatus');
                const statusAlert = document.getElementById('resignationStatusAlert');
                const statusTitle = document.getElementById('resignationStatusTitle');
                const statusMessage = document.getElementById('resignationStatusMessage');
                const formDiv = document.getElementById('resignationForm');
                
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
                statusTitle.textContent = `Resignation Request - ${statusText}`;
                
                let message = `Reason: ${resignation.request_type || 'Resignation'}`;
                if (resignation.message) {
                    message += `<br>Message: ${resignation.message}`;
                }
                if (resignation.remarks) {
                    message += `<br>Remarks: ${resignation.remarks}`;
                }
                if (resignation.reviewed_at) {
                    const reviewedDate = new Date(resignation.reviewed_at).toLocaleDateString();
                    message += `<br>Reviewed on: ${reviewedDate}`;
                }
                
                statusMessage.innerHTML = message;
                
                statusDiv.style.display = 'block';
                
                // Hide form if request is already processed
                if (resignation.status !== 'Pending') {
                    formDiv.style.display = 'none';
                } else {
                    // Disable form if pending
                    document.getElementById('submitResignationBtn').disabled = true;
                    document.getElementById('submitResignationBtn').innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Request Pending...';
                }
            }

            // Submit resignation request (global scope for onclick)
            window.submitResignation = async function() {
                const reason = document.getElementById('resignationReason').value;
                const message = document.getElementById('resignationMessage').value;
                const submitBtn = document.getElementById('submitResignationBtn');
                
                if (!reason) {
                    alert('Please select a reason for resignation.');
                    return;
                }
                
                // Build full message with reason
                const fullMessage = `Reason: ${reason}${message ? '\n\nAdditional Message: ' + message : ''}`;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Submitting...';
                
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
                            request_type: 'Resignation',
                            message: fullMessage
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        // Reload resignation status
                        await loadResignationStatus();
                        
                        // Show success message
                        alert('Resignation request submitted successfully! Your Office Head will review it.');
                        
                        // Reset form
                        document.getElementById('resignationReason').value = '';
                        document.getElementById('resignationMessage').value = '';
                    } else {
                        alert(result.message || 'Failed to submit resignation request. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i data-lucide="file-x" class="w-4 h-4 mr-2"></i> Submit Resignation Request';
                    }
                } catch (error) {
                    console.error('Error submitting resignation:', error);
                    alert('An error occurred while submitting your resignation request. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i data-lucide="file-x" class="w-4 h-4 mr-2"></i> Submit Resignation Request';
                }
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

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                loadProfileData();
                @if(Auth::user()->role === 'Student Assistant')
                loadResignationStatus();
                @endif
                reloadLucideIcons();
            });
        })();
    </script>
@endsection
