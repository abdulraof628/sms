<?php

use App\Http\Controllers\Business\OrganizationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['web', 'auth', 'role:developer,super-admin'])->prefix('business')->name('business.')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('business/Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    // Organization Management Routes
    Route::resource('organizations', OrganizationController::class)->middleware('role:developer,super-admin');
}); 