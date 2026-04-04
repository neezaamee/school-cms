<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SchoolController; // Added import
use App\Http\Controllers\Admin\SchoolUserController; // Added import
use App\Http\Controllers\Admin\SubscriptionPackageController;
use App\Http\Controllers\LocationController; // Added import for LocationController
use App\Http\Controllers\SchoolLandingController; // Added import
use App\Http\Controllers\Admin\GradeLevelController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\GradeScaleController;
use App\Http\Controllers\Admin\ExamTermController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\MarkEntryController;
use App\Http\Controllers\Admin\ResultController;

Route::get('/', function () {
    $packages = \App\Models\SubscriptionPackage::where('is_active', true)->get();
    return view('welcome', compact('packages'));
});

// Public School Landing Page
Route::get('/s/{slug}', [SchoolLandingController::class, 'show'])->name('school.landing');
Route::get('/s/{slug}/login', [SchoolLandingController::class, 'login'])->name('school.login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/update-password', \App\Livewire\Auth\ForceChangePassword::class)->name('auth.change-password');

    // User Features
    Route::get('/whats-new', [App\Http\Controllers\ChangelogController::class, 'index'])->name('user.changelogs');
    Route::get('/my-feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('user.feedback.index');
    Route::get('/feedback/create', [App\Http\Controllers\FeedbackController::class, 'create'])->name('user.feedback.create');
    Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('user.feedback.store');

    // Super Admin Only Routes
    Route::middleware(['role:super admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('schools', App\Http\Controllers\Admin\SchoolController::class);
        Route::get('schools/check/slug', [App\Http\Controllers\Admin\SchoolController::class, 'checkSlug'])->name('admin.schools.check-slug');
        Route::get('schools/check/email', [App\Http\Controllers\Admin\SchoolController::class, 'checkEmail'])->name('admin.schools.check-email');
        Route::resource('subscription-packages', App\Http\Controllers\Admin\SubscriptionPackageController::class);
        Route::resource('audit-logs', App\Http\Controllers\Admin\AuditLogController::class)->only(['index', 'show']);
        Route::resource('changelogs', App\Http\Controllers\Admin\ChangelogController::class, ['as' => 'admin']);
        Route::resource('feedback', App\Http\Controllers\Admin\FeedbackController::class, ['as' => 'admin'])->only(['index', 'show', 'update', 'destroy']);
        Route::resource('grade-levels', GradeLevelController::class)->names('admin.grade-levels');
        Route::resource('sections', SectionController::class)->names('admin.sections');
        Route::resource('subjects', SubjectController::class)->names('admin.subjects');
        Route::resource('grade-scales', GradeScaleController::class)->names('admin.grade-scales');
        Route::resource('exam-terms', ExamTermController::class)->names('admin.exam-terms');
        Route::resource('exams', ExamController::class)->names('admin.exams');

        // Mark Entry
        Route::get('mark-entry', [MarkEntryController::class, 'index'])->name('admin.mark-entry.index');
        Route::post('mark-entry', [MarkEntryController::class, 'store'])->name('admin.mark-entry.store');

        // Results
        Route::get('results', [ResultController::class, 'index'])->name('admin.results.index');
    });

    Route::resource('campuses', App\Http\Controllers\Admin\CampusController::class, ['as' => 'admin']);
    
    // Student Management Module
    Route::get('students/search-bform', [App\Http\Controllers\Admin\StudentController::class, 'searchByBForm'])->name('admin.students.search-bform');
    Route::get('students/search-parent-cnic', [App\Http\Controllers\Admin\StudentController::class, 'searchByParentCnic'])->name('admin.students.search-parent-cnic');
    Route::get('students/subjects-by-class', [App\Http\Controllers\Admin\StudentController::class, 'getSubjectsByClass'])->name('admin.students.subjects-by-class');
    Route::resource('students', App\Http\Controllers\Admin\StudentController::class, ['as' => 'admin']);
    Route::resource('enrollments', App\Http\Controllers\Admin\EnrollmentController::class, ['as' => 'admin']);

    // Staff Management
    Route::resource('staffs', \App\Http\Controllers\Admin\StaffController::class, ['as' => 'admin']);
    Route::post('staffs/{staff}/create-user', [App\Http\Controllers\Admin\StaffController::class, 'createUserAccount'])->name('admin.staffs.create-user');
    Route::resource('staff-designations', \App\Http\Controllers\Admin\StaffDesignationController::class, ['as' => 'admin']);

    // Fee Management
    Route::resource('fee-categories', \App\Http\Controllers\Admin\FeeCategoryController::class, ['as' => 'admin']);
    Route::resource('fee-structures', \App\Http\Controllers\Admin\FeeStructureController::class, ['as' => 'admin']);
    Route::resource('invoices', \App\Http\Controllers\Admin\FeeInvoiceController::class, ['as' => 'admin']);
    Route::post('invoices/generate', [\App\Http\Controllers\Admin\FeeInvoiceController::class, 'generate'])->name('admin.invoices.generate');
    Route::resource('payments', \App\Http\Controllers\Admin\FeePaymentController::class, ['as' => 'admin']);
    Route::get('invoices/{invoice}/print', [\App\Http\Controllers\Admin\FeeInvoiceController::class, 'print'])->name('admin.invoices.print');

    // Finance & Reports
    Route::get('finance/reports/collection', [\App\Http\Controllers\Admin\FinanceReportController::class, 'index'])->name('admin.finance.reports.collection');
    
    Route::resource('attendance', App\Http\Controllers\Admin\AttendanceController::class, ['as' => 'admin']);
    
    // Academic Management Module
    Route::resource('grade-levels', App\Http\Controllers\Admin\GradeLevelController::class, ['as' => 'admin']);
    Route::resource('sections', App\Http\Controllers\Admin\SectionController::class, ['as' => 'admin']);
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class, ['as' => 'admin']);
    
    // Dynamic Dropdowns
    Route::get('/schools/{school}/campuses', [App\Http\Controllers\Admin\SchoolController::class, 'getCampuses'])->name('schools.campuses');

    // Location Routes
    Route::get('/countries/{country}/cities', [LocationController::class, 'getCities'])->name('countries.cities');

    // School Owner Only Routes
    Route::middleware(['role:school owner'])->group(function () {
        Route::resource('school-users', SchoolUserController::class); // Added resource route
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
