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

Route::get('/', function () {
    return view('welcome');
});

// Public School Landing Page
Route::get('/s/{slug}', [SchoolLandingController::class, 'show'])->name('school.landing');
Route::get('/s/{slug}/login', [SchoolLandingController::class, 'login'])->name('school.login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
        Route::resource('subscription-packages', App\Http\Controllers\Admin\SubscriptionPackageController::class);
        Route::resource('audit-logs', App\Http\Controllers\Admin\AuditLogController::class)->only(['index', 'show']);
        Route::resource('changelogs', App\Http\Controllers\Admin\ChangelogController::class, ['as' => 'admin']);
        Route::resource('feedback', App\Http\Controllers\Admin\FeedbackController::class, ['as' => 'admin'])->only(['index', 'show', 'update', 'destroy']);
    });

    Route::resource('campuses', App\Http\Controllers\Admin\CampusController::class, ['as' => 'admin']);
    Route::resource('staffs', App\Http\Controllers\Admin\StaffController::class, ['as' => 'admin']);
    Route::post('staffs/{staff}/create-user', [App\Http\Controllers\Admin\StaffController::class, 'createUserAccount'])->name('admin.staffs.create-user');
    
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
