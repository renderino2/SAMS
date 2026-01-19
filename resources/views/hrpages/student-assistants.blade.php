@extends('../layout/' . $layout)

@section('subhead')
    <title>Student Assistant Management - SAMS</title>
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
                            Student Assistant Management
                        </h1>
                        <p class="text-slate-500 mt-2">Manage student assistant records, assignments, and service records.</p>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="searchInput" class="form-label">Search:</label>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or ID" />
                            </div>
                            <div>
                                <label for="officeFilter" class="form-label">Office:</label>
                                <select id="officeFilter" class="form-control">
                                    <option value="">All Offices</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Library">Library</option>
                                    <option value="Guidance">Guidance</option>
                                    <option value="Clinic">Clinic</option>
                                    <option value="IT">IT</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Assistant Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Student Assistant Records
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Assigned Office</th>
                                        <th>Status</th>
                                        <th>Service Record</th>
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

                <!-- Add Student Assistant Section -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                                Add Student Assistant
                            </h2>
                            <button id="toggleFormBtn" class="btn btn-primary">
                                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                Add Student Assistant
                            </button>
                        </div>
                        
                        <div class="add-form" id="addForm" style="display: none;">
                            <form id="saForm" class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label for="fullName" class="form-label">Full Name:</label>
                                    <input type="text" id="fullName" name="fullName" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="studentId" class="form-label">Student ID:</label>
                                    <input type="text" id="studentId" name="studentId" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" id="email" name="email" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="contact" class="form-label">Contact Number:</label>
                                    <input type="text" id="contact" name="contact" class="form-control" required />
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="office" class="form-label">Assign Office:</label>
                                    <select id="office" name="office" class="form-control" required>
                                        <option value="">Select Office</option>
                                        <option value="Registrar">Registrar</option>
                                        <option value="Library">Library</option>
                                        <option value="Guidance">Guidance</option>
                                        <option value="Clinic">Clinic</option>
                                        <option value="IT">IT</option>
                                    </select>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="status" class="form-label">Status:</label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="Apprentice">Apprentice</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Pending Assignment">Pending Assignment</option>
                                    </select>
                                </div>
                                <div class="col-span-12">
                                    <label for="notes" class="form-label">Service Record Notes:</label>
                                    <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Optional notes about the student assistant..."></textarea>
                                </div>
                                <div class="col-span-12">
                                    <button type="submit" class="btn btn-primary ">
                                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                        Add Student Assistant
                                    </button>
                                    <button type="button" id="cancelFormBtn" class="btn btn-secondary ml-2">
                                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>

                <!-- Student Assistant Details Panel -->
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
                                <span class="font-medium text-slate-600">Student ID:</span>
                                <span id="detailStudentId" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Email:</span>
                                <span id="detailEmail" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Contact:</span>
                                <span id="detailContact" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Office:</span>
                                <span id="detailOffice" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Status:</span>
                                <span id="detailStatus" class="text-slate-800"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="font-medium text-slate-600">Service Record Notes:</span>
                            <p id="detailNotes" class="text-slate-800 mt-1"></p>
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
            // Student assistant data loaded from API
            let saData = [];
            let filteredData = [];
            let currentSAId = null;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const officeFilter = document.getElementById('officeFilter');
            const saTableBody = document.getElementById('saTableBody');
            const addForm = document.getElementById('addForm');
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            const saForm = document.getElementById('saForm');
            const saDetailsPanel = document.getElementById('saDetailsPanel');
            const totalCount = document.getElementById('totalCount');
            const messageDiv = document.getElementById('message');

            // Load student assistants from API
            async function loadStudentAssistants() {
                try {
                    const response = await fetch('/api/sa-accounts', {
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
                    
                    if (result.success && result.data) {
                        saData = result.data.map(sa => ({
                            id: sa.id,
                            name: sa.full_name || sa.name,
                            studentId: sa.student_id_number,
                            email: sa.email,
                            contact: sa.contact || '—',
                            office: sa.office || '—',
                            status: sa.status || 'Pending Assignment',
                            notes: sa.service_notes || '',
                            dateAdded: sa.created_at ? new Date(sa.created_at).toISOString().split('T')[0] : ''
                        }));
                        filteredData = [...saData];
                        updateTotalCount();
                        renderTable();
                    } else {
                        saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">' + (result.message || 'No student assistants found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading student assistants:', error);
                    saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading student assistants</td></tr>';
                }
            }

            // Initialize page
            async function init() {
                await loadStudentAssistants();
                setupEventListeners();
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                officeFilter.addEventListener('change', filterData);
                toggleFormBtn.addEventListener('click', toggleForm);
                cancelFormBtn.addEventListener('click', hideForm);
                saForm.addEventListener('submit', handleFormSubmit);
            }

            // Filter data based on search and filters
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedOffice = officeFilter.value;

                filteredData = saData.filter(sa => {
                    const matchesSearch = !searchTerm || 
                        sa.name.toLowerCase().includes(searchTerm) || 
                        sa.studentId.toLowerCase().includes(searchTerm);
                    
                    const matchesOffice = !selectedOffice || sa.office === selectedOffice;

                    return matchesSearch && matchesOffice;
                });

                renderTable();
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

            // Render the student assistant table
            function renderTable() {
                if (filteredData.length === 0) {
                    saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No student assistants found</td></tr>';
                    reloadLucideIcons();
                    return;
                }

                saTableBody.innerHTML = filteredData.map((sa, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${sa.name}</td>
                        <td>${sa.studentId}</td>
                        <td>${sa.office}</td>
                        <td><span class="badge ${getStatusBadgeClass(sa.status)} text-white rounded p-1 whitespace-nowrap">${sa.status}</span></td>
                        <td class="max-w-xs truncate" title="${sa.notes}">${sa.notes || 'No notes'}</td>
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

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Active': return 'bg-success text-white';
                    case 'Inactive': return 'bg-danger text-white';
                    case 'Pending Assignment': return 'bg-warning text-white';
                    case 'Apprentice': return 'bg-primary text-white';
                    default: return 'bg-secondary text-white';
                }
            }

            // Update total count
            function updateTotalCount() {
                totalCount.textContent = saData.length;
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
                toggleFormBtn.innerHTML = 'Hide Form';
                addForm.scrollIntoView({ behavior: 'smooth' });
            }

            // Hide form
            function hideForm() {
                addForm.style.display = 'none';
                toggleFormBtn.innerHTML = 'Add Student Assistant';
                saForm.reset();
                messageDiv.innerHTML = '';
            }

            // Handle form submission
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = {
                    fullName: document.getElementById('fullName').value,
                    studentId: document.getElementById('studentId').value,
                    email: document.getElementById('email').value,
                    contact: document.getElementById('contact').value,
                    office: document.getElementById('office').value,
                    status: document.getElementById('status').value,
                    notes: document.getElementById('notes').value
                };
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch('/student-assistants/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        // Reload data from server
                        await loadStudentAssistants();
                        hideForm();
                        showMessage(result.message || 'Student assistant added successfully!', 'success');
                    } else {
                        const errorMsg = result.message || result.error || 'Failed to add student assistant';
                        showMessage(errorMsg, 'error');
                    }
                } catch (error) {
                    console.error('Error adding student assistant:', error);
                    showMessage('An error occurred while adding student assistant', 'error');
                }
            }

            // View student assistant function (global scope for onclick)
            window.viewSA = function(saId) {
                const sa = saData.find(s => s.id === saId);
                if (!sa) return;

                currentSAId = saId;
                
                // Populate details
                document.getElementById('detailName').textContent = sa.name;
                document.getElementById('detailStudentId').textContent = sa.studentId;
                document.getElementById('detailEmail').textContent = sa.email;
                document.getElementById('detailContact').textContent = sa.contact;
                document.getElementById('detailOffice').textContent = sa.office;
                document.getElementById('detailStatus').textContent = sa.status;
                document.getElementById('detailNotes').textContent = sa.notes || 'No notes available';

                // Show panel
                saDetailsPanel.style.display = 'block';
                saDetailsPanel.scrollIntoView({ behavior: 'smooth' });
            };

            // Close SA panel function (global scope for onclick)
            window.closeSAPanel = function() {
                saDetailsPanel.style.display = 'none';
                currentSAId = null;
            };

            // Edit SA function (global scope for onclick)
            window.editSA = function() {
                if (!currentSAId) return;
                showMessage('Edit functionality will be implemented in the next version', 'info');
            };

            // Delete SA function (global scope for onclick)
            window.deleteSA = async function() {
                if (!currentSAId) return;
                
                if (confirm('Are you sure you want to delete this student assistant?')) {
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        
                        const response = await fetch(`/api/sa-accounts/${currentSAId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok && result.success) {
                            await loadStudentAssistants();
                            closeSAPanel();
                            showMessage(result.message || 'Student assistant deleted successfully!', 'success');
                        } else {
                            showMessage(result.message || 'Failed to delete student assistant', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting student assistant:', error);
                        showMessage('An error occurred while deleting student assistant', 'error');
                    }
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
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.innerHTML = '';
                }, 5000);
            }

            // Initialize when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                init();
                reloadLucideIcons();
            });
        })();
    </script>
@endsection
