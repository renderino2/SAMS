@extends('../layout/' . $layout)

@section('subhead')
    <title>My Skill Inventory - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="crosshair" class="w-5 h-5 mr-2"></i>
                            My Skill Inventory
                        </h1>
                        <p class="text-slate-500 mt-2">These skills are visible to your HR & Office Head.</p>
                        <div class="alert alert-info mt-3 flex items-center" id="notificationBar">
                            <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                            You have 0 pending skill requests under review.
                        </div>
                    </div>
                </div>
                {{-- <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="upload" class="w-5 h-5 mr-2"></i>
                            Proof Upload
                        </h2>
                        <form data-file-types="image/jpeg|image/png|image/jpg|application/pdf" action="/file-upload" class="dropzone p-4">
                            <div class="fallback">
                                <input name="file" type="file" accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                            <div class="dz-message" data-dz-message>
                                <div class="text-lg font-medium">Drop files here or click to upload.</div>
                                <div class="text-slate-500">
                                    Accepted formats: PDF, JPG, JPEG, PNG
                                </div>
                            </div>
                        </form>
                        <div class="text-xs text-slate-500 mt-2">Maximum file size: 10MB</div>
                    </div>
                </div> --}}
                <!-- Skill Records Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Skill Records
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Skill Tag</th>
                                        <th>Added On</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="skillsBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">No skills added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Request New Skill Update Form -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                            Request New Skill Update
                        </h2>
                        <form id="addSkillForm" enctype="multipart/form-data" class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label for="skillName" class="form-label">Skill Name:</label>
                                <input type="text" id="skillName" name="skillName" class="form-control" placeholder="Skill Name (e.g., Canva)" required />
                            </div>
                            <div class="col-span-12">
                                <label for="skillNote" class="form-label">Description / Notes:</label>
                                <textarea id="skillNote" name="skillNote" rows="3" class="form-control" placeholder="Description / Notes (optional)"></textarea>
                            </div>
                            <div class="col-span-12">
                                <label for="proof_file" class="form-label">Proof Upload (Optional):</label>
                                <input type="file" id="proof_file" name="proof_file" 
                                       accept=".pdf,.jpg,.jpeg,.png" 
                                       class="form-control" />
                                <div class="text-xs text-slate-500 mt-1">
                                    Accepted formats: PDF, JPG, JPEG, PNG. Maximum file size: 10MB
                                </div>
                            </div>
                            <div class="col-span-12 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                    Request Skill Update
                                </button>
                            </div>
                        </form>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        (function () {
            const addSkillForm = document.getElementById('addSkillForm');
            const skillsBody = document.getElementById('skillsBody');
            const messageDiv = document.getElementById('message');
            const notificationBar = document.getElementById('notificationBar');
            let skillsList = [];

            // Load skills
            async function loadSkills() {
                try {
                    const response = await fetch('/api/sa-skills', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        skillsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error loading data</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        skillsList = result.data;
                        renderSkillsTable();
                        updateNotificationBar();
                    } else {
                        skillsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">' + (result.message || 'No skills found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading skills:', error);
                    skillsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">Error loading data</td></tr>';
                }
            }

            // Render skills table
            function renderSkillsTable() {
                if (skillsList.length === 0) {
                    skillsBody.innerHTML = '<tr><td colspan="5" class="text-center text-slate-500">No skills added yet</td></tr>';
                    return;
                }

                skillsBody.innerHTML = skillsList.map((skill, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${skill.skill_name}</td>
                        <td>${skill.formatted_date}</td>
                        <td>
                            <span class="badge ${getStatusBadgeClass(skill.status)} text-white rounded p-1">
                                ${skill.status}
                            </span>
                        </td>
                        <td>
                            ${skill.proof_file ? 
                                `<a href="${skill.proof_file}" target="_blank" class="text-primary hover:underline">
                                    <i data-lucide="file" class="w-4 h-4 inline mr-1"></i> View Proof
                                </a>` 
                                : 'No file uploaded'}
                            ${skill.remarks ? `<br><span class="text-xs text-slate-500">${skill.remarks}</span>` : ''}
                        </td>
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

            // Update notification bar
            function updateNotificationBar() {
                const pendingCount = skillsList.filter(skill => skill.status === 'Pending').length;
                notificationBar.innerHTML = `
                    <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                    You have ${pendingCount} pending skill request${pendingCount !== 1 ? 's' : ''} under review.
                `;
                reloadLucideIcons();
            }

            // Handle form submission
            addSkillForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(addSkillForm);
                const skillName = formData.get('skillName');
                
                if (!skillName || !skillName.trim()) {
                    showMessage('Please enter a skill name', 'error');
                    return;
                }

                // Validate file if provided
                const proofFile = formData.get('proof_file');
                if (proofFile && proofFile.size > 10 * 1024 * 1024) {
                    showMessage('File size must be less than 10MB', 'error');
                    return;
                }
                
                const submitBtn = addSkillForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Submitting...';
                reloadLucideIcons();
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch('/api/skill-requests', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        showMessage(result.message || 'Skill request submitted successfully!', 'success');
                        addSkillForm.reset();
                        await loadSkills();
                    } else {
                        showMessage(result.message || 'Failed to submit skill request', 'error');
                    }
                } catch (error) {
                    console.error('Error submitting skill request:', error);
                    showMessage('An error occurred while submitting skill request', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    reloadLucideIcons();
                }
            });

            // Show message function
            function showMessage(message, type) {
                messageDiv.innerHTML = `
                    <div class="alert alert-${type === 'success' ? 'success' : 'danger'} flex items-center">
                        <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-4 h-4 mr-2"></i>
                        ${message}
                    </div>
                `;
                
                reloadLucideIcons();
                
                setTimeout(() => {
                    messageDiv.innerHTML = '';
                }, 5000);
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
            loadSkills();
        })();
    </script>
@endsection
