@extends('../layout/' . $layout)

@section('subhead')
    <title>Office Head Account Management - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="user-cog" class="w-5 h-5 mr-2"></i>
                            Office Head Account Management
                        </h1>
                        <p class="text-slate-500 mt-2">Manage office head accounts, profiles, and access permissions.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Total Office Heads:</span>
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
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or email" />
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

                <!-- Office Heads Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Office Head Accounts
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Office</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="officeHeadTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Office Head Section -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                                Add New Office Head
                            </h2>
                            <button id="toggleFormBtn" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Office Head
                            </button>
                        </div>
                        
                        <div class="add-form" id="addForm" style="display: none;">
                            <form id="officeHeadForm" class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label for="name" class="form-label">Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="full_name" class="form-label">Full Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="full_name" name="full_name" class="form-control" required />
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
                                        Save Office Head
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

                <!-- Office Head Details Panel -->
                <div class="col-span-12 intro-y" id="officeHeadDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                            Office Head Details
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
                            <button class="btn btn-primary" onclick="editOfficeHead()">
                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteOfficeHead()">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Delete
                            </button>
                            <button class="btn btn-secondary" onclick="closeOfficeHeadPanel()">
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
            let officeHeadsData = [];
            let filteredData = [];
            let officesData = [];
            let currentOfficeHeadId = null;
            let isEditMode = false;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const officeFilter = document.getElementById('officeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const officeHeadTableBody = document.getElementById('officeHeadTableBody');
            const addForm = document.getElementById('addForm');
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            const officeHeadForm = document.getElementById('officeHeadForm');
            const officeHeadDetailsPanel = document.getElementById('officeHeadDetailsPanel');
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
                await Promise.all([loadOffices(), loadOfficeHeads()]);
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

            // Load office heads from API
            async function loadOfficeHeads() {
                try {
                    const response = await fetch('/api/officeheads');
                    const result = await response.json();
                    
                    if (result.success) {
                        officeHeadsData = result.data;
                        filteredData = [...officeHeadsData];
                        updateTotalCount();
                        renderTable();
                    } else {
                        showMessage('Failed to load office heads', 'error');
                    }
                } catch (error) {
                    console.error('Error loading office heads:', error);
                    showMessage('An error occurred while loading office heads', 'error');
                    officeHeadTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading office heads</td></tr>';
                }
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                officeFilter.addEventListener('change', filterData);
                statusFilter.addEventListener('change', filterData);
                toggleFormBtn.addEventListener('click', toggleForm);
                cancelFormBtn.addEventListener('click', hideForm);
                officeHeadForm.addEventListener('submit', handleFormSubmit);
            }

            // Filter data
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedOffice = officeFilter.value;
                const selectedStatus = statusFilter.value;

                filteredData = officeHeadsData.filter(oh => {
                    const matchesSearch = !searchTerm || 
                        oh.name.toLowerCase().includes(searchTerm) || 
                        oh.email.toLowerCase().includes(searchTerm);
                    
                    const matchesOffice = !selectedOffice || oh.office === selectedOffice;
                    
                    const matchesStatus = !selectedStatus || 
                        (selectedStatus === 'active' && oh.active) ||
                        (selectedStatus === 'inactive' && !oh.active);

                    return matchesSearch && matchesOffice && matchesStatus;
                });

                renderTable();
            }

            // Render table
            function renderTable() {
                if (filteredData.length === 0) {
                    officeHeadTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No office heads found</td></tr>';
                    return;
                }

                officeHeadTableBody.innerHTML = filteredData.map((oh, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${oh.name}</td>
                        <td>${oh.email}</td>
                        <td>${oh.office || '-'}</td>
                        <td>${oh.contact || '-'}</td>
                        <td>
                            <span class="badge ${oh.active ? 'bg-success' : 'bg-danger'} text-white rounded p-1 whitespace-nowrap">
                                ${oh.active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="viewOfficeHead(${oh.id})">
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
                totalCount.textContent = officeHeadsData.length;
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
                toggleFormBtn.innerHTML = '<i data-lucide="plus" class="w-4 h-4 mr-2"></i>Add Office Head';
                reloadLucideIcons();
                officeHeadForm.reset();
                messageDiv.innerHTML = '';
                isEditMode = false;
                currentOfficeHeadId = null;
                document.getElementById('password').required = true;
            }

            // Handle form submission
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(officeHeadForm);
                const data = {
                    name: formData.get('name'),
                    full_name: formData.get('full_name'),
                    email: formData.get('email'),
                    password: formData.get('password') || null,
                    office: formData.get('office'),
                    contact: formData.get('contact') || null,
                    gender: formData.get('gender'),
                    active: formData.get('active') === '1'
                };
                
                try {
                    const url = isEditMode ? `/api/officeheads/${currentOfficeHeadId}` : '/api/officeheads';
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
                        await loadOfficeHeads();
                        hideForm();
                        closeOfficeHeadPanel();
                        showMessage(result.message || (isEditMode ? 'Office head updated successfully!' : 'Office head created successfully!'), 'success');
                    } else {
                        const errorMessage = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Failed to save office head');
                        showMessage(errorMessage, 'error');
                    }
                } catch (error) {
                    console.error('Error saving office head:', error);
                    showMessage('An error occurred while saving office head', 'error');
                }
            }

            // View office head
            window.viewOfficeHead = function(id) {
                const oh = officeHeadsData.find(o => o.id === id);
                if (!oh) return;

                currentOfficeHeadId = id;
                
                document.getElementById('detailName').textContent = oh.name;
                document.getElementById('detailFullName').textContent = oh.full_name;
                document.getElementById('detailEmail').textContent = oh.email;
                document.getElementById('detailOffice').textContent = oh.office || '-';
                document.getElementById('detailContact').textContent = oh.contact || '-';
                document.getElementById('detailGender').textContent = oh.gender || '-';
                document.getElementById('detailStatus').innerHTML = `<span class="badge ${oh.active ? 'bg-success' : 'bg-danger'} text-white rounded p-1">${oh.active ? 'Active' : 'Inactive'}</span>`;

                reloadLucideIcons();
                officeHeadDetailsPanel.style.display = 'block';
                officeHeadDetailsPanel.scrollIntoView({ behavior: 'smooth' });
            };

            // Close panel
            window.closeOfficeHeadPanel = function() {
                officeHeadDetailsPanel.style.display = 'none';
                currentOfficeHeadId = null;
            };

            // Edit office head
            window.editOfficeHead = async function() {
                if (!currentOfficeHeadId) return;
                
                try {
                    const response = await fetch(`/api/officeheads/${currentOfficeHeadId}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        const oh = result.data;
                        
                        document.getElementById('name').value = oh.name;
                        document.getElementById('full_name').value = oh.full_name;
                        document.getElementById('email').value = oh.email;
                        document.getElementById('password').value = '';
                        document.getElementById('password').required = false;
                        document.getElementById('office').value = oh.office || '';
                        document.getElementById('contact').value = oh.contact || '';
                        document.getElementById('gender').value = oh.gender || '';
                        document.getElementById('active').value = oh.active ? '1' : '0';
                        
                        isEditMode = true;
                        showForm();
                        closeOfficeHeadPanel();
                    } else {
                        showMessage('Failed to load office head data', 'error');
                    }
                } catch (error) {
                    console.error('Error loading office head:', error);
                    showMessage('An error occurred while loading office head data', 'error');
                }
            };

            // Delete office head
            window.deleteOfficeHead = async function() {
                if (!currentOfficeHeadId) return;
                
                if (!confirm('Are you sure you want to delete this office head account? This action cannot be undone.')) {
                    return;
                }
                
                try {
                    const response = await fetch(`/api/officeheads/${currentOfficeHeadId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        await loadOfficeHeads();
                        closeOfficeHeadPanel();
                        showMessage(result.message || 'Office head deleted successfully!', 'success');
                    } else {
                        showMessage(result.message || 'Failed to delete office head', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting office head:', error);
                    showMessage('An error occurred while deleting office head', 'error');
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

