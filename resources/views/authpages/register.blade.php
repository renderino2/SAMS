@extends('../layout/' . $layout)

@section('head')
    <title>Register - SAMS</title>
@endsection

@section('content')
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Register Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    {{-- <img alt="SAMS - Student Assistant Management System" class="w-6" src="{{ asset('build/assets/images/logo.svg') }}"> --}}
                    <span class="text-white text-lg ml-3">
                        SAMS
                    </span>
                </a>
                <div class="my-auto">
                    <img alt="SAMS - Student Assistant Management System" class="-intro-x w-1/2 -mt-16" src="{{ asset('build/assets/images/final-cjc-logo-1.png') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">A few more clicks to <br> make your account.</div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Join the SAMS community and manage your activities</div>
                </div>
            </div>
            <!-- END: Register Info -->
            <!-- BEGIN: Register Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Register to SAMS</h2>
                    <div class="intro-x mt-2 text-slate-400 dark:text-slate-400 xl:hidden text-center">A few more clicks to register to your account. Join the SAMS community</div>
                    <div class="intro-x mt-8">
                        <form id="register-form">
                            <input id="studentId" type="text" class="intro-x login__input form-control py-3 px-4 block" placeholder="Student ID Number (e.g., 23-12345)" required>
                            <div id="error-studentId" class="login__input-error text-danger mt-2"></div>
                            
                            <input id="fullName" type="text" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Full Name" required>
                            <div id="error-fullName" class="login__input-error text-danger mt-2"></div>
                            
                            <input id="email" type="email" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Email Address" required>
                            <div id="error-email" class="login__input-error text-danger mt-2"></div>
                            
                            <select id="role" class="intro-x login__input form-control py-3 px-4 block mt-4" required>
                                <option value="">Select Role</option>
                                <option value="Student Assistant">Student Assistant</option>
                                <option value="Office Head">Office Head</option>
                                <option value="HR">HR</option>
                            </select>
                            <div id="error-role" class="login__input-error text-danger mt-2"></div>
                            
                            <div id="officeGroup" style="display: none;">
                                <select id="office" class="intro-x login__input form-control py-3 px-4 block mt-4">
                                    <option value="">Select Office</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Library">Library</option>
                                    <option value="Guidance">Guidance</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Clinic">Clinic</option>
                                </select>
                                <div id="error-office" class="login__input-error text-danger mt-2"></div>
                            </div>
                            
                            <input id="password" type="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Password" required>
                            <div id="error-password" class="login__input-error text-danger mt-2"></div>
                            
                            <input id="confirmPassword" type="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Confirm Password" required>
                            <div id="error-confirmPassword" class="login__input-error text-danger mt-2"></div>
                        </form>
                    </div>
                    <div class="intro-x flex items-center text-slate-600 dark:text-slate-500 mt-4 text-xs sm:text-sm">
                        <input id="agree-terms" type="checkbox" class="form-check-input border mr-2" required>
                        <label class="cursor-pointer select-none" for="agree-terms">I agree to the SAMS</label>
                        <a class="text-primary dark:text-slate-200 ml-1" href="">Terms and Conditions & </a> 
                        <a class="text-primary dark:text-slate-200 ml-1" href="">Privacy Policy</a>.
                    </div>
                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button id="btn-register" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" style="display: none;">Register</button>
                        <a href="{{ route('login.index') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 xl:mt-0 align-top">Sign in</a>
                    </div>
                    <div id="message" class="intro-x mt-4 text-center text-sm"></div>
                </div>
            </div>
            <!-- END: Register Form -->
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        (function () {
            // Show/hide office field and register button based on role selection
            $('#role').on('change', function() {
                const officeGroup = $('#officeGroup');
                const registerButton = $('#btn-register');
                const role = $(this).val();
                
                // Show/hide office field
                if (role === 'Office Head' || role === 'Student Assistant') {
                    officeGroup.show();
                } else {
                    officeGroup.hide();
                    $('#office').val('');
                }
                
                // Show/hide register button - only HR can register
                if (role === 'HR') {
                    registerButton.show();
                } else {
                    registerButton.hide();
                }
            });

            async function register() {
                // Reset state
                $('#register-form').find('.login__input').removeClass('border-danger');
                $('#register-form').find('.login__input-error').html('');
                $('#message').html('').removeClass('text-danger text-success');

                // Get form values
                let studentId = $('#studentId').val().trim();
                let fullName = $('#fullName').val().trim();
                let email = $('#email').val().trim();
                let role = $('#role').val();
                let office = $('#office').val();
                let password = $('#password').val();
                let confirmPassword = $('#confirmPassword').val();

                // Validate required fields
                let hasErrors = false;
                if (!studentId) {
                    $('#studentId').addClass('border-danger');
                    $('#error-studentId').html('Student ID is required');
                    hasErrors = true;
                }
                if (!fullName) {
                    $('#fullName').addClass('border-danger');
                    $('#error-fullName').html('Full Name is required');
                    hasErrors = true;
                }
                if (!email) {
                    $('#email').addClass('border-danger');
                    $('#error-email').html('Email is required');
                    hasErrors = true;
                }
                if (!role) {
                    $('#role').addClass('border-danger');
                    $('#error-role').html('Role is required');
                    hasErrors = true;
                }
                if ((role === 'Office Head' || role === 'Student Assistant') && !office) {
                    $('#office').addClass('border-danger');
                    $('#error-office').html('Office is required for this role');
                    hasErrors = true;
                }
                if (!password) {
                    $('#password').addClass('border-danger');
                    $('#error-password').html('Password is required');
                    hasErrors = true;
                }
                if (!confirmPassword) {
                    $('#confirmPassword').addClass('border-danger');
                    $('#error-confirmPassword').html('Password confirmation is required');
                    hasErrors = true;
                }

                if (hasErrors) {
                    return;
                }

                // Validate password match
                if (password !== confirmPassword) {
                    $('#confirmPassword').addClass('border-danger');
                    $('#error-confirmPassword').html('Passwords do not match');
                    return;
                }

                // Loading state
                $('#btn-register').html('<i data-loading-icon="oval" data-color="white" class="w-5 h-5 mx-auto"></i>');
                tailwind.svgLoader();

                try {
                    const response = await axios.post('register', {
                        studentId: studentId,
                        fullName: fullName,
                        email: email,
                        role: role,
                        office: office,
                        password: password,
                        confirmPassword: confirmPassword
                    });

                    if (response.data.success) {
                        $('#message').html('✅ Registration successful! Redirecting to login...').addClass('text-success');
                        setTimeout(() => {
                            window.location.href = "{{ route('login.index') }}";
                        }, 2000);
                    }
                } catch (error) {
                    $('#btn-register').html('Register');
                    
                    if (error.response && error.response.data && error.response.data.errors) {
                        // Handle validation errors
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            const fieldId = key === 'studentId' ? 'studentId' : 
                                          key === 'fullName' ? 'fullName' :
                                          key === 'confirmPassword' ? 'confirmPassword' : key;
                            $(`#${fieldId}`).addClass('border-danger');
                            $(`#error-${fieldId}`).html(Array.isArray(val) ? val[0] : val);
                        }
                    } else if (error.response && error.response.data && error.response.data.message) {
                        $('#message').html('❌ ' + error.response.data.message).addClass('text-danger');
                    } else {
                        $('#message').html('⚠️ Network error. Please try again.').addClass('text-danger');
                    }
                }
            }

            $('#register-form').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    register();
                }
            });

            $('#btn-register').on('click', function() {
                register();
            });
        })();
    </script>
@endsection
