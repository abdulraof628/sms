<?php

use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BranchClassController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpParser\Builder\Class_;
use App\Http\Middleware\RedirectNewUsers;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified', RedirectNewUsers::class])->group(function () {
    Route::get('get-started', function () {
        return Inertia::render('GetStarted');
    })->name('get-started');

    Route::get('dashboard', function () {
        $branchCount = \App\Models\Branch::count();
        $studentCount = \App\Models\Student::count();
        $classCount = \App\Models\BranchClass::count();
        // $userCount = \App\Models\User::where('is_active', true)->count(); // Removed as per request

        $branchStatusBreakdown = [
            'active' => \App\Models\Branch::where('status', true)->count(),
            'inactive' => \App\Models\Branch::where('status', false)->count(),
        ];

        $classDistribution = \App\Models\Branch::withCount('classes')->get(['id', 'name', 'classes_count']);

        $genderDistribution = [
            'male' => \App\Models\Student::where('gender', 'male')->count(),
            'female' => \App\Models\Student::where('gender', 'female')->count(),
            'other' => \App\Models\Student::whereNotIn('gender', ['male', 'female'])->count(),
        ];

        $recentBranches = \App\Models\Branch::latest()->take(5)->get(['id', 'name', 'created_at']);
        $recentStudents = \App\Models\Student::latest()->take(5)->get(['id', 'first_name', 'last_name', 'created_at']);
        $recentClasses = \App\Models\BranchClass::latest()->take(5)->get(['id', 'name', 'created_at']);

        return Inertia::render('organization/Dashboard', [
            'branchCount' => $branchCount,
            'studentCount' => $studentCount,
            'classCount' => $classCount,
            // 'userCount' => $userCount, // Removed as per request
            'branchStatusBreakdown' => $branchStatusBreakdown,
            'classDistribution' => $classDistribution,
            'genderDistribution' => $genderDistribution,
            'recentBranches' => $recentBranches,
            'recentStudents' => $recentStudents,
            'recentClasses' => $recentClasses,
        ]);
    })->name('dashboard');

    // Branch Management Routes
    Route::resource('branches', BranchController::class)->middleware('role:super-admin,admin,teacher');

    // Class Management Routes
    Route::resource('classes', BranchClassController::class)->middleware('role:super-admin,admin,teacher');

    // Student Management Routes
    Route::resource('students', StudentController::class)->middleware('role:super-admin,admin,teacher');

    // Staff Management Routes
    Route::resource('staff', \App\Http\Controllers\StaffController::class)->middleware('role:admin');
    Route::post('staff/{staff}/toggle-status', [\App\Http\Controllers\StaffController::class, 'toggleStatus'])->name('staff.toggle-status')->middleware('role:admin');
    
    // Staff Attendance Routes
    Route::prefix('staff-attendance')->name('staff.attendance.')->middleware('role:admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\StaffAttendanceController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\StaffAttendanceController::class, 'store'])->name('store');
        Route::post('/{staff}/clock-in', [\App\Http\Controllers\StaffAttendanceController::class, 'clockIn'])->name('clock-in');
        Route::post('/{staff}/clock-out', [\App\Http\Controllers\StaffAttendanceController::class, 'clockOut'])->name('clock-out');
        Route::put('/{attendance}', [\App\Http\Controllers\StaffAttendanceController::class, 'update'])->name('update');
        Route::delete('/{attendance}', [\App\Http\Controllers\StaffAttendanceController::class, 'destroy'])->name('destroy');
    });

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
require __DIR__.'/superadmin.php';