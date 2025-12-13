@extends('../layout/' . $layout)

@section('head')
    <title>Login - SAMS</title>
@endsection

@section('content')
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    {{-- <img alt="SAMS - Student Assistant Management System" class="w-6" src="{{ asset('build/assets/images/logo.svg') }}"> --}}
                    <span class="text-white text-lg ml-3">
                        SAMS
                    </span>
                </a>
                <div class="my-auto">
                    <img alt="CJC - LOGO" class="-intro-x w-1/2 -mt-16" src="{{ asset('build/assets/images/final-cjc-logo-1.png') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">Just a few more clicks <br> to log into your account.</div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Manage all your SAMS activities in one place</div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Sign In</h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your SAMS activities in one place</div>
                    <div class="intro-x mt-8">
                        <form id="login-form">
                            <input id="email" type="email" class="intro-x login__input form-control py-3 px-4 block" placeholder="Email Address" value="">
                            <div id="error-email" class="login__input-error text-danger mt-2"></div>
                            <input id="password" type="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Password" value="">
                            <div id="error-password" class="login__input-error text-danger mt-2"></div>
                        </form>
                    </div>
                    <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                        <div class="flex items-center mr-auto">
                            <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                            <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                        </div>
                        <a href="">Forgot Password?</a>
                    </div>
                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button id="btn-login" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                        <a id="register-link" href="{{ route('register.index') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 xl:mt-0 align-top">Register</a>
                    </div>
                    <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left">
                        By signin up, you agree to our <a class="text-primary dark:text-slate-200" href="">Terms and Conditions </a> & <a class="text-primary dark:text-slate-200" href="">Privacy Policy</a>
                    </div>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        (function () {
            // No role selection needed; always show Register link

            async function login() {
                // Reset state
                $('#login-form').find('.login__input').removeClass('border-danger')
                $('#login-form').find('.login__input-error').html('')

                // Get form values
                let email = $('#email').val().trim()
                let password = $('#password').val().trim()

                // Validate required fields
                if (!email || !password) {
                    if (!email) {
                        $('#email').addClass('border-danger')
                        $('#error-email').html('Email is required')
                    }
                    if (!password) {
                        $('#password').addClass('border-danger')
                        $('#error-password').html('Password is required')
                    }
                    return
                }

                // Loading state
                $('#btn-login').html('<i data-loading-icon="oval" data-color="white" class="w-5 h-5 mx-auto"></i>')
                tailwind.svgLoader()
                await helper.delay(1500)

                axios.post(`login`, {
                    email: email,
                    password: password
                }).then(res => {
                    // Redirect based on role
                    let redirectUrl = res.data.redirect || '/'
                    location.href = redirectUrl
                }).catch(err => {
                    $('#btn-login').html('Login')
                    if (err.response && err.response.data && err.response.data.errors) {
                        for (const [key, val] of Object.entries(err.response.data.errors)) {
                            $(`#${key}`).addClass('border-danger')
                            $(`#error-${key}`).html(val)
                        }
                    } else if (err.response && err.response.data && err.response.data.message) {
                        // Handle specific error messages
                        if (err.response.data.message.includes('User not found') || err.response.data.message.includes('Invalid credentials')) {
                            $('#email').addClass('border-danger')
                            $('#error-email').html(err.response.data.message)
                        } else if (err.response.data.message.includes('password')) {
                            $('#password').addClass('border-danger')
                            $('#error-password').html(err.response.data.message)
                        } else {
                            // Generic error
                            $('#email').addClass('border-danger')
                            $('#error-email').html(err.response.data.message)
                        }
                    } else {
                        // Network or other error
                        $('#email').addClass('border-danger')
                        $('#error-email').html('Network error. Please try again.')
                    }
                })
            }

            $('#login-form').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    login()
                }
            })

            $('#btn-login').on('click', function() {
                login()
            })
        })()
    </script>
@endsection
