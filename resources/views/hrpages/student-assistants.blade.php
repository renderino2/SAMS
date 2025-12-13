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
            // Sample student assistant data - in real implementation, this would come from the server
            let saData = [
                {
                    id: 1,
                    name: "John Doe",
                    studentId: "23-12345",
                    email: "john.doe@student.cjc.edu.ph",
                    contact: "09123456789",
                    office: "Registrar",
                    status: "Active",
                    notes: "Excellent performance in document processing. Reliable and punctual.",
                    dateAdded: "2024-01-15"
                },
                {
                    id: 2,
                    name: "Jane Smith",
                    studentId: "23-12346",
                    email: "jane.smith@student.cjc.edu.ph",
                    contact: "09123456790",
                    office: "Library",
                    status: "Active",
                    notes: "Great with library management systems. Helps students with research.",
                    dateAdded: "2024-01-16"
                },
                {
                    id: 3,
                    name: "Mike Johnson",
                    studentId: "23-12347",
                    email: "mike.johnson@student.cjc.edu.ph",
                    contact: "09123456791",
                    office: "Guidance",
                    status: "Inactive",
                    notes: "On leave for academic reasons. Expected to return next semester.",
                    dateAdded: "2024-01-17"
                },
                {
                    id: 4,
                    name: "Sarah Wilson",
                    studentId: "23-12348",
                    email: "sarah.wilson@student.cjc.edu.ph",
                    contact: "09123456792",
                    office: "Clinic",
                    status: "Pending Assignment",
                    notes: "New student assistant. Awaiting office assignment.",
                    dateAdded: "2024-01-18"
                },
                {
                    id: 5,
                    name: "David Brown",
                    studentId: "23-12349",
                    email: "david.brown@student.cjc.edu.ph",
                    contact: "09123456793",
                    office: "IT",
                    status: "Active",
                    notes: "Technical support specialist. Excellent with computer systems.",
                    dateAdded: "2024-01-19"
                }
            ];

            let filteredData = [...saData];
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

            // Initialize page
            function init() {
                updateTotalCount();
                renderTable();
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

            // Render the student assistant table
            function renderTable() {
                if (filteredData.length === 0) {
                    saTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No student assistants found</td></tr>';
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
                                
                                View
                            </button>
                        </td>
                    </tr>
                `).join('');
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Active': return 'bg-success text-white';
                    case 'Inactive': return 'bg-danger text-white';
                    case 'Pending Assignment': return 'bg-warning text-white';
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
                
                const formData = new FormData(saForm);
                const data = Object.fromEntries(formData.entries());
                
                try {
                    const response = await fetch('/student-assistants/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        // Add to local data
                        const newSA = {
                            id: saData.length + 1,
                            ...data,
                            dateAdded: new Date().toISOString().split('T')[0]
                        };
                        saData.push(newSA);
                        
                        updateTotalCount();
                        filterData();
                        hideForm();
                        showMessage(result.message || 'Student assistant added successfully!', 'success');
                    } else {
                        showMessage(result.message || 'Failed to add student assistant', 'error');
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
            window.deleteSA = function() {
                if (!currentSAId) return;
                
                if (confirm('Are you sure you want to delete this student assistant?')) {
                    const saIndex = saData.findIndex(s => s.id === currentSAId);
                    if (saIndex !== -1) {
                        saData.splice(saIndex, 1);
                        updateTotalCount();
                        filterData();
                        closeSAPanel();
                        showMessage('Student assistant deleted successfully!', 'success');
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
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endsection
