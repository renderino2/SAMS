@extends('../layout/' . $layout)

@section('subhead')
    <title>Contract Management - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Student Assistant Contract Management
                        </h1>
                        <p class="text-slate-500 mt-2">Manage student assistant contracts, renewals, and document storage.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Total Contracts:</span>
                            <span class="ml-2" id="totalContracts">0</span>
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
                            <div>
                                <label for="statusFilter" class="form-label">Status:</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="Active">Active</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                                Contract Records
                            </h2>
                            <button id="toggleUploadPanel" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Contract
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Office</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="contractList">
                                    <tr>
                                        <td colspan="8" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Upload/Add Contract Panel -->
                <div class="col-span-12 intro-y" id="uploadPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="upload" class="w-5 h-5 mr-2"></i>
                            Upload / Renew Contract
                        </h2>
                        
                        <form id="uploadForm" class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label for="uploadName" class="form-label">Full Name:</label>
                                <input type="text" id="uploadName" name="uploadName" class="form-control" required />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="uploadID" class="form-label">Student ID:</label>
                                <input type="text" id="uploadID" name="uploadID" class="form-control" required />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="uploadOffice" class="form-label">Office:</label>
                                <select id="uploadOffice" name="uploadOffice" class="form-control" required>
                                    <option value="">Select Office</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Library">Library</option>
                                    <option value="Guidance">Guidance</option>
                                    <option value="Clinic">Clinic</option>
                                    <option value="IT">IT</option>
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="contractType" class="form-label">Contract Type:</label>
                                <select id="contractType" name="contractType" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="New">New Contract</option>
                                    <option value="Renewal">Contract Renewal</option>
                                    <option value="Amendment">Contract Amendment</option>
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="startDate" class="form-label">Start Date:</label>
                                <input type="date" id="startDate" name="startDate" class="form-control" required />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="endDate" class="form-label">End Date:</label>
                                <input type="date" id="endDate" name="endDate" class="form-control" required />
                            </div>
                            <div class="col-span-12">
                                <label for="pdfUpload" class="form-label">Upload Contract PDF:</label>
                                <input type="file" id="pdfUpload" name="pdfUpload" class="form-control" accept="application/pdf" />
                                <div class="text-xs text-slate-500 mt-1">Only PDF files are accepted. Maximum file size: 10MB</div>
                            </div>
                            <div class="col-span-12">
                                <label for="notes" class="form-label">Notes (optional):</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Additional notes about the contract..."></textarea>
                            </div>
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                    Save Contract
                                </button>
                                <button type="button" id="cancelUploadBtn" class="btn btn-secondary ml-2">
                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                    Cancel
                                </button>
                            </div>
                        </form>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>

                <!-- Contract Details Panel -->
                <div class="col-span-12 intro-y" id="contractDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Contract Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="font-medium text-slate-600">Student Name:</span>
                                <span id="detailName" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Student ID:</span>
                                <span id="detailStudentId" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Office:</span>
                                <span id="detailOffice" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Contract Type:</span>
                                <span id="detailType" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Start Date:</span>
                                <span id="detailStartDate" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">End Date:</span>
                                <span id="detailEndDate" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Status:</span>
                                <span id="detailStatus" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Uploaded Date:</span>
                                <span id="detailUploadDate" class="text-slate-800"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="font-medium text-slate-600">Notes:</span>
                            <p id="detailNotes" class="text-slate-800 mt-1"></p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-primary" onclick="downloadContract()">
                                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                Download PDF
                            </button>
                            <button class="btn btn-warning" onclick="renewContract()">
                                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                Renew Contract
                            </button>
                            <button class="btn btn-danger" onclick="deleteContract()">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Delete
                            </button>
                            <button class="btn btn-secondary" onclick="closeContractPanel()">
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
            // Sample contract data - in real implementation, this would come from the server
            let contractData = [
                {
                    id: 1,
                    name: "John Doe",
                    studentId: "23-12345",
                    office: "Registrar",
                    type: "New",
                    startDate: "2024-01-15",
                    endDate: "2024-06-15",
                    status: "Active",
                    uploadDate: "2024-01-10",
                    notes: "Initial contract for student assistant position.",
                    pdfPath: "/contracts/john-doe-2024.pdf"
                },
                {
                    id: 2,
                    name: "Jane Smith",
                    studentId: "23-12346",
                    office: "Library",
                    type: "Renewal",
                    startDate: "2024-02-01",
                    endDate: "2024-07-31",
                    status: "Active",
                    uploadDate: "2024-01-25",
                    notes: "Contract renewal for continued service.",
                    pdfPath: "/contracts/jane-smith-2024-renewal.pdf"
                },
                {
                    id: 3,
                    name: "Mike Johnson",
                    studentId: "23-12347",
                    office: "Guidance",
                    type: "New",
                    startDate: "2023-09-01",
                    endDate: "2023-12-31",
                    status: "Expired",
                    uploadDate: "2023-08-25",
                    notes: "Previous semester contract. Expired.",
                    pdfPath: "/contracts/mike-johnson-2023.pdf"
                },
                {
                    id: 4,
                    name: "Sarah Wilson",
                    studentId: "23-12348",
                    office: "Clinic",
                    type: "Amendment",
                    startDate: "2024-03-01",
                    endDate: "2024-08-31",
                    status: "Pending",
                    uploadDate: "2024-02-20",
                    notes: "Contract amendment for extended hours.",
                    pdfPath: "/contracts/sarah-wilson-2024-amendment.pdf"
                },
                {
                    id: 5,
                    name: "David Brown",
                    studentId: "23-12349",
                    office: "IT",
                    type: "New",
                    startDate: "2024-01-20",
                    endDate: "2024-06-20",
                    status: "Active",
                    uploadDate: "2024-01-15",
                    notes: "New contract for IT support position.",
                    pdfPath: "/contracts/david-brown-2024.pdf"
                }
            ];

            let filteredData = [...contractData];
            let currentContractId = null;

            // DOM elements
            const searchInput = document.getElementById('searchInput');
            const officeFilter = document.getElementById('officeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const contractList = document.getElementById('contractList');
            const uploadPanel = document.getElementById('uploadPanel');
            const toggleUploadPanel = document.getElementById('toggleUploadPanel');
            const cancelUploadBtn = document.getElementById('cancelUploadBtn');
            const uploadForm = document.getElementById('uploadForm');
            const contractDetailsPanel = document.getElementById('contractDetailsPanel');
            const totalContracts = document.getElementById('totalContracts');
            const messageDiv = document.getElementById('message');

            // Initialize page
            function init() {
                updateTotalCount();
                renderTable();
                setupEventListeners();
                setDefaultDates();
            }

            // Setup event listeners
            function setupEventListeners() {
                searchInput.addEventListener('input', filterData);
                officeFilter.addEventListener('change', filterData);
                statusFilter.addEventListener('change', filterData);
                toggleUploadPanel.addEventListener('click', toggleUpload);
                cancelUploadBtn.addEventListener('click', hideUpload);
                uploadForm.addEventListener('submit', handleFormSubmit);
                
                // Date validation
                document.getElementById('startDate').addEventListener('change', validateDates);
                document.getElementById('endDate').addEventListener('change', validateDates);
            }

            // Set default dates
            function setDefaultDates() {
                const today = new Date();
                const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
                const sixMonthsLater = new Date(today.getFullYear(), today.getMonth() + 6, today.getDate());
                
                document.getElementById('startDate').value = today.toISOString().split('T')[0];
                document.getElementById('endDate').value = sixMonthsLater.toISOString().split('T')[0];
            }

            // Validate dates
            function validateDates() {
                const startDate = new Date(document.getElementById('startDate').value);
                const endDate = new Date(document.getElementById('endDate').value);
                
                if (endDate <= startDate) {
                    document.getElementById('endDate').setCustomValidity('End date must be after start date');
                } else {
                    document.getElementById('endDate').setCustomValidity('');
                }
            }

            // Filter data based on search and filters
            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedOffice = officeFilter.value;
                const selectedStatus = statusFilter.value;

                filteredData = contractData.filter(contract => {
                    const matchesSearch = !searchTerm || 
                        contract.name.toLowerCase().includes(searchTerm) || 
                        contract.studentId.toLowerCase().includes(searchTerm);
                    
                    const matchesOffice = !selectedOffice || contract.office === selectedOffice;
                    const matchesStatus = !selectedStatus || contract.status === selectedStatus;

                    return matchesSearch && matchesOffice && matchesStatus;
                });

                renderTable();
            }

            // Render the contract table
            function renderTable() {
                if (filteredData.length === 0) {
                    contractList.innerHTML = '<tr><td colspan="8" class="text-center text-slate-500">No contract records found</td></tr>';
                    return;
                }

                contractList.innerHTML = filteredData.map((contract, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${contract.name}</td>
                        <td>${contract.studentId}</td>
                        <td>${contract.office}</td>
                        <td>${formatDate(contract.startDate)}</td>
                        <td>${formatDate(contract.endDate)}</td>
                        <td><span class="badge ${getStatusBadgeClass(contract.status)} text-white rounded p-1">${contract.status}</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="viewContract(${contract.id})">
                              
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
                    case 'Expired': return 'bg-danger text-white';
                    case 'Pending': return 'bg-warning text-white';
                    default: return 'bg-secondary text-white';
                }
            }

            // Format date for display
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Update total count
            function updateTotalCount() {
                totalContracts.textContent = contractData.length;
            }

            // Toggle upload panel visibility
            function toggleUpload() {
                if (uploadPanel.style.display === 'none') {
                    showUpload();
                } else {
                    hideUpload();
                }
            }

            // Show upload panel
            function showUpload() {
                uploadPanel.style.display = 'block';
                toggleUploadPanel.innerHTML = '<i data-lucide="minus" class="w-4 h-4 mr-2"></i>Hide Form';
                uploadPanel.scrollIntoView({ behavior: 'smooth' });
            }

            // Hide upload panel
            function hideUpload() {
                uploadPanel.style.display = 'none';
                toggleUploadPanel.innerHTML = '<i data-lucide="plus" class="w-4 h-4 mr-2"></i>Add Contract';
                uploadForm.reset();
                setDefaultDates();
                messageDiv.innerHTML = '';
            }

            // Handle form submission
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(uploadForm);
                const data = Object.fromEntries(formData.entries());
                
                // Validate file upload
                const fileInput = document.getElementById('pdfUpload');
                if (!fileInput.files[0]) {
                    showMessage('Please upload a contract PDF file', 'error');
                    return;
                }
                
                try {
                    const response = await fetch('/contract-management/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        // Add to local data
                        const newContract = {
                            id: contractData.length + 1,
                            name: data.uploadName,
                            studentId: data.uploadID,
                            office: data.uploadOffice,
                            type: data.contractType,
                            startDate: data.startDate,
                            endDate: data.endDate,
                            status: 'Active',
                            uploadDate: new Date().toISOString().split('T')[0],
                            notes: data.notes || '',
                            pdfPath: `/contracts/${data.uploadID}-${new Date().getFullYear()}.pdf`
                        };
                        contractData.push(newContract);
                        
                        updateTotalCount();
                        filterData();
                        hideUpload();
                        showMessage(result.message || 'Contract uploaded successfully!', 'success');
                    } else {
                        showMessage(result.message || 'Failed to upload contract', 'error');
                    }
                } catch (error) {
                    console.error('Error uploading contract:', error);
                    showMessage('An error occurred while uploading contract', 'error');
                }
            }

            // View contract function (global scope for onclick)
            window.viewContract = function(contractId) {
                const contract = contractData.find(c => c.id === contractId);
                if (!contract) return;

                currentContractId = contractId;
                
                // Populate details
                document.getElementById('detailName').textContent = contract.name;
                document.getElementById('detailStudentId').textContent = contract.studentId;
                document.getElementById('detailOffice').textContent = contract.office;
                document.getElementById('detailType').textContent = contract.type;
                document.getElementById('detailStartDate').textContent = formatDate(contract.startDate);
                document.getElementById('detailEndDate').textContent = formatDate(contract.endDate);
                document.getElementById('detailStatus').textContent = contract.status;
                document.getElementById('detailUploadDate').textContent = formatDate(contract.uploadDate);
                document.getElementById('detailNotes').textContent = contract.notes || 'No notes available';

                // Show panel
                contractDetailsPanel.style.display = 'block';
                contractDetailsPanel.scrollIntoView({ behavior: 'smooth' });
            };

            // Close contract panel function (global scope for onclick)
            window.closeContractPanel = function() {
                contractDetailsPanel.style.display = 'none';
                currentContractId = null;
            };

            // Download contract function (global scope for onclick)
            window.downloadContract = function() {
                if (!currentContractId) return;
                
                const contract = contractData.find(c => c.id === currentContractId);
                if (contract) {
                    // In real implementation, this would download the actual PDF
                    showMessage('Download functionality will be implemented in the next version', 'info');
                }
            };

            // Renew contract function (global scope for onclick)
            window.renewContract = function() {
                if (!currentContractId) return;
                showMessage('Contract renewal functionality will be implemented in the next version', 'info');
            };

            // Delete contract function (global scope for onclick)
            window.deleteContract = function() {
                if (!currentContractId) return;
                
                if (confirm('Are you sure you want to delete this contract?')) {
                    const contractIndex = contractData.findIndex(c => c.id === currentContractId);
                    if (contractIndex !== -1) {
                        contractData.splice(contractIndex, 1);
                        updateTotalCount();
                        filterData();
                        closeContractPanel();
                        showMessage('Contract deleted successfully!', 'success');
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
