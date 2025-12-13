@extends('../layout/' . $layout)

@section('subhead')
    <title>Office Management - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="building" class="w-5 h-5 mr-2"></i>
                            Office Management
                        </h1>
                        <p class="text-slate-500 mt-2">Manage office records, locations, and contact information.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Total Offices:</span>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="searchInput" class="form-label">Search:</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, code, or location" />
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

                <!-- Offices Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Office Records
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Location</th>
                                        <th>Contact Person</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="officeTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Office Section -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                                Add New Office
                            </h2>
                            <button id="toggleFormBtn" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Office
                            </button>
                        </div>
                        
                        <div class="add-form" id="addForm" style="display: none;">
                            <form id="officeForm" class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label for="name" class="form-label">Office Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="code" class="form-label">Office Code:</label>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="e.g., REG, LIB, HR" />
                                </div>
                                <div class="col-span-12">
                                    <label for="description" class="form-label">Description:</label>
                                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Brief description of the office..."></textarea>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="location" class="form-label">Location:</label>
                                    <input type="text" id="location" name="location" class="form-control" placeholder="Building, Floor, Room Number" />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="contact_person" class="form-label">Contact Person:</label>
                                    <input type="text" id="contact_person" name="contact_person" class="form-control" />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="contact_email" class="form-label">Contact Email:</label>
                                    <input type="email" id="contact_email" name="contact_email" class="form-control" />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="contact_phone" class="form-label">Contact Phone:</label>
                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="is_active" class="form-label">Status:</label>
                                    <select id="is_active" name="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-span-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                        Save Office
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

                <!-- Office Details Panel -->
                <div class="col-span-12 intro-y" id="officeDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="building" class="w-5 h-5 mr-2"></i>
                            Office Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="font-medium text-slate-600">Name:</span>
                                <span id="detailName" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Code:</span>
                                <span id="detailCode" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Location:</span>
                                <span id="detailLocation" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Contact Person:</span>
                                <span id="detailContactPerson" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Contact Email:</span>
                                <span id="detailContactEmail" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Contact Phone:</span>
                                <span id="detailContactPhone" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Status:</span>
                                <span id="detailStatus" class="text-slate-800"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="font-medium text-slate-600">Description:</span>
                            <p id="detailDescription" class="text-slate-800 mt-1"></p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-primary" onclick="editOffice()">
                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteOffice()">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Delete
                            </button>
                            <button class="btn btn-secondary" onclick="closeOfficePanel()">
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
            let officesData = [];
            let filteredData = [];
            let currentOfficeId = null;
            let isEditMode = false;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const officeTableBody = document.getElementById('officeTableBody');
            const addForm = document.getElementById('addForm');
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            const officeForm = document.getElementById('officeForm');
            const officeDetailsPanel = document.getElementById('officeDetailsPanel');
            const totalCount = document.getElementById('totalCount');
            const messageDiv = document.getElementById('message');

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
                await loadOffices();
                setupEventListeners();
            }

            // Load offices from API
            async function loadOffices() {
                try {
                    const response = await fetch('/api/offices');
                    const result = await response.json();
                    
                    if (result.success) {
                        officesData = result.data;
                        filteredData = [...officesData];
                        updateTotalCount();
                        renderTable();
                    } else {
                        showMessage('Failed to load offices', 'error');
                    }
                } catch (error) {
                    console.error('Error loading offices:', error);
                    showMessage('An error occurred while loading offices', 'error');
                    officeTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading offices</td></tr>';
                }
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                statusFilter.addEventListener('change', filterData);
                toggleFormBtn.addEventListener('click', toggleForm);
                cancelFormBtn.addEventListener('click', hideForm);
                officeForm.addEventListener('submit', handleFormSubmit);
            }

            // Filter data based on search and filters
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedStatus = statusFilter.value;

                filteredData = officesData.filter(office => {
                    const matchesSearch = !searchTerm || 
                        office.name.toLowerCase().includes(searchTerm) || 
                        (office.code && office.code.toLowerCase().includes(searchTerm)) ||
                        (office.location && office.location.toLowerCase().includes(searchTerm));
                    
                    const matchesStatus = !selectedStatus || 
                        (selectedStatus === 'active' && office.is_active) ||
                        (selectedStatus === 'inactive' && !office.is_active);

                    return matchesSearch && matchesStatus;
                });

                renderTable();
            }

            // Render the office table
            function renderTable() {
                if (filteredData.length === 0) {
                    officeTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No offices found</td></tr>';
                    return;
                }

                officeTableBody.innerHTML = filteredData.map((office, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${office.name}</td>
                        <td>${office.code || '-'}</td>
                        <td>${office.location || '-'}</td>
                        <td>${office.contact_person || '-'}</td>
                        <td>
                            <span class="badge ${office.is_active ? 'bg-success' : 'bg-danger'} text-white rounded p-1 whitespace-nowrap">
                                ${office.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="viewOffice(${office.id})">
                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                View
                            </button>
                        </td>
                    </tr>
                `).join('');
                
                // Re-initialize Lucide icons after rendering table
                reloadLucideIcons();
            }

            // Update total count
            function updateTotalCount() {
                totalCount.textContent = officesData.length;
            }

            // Toggle form visibility
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
                toggleFormBtn.innerHTML = '<i data-lucide="plus" class="w-4 h-4 mr-2"></i>Add Office';
                reloadLucideIcons();
                officeForm.reset();
                messageDiv.innerHTML = '';
                isEditMode = false;
                currentOfficeId = null;
            }

            // Handle form submission
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(officeForm);
                const data = {
                    name: formData.get('name'),
                    code: formData.get('code') || null,
                    description: formData.get('description') || null,
                    location: formData.get('location') || null,
                    contact_person: formData.get('contact_person') || null,
                    contact_email: formData.get('contact_email') || null,
                    contact_phone: formData.get('contact_phone') || null,
                    is_active: formData.get('is_active') === '1'
                };
                
                try {
                    const url = isEditMode ? `/api/offices/${currentOfficeId}` : '/api/offices';
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
                        await loadOffices();
                        hideForm();
                        closeOfficePanel();
                        showMessage(result.message || (isEditMode ? 'Office updated successfully!' : 'Office created successfully!'), 'success');
                    } else {
                        const errorMessage = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Failed to save office');
                        showMessage(errorMessage, 'error');
                    }
                } catch (error) {
                    console.error('Error saving office:', error);
                    showMessage('An error occurred while saving office', 'error');
                }
            }

            // View office function (global scope for onclick)
            window.viewOffice = function(officeId) {
                const office = officesData.find(o => o.id === officeId);
                if (!office) return;

                currentOfficeId = officeId;
                
                // Populate details
                document.getElementById('detailName').textContent = office.name;
                document.getElementById('detailCode').textContent = office.code || '-';
                document.getElementById('detailLocation').textContent = office.location || '-';
                document.getElementById('detailContactPerson').textContent = office.contact_person || '-';
                document.getElementById('detailContactEmail').textContent = office.contact_email || '-';
                document.getElementById('detailContactPhone').textContent = office.contact_phone || '-';
                document.getElementById('detailStatus').innerHTML = `<span class="badge ${office.is_active ? 'bg-success' : 'bg-danger'} text-white rounded p-1">${office.is_active ? 'Active' : 'Inactive'}</span>`;
                document.getElementById('detailDescription').textContent = office.description || 'No description available';

                // Re-initialize Lucide icons after populating details
                reloadLucideIcons();

                // Show panel
                officeDetailsPanel.style.display = 'block';
                officeDetailsPanel.scrollIntoView({ behavior: 'smooth' });
            };

            // Close office panel function (global scope for onclick)
            window.closeOfficePanel = function() {
                officeDetailsPanel.style.display = 'none';
                currentOfficeId = null;
            };

            // Edit office function (global scope for onclick)
            window.editOffice = async function() {
                if (!currentOfficeId) return;
                
                try {
                    const response = await fetch(`/api/offices/${currentOfficeId}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        const office = result.data;
                        
                        // Populate form
                        document.getElementById('name').value = office.name;
                        document.getElementById('code').value = office.code || '';
                        document.getElementById('description').value = office.description || '';
                        document.getElementById('location').value = office.location || '';
                        document.getElementById('contact_person').value = office.contact_person || '';
                        document.getElementById('contact_email').value = office.contact_email || '';
                        document.getElementById('contact_phone').value = office.contact_phone || '';
                        document.getElementById('is_active').value = office.is_active ? '1' : '0';
                        
                        isEditMode = true;
                        showForm();
                        closeOfficePanel();
                    } else {
                        showMessage('Failed to load office data', 'error');
                    }
                } catch (error) {
                    console.error('Error loading office:', error);
                    showMessage('An error occurred while loading office data', 'error');
                }
            };

            // Delete office function (global scope for onclick)
            window.deleteOffice = async function() {
                if (!currentOfficeId) return;
                
                if (!confirm('Are you sure you want to delete this office? This action cannot be undone.')) {
                    return;
                }
                
                try {
                    const response = await fetch(`/api/offices/${currentOfficeId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        await loadOffices();
                        closeOfficePanel();
                        showMessage(result.message || 'Office deleted successfully!', 'success');
                    } else {
                        showMessage(result.message || 'Failed to delete office', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting office:', error);
                    showMessage('An error occurred while deleting office', 'error');
                }
            };

            // Show message function
            function showMessage(message, type) {
                messageDiv.innerHTML = `
                    <div class="alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} flex items-center">
                        <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-4 h-4 mr-2"></i>
                        ${message}
                    </div>
                `;
                
                // Re-initialize Lucide icons after showing message
                reloadLucideIcons();
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.innerHTML = '';
                }, 5000);
            }

            // Initialize when DOM is loaded
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection

