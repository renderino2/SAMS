@extends('../layout/' . $layout)

@section('subhead')
    <title>Office Head Dashboard - SAMS</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Office Head Dashboard Header -->
                <div class="col-span-12 mt-4">
                    <div class="intro-y box p-5">
                        <div class="flex items-center">
                            <h1 class="text-xl md:text-2xl font-medium">Office Head Dashboard</h1>
                            <div class="ml-auto">
                                <div class="w-5 h-5 bg-success rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Office Head Dashboard Header -->

                <!-- BEGIN: Stats Cards -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="users" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="assignedCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Assigned Student Assistants</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="attendanceCount">—</div>
                            <div class="text-base text-slate-500 mt-1">Attendance Today</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex items-center">
                                <i data-lucide="clipboard-check" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6" id="evalStatus">—</div>
                            <div class="text-base text-slate-500 mt-1">Evaluation Status</div>
                        </div>
                    </div>
                </div>
                <!-- END: Stats Cards -->

                <!-- BEGIN: DTR Summary Table -->
                <div class="col-span-12 mt-8 intro-y">
                    <div class="box p-5">
                        <div class="flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">DTR Summary per Assistant</h2>
                        </div>
                        <div class="overflow-x-auto mt-5">
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">NAME</th>
                                        <th class="whitespace-nowrap">ATTENDANCE</th>
                                        <th class="whitespace-nowrap">TIME IN / OUT</th>
                                        <th class="whitespace-nowrap">STATUS</th>
                                        <th class="whitespace-nowrap">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody id="dtrTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">Loading DTR data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-center mt-6 gap-3">
                            <button class="btn btn-success" onclick="approveOvertime()">
                                <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Approve Overtime
                            </button>
                            <button class="btn btn-danger" onclick="markAbsence()">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Mark Absence
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END: DTR Summary Table -->
            </div>
        </div>

        <!-- BEGIN: Side Panels -->
        <div class="col-span-12 2xl:col-span-3">
            <div class="2xl:border-l -mb-10 pb-10">
                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                    <!-- BEGIN: Quick Actions -->
                    
                    <!-- END: Quick Actions -->

                    <!-- BEGIN: Recent Activities -->
                    <div class="col-span-12 mt-3">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Recent Activities</h2>
                        </div>
                        <div class="mt-5 relative before:block before:absolute before:w-px before:h-[85%] before:bg-slate-200 before:dark:bg-darkmode-400 before:ml-5 before:mt-5">
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="user-check" class="w-5 h-5 text-success"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Student Assistant Check-in</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastCheckIn">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">New student assistant checked in</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Evaluation Submitted</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastEvaluation">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">New evaluation form submitted</div>
                                </div>
                            </div>
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center">
                                        <i data-lucide="send" class="w-5 h-5 text-warning"></i>
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">Request Review</div>
                                        <div class="text-xs text-slate-500 ml-auto" id="lastRequest">—</div>
                                    </div>
                                    <div class="text-slate-500 mt-1">New request pending review</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Recent Activities -->
                </div>
            </div>
        </div>
        <!-- END: Side Panels -->
    </div>

    <script>
        function approveOvertime() {
            // Implement approve overtime functionality
            console.log('Approving overtime...');
        }

        function markAbsence() {
            // Implement mark absence functionality
            console.log('Marking absence...');
        }
    </script>
@endsection
