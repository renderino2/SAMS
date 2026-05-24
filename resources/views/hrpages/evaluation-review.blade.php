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
                                    <option value="Pending">Pending</option>
                                    <option value="Reviewed">Reviewed</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
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
            // Evaluation data loaded from API
            let evaluationData = [];
            let filteredData = [];
            let currentEvaluationId = null;

            // DOM elements
            const searchName = document.getElementById('searchName');
            const filterOffice = document.getElementById('filterOffice');
            const filterStatus = document.getElementById('filterStatus');
            const evaluationTableBody = document.getElementById('evaluationTableBody');
            const evaluationDetailsPanel = document.getElementById('evaluationDetailsPanel');
            const totalEvaluations = document.getElementById('totalEvaluations');
            const messageDiv = document.getElementById('message');

            // Load evaluations from API
            async function loadEvaluations() {
                try {
                    const response = await fetch('/api/evaluations', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Non-JSON response received');
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data && Array.isArray(result.data)) {
                        // Map API data to frontend format
                        evaluationData = result.data.map(evaluationItem => {
                            // Use stored questions if available, otherwise use default
                            const storedQuestions = evaluationItem.criteria_questions || {};
                            const defaultQuestions = {
                                rate1: 'Punctuality and regular attendance',
                                rate2: 'Conscious use of time during working hours',
                                rate3: 'Ability to understand and follow directions',
                                rate4: 'Sufficient competency and skill',
                                rate5: 'Promptness in performing assigned work',
                                rate6: 'Attention to details (accuracy, neatness, etc.)',
                                rate7: 'Initiative (doing things without waiting for orders)',
                                rate8: 'Health Condition (balance work and studies)',
                                rate9: 'Spirit and Attitude',
                                rate10: 'Professional discretion'
                            };
                            
                            const criteria = {};
                            for (let i = 1; i <= 10; i++) {
                                const rateKey = `rate${i}`;
                                if (evaluationItem[rateKey] !== undefined && evaluationItem[rateKey] !== null) {
                                    const questionText = storedQuestions[rateKey] || defaultQuestions[rateKey];
                                    criteria[`${i}. ${questionText}`] = evaluationItem[rateKey];
                                }
                            }
                            
                            return {
                                id: evaluationItem.id,
                                date: evaluationItem.evaluation_date || evaluationItem.formatted_date || '',
                                studentName: evaluationItem.student_name || '',
                                studentId: evaluationItem.student_id_number || '',
                                office: evaluationItem.office || '',
                                ratedBy: evaluationItem.rated_by || '',
                                averageRating: parseFloat(evaluationItem.average_score) || 0,
                                status: evaluationItem.status || 'Pending',
                                criteria: criteria,
                                comments: evaluationItem.comments || '',
                                overallRating: evaluationItem.overall_rating || '',
                                natureOfWork: evaluationItem.nature_of_work || '',
                                hrStatus: evaluationItem.status || 'Pending',
                                hrRating: evaluationItem.hr_rating || '',
                                hrComments: evaluationItem.hr_comments || '',
                                studentAssistantId: evaluationItem.student_assistant_id || null
                            };
                        });
                        
                        filteredData = [...evaluationData];
                        updateTotalCount();
                        renderTable();
                    } else {
                        evaluationTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">' + (result.message || 'No evaluations found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading evaluations:', error);
                    evaluationTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-slate-500">Error loading evaluations</td></tr>';
                }
            }

            // Initialize page
            async function init() {
                await loadEvaluations();
                await loadOfficeFilter();
                setupEventListeners();
            }
            
            // Load unique offices from evaluations for filter
            async function loadOfficeFilter() {
                try {
                    const response = await fetch('/api/evaluations', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) return;
                    
                    const result = await response.json();
                    if (result.success && result.data && Array.isArray(result.data)) {
                        // Get unique offices
                        const uniqueOffices = [...new Set(result.data.map(e => e.office).filter(Boolean))].sort();
                        
                        const officeFilter = document.getElementById('filterOffice');
                        if (officeFilter) {
                            officeFilter.innerHTML = '<option value="">All Offices</option>' + 
                                uniqueOffices.map(office => `<option value="${office}">${office}</option>`).join('');
                        }
                    }
                } catch (error) {
                    console.error('Error loading offices for filter:', error);
                }
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

                evaluationTableBody.innerHTML = filteredData.map((evaluation, index) => {
                    const evalDate = evaluation.date ? formatDate(evaluation.date) : 'N/A';
                    return `
                        <tr>
                            <td>${evalDate}</td>
                            <td>${evaluation.studentName || 'N/A'}</td>
                            <td>${evaluation.office || 'N/A'}</td>
                            <td>${evaluation.ratedBy || 'N/A'}</td>
                            <td>
                                <span class="badge ${getRatingBadgeClass(evaluation.averageRating)} text-white rounded p-1">
                                    ${(evaluation.averageRating || 0).toFixed(1)}
                                </span>
                            </td>
                            <td><span class="badge ${getStatusBadgeClass(evaluation.status)} text-white rounded p-1">${evaluation.status || 'Pending'}</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm text-white" onclick="viewEvaluation(${evaluation.id})">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
                
                // Reload Lucide icons
                reloadLucideIcons();
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
                    case 'Pending': return 'bg-pending text-white';
                    case 'Approved': return 'bg-primary text-white';
                    case 'Rejected': return 'bg-danger text-white';
                    default: return 'bg-warning text-white';
                }
            }

            // Format date for display
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                try {
                    const date = new Date(dateString);
                    if (isNaN(date.getTime())) {
                        // Try parsing as is if it's already formatted
                        return dateString;
                    }
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                } catch (error) {
                    return dateString; // Return as-is if parsing fails
                }
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

            // Update total count
            function updateTotalCount() {
                totalEvaluations.textContent = evaluationData.length;
            }

            // View evaluation function (global scope for onclick)
            window.viewEvaluation = async function(evaluationId) {
                try {
                    // Fetch full evaluation details from API
                    const response = await fetch(`/api/evaluations/${evaluationId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        const evaluation = result.data;
                        currentEvaluationId = evaluationId;
                        
                        // Use stored questions if available, otherwise use default
                        const storedQuestions = evaluation.criteria_questions || {};
                        const defaultQuestions = {
                            rate1: 'Punctuality and regular attendance',
                            rate2: 'Conscious use of time during working hours',
                            rate3: 'Ability to understand and follow directions',
                            rate4: 'Sufficient competency and skill',
                            rate5: 'Promptness in performing assigned work',
                            rate6: 'Attention to details (accuracy, neatness, etc.)',
                            rate7: 'Initiative (doing things without waiting for orders)',
                            rate8: 'Health Condition (balance work and studies)',
                            rate9: 'Spirit and Attitude',
                            rate10: 'Professional discretion'
                        };
                        
                        const criteria = {};
                        for (let i = 1; i <= 10; i++) {
                            const rateKey = `rate${i}`;
                            if (evaluation[rateKey] !== undefined && evaluation[rateKey] !== null) {
                                const questionText = storedQuestions[rateKey] || defaultQuestions[rateKey];
                                criteria[`${i}. ${questionText}`] = evaluation[rateKey];
                            }
                        }
                        
                        // Populate basic details
                        document.getElementById('detailStudentName').textContent = evaluation.student_name || 'N/A';
                        document.getElementById('detailStudentId').textContent = evaluation.student_id_number || 'N/A';
                        document.getElementById('detailOffice').textContent = evaluation.office || 'N/A';
                        document.getElementById('detailRatedBy').textContent = evaluation.rated_by || 'N/A';
                        const evalDate = evaluation.evaluation_date ? new Date(evaluation.evaluation_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) : 'N/A';
                        document.getElementById('detailDate').textContent = evalDate;
                        const avgScore = parseFloat(evaluation.average_score) || 0;
                        document.getElementById('detailOverallRating').innerHTML = `<span class="badge ${getRatingBadgeClass(avgScore)} text-white rounded px-2 py-1">${avgScore.toFixed(1)} - ${evaluation.overall_rating || 'N/A'}</span>`;
                        document.getElementById('detailComments').textContent = evaluation.comments || 'No comments';

                        // Populate criteria
                        const criteriaContainer = document.getElementById('criteriaDetails');
                        if (Object.keys(criteria).length > 0) {
                            criteriaContainer.innerHTML = Object.entries(criteria).map(([criterion, rating]) => `
                                <div class="flex justify-between items-center p-2 bg-slate-50 rounded">
                                    <span class="font-medium text-slate-600 mr-2">${criterion}:</span>
                                    <span class="badge ${getRatingBadgeClass(rating)} text-white rounded p-1">${rating}</span>
                                </div>
                            `).join('');
                        } else {
                            criteriaContainer.innerHTML = '<div class="text-slate-500">No criteria data available</div>';
                        }

                        // Populate HR review fields
                        document.getElementById('hrStatus').value = evaluation.status || 'Pending';
                        document.getElementById('hrRating').value = evaluation.hr_rating || '';
                        document.getElementById('hrComments').value = evaluation.hr_comments || '';

                        // Show panel
                        evaluationDetailsPanel.style.display = 'block';
                        evaluationDetailsPanel.scrollIntoView({ behavior: 'smooth' });
                        
                        // Reload Lucide icons
                        reloadLucideIcons();
                    } else {
                        showMessage(result.message || 'Failed to load evaluation details', 'error');
                    }
                } catch (error) {
                    console.error('Error loading evaluation details:', error);
                    showMessage('Error loading evaluation details', 'error');
                }
            };

            // Close evaluation panel function (global scope for onclick)
            window.closeEvaluationPanel = function() {
                evaluationDetailsPanel.style.display = 'none';
                currentEvaluationId = null;
            };

            // Save review function (global scope for onclick)
            window.saveReview = async function() {
                if (!currentEvaluationId) {
                    showMessage('Please select an evaluation to review', 'error');
                    return;
                }
                
                const hrStatus = document.getElementById('hrStatus').value;
                const hrRating = document.getElementById('hrRating').value;
                const hrComments = document.getElementById('hrComments').value;
                
                if (!hrStatus) {
                    showMessage('Please select a review status', 'error');
                    return;
                }
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch('/evaluation-review/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            evaluationId: currentEvaluationId,
                            hrStatus: hrStatus,
                            hrRating: hrRating || null,
                            hrComments: hrComments || null
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        // Reload evaluations to get updated data
                        await loadEvaluations();
                        showMessage(result.message || 'Review saved successfully!', 'success');
                        // Close panel after successful save
                        setTimeout(() => {
                            closeEvaluationPanel();
                        }, 1500);
                    } else {
                        showMessage(result.message || 'Failed to save review', 'error');
                    }
                } catch (error) {
                    console.error('Error saving review:', error);
                    showMessage('An error occurred while saving review', 'error');
                }
            };

            // Approve evaluation function (global scope for onclick)
            window.approveEvaluation = async function() {
                if (!currentEvaluationId) {
                    showMessage('Please select an evaluation to approve', 'error');
                    return;
                }
                
                if (!confirm('Are you sure you want to approve this evaluation?')) {
                    return;
                }
                
                document.getElementById('hrStatus').value = 'Approved';
                // Auto-select rating based on average score if available
                const evaluation = evaluationData.find(e => e.id === currentEvaluationId);
                if (evaluation && evaluation.averageRating >= 4.5) {
                    document.getElementById('hrRating').value = 'Excellent';
                } else if (evaluation && evaluation.averageRating >= 3.5) {
                    document.getElementById('hrRating').value = 'Good';
                } else {
                    document.getElementById('hrRating').value = 'Satisfactory';
                }
                
                await saveReview();
            };

            // Reject evaluation function (global scope for onclick)
            window.rejectEvaluation = async function() {
                if (!currentEvaluationId) {
                    showMessage('Please select an evaluation to reject', 'error');
                    return;
                }
                
                if (!confirm('Are you sure you want to reject this evaluation?')) {
                    return;
                }
                
                document.getElementById('hrStatus').value = 'Rejected';
                document.getElementById('hrRating').value = 'Poor';
                
                await saveReview();
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
