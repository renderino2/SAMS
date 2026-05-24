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
                        {{-- <a id="register-link" href="{{ route('register.index') }}" class="btn btn-outline-primary py-3 px-4 w-full xl:w-32 xl:mt-0 align-top">Register</a> --}}
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

            // Get user's current location
            function getCurrentLocation() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Geolocation is not supported by your browser.'))
                        return
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            resolve({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude
                            })
                        },
                        (error) => {
                            let errorMessage = 'Unable to retrieve your location.'
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = 'Location access denied. Please enable location services to log in.'
                                    break
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = 'Location information is unavailable.'
                                    break
                                case error.TIMEOUT:
                                    errorMessage = 'Location request timed out. Please try again.'
                                    break
                            }
                            reject(new Error(errorMessage))
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    )
                })
            }

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
                await helper.delay(500)

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                if (!csrfToken) {
                    console.error('CSRF token not found')
                    $('#btn-login').html('Login')
                    $('#email').addClass('border-danger')
                    $('#error-email').html('Security token missing. Please refresh the page.')
                    return
                }

                // Prepare login data
                let loginData = {
                    email: email,
                    password: password
                }

                // Get location for Student Assistants (we'll check after getting user info, but request it now)
                // For now, we'll always request location and let the backend decide if it's needed
                let locationError = null
                try {
                    const location = await getCurrentLocation()
                    // Ensure coordinates are numbers, not strings
                    loginData.latitude = parseFloat(location.latitude)
                    loginData.longitude = parseFloat(location.longitude)
                    
                    // Validate coordinates
                    if (isNaN(loginData.latitude) || isNaN(loginData.longitude)) {
                        throw new Error('Invalid location coordinates received')
                    }
                    
                    console.log('Location captured:', {
                        latitude: loginData.latitude,
                        longitude: loginData.longitude
                    })
                } catch (error) {
                    // If location fails, we'll still try to login
                    // The backend will check if the user is a Student Assistant and reject if location is missing
                    locationError = error
                    console.warn('Location error:', error.message)
                    // Don't add location to loginData - backend will handle the error
                }

                await helper.delay(1000)

                // Make sure axios has CSRF token configured
                if (window.axios && !window.axios.defaults.headers.common['X-CSRF-TOKEN']) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken
                }

                axios.post(`login`, loginData, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(res => {
                    // Redirect based on role
                    let redirectUrl = res.data.redirect || '/'
                    location.href = redirectUrl
                }).catch(err => {
                    $('#btn-login').html('Login')
                    console.error('Login error:', err)
                    
                    if (err.response && err.response.data) {
                        const responseData = err.response.data
                        
                        // Handle validation errors
                        if (responseData.errors) {
                            for (const [key, val] of Object.entries(responseData.errors)) {
                                $(`#${key}`).addClass('border-danger')
                                $(`#error-${key}`).html(Array.isArray(val) ? val[0] : val)
                            }
                            return
                        }
                        
                        // Handle error messages
                        if (responseData.message) {
                            const errorMessage = responseData.message
                            const distance = responseData.distance
                            
                            // Build full error message with distance if available
                            let fullMessage = errorMessage
                            if (distance !== undefined && distance !== null) {
                                fullMessage += ` (Distance: ${distance} meters)`
                            }
                            
                            if (errorMessage.includes('Location access is required') || 
                                errorMessage.includes('outside the campus area') ||
                                errorMessage.includes('location')) {
                                // Location-related errors - show on both fields or a general message
                                $('#email').addClass('border-danger')
                                $('#error-email').html(fullMessage)
                                $('#password').addClass('border-danger')
                                if (locationError) {
                                    $('#error-password').html('Location access failed: ' + locationError.message)
                                } else {
                                    $('#error-password').html('Please enable location services and try again.')
                                }
                            } else if (errorMessage.includes('User not found') || errorMessage.includes('Invalid credentials')) {
                                $('#email').addClass('border-danger')
                                $('#error-email').html(errorMessage)
                            } else if (errorMessage.includes('password')) {
                                $('#password').addClass('border-danger')
                                $('#error-password').html(errorMessage)
                            } else {
                                // Generic error
                                $('#email').addClass('border-danger')
                                $('#error-email').html(fullMessage)
                            }
                            return
                        }
                    }
                    
                    // Network or other error
                    $('#email').addClass('border-danger')
                    $('#error-email').html('Network error. Please try again.')
                    console.error('Unexpected error:', err)
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
