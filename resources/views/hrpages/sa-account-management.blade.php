@extends('../layout/' . $layout)

@section('subhead')
    <title>Student Assistant Account & Profile Management - SAMS</title>
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
                            Student Assistant Account & Profile Management
                        </h1>
                        <p class="text-slate-500 mt-2">Manage student assistant accounts, profiles, and assignments.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Total Student Assistants:</span>
                            <span class="ml-2" id="totalCount">0</span>
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
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, ID, or email" />
                            </div>
                            <div>
                                <label for="officeFilter" class="form-label">Office:</label>
                                <select id="officeFilter" class="form-control">
                                    <option value="">All Offices</option>
                                </select>
                            </div>
                            <div>
                                <label for="statusFilter" class="form-label">Status:</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SA Accounts Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Student Assistant Accounts
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Email</th>
                                        <th>Office</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="saTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add SA Section -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                                Add New Student Assistant
                            </h2>
                            <button id="toggleFormBtn" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Student Assistant
                            </button>
                        </div>
                        
                        <div class="add-form" id="addForm" style="display: none;">
                            <form id="saForm" class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label for="name" class="form-label">Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="full_name" class="form-label">Full Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="full_name" name="full_name" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="student_id_number" class="form-label">Student ID Number: <span class="text-danger">*</span></label>
                                    <input type="text" id="student_id_number" name="student_id_number" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="password" class="form-label">Password: <span class="text-danger">*</span></label>
                                    <input type="password" id="password" name="password" class="form-control" required minlength="8" />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="office" class="form-label">Office: <span class="text-danger">*</span></label>
                                    <select id="office" name="office" class="form-control" required>
                                        <option value="">Select Office</option>
                                    </select>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="contact" class="form-label">Contact Number:</label>
                                    <input type="text" id="contact" name="contact" class="form-control" placeholder="09XXXXXXXXX" pattern="^09\d{9}$" />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="gender" class="form-label">Gender: <span class="text-danger">*</span></label>
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="active" class="form-label">Status:</label>
                                    <select id="active" name="active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-span-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                        Save Student Assistant
                                    </button>
                                    <button type="button" id="cancelFormBtn" class="btn btn-secondary ml-2">
                                        <i data-lucide="x" class="w-4 h-4 mr-2 text-black"></i>
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>

                <!-- SA Details Panel -->
                <div class="col-span-12 intro-y" id="saDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                            Student Assistant Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="font-medium text-slate-600">Name:</span>
                                <span id="detailName" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Full Name:</span>
                                <span id="detailFullName" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Student ID:</span>
                                <span id="detailStudentId" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Email:</span>
                                <span id="detailEmail" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Office:</span>
                                <span id="detailOffice" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Contact:</span>
                                <span id="detailContact" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Gender:</span>
                                <span id="detailGender" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Status:</span>
                                <span id="detailStatus" class="text-slate-800"></span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-primary" onclick="editSA()">
                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteSA()">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Delete
                            </button>
                            <button class="btn btn-secondary" onclick="closeSAPanel()">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Close
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
            let saData = [];
            let filteredData = [];
            let officesData = [];
            let currentSAId = null;
            let isEditMode = false;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const officeFilter = document.getElementById('officeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const saTableBody = document.getElementById('saTableBody');
            const addForm = document.getElementById('addForm');
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            const saForm = document.getElementById('saForm');
            const saDetailsPanel = document.getElementById('saDetailsPanel');
            const totalCount = document.getElementById('totalCount');
            const messageDiv = document.getElementById('message');
            const officeSelect = document.getElementById('office');

            // Helper function to re-initialize Lucide icons
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
            async function init() {
                await Promise.all([loadOffices(), loadSAAccounts()]);
                setupEventListeners();
            }

            // Load offices for dropdown
            async function loadOffices() {
                try {
                    const response = await fetch('/api/offices');
                    const result = await response.json();
                    
                    if (result.success) {
                        officesData = result.data.filter(o => o.is_active);
                        officeSelect.innerHTML = '<option value="">Select Office</option>' + 
                            officesData.map(office => `<option value="${office.name}">${office.name}</option>`).join('');
                        
                        // Populate office filter
                        officeFilter.innerHTML = '<option value="">All Offices</option>' + 
                            officesData.map(office => `<option value="${office.name}">${office.name}</option>`).join('');
                    }
                } catch (error) {
                    console.error('Error loading offices:', error);
                }
            }

            // Load SA accounts from API
            async function loadSAAccounts() {
                try {
                    const response = await fetch('/api/sa-accounts');
                    const result = await response.json();
                    
                    if (result.success) {
                        saData = result.data;
                        filteredData = [...saData];
                        updateTotalCount();
                        renderTable();
                    } else {
                        showMessage('Failed to load student assistants', 'error');
                    }
                } catch (error) {
                    console.error('Error loading student assistants:', error);
                    showMessage('An error occurred while loading student assistants', 'error');
                    saTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">Error loading student assistants</td></tr>';
                }
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                officeFilter.addEventListener('change', filterData);
                statusFilter.addEventListener('change', filterData);
                toggleFormBtn.addEventListener('click', toggleForm);
                cancelFormBtn.addEventListener('click', hideForm);
                saForm.addEventListener('submit', handleFormSubmit);
            }

            // Filter data
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedOffice = officeFilter.value;
                const selectedStatus = statusFilter.value;

                filteredData = saData.filter(sa => {
                    const matchesSearch = !searchTerm || 
                        sa.name.toLowerCase().includes(searchTerm) || 
                        (sa.student_id_number && sa.student_id_number.toLowerCase().includes(searchTerm)) ||
                        sa.email.toLowerCase().includes(searchTerm);
                    
                    const matchesOffice = !selectedOffice || sa.office === selectedOffice;
                    
                    const matchesStatus = !selectedStatus || 
                        (selectedStatus === 'active' && sa.active) ||
                        (selectedStatus === 'inactive' && !sa.active);

                    return matchesSearch && matchesOffice && matchesStatus;
                });

                renderTable();
            }

            // Render table
            function renderTable() {
                if (filteredData.length === 0) {
                    saTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">No student assistants found</td></tr>';
                    return;
                }

                saTableBody.innerHTML = filteredData.map((sa, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${sa.name}</td>
                        <td>${sa.student_id_number || '-'}</td>
                        <td>${sa.email}</td>
                        <td>${sa.office || '-'}</td>
                        <td>${sa.contact || '-'}</td>
                        <td>
                            <span class="badge ${sa.active ? 'bg-success' : 'bg-danger'} text-white rounded p-1 whitespace-nowrap">
                                ${sa.active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="viewSA(${sa.id})">
                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                View
                            </button>
                        </td>
                    </tr>
                `).join('');
                
                reloadLucideIcons();
            }

            // Update total count
            function updateTotalCount() {
                totalCount.textContent = saData.length;
            }

            // Toggle form
            function toggleForm() {
                if (addForm.style.display === 'none') {
                    showForm();
                } else {
                    hideForm();
                }
            }

            // Show form
            function showForm() {
                addForm.style.display = 'block';
                toggleFormBtn.innerHTML = '<i data-lucide="x" class="w-4 h-4 mr-2"></i>Hide Form';
                reloadLucideIcons();
                addForm.scrollIntoView({ behavior: 'smooth' });
            }

            // Hide form
            function hideForm() {
                addForm.style.display = 'none';
                toggleFormBtn.innerHTML = '<i data-lucide="plus" class="w-4 h-4 mr-2"></i>Add Student Assistant';
                reloadLucideIcons();
                saForm.reset();
                messageDiv.innerHTML = '';
                isEditMode = false;
                currentSAId = null;
                document.getElementById('password').required = true;
            }

            // Handle form submission
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(saForm);
                const data = {
                    name: formData.get('name'),
                    full_name: formData.get('full_name'),
                    student_id_number: formData.get('student_id_number'),
                    email: formData.get('email'),
                    password: formData.get('password') || null,
                    office: formData.get('office'),
                    contact: formData.get('contact') || null,
                    gender: formData.get('gender'),
                    active: formData.get('active') === '1'
                };
                
                try {
                    const url = isEditMode ? `/api/sa-accounts/${currentSAId}` : '/api/sa-accounts';
                    const method = isEditMode ? 'PUT' : 'POST';
                    
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        await loadSAAccounts();
                        hideForm();
                        closeSAPanel();
                        showMessage(result.message || (isEditMode ? 'Student assistant updated successfully!' : 'Student assistant created successfully!'), 'success');
                    } else {
                        const errorMessage = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Failed to save student assistant');
                        showMessage(errorMessage, 'error');
                    }
                } catch (error) {
                    console.error('Error saving student assistant:', error);
                    showMessage('An error occurred while saving student assistant', 'error');
                }
            }

            // View SA
            window.viewSA = function(id) {
                const sa = saData.find(s => s.id === id);
                if (!sa) return;

                currentSAId = id;
                
                document.getElementById('detailName').textContent = sa.name;
                document.getElementById('detailFullName').textContent = sa.full_name;
                document.getElementById('detailStudentId').textContent = sa.student_id_number || '-';
                document.getElementById('detailEmail').textContent = sa.email;
                document.getElementById('detailOffice').textContent = sa.office || '-';
                document.getElementById('detailContact').textContent = sa.contact || '-';
                document.getElementById('detailGender').textContent = sa.gender || '-';
                document.getElementById('detailStatus').innerHTML = `<span class="badge ${sa.active ? 'bg-success' : 'bg-danger'} text-white rounded p-1">${sa.active ? 'Active' : 'Inactive'}</span>`;

                reloadLucideIcons();
                saDetailsPanel.style.display = 'block';
                saDetailsPanel.scrollIntoView({ behavior: 'smooth' });
            };

            // Close panel
            window.closeSAPanel = function() {
                saDetailsPanel.style.display = 'none';
                currentSAId = null;
            };

            // Edit SA
            window.editSA = async function() {
                if (!currentSAId) return;
                
                try {
                    const response = await fetch(`/api/sa-accounts/${currentSAId}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        const sa = result.data;
                        
                        document.getElementById('name').value = sa.name;
                        document.getElementById('full_name').value = sa.full_name;
                        document.getElementById('student_id_number').value = sa.student_id_number || '';
                        document.getElementById('email').value = sa.email;
                        document.getElementById('password').value = '';
                        document.getElementById('password').required = false;
                        document.getElementById('office').value = sa.office || '';
                        document.getElementById('contact').value = sa.contact || '';
                        document.getElementById('gender').value = sa.gender || '';
                        document.getElementById('active').value = sa.active ? '1' : '0';
                        
                        isEditMode = true;
                        showForm();
                        closeSAPanel();
                    } else {
                        showMessage('Failed to load student assistant data', 'error');
                    }
                } catch (error) {
                    console.error('Error loading student assistant:', error);
                    showMessage('An error occurred while loading student assistant data', 'error');
                }
            };

            // Delete SA
            window.deleteSA = async function() {
                if (!currentSAId) return;
                
                if (!confirm('Are you sure you want to delete this student assistant account? This action cannot be undone.')) {
                    return;
                }
                
                try {
                    const response = await fetch(`/api/sa-accounts/${currentSAId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        await loadSAAccounts();
                        closeSAPanel();
                        showMessage(result.message || 'Student assistant deleted successfully!', 'success');
                    } else {
                        showMessage(result.message || 'Failed to delete student assistant', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting student assistant:', error);
                    showMessage('An error occurred while deleting student assistant', 'error');
                }
            };

            // Show message
            function showMessage(message, type) {
                messageDiv.innerHTML = `
                    <div class="alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} flex items-center">
                        <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-4 h-4 mr-2"></i>
                        ${message}
                    </div>
                `;
                
                reloadLucideIcons();
                
                setTimeout(() => {
                    messageDiv.innerHTML = '';
                }, 5000);
            }

            // Initialize
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection

