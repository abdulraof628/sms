<?php

use App\Http\Controllers\SuperAdmin\TenantController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['web', 'auth', 'role:super-admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('superadmin/Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    // Tenant Management Routes
    Route::resource('tenants', TenantController::class)->middleware('role:super-admin');
    Route::post('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
}); 