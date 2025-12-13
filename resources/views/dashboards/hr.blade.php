@extends('../layout/' . $layout)

@section('subhead')
    <title>HR Dashboard - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: HR Overview Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <h1 class="text-xl md:text-2xl font-medium">HR Dashboard Overview</h1>
                        <p class="text-slate-500 mt-2">Monitor contract activity, student assistant attendance, and HR actions.</p>
                    </div>
                </div>
                <!-- END: HR Overview Header -->

                <!-- BEGIN: KPI Cards -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="users" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="totalSAs">0</div>
                            <div class="text-base text-slate-500 mt-1">Student Assistants</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="file-check-2" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="activeContracts">0</div>
                            <div class="text-base text-slate-500 mt-1">Active Contracts</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="clipboard-list" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="pendingEvals">0</div>
                            <div class="text-base text-slate-500 mt-1">Pending Evaluations</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="alarm-clock" class="report-box__icon text-danger"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="expiringContracts">0</div>
                            <div class="text-base text-slate-500 mt-1">Expiring Contracts</div>
                        </div>
                    </div>
                </div>
                <!-- END: KPI Cards -->

                <!-- BEGIN: Attendance Summary -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5"> Attendance Summary</h2>
                        </div>
                        <div class="overflow-x-auto mt-5">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">OFFICE</th>
                                        <th class="whitespace-nowrap">TOTAL</th>
                                        <th class="whitespace-nowrap">PRESENT</th>
                                        <th class="whitespace-nowrap">LATE</th>
                                        <th class="whitespace-nowrap">ABSENT</th>
                                        <th class="whitespace-nowrap">OVERTIME</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-slate-500">Loading attendance data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END: Attendance Summary -->
            </div>
        </div>

        <!-- BEGIN: Side Panels -->
        <div class="col-span-12 2xl:col-span-3">
            <div class="2xl:border-l -mb-10 pb-10">
                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                    <!-- BEGIN: Recent Activity -->
                    <div class="col-span-12 mt-3 2xl:mt-8">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Recent Activity</h2>
                        </div>
                        <div class="mt-5 box p-5">
                            <ul id="notificationsList" class="list-disc pl-5 text-slate-600">
                                <li>Loading notifications...</li>
                            </ul>
                        </div>
                    </div>
                    <!-- END: Recent Activity -->

                    <!-- BEGIN: Quick Actions -->
                    <div class="col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Quick Actions</h2>
                        </div>
                        <div class="mt-5 intro-y grid grid-cols-1 gap-3">
                            <a href="{{ route('student.assistants') }}" class="btn box text-left">Manage Student Assistants</a>
                            <a href="{{ route('contract.management') }}" class="btn box text-left">Manage Contracts</a>
                            <a href="{{ route('reports') }}" class="btn box text-left">View Reports</a>
                            <a href="{{ route('evaluation.review') }}" class="btn box text-left">Review Evaluations</a>
                        </div>
                    </div>
                    <!-- END: Quick Actions -->
                </div>
            </div>
        </div>
        <!-- END: Side Panels -->
    </div>
@endsection

