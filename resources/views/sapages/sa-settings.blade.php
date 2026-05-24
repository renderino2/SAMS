@extends('../layout/' . $layout)

@section('subhead')
    <title>Account Settings - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                            Account Settings
                        </h1>
                        <p class="text-slate-500 mt-2">Manage your login credentials and personal details securely.</p>
                    </div>
                </div>

                <!-- Update Account Details -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
                            Update Account Details
                        </h2>
                        
                        <form id="settingsForm" class="grid grid-cols-12 gap-4" enctype="multipart/form-data">
                            <!-- Profile Photo Section -->
                            <div class="col-span-12 mb-4">
                                <label class="form-label">Profile Photo:</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-700">
                                        <img id="profilePhotoPreview" 
                                             src="{{ Auth::user()->profile_photo ? asset('storage/profiles/' . Auth::user()->profile_photo) : asset('build/assets/images/placeholders/200x200.jpg') }}" 
                                             alt="Profile Photo" 
                                             class="w-full h-full object-cover" data-action="zoom">
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="profile_photo" name="profile_photo" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif" 
                                               class="form-control" />
                                        <div class="text-xs text-slate-500 mt-1">
                                            Maximum file size: 2MB. Allowed formats: JPG, PNG, GIF
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="full_name" class="form-label">Full Name:</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" 
                                       value="{{ Auth::user()->full_name ?? Auth::user()->name }}" required />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="{{ Auth::user()->email }}" required />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="student_id" class="form-label">Student ID:</label>
                                <input type="text" id="student_id" name="student_id" class="form-control" 
                                       value="{{ Auth::user()->student_id_number }}" readonly />
                                <div class="text-xs text-slate-500 mt-1">Student ID cannot be changed</div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="contact" class="form-label">Contact Number:</label>
                                <input type="text" id="contact" name="contact" class="form-control" 
                                       value="{{ Auth::user()->contact }}" 
                                       pattern="^09\d{9}$" 
                                       title="Please enter a valid 11-digit mobile number starting with 09" />
                            </div>
                            <div class="col-span-12">
                                <label for="address" class="form-label">Address:</label>
                                <textarea id="address" name="address" class="form-control" rows="3" 
                                          placeholder="Enter your complete address">{{ Auth::user()->address }}</textarea>
                            </div>
                            @if(Auth::user()->role === 'Student Assistant')
                            <div class="col-span-12">
                                <label for="guardian_address" class="form-label">Guardian Address:</label>
                                <textarea id="guardian_address" name="guardian_address" class="form-control" rows="3" 
                                          placeholder="Enter your guardian's complete address">{{ Auth::user()->guardian_address }}</textarea>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="course" class="form-label">Course:</label>
                                <input type="text" id="course" name="course" class="form-control" 
                                       value="{{ Auth::user()->course }}" 
                                       placeholder="e.g., BS Computer Science" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="year_level" class="form-label">Year Level:</label>
                                <input type="text" id="year_level" name="year_level" class="form-control" 
                                       value="{{ Auth::user()->year_level }}" 
                                       placeholder="e.g., 1st Year, 2nd Year" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="section" class="form-label">Section:</label>
                                <input type="text" id="section" name="section" class="form-control" 
                                       value="{{ Auth::user()->section }}" 
                                       placeholder="e.g., A, B, C" />
                            </div>
                            @endif
                            <div class="col-span-12 md:col-span-6">
                                <label for="role" class="form-label">Role:</label>
                                <input type="text" id="role" name="role" class="form-control" 
                                       value="{{ Auth::user()->role }}" readonly />
                                <div class="text-xs text-slate-500 mt-1">Role cannot be changed</div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="office" class="form-label">Office:</label>
                                <input type="text" id="office" name="office" class="form-control" 
                                       value="{{ Auth::user()->office }}" readonly />
                                <div class="text-xs text-slate-500 mt-1">Office cannot be changed</div>
                            </div>
                            <div class="col-span-12">
                                <hr class="my-4">
                                <h3 class="text-md font-medium mb-3">Change Password</h3>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="current_password" class="form-label">Current Password:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" 
                                       placeholder="Enter current password to change" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="new_password" class="form-label">New Password:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" 
                                       placeholder="Enter new password" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="confirm_password" class="form-label">Confirm New Password:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                       placeholder="Confirm new password" />
                            </div>
                        </form>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>

                <!-- Security Information -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="shield" class="w-5 h-5 mr-2"></i>
                            Security Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <span class="font-medium text-slate-600 w-32">Account Status:</span>
                                <span class="badge bg-success text-white rounded p-1">Active</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-slate-600 w-32">Last Updated:</span>
                                <span class="text-slate-800">{{ Auth::user()->updated_at ? Auth::user()->updated_at->format('M d, Y H:i') : 'Never' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-slate-600 w-32">Member Since:</span>
                                <span class="text-slate-800">{{ Auth::user()->created_at ? Auth::user()->created_at->format('M d, Y') : 'Unknown' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-slate-600 w-32">Email Verified:</span>
                                <span class="badge {{ Auth::user()->email_verified_at ? 'bg-success' : 'bg-warning' }} text-white rounded p-1">
                                    {{ Auth::user()->email_verified_at ? 'Verified' : 'Pending' }}
                                </span>
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
            const settingsForm = document.getElementById('settingsForm');
            const messageDiv = document.getElementById('message');

            // Handle profile photo preview
            const profilePhotoInput = document.getElementById('profile_photo');
            const profilePhotoPreview = document.getElementById('profilePhotoPreview');
            
            profilePhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showMessage('File size must be less than 2MB', 'error');
                        e.target.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        showMessage('Only JPG, PNG, and GIF images are allowed', 'error');
                        e.target.value = '';
                        return;
                    }
                    
                    // Preview image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePhotoPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle form submission
            settingsForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(settingsForm);
                
                // Validate passwords if any password field is filled
                const currentPassword = formData.get('current_password');
                const newPassword = formData.get('new_password');
                const confirmPassword = formData.get('confirm_password');
                
                if (currentPassword || newPassword || confirmPassword) {
                    if (!currentPassword) {
                        showMessage('Current password is required to change password', 'error');
                        return;
                    }
                    if (!newPassword) {
                        showMessage('New password is required', 'error');
                        return;
                    }
                    if (newPassword !== confirmPassword) {
                        showMessage('New passwords do not match', 'error');
                        return;
                    }
                    if (newPassword.length < 8) {
                        showMessage('New password must be at least 8 characters long', 'error');
                        return;
                    }
                }

                // Validate contact number if provided
                const contact = formData.get('contact');
                if (contact && !/^09\d{9}$/.test(contact)) {
                    showMessage('Please enter a valid 11-digit mobile number starting with 09', 'error');
                    return;
                }

                // Show loading state - get both the form button and fixed button
                const formSubmitBtn = settingsForm.querySelector('button[type="submit"]');
                const fixedSubmitBtn = document.querySelector('button[form="settingsForm"]');
                const submitBtn = fixedSubmitBtn || formSubmitBtn;
                
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Updating...';
                reloadLucideIcons();

                try {
                    const response = await fetch('/settings/update-account', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        showMessage(result.message || 'Account updated successfully!', 'success');
                        
                        // Update the page with new data if needed
                        if (result.user) {
                            // Update form fields with new data
                            document.getElementById('full_name').value = result.user.full_name || result.user.name;
                            document.getElementById('email').value = result.user.email;
                            document.getElementById('contact').value = result.user.contact || '';
                            
                            // Update address fields
                            if (result.user.address !== undefined) {
                                document.getElementById('address').value = result.user.address || '';
                            }
                            @if(Auth::user()->role === 'Student Assistant')
                            if (result.user.guardian_address !== undefined) {
                                document.getElementById('guardian_address').value = result.user.guardian_address || '';
                            }
                            if (result.user.course !== undefined) {
                                document.getElementById('course').value = result.user.course || '';
                            }
                            if (result.user.year_level !== undefined) {
                                document.getElementById('year_level').value = result.user.year_level || '';
                            }
                            if (result.user.section !== undefined) {
                                document.getElementById('section').value = result.user.section || '';
                            }
                            @endif
                            
                            // Update profile photo if uploaded
                            if (result.user.profile_photo) {
                                profilePhotoPreview.src = result.user.profile_photo;
                            }
                        }
                        
                        // Clear password fields
                        document.getElementById('current_password').value = '';
                        document.getElementById('new_password').value = '';
                        document.getElementById('confirm_password').value = '';
                        
                        // Clear file input
                        profilePhotoInput.value = '';
                        
                    } else {
                        showMessage(result.message || 'Failed to update account', 'error');
                    }
                } catch (error) {
                    console.error('Error updating account:', error);
                    showMessage('An error occurred while updating account', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    reloadLucideIcons();
                }
            });

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

            // Real-time password confirmation validation
            document.getElementById('confirm_password').addEventListener('input', function() {
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = this.value;
                
                if (confirmPassword && newPassword !== confirmPassword) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });

            document.getElementById('new_password').addEventListener('input', function() {
                const confirmPassword = document.getElementById('confirm_password');
                if (confirmPassword.value) {
                    confirmPassword.dispatchEvent(new Event('input'));
                }
            });

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
