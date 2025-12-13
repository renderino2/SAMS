@extends('../layout/' . $layout)

@section('subhead')
    <title>Evaluation Form - SAMS</title>
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
                            Student Assistant Efficiency Rating Form
                        </h1>
                        <p class="text-slate-500 mt-2">Evaluate student assistant performance based on established criteria.</p>
                        <div class="alert alert-info mt-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            <span class="font-medium">Rating Scale:</span>
                            <span class="ml-2">9-10 Excellent | 7-8 Good | 5-6 Fair | 3-4 Poor</span>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Form -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <form id="evaluationForm" class="grid grid-cols-12 gap-4">
                            <!-- Basic Information -->
                            <div class="col-span-12">
                                <h2 class="text-lg font-medium mb-4 flex items-center">
                                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                                    Basic Information
                                </h2>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="studentName" class="form-label">Student Name:</label>
                                <input type="text" id="studentName" name="studentName" class="form-control" required />
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="natureOfWork" class="form-label">Nature of Work:</label>
                                <input type="text" id="natureOfWork" name="natureOfWork" class="form-control" required />
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="office" class="form-label">Office:</label>
                                <input type="text" id="office" name="office" class="form-control" required />
                            </div>

                            <!-- Rating Criteria -->
                            <div class="col-span-12">
                                <h2 class="text-lg font-medium mb-4 flex items-center">
                                    <i data-lucide="star" class="w-5 h-5 mr-2"></i>
                                    Rating Criteria
                                </h2>
                                <p class="text-slate-500 mb-4">Rate each criterion from 1 to 10 (1 = Poor, 10 = Excellent)</p>
                            </div>

                            <!-- Criterion 1 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate1" class="form-label mb-0 flex-1">1. Punctuality and regular attendance</label>
                                    <input type="number" id="rate1" name="rate1" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 2 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate2" class="form-label mb-0 flex-1">2. Conscious use of time during working hours</label>
                                    <input type="number" id="rate2" name="rate2" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 3 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate3" class="form-label mb-0 flex-1">3. Ability to understand and follow directions</label>
                                    <input type="number" id="rate3" name="rate3" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 4 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate4" class="form-label mb-0 flex-1">4. Sufficient competency and skill</label>
                                    <input type="number" id="rate4" name="rate4" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 5 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate5" class="form-label mb-0 flex-1">5. Promptness in performing assigned work</label>
                                    <input type="number" id="rate5" name="rate5" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 6 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate6" class="form-label mb-0 flex-1">6. Attention to details (accuracy, neatness, etc.)</label>
                                    <input type="number" id="rate6" name="rate6" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 7 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate7" class="form-label mb-0 flex-1">7. Initiative (doing things without waiting for orders)</label>
                                    <input type="number" id="rate7" name="rate7" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 8 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate8" class="form-label mb-0 flex-1">8. Health Condition (balance work and studies)</label>
                                    <input type="number" id="rate8" name="rate8" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 9 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate9" class="form-label mb-0 flex-1">9. Spirit and Attitude</label>
                                    <input type="number" id="rate9" name="rate9" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 10 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <label for="rate10" class="form-label mb-0 flex-1">10. Professional discretion</label>
                                    <input type="number" id="rate10" name="rate10" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Comments -->
                            <div class="col-span-12">
                                <label for="comments" class="form-label">Comments:</label>
                                <textarea id="comments" name="comments" class="form-control" rows="4" 
                                          placeholder="Optional feedback and additional comments..."></textarea>
                            </div>

                            <!-- Evaluation Details -->
                            <div class="col-span-12">
                                <h2 class="text-lg font-medium mb-4 flex items-center">
                                    <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                                    Evaluation Details
                                </h2>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" id="date" name="date" class="form-control" required />
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="ratedBy" class="form-label">Rated by:</label>
                                <input type="text" id="ratedBy" name="ratedBy" class="form-control" 
                                       value="{{ Auth::user()->full_name ?? Auth::user()->name }}" required />
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="head" class="form-label">Head of Office:</label>
                                <input type="text" id="head" name="head" class="form-control" 
                                       value="{{ Auth::user()->full_name ?? Auth::user()->name }}" required />
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                    Submit Evaluation
                                </button>
                            </div>
                        </form>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>

                <!-- Evaluation Summary -->
                <div class="col-span-12 intro-y" id="evaluationSummary" style="display: none;">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="bar-chart" class="w-5 h-5 mr-2"></i>
                            Evaluation Summary
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold text-primary" id="totalScore">0</div>
                                <div class="text-slate-500">Total Score</div>
                            </div>
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold text-success" id="averageScore">0.0</div>
                                <div class="text-slate-500">Average Score</div>
                            </div>
                            <div class="text-center p-4 border rounded-lg">
                                <div class="text-2xl font-bold" id="overallRating">-</div>
                                <div class="text-slate-500">Overall Rating</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Management -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-2">
                                <i data-lucide="list" class="w-5 h-5 mr-2"></i>
                                Manage Evaluations
                            </h2>
                            <button id="refreshEvaluations" class="btn btn-secondary btn-sm">
                                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Refresh
                            </button>
                        </div>
                        <div class="mb-4">
                            <input type="text" id="searchEvaluations" class="form-control" placeholder="Search by student name or office..." />
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Office</th>
                                        <th>Date</th>
                                        <th>Total Score</th>
                                        <th>Average</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="evaluationsTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
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
            const evaluationForm = document.getElementById('evaluationForm');
            const messageDiv = document.getElementById('message');
            const evaluationSummary = document.getElementById('evaluationSummary');

            // Evaluation Management variables
            let evaluationsList = [];
            let filteredEvaluationsList = [];
            let currentEditId = null;

            // Set current date
            document.getElementById('date').value = new Date().toISOString().split('T')[0];

            // Handle form submission
            evaluationForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = evaluationForm.querySelector('button[type="submit"]');
                const isUpdateMode = submitBtn.dataset.mode === 'update' && currentEditId;
                
                const formData = new FormData(evaluationForm);
                const data = Object.fromEntries(formData.entries());
                
                // Validate all rating fields
                const ratings = [];
                for (let i = 1; i <= 10; i++) {
                    const rating = parseInt(data[`rate${i}`]);
                    if (isNaN(rating) || rating < 1 || rating > 10) {
                        showMessage(`Please enter a valid rating (1-10) for criterion ${i}`, 'error');
                        return;
                    }
                    ratings.push(rating);
                }

                // Calculate scores
                const totalScore = ratings.reduce((sum, rating) => sum + rating, 0);
                const averageScore = (totalScore / ratings.length).toFixed(1);
                const overallRating = getOverallRating(averageScore);

                // Show summary
                showEvaluationSummary(totalScore, averageScore, overallRating);

                if (isUpdateMode) {
                    // Update existing evaluation
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        
                        const response = await fetch(`/api/evaluations/${currentEditId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ...data,
                                totalScore: totalScore,
                                averageScore: parseFloat(averageScore),
                                overallRating: overallRating
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            showMessage(result.message || 'Evaluation updated successfully!', 'success');
                            evaluationForm.reset();
                            evaluationSummary.style.display = 'none';
                            document.getElementById('date').value = new Date().toISOString().split('T')[0];
                            document.getElementById('ratedBy').value = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                            document.getElementById('head').value = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                            submitBtn.innerHTML = '<i data-lucide="send" class="w-4 h-4 mr-2"></i> Submit Evaluation';
                            submitBtn.dataset.mode = 'create';
                            currentEditId = null;
                            await loadEvaluations();
                            reloadLucideIcons();
                        } else {
                            showMessage(result.message || 'Failed to update evaluation', 'error');
                        }
                    } catch (error) {
                        console.error('Error updating evaluation:', error);
                        showMessage('An error occurred while updating evaluation', 'error');
                    }
                } else {
                    // Create new evaluation
                    try {
                        const response = await fetch('/evaluation-form/submit', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ...data,
                                ratings: ratings,
                                totalScore: totalScore,
                                averageScore: parseFloat(averageScore),
                                overallRating: overallRating
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            showMessage(result.message || 'Evaluation submitted successfully!', 'success');
                            evaluationForm.reset();
                            evaluationSummary.style.display = 'none';
                            document.getElementById('date').value = new Date().toISOString().split('T')[0];
                            document.getElementById('ratedBy').value = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                            document.getElementById('head').value = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                            // Reload evaluations list
                            if (typeof loadEvaluations === 'function') {
                                await loadEvaluations();
                            }
                        } else {
                            showMessage(result.message || 'Failed to submit evaluation', 'error');
                        }
                    } catch (error) {
                        console.error('Error submitting evaluation:', error);
                        showMessage('An error occurred while submitting evaluation', 'error');
                    }
                }
            });

            // Show evaluation summary
            function showEvaluationSummary(totalScore, averageScore, overallRating) {
                document.getElementById('totalScore').textContent = totalScore;
                document.getElementById('averageScore').textContent = averageScore;
                document.getElementById('overallRating').textContent = overallRating;
                
                // Color code the overall rating
                const ratingElement = document.getElementById('overallRating');
                ratingElement.className = 'text-2xl font-bold';
                
                if (averageScore >= 9) {
                    ratingElement.classList.add('text-success');
                } else if (averageScore >= 7) {
                    ratingElement.classList.add('text-primary');
                } else if (averageScore >= 5) {
                    ratingElement.classList.add('text-warning');
                } else {
                    ratingElement.classList.add('text-danger');
                }
                
                evaluationSummary.style.display = 'block';
                evaluationSummary.scrollIntoView({ behavior: 'smooth' });
            }

            // Get overall rating based on average score
            function getOverallRating(averageScore) {
                if (averageScore >= 9) return 'Excellent';
                if (averageScore >= 7) return 'Good';
                if (averageScore >= 5) return 'Fair';
                return 'Poor';
            }

            // Show message function
            function showMessage(message, type) {
                messageDiv.innerHTML = `
                    <div class="alert alert-${type === 'success' ? 'success' : 'danger'} flex items-center">
                        <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-4 h-4 mr-2"></i>
                        ${message}
                    </div>
                `;
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.innerHTML = '';
                }, 5000);
            }

            // Real-time rating validation
            for (let i = 1; i <= 10; i++) {
                const ratingInput = document.getElementById(`rate${i}`);
                ratingInput.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    if (value < 1 || value > 10) {
                        this.setCustomValidity('Rating must be between 1 and 10');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }

            // Evaluation Management
            const evaluationsTableBody = document.getElementById('evaluationsTableBody');
            const searchEvaluations = document.getElementById('searchEvaluations');
            const refreshEvaluations = document.getElementById('refreshEvaluations');

            // Load evaluations
            async function loadEvaluations() {
                try {
                    const response = await fetch('/api/evaluations', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text.substring(0, 200));
                        evaluationsTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">Error loading data</td></tr>';
                        return;
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        evaluationsList = result.data;
                        filteredEvaluationsList = [...evaluationsList];
                        renderEvaluationsTable();
                    } else {
                        evaluationsTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">' + (result.message || 'No evaluations found') + '</td></tr>';
                    }
                } catch (error) {
                    console.error('Error loading evaluations:', error);
                    evaluationsTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">Error loading data</td></tr>';
                }
            }

            // Filter evaluations
            function filterEvaluations() {
                const searchTerm = searchEvaluations.value.toLowerCase();
                filteredEvaluationsList = evaluationsList.filter(evaluation => {
                    const studentName = (evaluation.student_name || '').toLowerCase();
                    const office = (evaluation.office || '').toLowerCase();
                    return studentName.includes(searchTerm) || office.includes(searchTerm);
                });
                renderEvaluationsTable();
            }

            // Render evaluations table
            function renderEvaluationsTable() {
                if (filteredEvaluationsList.length === 0) {
                    evaluationsTableBody.innerHTML = '<tr><td colspan="9" class="text-center text-slate-500">No evaluations found</td></tr>';
                    return;
                }

                evaluationsTableBody.innerHTML = filteredEvaluationsList.map((evaluation, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${evaluation.student_name}</td>
                        <td>${evaluation.office}</td>
                        <td>${evaluation.formatted_date}</td>
                        <td>${evaluation.total_score}</td>
                        <td>${evaluation.average_score}</td>
                        <td>
                            <span class="badge ${getRatingBadgeClass(evaluation.overall_rating)} rounded p-1">
                                ${evaluation.overall_rating}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${getStatusBadgeClass(evaluation.status)} rounded p-1">
                                ${evaluation.status}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm text-white mr-1" onclick="viewEvaluation(${evaluation.id})">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button class="btn btn-warning btn-sm text-white mr-1" onclick="editEvaluation(${evaluation.id})">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <button class="btn btn-danger btn-sm text-white" onclick="deleteEvaluation(${evaluation.id})">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
                
                reloadLucideIcons();
            }

            // Get rating badge class
            function getRatingBadgeClass(rating) {
                switch (rating) {
                    case 'Excellent':
                        return 'bg-success text-white';
                    case 'Good':
                        return 'bg-primary text-white';
                    case 'Fair':
                        return 'bg-warning text-white';
                    case 'Poor':
                        return 'bg-danger text-white';
                    default:
                        return 'bg-secondary text-white';
                }
            }

            // Get status badge class
            function getStatusBadgeClass(status) {
                switch (status) {
                    case 'Reviewed':
                        return 'bg-success text-white';
                    case 'Pending':
                        return 'bg-warning text-white';
                    case 'Archived':
                        return 'bg-secondary text-white';
                    default:
                        return 'bg-secondary text-white';
                }
            }

            // View evaluation
            window.viewEvaluation = async function(id) {
                try {
                    const response = await fetch(`/api/evaluations/${id}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        const evaluation = result.data;
                        alert(`Evaluation Details:\n\nStudent: ${evaluation.student_name}\nOffice: ${evaluation.office}\nDate: ${evaluation.evaluation_date}\nTotal Score: ${evaluation.total_score}\nAverage: ${evaluation.average_score}\nRating: ${evaluation.overall_rating}\nStatus: ${evaluation.status}\n\nComments: ${evaluation.comments || 'None'}`);
                    } else {
                        showMessage(result.message || 'Failed to load evaluation', 'error');
                    }
                } catch (error) {
                    console.error('Error viewing evaluation:', error);
                    showMessage('Error loading evaluation', 'error');
                }
            };

            // Edit evaluation
            window.editEvaluation = async function(id) {
                try {
                    const response = await fetch(`/api/evaluations/${id}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        const evaluation = result.data;
                        currentEditId = id;
                        
                        // Populate form
                        document.getElementById('studentName').value = evaluation.student_name;
                        document.getElementById('natureOfWork').value = evaluation.nature_of_work;
                        document.getElementById('office').value = evaluation.office;
                        document.getElementById('rate1').value = evaluation.rate1;
                        document.getElementById('rate2').value = evaluation.rate2;
                        document.getElementById('rate3').value = evaluation.rate3;
                        document.getElementById('rate4').value = evaluation.rate4;
                        document.getElementById('rate5').value = evaluation.rate5;
                        document.getElementById('rate6').value = evaluation.rate6;
                        document.getElementById('rate7').value = evaluation.rate7;
                        document.getElementById('rate8').value = evaluation.rate8;
                        document.getElementById('rate9').value = evaluation.rate9;
                        document.getElementById('rate10').value = evaluation.rate10;
                        document.getElementById('comments').value = evaluation.comments || '';
                        document.getElementById('date').value = evaluation.evaluation_date;
                        document.getElementById('ratedBy').value = evaluation.rated_by;
                        document.getElementById('head').value = evaluation.head_of_office;
                        
                        // Update submit button
                        const submitBtn = evaluationForm.querySelector('button[type="submit"]');
                        submitBtn.innerHTML = '<i data-lucide="save" class="w-4 h-4 mr-2"></i> Update Evaluation';
                        submitBtn.dataset.mode = 'update';
                        
                        // Scroll to form
                        evaluationForm.scrollIntoView({ behavior: 'smooth' });
                        reloadLucideIcons();
                    } else {
                        showMessage(result.message || 'Failed to load evaluation', 'error');
                    }
                } catch (error) {
                    console.error('Error loading evaluation for edit:', error);
                    showMessage('Error loading evaluation', 'error');
                }
            };

            // Delete evaluation
            window.deleteEvaluation = async function(id) {
                if (!confirm('Are you sure you want to delete this evaluation? This action cannot be undone.')) {
                    return;
                }

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch(`/api/evaluations/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showMessage(result.message || 'Evaluation deleted successfully!', 'success');
                        await loadEvaluations();
                    } else {
                        showMessage(result.message || 'Failed to delete evaluation', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting evaluation:', error);
                    showMessage('Error deleting evaluation', 'error');
                }
            };


            // Event listeners
            searchEvaluations.addEventListener('input', filterEvaluations);
            refreshEvaluations.addEventListener('click', loadEvaluations);

            // Initialize evaluations on page load
            loadEvaluations();

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
        })();
    </script>
@endsection
