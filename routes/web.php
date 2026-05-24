<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\ColorSchemeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root domain to login page (handles Cloudflare tunnel root hits)
Route::get('/', function () {
    return redirect()->route('login.index');
});

Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');
Route::get('color-scheme-switcher/{color_scheme}', [ColorSchemeController::class, 'switch'])->name('color-scheme-switcher');

Route::controller(AuthController::class)->middleware('loggedin')->group(function() {
    Route::get('login', 'loginView')->name('login.index');
    Route::post('login', 'login')->name('login.check');
    Route::get('register', 'registerView')->name('register.index');
    Route::post('register', 'register')->name('register.store');
});

Route::middleware('auth')->group(function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes
            Route::get('sa-dashboard', [AuthController::class, 'saDashboard'])->name('sa.dashboard');
            Route::get('officehead-dashboard', [AuthController::class, 'officeheadDashboard'])->name('officehead.dashboard');
            Route::get('hr-dashboard', [AuthController::class, 'hrDashboard'])->name('hr.dashboard');
Route::get('sa-attendance', [AuthController::class, 'saAttendance'])->name('sa.attendance');
Route::post('api/attendance/time-in', [AuthController::class, 'timeIn'])->name('attendance.time-in');
Route::post('api/attendance/time-out', [AuthController::class, 'timeOut'])->name('attendance.time-out');
Route::get('api/attendance/today', [AuthController::class, 'getTodayAttendance'])->name('attendance.today');
Route::get('api/attendance/history', [AuthController::class, 'getAttendanceHistory'])->name('attendance.history');
Route::get('sa-requests', [AuthController::class, 'saRequests'])->name('sa.requests');
Route::get('api/sa-requests', [AuthController::class, 'getSARequests'])->name('sa.requests.index');
Route::post('api/requests', [AuthController::class, 'createRequest'])->name('requests.create');
Route::post('api/officehead-sa-requests', [AuthController::class, 'createOfficeHeadSARequest'])->name('officehead.sa.requests.create');
Route::get('api/office-requests', [AuthController::class, 'getOfficeRequests'])->name('office.requests.index');
Route::get('api/all-requests', [AuthController::class, 'getAllRequests'])->name('all.requests.index');
Route::put('api/requests/{id}', [AuthController::class, 'processRequest'])->name('requests.process');
Route::get('sa-skill-inventory', [AuthController::class, 'saSkillInventory'])->name('sa.skill-inventory');
Route::get('api/sa-skills', [AuthController::class, 'getSASkills'])->name('sa.skills.index');
Route::post('api/skill-requests', [AuthController::class, 'createSkillRequest'])->name('skill.requests.create');
Route::get('api/office-skills', [AuthController::class, 'getOfficeSkills'])->name('office.skills.index');
Route::get('api/all-skills', [AuthController::class, 'getAllSkills'])->name('all.skills.index');
Route::put('api/skill-requests/{id}', [AuthController::class, 'processSkillRequest'])->name('skill.requests.process');
Route::get('profile', [AuthController::class, 'profile'])->name('profile');

Route::post('profile/update-contact', [AuthController::class, 'updateContact'])->name('profile.update-contact');
Route::get('settings', [AuthController::class, 'settings'])->name('settings');
Route::post('settings/update-account', [AuthController::class, 'updateAccount'])->name('settings.update-account');
Route::get('dtr-monitoring', [AuthController::class, 'dtrMonitoring'])->name('dtr.monitoring');
Route::get('api/office-attendances', [AuthController::class, 'getOfficeAttendances'])->name('office.attendances.index');
Route::get('api/user-attendance-details/{userId}', [AuthController::class, 'getUserAttendanceDetails'])->name('user.attendance.details');
Route::post('api/attendance/{id}/verify-photos', [AuthController::class, 'verifyAttendancePhotos'])->name('attendance.verify.photos');
Route::post('dtr-monitoring/review', [AuthController::class, 'reviewDTR'])->name('dtr.review');
Route::post('api/approve-overtime', [AuthController::class, 'approveOvertime'])->name('overtime.approve');
Route::post('api/mark-absence', [AuthController::class, 'markAbsence'])->name('absence.mark');
Route::get('evaluation-form', [AuthController::class, 'evaluationForm'])->name('evaluation.form');
Route::post('evaluation-form/submit', [AuthController::class, 'submitEvaluation'])->name('evaluation.submit');
Route::get('api/evaluations', [AuthController::class, 'getEvaluations'])->name('evaluations.index');
Route::get('api/evaluations/{id}', [AuthController::class, 'getEvaluation'])->name('evaluations.show');
Route::put('api/evaluations/{id}', [AuthController::class, 'updateEvaluation'])->name('evaluations.update');
Route::delete('api/evaluations/{id}', [AuthController::class, 'deleteEvaluation'])->name('evaluations.delete');
Route::get('api/evaluation-criteria-settings', [AuthController::class, 'getEvaluationCriteriaSettings'])->name('evaluation.criteria.settings');
Route::put('api/evaluation-criteria-settings', [AuthController::class, 'updateEvaluationCriteriaSettings'])->name('evaluation.criteria.settings.update');
Route::get('requests-review', [AuthController::class, 'requestsReview'])->name('requests.review');
Route::get('hr-requests-review', [AuthController::class, 'hrRequestsReview'])->name('hr.requests.review');
Route::post('requests-review/submit', [AuthController::class, 'submitRequestReview'])->name('requests.submit');
Route::get('sa-management', [AuthController::class, 'officeHeadSAManagement'])->name('officehead.sa.management');
Route::get('officehead-sa-account-management', [AuthController::class, 'officeHeadSAAccountManagement'])->name('officehead.sa.account.management');
Route::get('api/office-sas', [AuthController::class, 'getOfficeSAs'])->name('office.sas.index');
Route::put('api/office-sas/{id}', [AuthController::class, 'updateOfficeSA'])->name('office.sas.update');
Route::get('api/office-dashboard-stats', [AuthController::class, 'getOfficeDashboardStats'])->name('office.dashboard.stats');
Route::get('api/hr-dashboard-stats', [AuthController::class, 'getHRDashboardStats'])->name('hr.dashboard.stats');
Route::get('api/analytics', [AuthController::class, 'getAnalytics'])->name('analytics');
Route::get('api/attendance-summary', [AuthController::class, 'getAttendanceSummary'])->name('attendance.summary');
Route::get('api/sa-attendance-stats', [AuthController::class, 'getSAAttendanceStats'])->name('sa.attendance.stats');
Route::put('api/update-attendance', [AuthController::class, 'updateAttendance'])->name('attendance.update');
Route::post('api/apprentices/{id}/approve', [AuthController::class, 'approveApprentice'])->name('apprentices.approve');
Route::get('api/sa-performance/{id}', [AuthController::class, 'getSAPerformance'])->name('sa.performance');
Route::get('student-assistants', [AuthController::class, 'studentAssistants'])->name('student.assistants');
Route::post('student-assistants/add', [AuthController::class, 'addStudentAssistant'])->name('student.assistants.add');
Route::get('contract-management', [AuthController::class, 'contractManagement'])->name('contract.management');
Route::get('api/contracts', [AuthController::class, 'getContracts'])->name('contracts.index');
Route::post('contract-management/upload', [AuthController::class, 'uploadContract'])->name('contract.upload');
Route::get('skill-inventory', [AuthController::class, 'skillInventory'])->name('skill.inventory');
Route::get('skill-review', [AuthController::class, 'skillReview'])->name('skill.review');
Route::post('skill-inventory/add', [AuthController::class, 'addSkill'])->name('skill.add');
Route::get('evaluation-review', [AuthController::class, 'evaluationReview'])->name('evaluation.review');
Route::post('evaluation-review/save', [AuthController::class, 'saveEvaluationReview'])->name('evaluation.review.save');
Route::get('reports', [AuthController::class, 'reports'])->name('reports');
Route::post('reports/download', [AuthController::class, 'downloadReport'])->name('reports.download');
Route::get('api/reports/performance', [AuthController::class, 'getPerformanceData'])->name('reports.performance');
Route::get('office-management', [AuthController::class, 'officeManagement'])->name('office.management');
Route::get('api/offices', [AuthController::class, 'getOffices'])->name('offices.index');
Route::get('api/offices/{id}', [AuthController::class, 'getOffice'])->name('offices.show');
Route::post('api/offices', [AuthController::class, 'createOffice'])->name('offices.create');
Route::put('api/offices/{id}', [AuthController::class, 'updateOffice'])->name('offices.update');
Route::delete('api/offices/{id}', [AuthController::class, 'deleteOffice'])->name('offices.delete');
Route::get('officehead-management', [AuthController::class, 'officeHeadManagement'])->name('officehead.management');
Route::get('api/officeheads', [AuthController::class, 'getOfficeHeads'])->name('officeheads.index');
Route::get('api/officeheads/{id}', [AuthController::class, 'getOfficeHead'])->name('officeheads.show');
Route::post('api/officeheads', [AuthController::class, 'createOfficeHead'])->name('officeheads.create');
Route::put('api/officeheads/{id}', [AuthController::class, 'updateOfficeHead'])->name('officeheads.update');
Route::delete('api/officeheads/{id}', [AuthController::class, 'deleteOfficeHead'])->name('officeheads.delete');
Route::get('sa-account-management', [AuthController::class, 'saAccountManagement'])->name('sa.account.management');
Route::get('api/sa-accounts', [AuthController::class, 'getSAAccounts'])->name('sa.accounts.index');
Route::get('api/sa-accounts/{id}', [AuthController::class, 'getSAAccount'])->name('sa.accounts.show');
Route::post('api/sa-accounts', [AuthController::class, 'createSAAccount'])->name('sa.accounts.create');
Route::put('api/sa-accounts/{id}', [AuthController::class, 'updateSAAccount'])->name('sa.accounts.update');
Route::delete('api/sa-accounts/{id}', [AuthController::class, 'deleteSAAccount'])->name('sa.accounts.delete');
    
    Route::controller(PageController::class)->group(function() {
        Route::get('/dashboard-Overview-1-page', 'dashboardOverview1')->name('dashboard-overview-1');
        Route::get('dashboard-overview-2-page', 'dashboardOverview2')->name('dashboard-overview-2');
        Route::get('dashboard-overview-3-page', 'dashboardOverview3')->name('dashboard-overview-3');
        Route::get('dashboard-overview-4-page', 'dashboardOverview4')->name('dashboard-overview-4');
        Route::get('categories-page', 'categories')->name('categories');
        Route::get('add-product-page', 'addProduct')->name('add-product');
        Route::get('product-list-page', 'productList')->name('product-list');
        Route::get('product-grid-page', 'productGrid')->name('product-grid');
        Route::get('transaction-list-page', 'transactionList')->name('transaction-list');
        Route::get('transaction-detail-page', 'transactionDetail')->name('transaction-detail');
        Route::get('seller-list-page', 'sellerList')->name('seller-list');
        Route::get('seller-detail-page', 'sellerDetail')->name('seller-detail');
        Route::get('reviews-page', 'reviews')->name('reviews');
        Route::get('inbox-page', 'inbox')->name('inbox');
        Route::get('file-manager-page', 'fileManager')->name('file-manager');
        Route::get('point-of-sale-page', 'pointOfSale')->name('point-of-sale');
        Route::get('chat-page', 'chat')->name('chat');
        Route::get('post-page', 'post')->name('post');
        Route::get('calendar-page', 'calendar')->name('calendar');
        Route::get('crud-data-list-page', 'crudDataList')->name('crud-data-list');
        Route::get('crud-form-page', 'crudForm')->name('crud-form');
        Route::get('users-layout-1-page', 'usersLayout1')->name('users-layout-1');
        Route::get('users-layout-2-page', 'usersLayout2')->name('users-layout-2');
        Route::get('users-layout-3-page', 'usersLayout3')->name('users-layout-3');
        Route::get('profile-overview-1-page', 'profileOverview1')->name('profile-overview-1');
        Route::get('profile-overview-2-page', 'profileOverview2')->name('profile-overview-2');
        Route::get('profile-overview-3-page', 'profileOverview3')->name('profile-overview-3');
        Route::get('wizard-layout-1-page', 'wizardLayout1')->name('wizard-layout-1');
        Route::get('wizard-layout-2-page', 'wizardLayout2')->name('wizard-layout-2');
        Route::get('wizard-layout-3-page', 'wizardLayout3')->name('wizard-layout-3');
        Route::get('blog-layout-1-page', 'blogLayout1')->name('blog-layout-1');
        Route::get('blog-layout-2-page', 'blogLayout2')->name('blog-layout-2');
        Route::get('blog-layout-3-page', 'blogLayout3')->name('blog-layout-3');
        Route::get('pricing-layout-1-page', 'pricingLayout1')->name('pricing-layout-1');
        Route::get('pricing-layout-2-page', 'pricingLayout2')->name('pricing-layout-2');
        Route::get('invoice-layout-1-page', 'invoiceLayout1')->name('invoice-layout-1');
        Route::get('invoice-layout-2-page', 'invoiceLayout2')->name('invoice-layout-2');
        Route::get('faq-layout-1-page', 'faqLayout1')->name('faq-layout-1');
        Route::get('faq-layout-2-page', 'faqLayout2')->name('faq-layout-2');
        Route::get('faq-layout-3-page', 'faqLayout3')->name('faq-layout-3');
        Route::get('login-page', 'login')->name('login');
        Route::get('register-page', 'register')->name('register');
        Route::get('error-page-page', 'errorPage')->name('error-page');
        Route::get('update-profile-page', 'updateProfile')->name('update-profile');
        Route::get('change-password-page', 'changePassword')->name('change-password');
        Route::get('regular-table-page', 'regularTable')->name('regular-table');
        Route::get('tabulator-page', 'tabulator')->name('tabulator');
        Route::get('modal-page', 'modal')->name('modal');
        Route::get('slide-over-page', 'slideOver')->name('slide-over');
        Route::get('notification-page', 'notification')->name('notification');
        Route::get('tab-page', 'tab')->name('tab');
        Route::get('accordion-page', 'accordion')->name('accordion');
        Route::get('button-page', 'button')->name('button');
        Route::get('alert-page', 'alert')->name('alert');
        Route::get('progress-bar-page', 'progressBar')->name('progress-bar');
        Route::get('tooltip-page', 'tooltip')->name('tooltip');
        Route::get('dropdown-page', 'dropdown')->name('dropdown');
        Route::get('typography-page', 'typography')->name('typography');
        Route::get('icon-page', 'icon')->name('icon');
        Route::get('loading-icon-page', 'loadingIcon')->name('loading-icon');
        Route::get('regular-form-page', 'regularForm')->name('regular-form');
        Route::get('datepicker-page', 'datepicker')->name('datepicker');
        Route::get('tom-select-page', 'tomSelect')->name('tom-select');
        Route::get('file-upload-page', 'fileUpload')->name('file-upload');
        Route::get('wysiwyg-editor-classic', 'wysiwygEditorClassic')->name('wysiwyg-editor-classic');
        Route::get('wysiwyg-editor-inline', 'wysiwygEditorInline')->name('wysiwyg-editor-inline');
        Route::get('wysiwyg-editor-balloon', 'wysiwygEditorBalloon')->name('wysiwyg-editor-balloon');
        Route::get('wysiwyg-editor-balloon-block', 'wysiwygEditorBalloonBlock')->name('wysiwyg-editor-balloon-block');
        Route::get('wysiwyg-editor-document', 'wysiwygEditorDocument')->name('wysiwyg-editor-document');
        Route::get('validation-page', 'validation')->name('validation');
        Route::get('chart-page', 'chart')->name('chart');
        Route::get('slider-page', 'slider')->name('slider');
        Route::get('image-zoom-page', 'imageZoom')->name('image-zoom');
    });
});
