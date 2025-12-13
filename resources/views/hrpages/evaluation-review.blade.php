@extends('../layout/' . $layout)

@section('subhead')
    <title>Evaluation Review - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="clipboard-check" class="w-5 h-5 mr-2"></i>
                            Evaluation Review
                        </h1>
                        <p class="text-slate-500 mt-2">Review and manage student assistant performance evaluations submitted by office heads.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Total Evaluations:</span>
                            <span class="ml-2" id="totalEvaluations">0</span>
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
                                <label for="searchName" class="form-label">Search:</label>
                                <input type="text" id="searchName" class="form-control" placeholder="Search by name or ID" />
                            </div>
                            <div>
                                <label for="filterOffice" class="form-label">Office:</label>
                                <select id="filterOffice" class="form-control">
                                    <option value="">All Offices</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Library">Library</option>
                                    <option value="Guidance">Guidance</option>
                                    <option value="Clinic">Clinic</option>
                                    <option value="IT">IT</option>
                                </select>
                            </div>
                            <div>
                                <label for="filterStatus" class="form-label">Status:</label>
                                <select id="filterStatus" class="form-control">
                                    <option value="">Status: All</option>
                                    <option value="Reviewed">Reviewed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Review Table -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                            Performance Evaluations
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student Name</th>
                                        <th>Office</th>
                                        <th>Rated By</th>
                                        <th>Average Rating</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="evaluationTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-slate-500">Loading evaluations...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Details Panel -->
                <div class="col-span-12 intro-y" id="evaluationDetailsPanel" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="clipboard-check" class="w-5 h-5 mr-2"></i>
                            Evaluation Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="font-medium text-slate-600">Student Name:</span>
                                <span id="detailStudentName" class="text-slate-800"></span>
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
                                <span class="font-medium text-slate-600">Rated By:</span>
                                <span id="detailRatedBy" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Evaluation Date:</span>
                                <span id="detailDate" class="text-slate-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Overall Rating:</span>
                                <span id="detailOverallRating" class="text-slate-800"></span>
                            </div>
                        </div>
                        
                        <!-- Performance Criteria -->
                        <div class="mb-4">
                            <h3 class="text-md font-medium mb-3">Performance Criteria</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="criteriaDetails">
                                <!-- Criteria will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <!-- Comments -->
                        <div class="mb-4">
                            <span class="font-medium text-slate-600">Comments:</span>
                            <p id="detailComments" class="text-slate-800 mt-1"></p>
                        </div>
                        
                        <!-- HR Review Section -->
                        <div class="mb-4">
                            <h3 class="text-md font-medium mb-3">HR Review</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hrStatus" class="form-label">Review Status:</label>
                                    <select id="hrStatus" class="form-control">
                                        <option value="Pending">Pending</option>
                                        <option value="Reviewed">Reviewed</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="hrRating" class="form-label">HR Rating:</label>
                                    <select id="hrRating" class="form-control">
                                        <option value="">Select Rating</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Satisfactory">Satisfactory</option>
                                        <option value="Needs Improvement">Needs Improvement</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="hrComments" class="form-label">HR Comments:</label>
                                <textarea id="hrComments" class="form-control" rows="3" placeholder="Enter HR review comments..."></textarea>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button class="btn btn-success" onclick="approveEvaluation()">
                                <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                Approve
                            </button>
                            <button class="btn btn-warning" onclick="saveReview()">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                Save Review
                            </button>
                            <button class="btn btn-danger" onclick="rejectEvaluation()">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Reject
                            </button>
                            <button class="btn btn-secondary" onclick="closeEvaluationPanel()">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Close
                            </button>
                        </div>
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
            // Sample evaluation data - in real implementation, this would come from the server
            let evaluationData = [
                {
                    id: 1,
                    date: "2024-01-15",
                    studentName: "John Doe",
                    studentId: "23-12345",
                    office: "Registrar",
                    ratedBy: "Dr. Maria Santos",
                    averageRating: 4.2,
                    status: "Pending",
                    criteria: {
                        "Work Quality": 4,
                        "Punctuality": 5,
                        "Communication": 4,
                        "Teamwork": 4,
                        "Initiative": 3,
                        "Problem Solving": 4,
                        "Professionalism": 5,
                        "Adaptability": 4,
                        "Time Management": 4,
                        "Overall Performance": 4
                    },
                    comments: "John demonstrates excellent work quality and punctuality. He shows good communication skills and works well with the team. Areas for improvement include taking more initiative in problem-solving tasks.",
                    hrStatus: "Pending",
                    hrRating: "",
                    hrComments: ""
                },
                {
                    id: 2,
                    date: "2024-01-16",
                    studentName: "Jane Smith",
                    studentId: "23-12346",
                    office: "Library",
                    ratedBy: "Ms. Lisa Garcia",
                    averageRating: 4.8,
                    status: "Reviewed",
                    criteria: {
                        "Work Quality": 5,
                        "Punctuality": 5,
                        "Communication": 5,
                        "Teamwork": 4,
                        "Initiative": 5,
                        "Problem Solving": 5,
                        "Professionalism": 5,
                        "Adaptability": 4,
                        "Time Management": 5,
                        "Overall Performance": 5
                    },
                    comments: "Jane is an outstanding student assistant. She consistently delivers high-quality work, is always punctual, and demonstrates excellent communication skills. She takes initiative and solves problems effectively.",
                    hrStatus: "Reviewed",
                    hrRating: "Excellent",
                    hrComments: "Approved for contract renewal. Excellent performance across all criteria."
                },
                {
                    id: 3,
                    date: "2024-01-17",
                    studentName: "Mike Johnson",
                    studentId: "23-12347",
                    office: "Guidance",
                    ratedBy: "Mr. Robert Lee",
                    averageRating: 3.1,
                    status: "Pending",
                    criteria: {
                        "Work Quality": 3,
                        "Punctuality": 2,
                        "Communication": 3,
                        "Teamwork": 3,
                        "Initiative": 3,
                        "Problem Solving": 3,
                        "Professionalism": 4,
                        "Adaptability": 3,
                        "Time Management": 2,
                        "Overall Performance": 3
                    },
                    comments: "Mike shows potential but needs improvement in punctuality and time management. His work quality is satisfactory but could be enhanced with better focus and attention to detail.",
                    hrStatus: "Pending",
                    hrRating: "",
                    hrComments: ""
                },
                {
                    id: 4,
                    date: "2024-01-18",
                    studentName: "Sarah Wilson",
                    studentId: "23-12348",
                    office: "Clinic",
                    ratedBy: "Dr. David Brown",
                    averageRating: 4.5,
                    status: "Reviewed",
                    criteria: {
                        "Work Quality": 5,
                        "Punctuality": 4,
                        "Communication": 4,
                        "Teamwork": 5,
                        "Initiative": 4,
                        "Problem Solving": 4,
                        "Professionalism": 5,
                        "Adaptability": 5,
                        "Time Management": 4,
                        "Overall Performance": 4
                    },
                    comments: "Sarah is a reliable and professional student assistant. She works well with the medical staff and handles sensitive information appropriately. Minor improvement needed in time management.",
                    hrStatus: "Reviewed",
                    hrRating: "Good",
                    hrComments: "Approved with recommendation for time management training."
                },
                {
                    id: 5,
                    date: "2024-01-19",
                    studentName: "David Brown",
                    studentId: "23-12349",
                    office: "IT",
                    ratedBy: "Mr. Alex Chen",
                    averageRating: 4.7,
                    status: "Pending",
                    criteria: {
                        "Work Quality": 5,
                        "Punctuality": 5,
                        "Communication": 4,
                        "Teamwork": 5,
                        "Initiative": 5,
                        "Problem Solving": 5,
                        "Professionalism": 4,
                        "Adaptability": 5,
                        "Time Management": 4,
                        "Overall Performance": 5
                    },
                    comments: "David is an exceptional student assistant with strong technical skills. He takes initiative in solving IT problems and works well with the team. Highly recommended for continued employment.",
                    hrStatus: "Pending",
                    hrRating: "",
                    hrComments: ""
                }
            ];

            let filteredData = [...evaluationData];
            let currentEvaluationId = null;

            // DOM elements
            const searchName = document.getElementById('searchName');
            const filterOffice = document.getElementById('filterOffice');
            const filterStatus = document.getElementById('filterStatus');
            const evaluationTableBody = document.getElementById('evaluationTableBody');
            const evaluationDetailsPanel = document.getElementById('evaluationDetailsPanel');
            const totalEvaluations = document.getElementById('totalEvaluations');
            const messageDiv = document.getElementById('message');

            // Initialize page
            function init() {
                updateTotalCount();
                renderTable();
                setupEventListeners();
            }

            // Setup event listeners
            function setupEventListeners() {
                searchName.addEventListener('input', filterData);
                filterOffice.addEventListener('change', filterData);
                filterStatus.addEventListener('change', filterData);
            }

            // Filter data based on search and filters
            function filterData() {
                const searchTerm = searchName.value.toLowerCase();
                const selectedOffice = filterOffice.value;
                const selectedStatus = filterStatus.value;

                filteredData = evaluationData.filter(evaluation => {
                    const matchesSearch = !searchTerm || 
                        evaluation.studentName.toLowerCase().includes(searchTerm) || 
                        evaluation.studentId.toLowerCase().includes(searchTerm);
                    
                    const matchesOffice = !selectedOffice || evaluation.office === selectedOffice;
                    const matchesStatus = !selectedStatus || evaluation.status === selectedStatus;

                    return matchesSearch && matchesOffice && matchesStatus;
                });

                renderTable();
            }

            // Render the evaluation table
            function renderTable() {
                if (filteredData.length === 0) {
                    evaluationTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">No evaluation records found</td></tr>';
                    return;
                }

                evaluationTableBody.innerHTML = filteredData.map((evaluation, index) => `
                    <tr>
                        <td>${formatDate(evaluation.date)}</td>
                        <td>${evaluation.studentName}</td>
                        <td>${evaluation.office}</td>
                        <td>${evaluation.ratedBy}</td>
                        <td>
                            <span class="badge ${getRatingBadgeClass(evaluation.averageRating)} text-white rounded p-1">
                                ${evaluation.averageRating.toFixed(1)}
                            </span>
                        </td>
                        <td><span class="badge ${getStatusBadgeClass(evaluation.status)} text-white rounded p-1">${evaluation.status}</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white" onclick="viewEvaluation(${evaluation.id})">
                               
                                View
                            </button>
                        </td>
                    </tr>
                `).join('');
            }

            // Get rating badge class
            function getRatingBadgeClass(rating) {
                if (rating >= 4.5) return 'bg-success text-white';
                if (rating >= 3.5) return 'bg-warning text-white';
                if (rating >= 2.5) return 'bg-pending text-white';
                return 'bg-danger text-white';
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Reviewed': return 'bg-success text-white';
                    case 'Pending': return 'bg-warning text-white';
                    case 'Approved': return 'bg-blue-500 text-white';
                    case 'Rejected': return 'bg-danger text-white';
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
                totalEvaluations.textContent = evaluationData.length;
            }

            // View evaluation function (global scope for onclick)
            window.viewEvaluation = function(evaluationId) {
                const evaluation = evaluationData.find(e => e.id === evaluationId);
                if (!evaluation) return;

                currentEvaluationId = evaluationId;
                
                // Populate basic details
                document.getElementById('detailStudentName').textContent = evaluation.studentName;
                document.getElementById('detailStudentId').textContent = evaluation.studentId;
                document.getElementById('detailOffice').textContent = evaluation.office;
                document.getElementById('detailRatedBy').textContent = evaluation.ratedBy;
                document.getElementById('detailDate').textContent = formatDate(evaluation.date);
                document.getElementById('detailOverallRating').textContent = evaluation.averageRating.toFixed(1);
                document.getElementById('detailComments').textContent = evaluation.comments;

                // Populate criteria
                const criteriaContainer = document.getElementById('criteriaDetails');
                criteriaContainer.innerHTML = Object.entries(evaluation.criteria).map(([criterion, rating]) => `
                    <div class="flex justify-between items-center p-2 bg-slate-50 rounded">
                        <span class="font-medium text-slate-600 mr-2">${criterion}:</span>
                        <span class="badge ${getRatingBadgeClass(rating)} text-white rounded p-1">${rating}</span>
                    </div>
                `).join('');

                // Populate HR review fields
                document.getElementById('hrStatus').value = evaluation.hrStatus;
                document.getElementById('hrRating').value = evaluation.hrRating;
                document.getElementById('hrComments').value = evaluation.hrComments;

                // Show panel
                evaluationDetailsPanel.style.display = 'block';
                evaluationDetailsPanel.scrollIntoView({ behavior: 'smooth' });
            };

            // Close evaluation panel function (global scope for onclick)
            window.closeEvaluationPanel = function() {
                evaluationDetailsPanel.style.display = 'none';
                currentEvaluationId = null;
            };

            // Save review function (global scope for onclick)
            window.saveReview = async function() {
                if (!currentEvaluationId) return;
                
                const hrStatus = document.getElementById('hrStatus').value;
                const hrRating = document.getElementById('hrRating').value;
                const hrComments = document.getElementById('hrComments').value;
                
                try {
                    const response = await fetch('/evaluation-review/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            evaluationId: currentEvaluationId,
                            hrStatus: hrStatus,
                            hrRating: hrRating,
                            hrComments: hrComments
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        // Update local data
                        const evaluationIndex = evaluationData.findIndex(e => e.id === currentEvaluationId);
                        if (evaluationIndex !== -1) {
                            evaluationData[evaluationIndex].hrStatus = hrStatus;
                            evaluationData[evaluationIndex].hrRating = hrRating;
                            evaluationData[evaluationIndex].hrComments = hrComments;
                            evaluationData[evaluationIndex].status = hrStatus === 'Reviewed' ? 'Reviewed' : 'Pending';
                        }
                        
                        filterData();
                        showMessage(result.message || 'Review saved successfully!', 'success');
                    } else {
                        showMessage(result.message || 'Failed to save review', 'error');
                    }
                } catch (error) {
                    console.error('Error saving review:', error);
                    showMessage('An error occurred while saving review', 'error');
                }
            };

            // Approve evaluation function (global scope for onclick)
            window.approveEvaluation = function() {
                if (!currentEvaluationId) return;
                
                document.getElementById('hrStatus').value = 'Approved';
                document.getElementById('hrRating').value = 'Excellent';
                saveReview();
            };

            // Reject evaluation function (global scope for onclick)
            window.rejectEvaluation = function() {
                if (!currentEvaluationId) return;
                
                document.getElementById('hrStatus').value = 'Rejected';
                document.getElementById('hrRating').value = 'Poor';
                saveReview();
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
