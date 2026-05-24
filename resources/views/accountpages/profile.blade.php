@extends('../layout/' . $layout)

@section('subhead')
    <title>My Profile - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                            My Profile
                        </h1>
                        <p class="text-slate-500 mt-2">View and manage your personal and academic details.</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="col-span-12 intro-y">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-4 flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Basic Information
                        </h2>
                        <div class="profile-info" id="profileData">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Student ID:</span>
                                    <span id="sid" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Full Name:</span>
                                    <span id="fullName" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Email:</span>
                                    <span id="email" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Contact:</span>
                                    <span id="contact" class="text-slate-800">—</span>
                                </div>
                                @if(Auth::user()->role === 'Student Assistant')
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Gender:</span>
                                    <span id="gender" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Course:</span>
                                    <span id="course" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Year Level:</span>
                                    <span id="yearLevel" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Section:</span>
                                    <span id="section" class="text-slate-800">—</span>
                                </div>
                               
                                
                                @endif
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Role:</span>
                                    <span id="role" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Office:</span>
                                    <span id="office" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Address:</span>
                                    <span id="address" class="text-slate-800">—</span>
                                </div>
                                @if(Auth::user()->role === 'Student Assistant')
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Guardian Address:</span>
                                    <span id="guardianAddress" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Contract Start:</span>
                                    <span id="contractStart" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Contract End:</span>
                                    <span id="contractEnd" class="text-slate-800">—</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-slate-600 w-24">Scheduled Time In:</span>
                                    <span id="scheduledTimeIn" class="text-slate-800">—</span>
                                </div>
                                @endif
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
            // Load profile data on page load
            function loadProfileData() {
                // Get user data from Laravel
                const user = @json(Auth::user());
                
                if (user) {
                    document.getElementById('sid').textContent = user.student_id_number || '—';
                    document.getElementById('fullName').textContent = user.full_name || user.name || '—';
                    document.getElementById('email').textContent = user.email || '—';
                    document.getElementById('contact').textContent = user.contact || '—';
                    document.getElementById('role').textContent = user.role || '—';
                    document.getElementById('office').textContent = user.office || '—';
                    document.getElementById('address').textContent = user.address || '—';
                    
                    @if(Auth::user()->role === 'Student Assistant')
                    // Format gender
                    const gender = user.gender || '—';
                    document.getElementById('gender').textContent = gender.charAt(0).toUpperCase() + gender.slice(1) || '—';
                    
                    document.getElementById('course').textContent = user.course || '—';
                    document.getElementById('yearLevel').textContent = user.year_level || '—';
                    document.getElementById('section').textContent = user.section || '—';
                    document.getElementById('guardianAddress').textContent = user.guardian_address || '—';
                    
                    // Format scheduled time in
                    if (user.scheduled_time_in) {
                        const timeParts = user.scheduled_time_in.split(':');
                        const hour = parseInt(timeParts[0]);
                        const minute = timeParts[1];
                        const ampm = hour >= 12 ? 'PM' : 'AM';
                        const displayHour = hour % 12 || 12;
                        document.getElementById('scheduledTimeIn').textContent = `${displayHour}:${minute} ${ampm}`;
                    } else {
                        document.getElementById('scheduledTimeIn').textContent = 'Not set';
                    }
                    
                    // Format contract dates
                    const formatDate = (dateString) => {
                        if (!dateString) return '—';
                        const date = new Date(dateString);
                        return date.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                    };
                    document.getElementById('contractStart').textContent = formatDate(user.contract_start_date);
                    document.getElementById('contractEnd').textContent = formatDate(user.contract_end_date);
                    @endif
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

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                loadProfileData();
                reloadLucideIcons();
            });
        })();
    </script>
@endsection
