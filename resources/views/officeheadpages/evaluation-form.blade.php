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
                                <label for="studentName" class="form-label">Student Name: <span class="text-danger">*</span></label>
                                <select id="studentName" name="studentName" class="form-control" required>
                                    <option value="">Select Student Assistant...</option>
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="officeSelect" class="form-label">Office: <span class="text-danger">*</span></label>
                                <select id="officeSelect" name="office" class="form-control" style="display: none;">
                                    <option value="">Select Office...</option>
                                </select>
                                <input type="text" id="officeReadonly" name="office" class="form-control" readonly 
                                       style="background-color: #f3f4f6; cursor: not-allowed; display: none;" placeholder="Office will be auto-filled..." />
                                <small id="officeHelper" class="form-text text-muted" style="display: none;"></small>
                            </div>

                            <!-- Rating Criteria -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="mr-auto">
                                        <h2 class="text-lg font-medium flex items-center">
                                            <i data-lucide="star" class="w-5 h-5 mr-2"></i>
                                            Rating Criteria
                                        </h2>
                                        <p class="text-slate-500 mt-1">Rate each criterion from 1 to 10 (1 = Poor, 10 = Excellent)</p>
                                    </div>
                                    <button type="button" id="editCriteriaBtn" class="btn btn-outline-secondary btn-sm flex items-center justify-center">
                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                        Edit Questions
                                    </button>
                                </div>
                            </div>

                            <!-- Criterion 1 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">1. </span>
                                        <input type="text" id="question1" name="question1" class="form-control inline-block" 
                                               value="Punctuality and regular attendance" style="display: none;" />
                                        <label for="rate1" class="form-label mb-0 inline-block" id="label1">Punctuality and regular attendance</label>
                                    </div>
                                    <input type="number" id="rate1" name="rate1" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 2 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">2. </span>
                                        <input type="text" id="question2" name="question2" class="form-control inline-block" 
                                               value="Conscious use of time during working hours" style="display: none;" />
                                        <label for="rate2" class="form-label mb-0 inline-block" id="label2">Conscious use of time during working hours</label>
                                    </div>
                                    <input type="number" id="rate2" name="rate2" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 3 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">3. </span>
                                        <input type="text" id="question3" name="question3" class="form-control inline-block" 
                                               value="Ability to understand and follow directions" style="display: none;" />
                                        <label for="rate3" class="form-label mb-0 inline-block" id="label3">Ability to understand and follow directions</label>
                                    </div>
                                    <input type="number" id="rate3" name="rate3" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 4 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">4. </span>
                                        <input type="text" id="question4" name="question4" class="form-control inline-block" 
                                               value="Sufficient competency and skill" style="display: none;" />
                                        <label for="rate4" class="form-label mb-0 inline-block" id="label4">Sufficient competency and skill</label>
                                    </div>
                                    <input type="number" id="rate4" name="rate4" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 5 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">5. </span>
                                        <input type="text" id="question5" name="question5" class="form-control inline-block" 
                                               value="Promptness in performing assigned work" style="display: none;" />
                                        <label for="rate5" class="form-label mb-0 inline-block" id="label5">Promptness in performing assigned work</label>
                                    </div>
                                    <input type="number" id="rate5" name="rate5" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 6 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">6. </span>
                                        <input type="text" id="question6" name="question6" class="form-control inline-block" 
                                               value="Attention to details (accuracy, neatness, etc.)" style="display: none;" />
                                        <label for="rate6" class="form-label mb-0 inline-block" id="label6">Attention to details (accuracy, neatness, etc.)</label>
                                    </div>
                                    <input type="number" id="rate6" name="rate6" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 7 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">7. </span>
                                        <input type="text" id="question7" name="question7" class="form-control inline-block" 
                                               value="Initiative (doing things without waiting for orders)" style="display: none;" />
                                        <label for="rate7" class="form-label mb-0 inline-block" id="label7">Initiative (doing things without waiting for orders)</label>
                                    </div>
                                    <input type="number" id="rate7" name="rate7" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 8 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">8. </span>
                                        <input type="text" id="question8" name="question8" class="form-control inline-block" 
                                               value="Health Condition (balance work and studies)" style="display: none;" />
                                        <label for="rate8" class="form-label mb-0 inline-block" id="label8">Health Condition (balance work and studies)</label>
                                    </div>
                                    <input type="number" id="rate8" name="rate8" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 9 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">9. </span>
                                        <input type="text" id="question9" name="question9" class="form-control inline-block" 
                                               value="Spirit and Attitude" style="display: none;" />
                                        <label for="rate9" class="form-label mb-0 inline-block" id="label9">Spirit and Attitude</label>
                                    </div>
                                    <input type="number" id="rate9" name="rate9" class="form-control w-20 text-center" 
                                           min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Criterion 10 -->
                            <div class="col-span-12">
                                <div class="flex items-center justify-between p-1 border rounded-lg">
                                    <div class="flex-1 mr-2">
                                        <span class="text-slate-600 font-medium">10. </span>
                                        <input type="text" id="question10" name="question10" class="form-control inline-block" 
                                               value="Professional discretion" style="display: none;" />
                                        <label for="rate10" class="form-label mb-0 inline-block" id="label10">Professional discretion</label>
                                    </div>
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

                <!-- Evaluation History -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium flex items-center mr-auto">
                                <i data-lucide="history" class="w-5 h-5 mr-2"></i>
                                Evaluation History
                            </h2>
                            <button id="refreshEvaluations" class="btn btn-secondary btn-sm">
                                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Refresh
                            </button>
                        </div>
                        <div class="mb-4 flex items-center gap-2 justify-center">
                            <input type="text" id="searchEvaluations" class="form-control flex-1" placeholder="Search by student name or office..." />
                            <div class="flex items-center gap-2 justify-center">
                                <label class="form-label mb-0 inline-block">Filter by Status:</label>
                                <select id="filterStatus" class="form-control w-auto">
                                    <option value="">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Reviewed">Reviewed</option>
                                    <option value="Archived">Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">#</th>
                                        <th class="whitespace-nowrap">Student Name</th>
                                        <th class="whitespace-nowrap">Office</th>
                                        <th class="whitespace-nowrap">Evaluation Date</th>
                                        <th class="whitespace-nowrap">Rated By</th>
                                        <th class="whitespace-nowrap">Total Score</th>
                                        <th class="whitespace-nowrap">Average</th>
                                        <th class="whitespace-nowrap">Rating</th>
                                        <th class="whitespace-nowrap">Status</th>
                                        <th class="whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="evaluationsTableBody">
                                    <tr>
                                        <td colspan="11" class="text-center text-slate-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex items-center justify-center">
                            <div class="text-slate-500 mr-2">
                                <span id="evaluationCount">0</span> evaluation(s) found
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="prevPage" class="btn btn-secondary btn-sm" disabled>
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Previous
                                </button>
                                <span class="text-slate-500">
                                    Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
                                </span>
                                <button id="nextPage" class="btn btn-secondary btn-sm" disabled>
                                    Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
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
            let studentAssistantsList = [];
            let officesList = [];
            let currentOffice = '';
            let currentPage = 1;
            const itemsPerPage = 10;

            // Set current date
            document.getElementById('date').value = new Date().toISOString().split('T')[0];

            // Load criteria questions from settings
            let criteriaQuestions = {};
            let isEditMode = false;

            async function loadCriteriaQuestions() {
                try {
                    const response = await fetch('/api/evaluation-criteria-settings', {
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        if (result.success && result.data) {
                            criteriaQuestions = result.data;
                            // Update labels with loaded questions
                            for (let i = 1; i <= 10; i++) {
                                const key = `rate${i}`;
                                if (criteriaQuestions[key]) {
                                    document.getElementById(`label${i}`).textContent = criteriaQuestions[key];
                                    document.getElementById(`question${i}`).value = criteriaQuestions[key];
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error loading criteria questions:', error);
                }
            }

            // Toggle edit mode for questions
            document.getElementById('editCriteriaBtn').addEventListener('click', function() {
                isEditMode = !isEditMode;
                
                for (let i = 1; i <= 10; i++) {
                    const label = document.getElementById(`label${i}`);
                    const input = document.getElementById(`question${i}`);
                    
                    if (isEditMode) {
                        label.style.display = 'none';
                        input.style.display = 'inline-block';
                        input.style.width = 'calc(100% - 40px)';
                    } else {
                        label.style.display = 'inline-block';
                        input.style.display = 'none';
                        label.textContent = input.value;
                    }
                }
                
                this.innerHTML = isEditMode 
                    ? '<i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Questions'
                    : '<i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Questions';
                
                reloadLucideIcons();
            });

            // Load offices for dropdown
            async function loadOffices() {
                try {
                    const response = await fetch('/api/offices', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.data && Array.isArray(result.data)) {
                        officesList = result.data;
                        
                        // Populate office dropdown
                        const officeSelect = document.getElementById('officeSelect');
                        if (officeSelect) {
                            // Clear existing options except the first one
                            officeSelect.innerHTML = '<option value="">Select Office...</option>';
                            
                            // Add offices from database
                            result.data.forEach(office => {
                                // Only add active offices, or all if is_active is not set
                                if (office.is_active !== false) {
                                    const option = document.createElement('option');
                                    option.value = office.name;
                                    option.textContent = office.name;
                                    officeSelect.appendChild(option);
                                }
                            });
                            
                            console.log('Offices loaded successfully:', officesList.length);
                        }
                    } else {
                        console.error('Failed to load offices - invalid response:', result);
                    }
                } catch (error) {
                    console.error('Error loading offices:', error);
                }
            }

            // Load student assistants for dropdown
            async function loadStudentAssistants() {
                try {
                    const response = await fetch('/api/office-sas', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    console.log('Loading student assistants, response status:', response.status);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('API error response:', errorText);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response when loading student assistants:', text.substring(0, 200));
                        throw new Error('Non-JSON response received');
                    }
                    
                    const result = await response.json();
                    console.log('Student assistants API result:', result);
                    
                    if (result.success && result.data && Array.isArray(result.data)) {
                        studentAssistantsList = result.data;
                        currentOffice = result.office || '';
                        
                        // Populate student name dropdown
                        const studentNameSelect = document.getElementById('studentName');
                        studentNameSelect.innerHTML = '<option value="">Select Student Assistant...</option>';
                        
                        // Filter student assistants - include all if status is null/undefined, or Active/Apprentice
                        const activeSAs = result.data.filter(sa => {
                            // Include all student assistants, or filter by status if status field exists
                            const status = sa.status;
                            return !status || status === 'Active' || status === 'Apprentice' || status === 'Pending Assignment';
                        });
                        
                        console.log('Filtered student assistants:', activeSAs.length, 'out of', result.data.length);
                        
                        if (activeSAs.length === 0) {
                            // Show all if no active ones found (fallback)
                            result.data.forEach(sa => {
                                const option = document.createElement('option');
                                option.value = sa.id;
                                const fullName = sa.full_name || sa.name || 'Unknown';
                                const studentId = sa.student_id_number || '';
                                option.textContent = studentId ? `${fullName} (${studentId})` : fullName;
                                option.dataset.studentName = fullName;
                                option.dataset.studentId = studentId;
                                option.dataset.office = sa.office || '';
                                studentNameSelect.appendChild(option);
                            });
                        } else {
                            activeSAs.forEach(sa => {
                                const option = document.createElement('option');
                                option.value = sa.id;
                                const fullName = sa.full_name || sa.name || 'Unknown';
                                const studentId = sa.student_id_number || '';
                                option.textContent = studentId ? `${fullName} (${studentId})` : fullName;
                                option.dataset.studentName = fullName;
                                option.dataset.studentId = studentId;
                                option.dataset.office = sa.office || '';
                                studentNameSelect.appendChild(option);
                            });
                        }
                        
                        // Set office field based on user role
                        const officeSelect = document.getElementById('officeSelect');
                        const officeReadonly = document.getElementById('officeReadonly');
                        
                        if (currentOffice) {
                            // Office Head - show readonly field
                            if (officeReadonly) {
                                officeReadonly.value = currentOffice;
                                officeReadonly.style.display = 'block';
                                officeReadonly.required = true;
                                if (officeSelect) {
                                    officeSelect.style.display = 'none';
                                    officeSelect.required = false;
                                }
                            }
                        } else {
                            // HR - show dropdown to select office
                            if (officeSelect) {
                                officeSelect.style.display = 'block';
                                officeSelect.required = true;
                                if (officeReadonly) {
                                    officeReadonly.style.display = 'none';
                                    officeReadonly.required = false;
                                }
                            }
                        }
                        
                        console.log('Student assistants loaded successfully:', activeSAs.length || result.data.length);
                    } else {
                        console.error('Failed to load student assistants - invalid response:', result);
                        const studentNameSelect = document.getElementById('studentName');
                        studentNameSelect.innerHTML = '<option value="">No student assistants available. ' + (result.message || 'Please check your office assignment.') + '</option>';
                    }
                } catch (error) {
                    console.error('Error loading student assistants:', error);
                    const studentNameSelect = document.getElementById('studentName');
                    studentNameSelect.innerHTML = '<option value="">Error loading student assistants. Please refresh the page.</option>';
                }
            }

            // Handle student name selection change - auto-populate office for HR
            document.getElementById('studentName').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const officeSelect = document.getElementById('officeSelect');
                const officeReadonly = document.getElementById('officeReadonly');
                const officeHelper = document.getElementById('officeHelper');
                
                if (selectedOption && selectedOption.value) {
                    const studentId = selectedOption.dataset.studentId;
                    const saOffice = selectedOption.dataset.office || '';
                    
                    // Only auto-populate for HR (when officeSelect is visible)
                    if (!currentOffice && officeSelect && officeSelect.style.display !== 'none') {
                        if (saOffice && saOffice.trim() !== '') {
                            // Set the office dropdown value if it exists in the options
                            const officeOption = Array.from(officeSelect.options).find(opt => opt.value === saOffice);
                            if (officeOption) {
                                officeSelect.value = saOffice;
                                // Show helper text
                                if (officeHelper) {
                                    officeHelper.textContent = `Auto-filled from student assistant's assigned office`;
                                    officeHelper.className = 'form-text text-success';
                                    officeHelper.style.display = 'block';
                                }
                                // Show a visual indicator that it was auto-filled
                                officeSelect.classList.add('border-success');
                                setTimeout(() => {
                                    officeSelect.classList.remove('border-success');
                                    if (officeHelper) {
                                        officeHelper.style.display = 'none';
                                    }
                                }, 3000);
                            } else {
                                // Office exists but not in dropdown - add it or show message
                                officeSelect.value = '';
                                if (officeHelper) {
                                    officeHelper.textContent = `Student assistant's office "${saOffice}" is not in the list. Please select manually.`;
                                    officeHelper.className = 'form-text text-warning';
                                    officeHelper.style.display = 'block';
                                }
                                showMessage(`Selected student assistant's office "${saOffice}" is not in the office list. Please select an office manually.`, 'warning');
                            }
                        } else {
                            // No office assigned - show message and allow manual selection
                            officeSelect.value = '';
                            if (officeHelper) {
                                officeHelper.textContent = 'No assigned office - Please select an office manually';
                                officeHelper.className = 'form-text text-danger';
                                officeHelper.style.display = 'block';
                            }
                            showMessage('Selected student assistant has no assigned office. Please select an office manually.', 'warning');
                        }
                    }
                } else {
                    // Reset office field when no student is selected (for HR)
                    if (!currentOffice && officeSelect && officeSelect.style.display !== 'none') {
                        officeSelect.value = '';
                        if (officeHelper) {
                            officeHelper.style.display = 'none';
                        }
                    }
                }
            });

            // Handle form submission
            evaluationForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = evaluationForm.querySelector('button[type="submit"]');
                const isUpdateMode = submitBtn.dataset.mode === 'update' && currentEditId;
                
                const formData = new FormData(evaluationForm);
                        // Get selected student name from dropdown
                        const studentSelect = document.getElementById('studentName');
                        const selectedOption = studentSelect.options[studentSelect.selectedIndex];
                        let studentName = '';
                        
                        if (selectedOption && selectedOption.value) {
                            // Use dataset.studentName if available, otherwise extract from textContent
                            if (selectedOption.dataset.studentName) {
                                studentName = selectedOption.dataset.studentName;
                            } else {
                                // Extract name from "Name (ID)" format
                                const text = selectedOption.textContent.trim();
                                const match = text.match(/^(.+?)\s*\(/);
                                studentName = match ? match[1].trim() : text;
                            }
                        }
                        
                        // Validate required fields
                        if (!studentName) {
                            showMessage('Please select a student assistant', 'error');
                            return;
                        }
                        
                        // Get office value - check both readonly and select fields
                        const officeReadonly = document.getElementById('officeReadonly');
                        const officeSelect = document.getElementById('officeSelect');
                        const officeValue = (officeReadonly && officeReadonly.style.display !== 'none') 
                            ? officeReadonly.value 
                            : (officeSelect ? officeSelect.value : '');
                        
                        if (!officeValue) {
                            showMessage('Please select an office', 'error');
                            return;
                        }
                        
                        // Collect criteria questions
                        const criteriaQuestions = {};
                        for (let i = 1; i <= 10; i++) {
                            const questionInput = document.getElementById(`question${i}`);
                            criteriaQuestions[`rate${i}`] = questionInput.value;
                        }

                        const formDataObj = {
                            studentName: studentName,
                            office: officeValue,
                            date: document.getElementById('date').value,
                            ratedBy: document.getElementById('ratedBy').value,
                            head: document.getElementById('head').value,
                            comments: document.getElementById('comments').value || '',
                            criteriaQuestions: criteriaQuestions
                        };
                        
                        console.log('Submitting evaluation with data:', formDataObj);
                        
                        // Get all ratings
                        const ratings = [];
                        for (let i = 1; i <= 10; i++) {
                            const rateInput = document.getElementById(`rate${i}`);
                            const rating = parseInt(rateInput.value);
                            if (isNaN(rating) || rating < 1 || rating > 10) {
                                showMessage(`Please enter a valid rating (1-10) for criterion ${i}`, 'error');
                                return;
                            }
                            ratings.push(rating);
                            formDataObj[`rate${i}`] = rating;
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
                                ...formDataObj,
                                totalScore: totalScore,
                                averageScore: parseFloat(averageScore),
                                overallRating: overallRating,
                                criteriaQuestions: criteriaQuestions
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
                            document.getElementById('studentName').value = '';
                            // Reset office field
                            const officeReadonly = document.getElementById('officeReadonly');
                            const officeSelect = document.getElementById('officeSelect');
                            if (currentOffice && officeReadonly) {
                                officeReadonly.value = currentOffice;
                                officeReadonly.style.display = 'block';
                                officeReadonly.required = true;
                                if (officeSelect) {
                                    officeSelect.style.display = 'none';
                                    officeSelect.required = false;
                                }
                            } else if (officeSelect) {
                                officeSelect.value = '';
                                officeSelect.style.display = 'block';
                                officeSelect.required = true;
                                if (officeReadonly) {
                                    officeReadonly.style.display = 'none';
                                    officeReadonly.required = false;
                                }
                            }
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
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        
                        if (!csrfToken) {
                            showMessage('CSRF token not found. Please refresh the page.', 'error');
                            return;
                        }
                        
                        const response = await fetch('/evaluation-form/submit', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ...formDataObj,
                                ratings: ratings,
                                totalScore: totalScore,
                                averageScore: parseFloat(averageScore),
                                overallRating: overallRating
                            })
                        });
                        
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 500));
                            showMessage('Server returned an error. Please check the console for details.', 'error');
                            return;
                        }
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            showMessage(result.message || 'Evaluation submitted successfully!', 'success');
                            evaluationForm.reset();
                            evaluationSummary.style.display = 'none';
                            document.getElementById('date').value = new Date().toISOString().split('T')[0];
                            document.getElementById('ratedBy').value = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                            document.getElementById('head').value = '{{ Auth::user()->full_name ?? Auth::user()->name }}';
                            document.getElementById('studentName').value = '';
                            // Reset office field
                            const officeReadonly = document.getElementById('officeReadonly');
                            const officeSelect = document.getElementById('officeSelect');
                            if (currentOffice && officeReadonly) {
                                officeReadonly.value = currentOffice;
                                officeReadonly.style.display = 'block';
                                officeReadonly.required = true;
                                if (officeSelect) {
                                    officeSelect.style.display = 'none';
                                    officeSelect.required = false;
                                }
                            } else if (officeSelect) {
                                officeSelect.value = '';
                                officeSelect.style.display = 'block';
                                officeSelect.required = true;
                                if (officeReadonly) {
                                    officeReadonly.style.display = 'none';
                                    officeReadonly.required = false;
                                }
                            }
                            // Reload evaluations list
                            if (typeof loadEvaluations === 'function') {
                                await loadEvaluations();
                            }
                            
                            // Notify dashboard to refresh stats (using BroadcastChannel for cross-tab communication)
                            if (typeof BroadcastChannel !== 'undefined') {
                                const channel = new BroadcastChannel('dashboard-updates');
                                channel.postMessage({ type: 'evaluation-submitted' });
                            }
                            
                            // Also try to refresh if we're on the same page (for iframe or same window scenarios)
                            if (window.loadDashboardStats && typeof window.loadDashboardStats === 'function') {
                                await window.loadDashboardStats();
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
                        currentPage = 1;
                        renderEvaluationsTable();
                        updatePagination();
                    } else {
                        evaluationsTableBody.innerHTML = '<tr><td colspan="11" class="text-center text-slate-500 py-8">' + (result.message || 'No evaluations found') + '</td></tr>';
                        document.getElementById('evaluationCount').textContent = '0';
                    }
                } catch (error) {
                    console.error('Error loading evaluations:', error);
                    evaluationsTableBody.innerHTML = '<tr><td colspan="11" class="text-center text-slate-500 py-8">Error loading data. Please try again.</td></tr>';
                    document.getElementById('evaluationCount').textContent = '0';
                }
            }

            // Filter evaluations
            function filterEvaluations() {
                const searchTerm = searchEvaluations.value.toLowerCase();
                const statusFilter = document.getElementById('filterStatus').value;
                
                filteredEvaluationsList = evaluationsList.filter(evaluation => {
                    const studentName = (evaluation.student_name || '').toLowerCase();
                    const office = (evaluation.office || '').toLowerCase();
                    const status = (evaluation.status || '').toLowerCase();
                    
                    const matchesSearch = !searchTerm || 
                        studentName.includes(searchTerm) || 
                        office.includes(searchTerm);
                    
                    const matchesStatus = !statusFilter || status === statusFilter.toLowerCase();
                    
                    return matchesSearch && matchesStatus;
                });
                
                currentPage = 1; // Reset to first page when filtering
                renderEvaluationsTable();
                updatePagination();
            }

            // Render evaluations table with pagination
            function renderEvaluationsTable() {
                if (filteredEvaluationsList.length === 0) {
                    evaluationsTableBody.innerHTML = '<tr><td colspan="11" class="text-center text-slate-500 py-8">No evaluations found</td></tr>';
                    document.getElementById('evaluationCount').textContent = '0';
                    return;
                }

                // Calculate pagination
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedEvaluations = filteredEvaluationsList.slice(startIndex, endIndex);

                evaluationsTableBody.innerHTML = paginatedEvaluations.map((evaluation, index) => {
                    const globalIndex = startIndex + index + 1;
                    return `
                    <tr>
                        <td class="whitespace-nowrap">${globalIndex}</td>
                        <td class="whitespace-nowrap">${evaluation.student_name || 'N/A'}</td>
                        <td class="whitespace-nowrap">${evaluation.office || 'N/A'}</td>
                        <td class="whitespace-nowrap">${evaluation.formatted_date || 'N/A'}</td>
                        <td class="whitespace-nowrap">${evaluation.rated_by || 'N/A'}</td>
                        <td class="whitespace-nowrap text-center">${evaluation.total_score || 0}</td>
                        <td class="whitespace-nowrap text-center">${evaluation.average_score || '0.0'}</td>
                        <td class="whitespace-nowrap">
                            <span class="badge ${getRatingBadgeClass(evaluation.overall_rating)} rounded px-2 py-1 whitespace-nowrap">
                                ${evaluation.overall_rating || 'N/A'}
                            </span>
                        </td>
                        <td class="whitespace-nowrap">
                            <span class="badge ${getStatusBadgeClass(evaluation.status)} rounded px-2 py-1 whitespace-nowrap">
                                ${evaluation.status || 'Pending'}
                            </span>
                        </td>
                        <td class="whitespace-nowrap">
                            <div class="flex items-center gap-1">
                                <button class="btn btn-primary btn-sm text-white mr-2" onclick="viewEvaluation(${evaluation.id})" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="btn btn-warning btn-sm text-white mr-2" onclick="editEvaluation(${evaluation.id})" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button class="btn btn-danger btn-sm text-white" onclick="deleteEvaluation(${evaluation.id})" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                }).join('');
                
                // Update evaluation count
                document.getElementById('evaluationCount').textContent = filteredEvaluationsList.length;
                
                reloadLucideIcons();
            }

            // Update pagination controls
            function updatePagination() {
                const totalPages = Math.ceil(filteredEvaluationsList.length / itemsPerPage);
                document.getElementById('currentPage').textContent = currentPage;
                document.getElementById('totalPages').textContent = totalPages || 1;
                
                const prevBtn = document.getElementById('prevPage');
                const nextBtn = document.getElementById('nextPage');
                
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage >= totalPages || totalPages === 0;
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
                        return 'bg-pending text-white';
                    case 'Archived':
                        return 'bg-warning text-white';
                    default:
                        return 'bg-primary text-white';
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
                        // Use stored questions if available, otherwise use default
                        const questions = evaluation.criteria_questions || {};
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
                        
                        const details = `
Evaluation Details:

Student Name: ${evaluation.student_name || 'N/A'}
Office: ${evaluation.office || 'N/A'}
Evaluation Date: ${evaluation.evaluation_date || 'N/A'}
Rated By: ${evaluation.rated_by || 'N/A'}
Head of Office: ${evaluation.head_of_office || 'N/A'}

Ratings:
1. ${questions.rate1 || defaultQuestions.rate1}: ${evaluation.rate1 || 0}/10
2. ${questions.rate2 || defaultQuestions.rate2}: ${evaluation.rate2 || 0}/10
3. ${questions.rate3 || defaultQuestions.rate3}: ${evaluation.rate3 || 0}/10
4. ${questions.rate4 || defaultQuestions.rate4}: ${evaluation.rate4 || 0}/10
5. ${questions.rate5 || defaultQuestions.rate5}: ${evaluation.rate5 || 0}/10
6. ${questions.rate6 || defaultQuestions.rate6}: ${evaluation.rate6 || 0}/10
7. ${questions.rate7 || defaultQuestions.rate7}: ${evaluation.rate7 || 0}/10
8. ${questions.rate8 || defaultQuestions.rate8}: ${evaluation.rate8 || 0}/10
9. ${questions.rate9 || defaultQuestions.rate9}: ${evaluation.rate9 || 0}/10
10. ${questions.rate10 || defaultQuestions.rate10}: ${evaluation.rate10 || 0}/10

Total Score: ${evaluation.total_score || 0}
Average Score: ${evaluation.average_score || '0.0'}
Overall Rating: ${evaluation.overall_rating || 'N/A'}
Status: ${evaluation.status || 'Pending'}

Comments: ${evaluation.comments || 'None'}
                        `.trim();
                        alert(details);
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
                        
                        // Load stored questions if available
                        if (evaluation.criteria_questions) {
                            for (let i = 1; i <= 10; i++) {
                                const key = `rate${i}`;
                                if (evaluation.criteria_questions[key]) {
                                    document.getElementById(`question${i}`).value = evaluation.criteria_questions[key];
                                    document.getElementById(`label${i}`).textContent = evaluation.criteria_questions[key];
                                }
                            }
                        }
                        
                        // Find student assistant by name to get ID
                        const sa = studentAssistantsList.find(s => 
                            (s.full_name || s.name) === evaluation.student_name
                        );
                        
                        // Populate form
                        document.getElementById('studentName').value = sa ? sa.id : '';
                        if (!sa && evaluation.student_name) {
                            // If student not found in current list, add as option
                            const studentSelect = document.getElementById('studentName');
                            const option = document.createElement('option');
                            option.value = evaluation.student_name;
                            option.textContent = evaluation.student_name;
                            option.selected = true;
                            studentSelect.appendChild(option);
                        }
                        // Set office field
                        const officeReadonly = document.getElementById('officeReadonly');
                        const officeSelect = document.getElementById('officeSelect');
                        const officeValue = evaluation.office || currentOffice;
                        if (officeValue) {
                            if (currentOffice && officeReadonly) {
                                // Office Head - use readonly field
                                officeReadonly.value = officeValue;
                                officeReadonly.style.display = 'block';
                                officeReadonly.required = true;
                                if (officeSelect) {
                                    officeSelect.style.display = 'none';
                                    officeSelect.required = false;
                                }
                            } else if (officeSelect) {
                                // HR - use dropdown, check if value exists in options
                                officeSelect.style.display = 'block';
                                officeSelect.required = true;
                                if (officeReadonly) {
                                    officeReadonly.style.display = 'none';
                                    officeReadonly.required = false;
                                }
                                const officeOption = Array.from(officeSelect.options).find(opt => opt.value === officeValue);
                                if (officeOption) {
                                    officeSelect.value = officeValue;
                                } else {
                                    // Office not in dropdown, show warning
                                    officeSelect.value = '';
                                    showMessage(`Evaluation office "${officeValue}" is not in the office list. Please select an office manually.`, 'warning');
                                }
                            }
                        } else {
                            // No office in evaluation
                            if (!currentOffice && officeSelect) {
                                officeSelect.style.display = 'block';
                                officeSelect.required = true;
                                if (officeReadonly) {
                                    officeReadonly.style.display = 'none';
                                    officeReadonly.required = false;
                                }
                                officeSelect.value = '';
                                showMessage('This evaluation has no office assigned. Please select an office.', 'warning');
                            }
                        }
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
            document.getElementById('filterStatus').addEventListener('change', filterEvaluations);
            refreshEvaluations.addEventListener('click', () => {
                currentPage = 1;
                loadEvaluations();
            });
            
            // Pagination event listeners
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderEvaluationsTable();
                    updatePagination();
                }
            });
            
            document.getElementById('nextPage').addEventListener('click', () => {
                const totalPages = Math.ceil(filteredEvaluationsList.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderEvaluationsTable();
                    updatePagination();
                }
            });


            // Initialize on page load
            async function init() {
                await loadOffices();
                await loadStudentAssistants();
                await loadCriteriaQuestions();
                await loadEvaluations();
                reloadLucideIcons();
            }

            // Initialize evaluations and student assistants on page load
            init();

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
