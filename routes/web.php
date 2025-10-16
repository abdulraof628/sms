<?php

use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpParser\Builder\Class_;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\RedirectNewUsers;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified', RedirectNewUsers::class, 'tenant.access'])->group(function () {
    Route::get('get-started', function () {
        return Inertia::render('organization/GetStarted');
    })->name('get-started');

    Route::get('dashboard', function () {
        $schoolCount = \App\Models\School::count();
        $studentCount = \App\Models\Student::count();
        $classCount = \App\Models\SchoolClass::count();
        // $userCount = \App\Models\User::where('is_active', true)->count(); // Removed as per request

        $schoolStatusBreakdown = [
            'active' => \App\Models\School::where('status', true)->count(),
            'inactive' => \App\Models\School::where('status', false)->count(),
        ];

        $classDistribution = \App\Models\School::withCount('classes')->get(['id', 'name', 'classes_count']);

        $genderDistribution = [
            'male' => \App\Models\Student::where('gender', 'male')->count(),
            'female' => \App\Models\Student::where('gender', 'female')->count(),
            'other' => \App\Models\Student::whereNotIn('gender', ['male', 'female'])->count(),
        ];

        $recentSchools = \App\Models\School::latest()->take(5)->get(['id', 'name', 'created_at']);
        $recentStudents = \App\Models\Student::latest()->take(5)->get(['id', 'first_name', 'last_name', 'created_at']);
        $recentClasses = \App\Models\SchoolClass::latest()->take(5)->get(['id', 'name', 'created_at']);

        return Inertia::render('organization/Dashboard', [
            'schoolCount' => $schoolCount,
            'studentCount' => $studentCount,
            'classCount' => $classCount,
            // 'userCount' => $userCount, // Removed as per request
            'schoolStatusBreakdown' => $schoolStatusBreakdown,
            'classDistribution' => $classDistribution,
            'genderDistribution' => $genderDistribution,
            'recentSchools' => $recentSchools,
            'recentStudents' => $recentStudents,
            'recentClasses' => $recentClasses,
        ]);
    })->name('dashboard');

    // // Tenant Management Routes (creating tenants, etc.)
    Route::resource('tenants', TenantController::class)->middleware('role:super-admin,admin');

    // School Management Routes
    Route::resource('schools', SchoolController::class)->middleware('role:super-admin,admin,teacher');

    // Class Management Routes
    Route::resource('classes', SchoolClassController::class)->middleware('role:super-admin,admin,teacher');

    // Student Management Routes
    Route::resource('students', StudentController::class)->middleware('role:super-admin,admin,teacher');

    // User Management Routes
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');

    // Daily Reports Routes
    Route::prefix('daily')->name('daily.')->group(function () {
        Route::get('/', [DailyReportController::class, 'index'])->name('index');
        Route::get('/create', [DailyReportController::class, 'create'])->name('create');
        Route::post('/', [DailyReportController::class, 'store'])->name('store');
        Route::get('/{dailyReport}', [DailyReportController::class, 'show'])->name('show');
        Route::get('/{dailyReport}/edit', [DailyReportController::class, 'edit'])->name('edit');
        Route::put('/{dailyReport}', [DailyReportController::class, 'update'])->name('update');
        Route::delete('/{dailyReport}', [DailyReportController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/business.php';