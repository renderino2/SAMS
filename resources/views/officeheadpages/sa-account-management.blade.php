@extends('../layout/' . $layout)

@section('subhead')
    <title>SA Account Management - SAMS</title>
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
                            Student Assistant Account Management
                        </h1>
                        <p class="text-slate-500 mt-2">Manage student assistant accounts, modify their details, and assign scheduled time-in.</p>
                        <div class="mt-3">
                            <span class="text-sm text-slate-600">Office: <strong id="officeName">Loading...</strong></span>
                        </div>
                    </div>
                </div>

                <!-- SA List -->
                <div class="col-span-12 lg:col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                            Student Assistants
                        </h2>
                        <!-- Office Head Request: Additional SAs -->
                        <div class="alert alert-info flex items-start mb-4">
                            <i data-lucide="info" class="w-4 h-4 mr-2 mt-0.5"></i>
                            <div class="w-full">
                                <div class="font-medium">Request additional Student Assistants</div>
                                <div class="text-slate-600 text-sm">Send a request to HR with the quantity you need for your office. HR will review it in Requests Review.</div>
                                <div class="grid grid-cols-12 gap-3 mt-3">
                                    <div class="col-span-12 md:col-span-3">
                                        <label class="form-label" for="saRequestQty">Quantity</label>
                                        <input id="saRequestQty" type="number" min="1" max="50" class="form-control" placeholder="e.g. 2">
                                    </div>
                                    <div class="col-span-12 md:col-span-7">
                                        <label class="form-label" for="saRequestNote">Message (optional)</label>
                                        <input id="saRequestNote" type="text" class="form-control" placeholder="Reason / details (optional)">
                                    </div>
                                    <div class="col-span-12 md:col-span-2 flex items-end">
                                        <button id="submitSARequestBtn" class="btn btn-primary w-full">
                                            <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                            Request
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, student ID, or email..." />
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Email</th>
                                        <th>Scheduled Time In</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="saTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit SA Account Modal -->
    <div id="editSAModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto" id="modalTitle">Edit Student Assistant Account</h2>
                    <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <form id="editSAForm">
                        <input type="hidden" id="editSAId" name="id">
                        
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Personal Information -->
                            <div class="col-span-12">
                                <h3 class="text-base font-medium mb-3">Personal Information</h3>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6">
                                <label for="editFullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="editFullName" name="full_name" class="form-control" required>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6">
                                <label for="editStudentId" class="form-label">Student ID <span class="text-danger">*</span></label>
                                <input type="text" id="editStudentId" name="student_id_number" class="form-control" required>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6">
                                <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="editEmail" name="email" class="form-control" required>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6">
                                <label for="editContact" class="form-label">Contact Number</label>
                                <input type="text" id="editContact" name="contact" class="form-control">
                            </div>
                            
                            <!-- Academic Information -->
                            <div class="col-span-12 mt-4">
                                <h3 class="text-base font-medium mb-3">Academic Information</h3>
                            </div>
                            
                            <div class="col-span-12 md:col-span-4">
                                <label for="editCourse" class="form-label">Course</label>
                                <input type="text" id="editCourse" name="course" class="form-control">
                            </div>
                            
                            <div class="col-span-12 md:col-span-4">
                                <label for="editYearLevel" class="form-label">Year Level</label>
                                <input type="text" id="editYearLevel" name="year_level" class="form-control">
                            </div>
                            
                            <div class="col-span-12 md:col-span-4">
                                <label for="editSection" class="form-label">Section</label>
                                <input type="text" id="editSection" name="section" class="form-control">
                            </div>
                            
                            <!-- Schedule & Status -->
                            <div class="col-span-12 mt-4">
                                <h3 class="text-base font-medium mb-3">Schedule & Status</h3>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6">
                                <label for="editScheduledTimeIn" class="form-label">Scheduled Time In</label>
                                <input type="time" id="editScheduledTimeIn" name="scheduled_time_in" class="form-control">
                                <div class="text-xs text-slate-500 mt-1">Set the expected time in for this student assistant</div>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6">
                                <label for="editScheduledTimeOut" class="form-label">Scheduled Time Out</label>
                                <input type="time" id="editScheduledTimeOut" name="scheduled_time_out" class="form-control">
                                <div class="text-xs text-slate-500 mt-1">Set the expected time out for this student assistant</div>
                            </div>
                            
                            <!-- Work Schedule (Broken Schedule) -->
                            <div class="col-span-12 mt-2">
                                <label class="form-label">Work Schedule (For Broken Schedules):</label>
                                <div id="editScheduleSlotsContainer" class="space-y-3">
                                    <!-- Schedule slots will be added here -->
                                </div>
                                <button type="button" id="addEditScheduleSlotBtn" class="btn btn-sm btn-outline-primary mt-2">
                                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                                    Add Time Slot
                                </button>
                                <small class="block text-slate-500 mt-1">Add multiple time slots for broken schedules (e.g., 8:00 AM - 12:00 PM, 1:00 PM - 5:00 PM)</small>
                            </div>
                            
                            <div class="col-span-12 md:col-span-6 mt-4">
                                <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                <select id="editStatus" name="status" class="form-control" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending Assignment">Pending Assignment</option>
                                    <option value="Apprentice">Apprentice</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex justify-end">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="button" id="saveSABtn" class="btn btn-primary w-20">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save
                    </button>
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

        // DOM elements
        const searchInput = document.getElementById('searchInput');
        const saTableBody = document.getElementById('saTableBody');
        const officeName = document.getElementById('officeName');
        const editSAModal = document.getElementById('editSAModal');
        const editSAForm = document.getElementById('editSAForm');
        const saveSABtn = document.getElementById('saveSABtn');
        
        // Schedule slots management
        let scheduleSlotCounter = 0;
        
        // Add schedule slot
        function addEditScheduleSlot(timeIn = '', timeOut = '') {
            const container = document.getElementById('editScheduleSlotsContainer');
            if (!container) return;
            
            const slotId = `editScheduleSlot_${scheduleSlotCounter++}`;
            
            const slotHTML = `
                <div class="schedule-slot flex items-center gap-2 p-3 border rounded" data-slot-id="${slotId}">
                    <div class="flex-1">
                        <label class="form-label text-xs">Time In:</label>
                        <input type="time" class="form-control form-control-sm schedule-time-in" value="${timeIn}" />
                    </div>
                    <div class="flex-1">
                        <label class="form-label text-xs">Time Out:</label>
                        <input type="time" class="form-control form-control-sm schedule-time-out" value="${timeOut}" />
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-slot-btn" onclick="removeEditScheduleSlot('${slotId}')">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', slotHTML);
            reloadLucideIcons();
        }
        
        // Remove schedule slot
        window.removeEditScheduleSlot = function(slotId) {
            const slot = document.querySelector(`[data-slot-id="${slotId}"]`);
            if (slot) {
                slot.remove();
            }
        };
        
        // Get all schedule slots
        function getEditScheduleSlots() {
            const slots = [];
            const slotElements = document.querySelectorAll('#editScheduleSlotsContainer .schedule-slot');
            
            slotElements.forEach(slot => {
                const timeIn = slot.querySelector('.schedule-time-in').value;
                const timeOut = slot.querySelector('.schedule-time-out').value;
                
                if (timeIn && timeOut) {
                    slots.push({
                        time_in: timeIn,
                        time_out: timeOut
                    });
                }
            });
            
            return slots;
        }
        
        // Clear schedule slots
        function clearEditScheduleSlots() {
            const container = document.getElementById('editScheduleSlotsContainer');
            if (container) {
                container.innerHTML = '';
                scheduleSlotCounter = 0;
            }
        }

        // Initialize
        async function init() {
            await loadSAs();
            setupEventListeners();
            reloadLucideIcons();
        }

        // Setup event listeners
        function setupEventListeners() {
            searchInput.addEventListener('input', filterSAs);
            saveSABtn.addEventListener('click', saveSA);
            const submitSARequestBtn = document.getElementById('submitSARequestBtn');
            if (submitSARequestBtn) {
                submitSARequestBtn.addEventListener('click', submitSARequest);
            }
            
            // Add schedule slot button
            const addEditScheduleSlotBtn = document.getElementById('addEditScheduleSlotBtn');
            if (addEditScheduleSlotBtn) {
                addEditScheduleSlotBtn.addEventListener('click', () => addEditScheduleSlot());
            }
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
                    saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading data</td></tr>';
                    return;
                }
                
                const result = await response.json();
                
                if (result.success) {
                    saList = result.data;
                    filteredSAList = [...saList];
                    officeName.textContent = result.office || 'N/A';
                    renderSATable();
                } else {
                    saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">' + (result.message || 'No student assistants found') + '</td></tr>';
                }
            } catch (error) {
                console.error('Error loading SAs:', error);
                saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading data</td></tr>';
            }
        }

        // Filter SAs
        function filterSAs() {
            const searchTerm = searchInput.value.toLowerCase();
            filteredSAList = saList.filter(sa => {
                const name = (sa.full_name || sa.name || '').toLowerCase();
                const studentId = (sa.student_id_number || '').toLowerCase();
                const email = (sa.email || '').toLowerCase();
                return name.includes(searchTerm) || studentId.includes(searchTerm) || email.includes(searchTerm);
            });
            renderSATable();
        }

        // Render SA Table
        function renderSATable() {
            if (filteredSAList.length === 0) {
                saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No student assistants found</td></tr>';
                return;
            }

            saTableBody.innerHTML = filteredSAList.map((sa, index) => {
                const scheduledTime = sa.scheduled_time_in 
                    ? formatTime(sa.scheduled_time_in) 
                    : '<span class="text-slate-400">Not set</span>';
                
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${sa.full_name || sa.name}</td>
                        <td>${sa.student_id_number || 'N/A'}</td>
                        <td>${sa.email || 'N/A'}</td>
                        <td>${scheduledTime}</td>
                        <td>
                            <span class="badge ${sa.active ? 'bg-success text-white' : 'bg-danger text-white'} rounded p-1">
                                ${sa.status || (sa.active ? 'Active' : 'Inactive')}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="editSA(${sa.id})">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            reloadLucideIcons();
        }

        // Format time from HH:MM:SS to HH:MM AM/PM
        function formatTime(timeString) {
            if (!timeString) return '--';
            const [hours, minutes] = timeString.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        }

        // Edit SA
        window.editSA = async function(saId) {
            const sa = saList.find(s => s.id === saId);
            if (!sa) {
                showMessage('Student assistant not found', 'error');
                return;
            }

            // Populate form
            document.getElementById('editSAId').value = sa.id;
            document.getElementById('editFullName').value = sa.full_name || sa.name || '';
            document.getElementById('editStudentId').value = sa.student_id_number || '';
            document.getElementById('editEmail').value = sa.email || '';
            document.getElementById('editContact').value = sa.contact || '';
            document.getElementById('editCourse').value = sa.course || '';
            document.getElementById('editYearLevel').value = sa.year_level || '';
            document.getElementById('editSection').value = sa.section || '';
            document.getElementById('editStatus').value = sa.status || 'Active';
            
            // Format scheduled_time_in for time input (HH:MM)
            const formatTimeForInput = (timeString) => {
                if (!timeString) return '';
                if (timeString.includes(':')) {
                    const parts = timeString.split(':');
                    return `${parts[0]}:${parts[1]}`;
                }
                return timeString;
            };
            
            if (sa.scheduled_time_in) {
                document.getElementById('editScheduledTimeIn').value = formatTimeForInput(sa.scheduled_time_in);
            } else {
                document.getElementById('editScheduledTimeIn').value = '';
            }
            
            // Format scheduled_time_out for time input (HH:MM)
            if (sa.scheduled_time_out) {
                document.getElementById('editScheduledTimeOut').value = formatTimeForInput(sa.scheduled_time_out);
            } else {
                document.getElementById('editScheduledTimeOut').value = '';
            }
            
            // Load work schedule slots
            clearEditScheduleSlots();
            if (sa.work_schedule && Array.isArray(sa.work_schedule) && sa.work_schedule.length > 0) {
                sa.work_schedule.forEach(slot => {
                    addEditScheduleSlot(
                        formatTimeForInput(slot.time_in),
                        formatTimeForInput(slot.time_out)
                    );
                });
            }

            // Open modal
            if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                const modal = tailwind.Modal.getOrCreateInstance(editSAModal);
                modal.show();
            } else {
                editSAModal.style.display = 'block';
                editSAModal.classList.add('show');
                // Ensure aria-hidden is false when modal is shown
                editSAModal.setAttribute('aria-hidden', 'false');
            }
        };
        
        // Clear form when modal is closed and manage aria-hidden
        if (editSAModal) {
            // When modal is shown, ensure aria-hidden is false
            editSAModal.addEventListener('shown.tw.modal', function() {
                editSAModal.setAttribute('aria-hidden', 'false');
            });
            
            // When modal is hidden, set aria-hidden to true
            editSAModal.addEventListener('hidden.tw.modal', function() {
                editSAModal.setAttribute('aria-hidden', 'true');
                clearEditScheduleSlots();
            });
            
            // Also handle non-Tailwind modals
            editSAModal.addEventListener('shown', function() {
                editSAModal.setAttribute('aria-hidden', 'false');
            });
            
            editSAModal.addEventListener('hidden', function() {
                editSAModal.setAttribute('aria-hidden', 'true');
                clearEditScheduleSlots();
            });
        }

        // Save SA
        async function saveSA() {
            const formData = new FormData(editSAForm);
            const saId = formData.get('id');
            
            if (!saId) {
                showMessage('Student assistant ID is missing', 'error');
                return;
            }

            // Validate required fields
            if (!formData.get('full_name') || !formData.get('student_id_number') || !formData.get('email')) {
                showMessage('Please fill in all required fields', 'error');
                return;
            }

            // Disable button and show loading
            saveSABtn.disabled = true;
            const originalHTML = saveSABtn.innerHTML;
            saveSABtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Saving...';
            reloadLucideIcons();

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                 document.querySelector('input[name="_token"]')?.value || '';
                
                if (!csrfToken) {
                    showMessage('CSRF token not found. Please refresh the page.', 'error');
                    saveSABtn.disabled = false;
                    saveSABtn.innerHTML = originalHTML;
                    reloadLucideIcons();
                    return;
                }

                // Get schedule slots
                const scheduleSlots = getEditScheduleSlots();
                
                // Prepare data
                const data = {
                    full_name: formData.get('full_name'),
                    student_id_number: formData.get('student_id_number'),
                    email: formData.get('email'),
                    contact: formData.get('contact') || null,
                    course: formData.get('course') || null,
                    year_level: formData.get('year_level') || null,
                    section: formData.get('section') || null,
                    status: formData.get('status'),
                    scheduled_time_in: formData.get('scheduled_time_in') || null,
                    scheduled_time_out: formData.get('scheduled_time_out') || null,
                    work_schedule: scheduleSlots.length > 0 ? scheduleSlots : null
                };

                const response = await fetch(`/api/office-sas/${saId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    showMessage(result.message || 'Student assistant updated successfully!', 'success');
                    
                    // Close modal
                    if (window.tailwind && window.tailwind.Modal && typeof window.tailwind.Modal.getOrCreateInstance === 'function') {
                        const modal = tailwind.Modal.getOrCreateInstance(editSAModal);
                        modal.hide();
                    } else {
                        editSAModal.style.display = 'none';
                        editSAModal.classList.remove('show');
                        // Ensure aria-hidden is true when modal is hidden
                        editSAModal.setAttribute('aria-hidden', 'true');
                    }
                    
                    // Reload SA list
                    await loadSAs();
                } else {
                    const errorMsg = result.message || result.error || 'Failed to update student assistant';
                    showMessage(errorMsg, 'error');
                    saveSABtn.disabled = false;
                    saveSABtn.innerHTML = originalHTML;
                    reloadLucideIcons();
                }
            } catch (error) {
                console.error('Error saving SA:', error);
                showMessage('An error occurred while saving: ' + error.message, 'error');
                saveSABtn.disabled = false;
                saveSABtn.innerHTML = originalHTML;
                reloadLucideIcons();
            }
        }

        // Submit Office Head request for additional SAs to HR
        async function submitSARequest() {
            const qtyEl = document.getElementById('saRequestQty');
            const noteEl = document.getElementById('saRequestNote');
            const btn = document.getElementById('submitSARequestBtn');

            if (!qtyEl || !noteEl || !btn) {
                console.error('Required elements not found');
                showMessage('Form elements not found. Please refresh the page.', 'error');
                return;
            }

            const quantity = parseInt(qtyEl.value || '', 10);
            const message = (noteEl.value || '').trim();

            if (!quantity || quantity < 1 || quantity > 50) {
                showMessage('Please enter a valid quantity (1-50).', 'error');
                qtyEl.focus();
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value || '';
            
            if (!csrfToken) {
                showMessage('CSRF token not found. Please refresh the page.', 'error');
                return;
            }

            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Sending...';
            reloadLucideIcons();

            try {
                const requestBody = {
                    quantity: quantity,
                    message: message || null
                };

                console.log('Submitting SA request:', requestBody);

                const response = await fetch('/api/officehead-sa-requests', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    showMessage('Server returned an invalid response. Please try again.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                    reloadLucideIcons();
                    return;
                }

                const result = await response.json();
                console.log('Response data:', result);

                if (response.ok && result.success) {
                    showMessage(result.message || 'Request submitted successfully!', 'success');
                    qtyEl.value = '';
                    noteEl.value = '';
                } else {
                    // Handle validation errors
                    let errorMsg = result.message || result.error || 'Failed to submit request';
                    
                    if (result.errors) {
                        const errorMessages = [];
                        for (const field in result.errors) {
                            if (result.errors.hasOwnProperty(field)) {
                                errorMessages.push(...result.errors[field]);
                            }
                        }
                        if (errorMessages.length > 0) {
                            errorMsg = errorMessages.join(', ');
                        }
                    }
                    
                    showMessage(errorMsg, 'error');
                }
            } catch (error) {
                console.error('Error submitting SA request:', error);
                showMessage('An error occurred while submitting request: ' + (error.message || 'Unknown error'), 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
                reloadLucideIcons();
            }
        }

        // Show message
        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'error' ? 'danger' : 'info'} flex items-center fixed top-4 right-4 z-50 shadow-lg`;
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

