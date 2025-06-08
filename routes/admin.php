<?php
// routes/admin.php
// Include this file in your web.php using: Route::prefix('admin')->group(base_path('routes/admin.php'));

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Admin routes - all under '/admin' prefix
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/create', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });
    
    // Bank Officer Management
    Route::get('/officers', [AdminController::class, 'officers'])->name('admin.officers');
    
    // Customer Registration Management
    Route::prefix('registrations')->group(function () {
        Route::get('/', [AdminController::class, 'registrations'])->name('admin.registrations');
        Route::get('/{id}', [AdminController::class, 'viewCustomer'])->name('admin.customer.view');
        Route::put('/{id}/status', [AdminController::class, 'updateCustomerStatus'])->name('admin.customer.status');
    });
    
    // Activity Logs
    Route::get('/activity', [AdminController::class, 'activityLogs'])->name('admin.activity');
    
    // System Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/settings/security', [AdminController::class, 'security'])->name('admin.security');
    Route::get('/settings/api', [AdminController::class, 'apiConfig'])->name('admin.api');
    Route::get('/logs', [AdminController::class, 'activityLogs'])->name('admin.logs');
    
    // Role Management
    Route::get('/roles', [AdminController::class, 'roles'])->name('admin.roles');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('admin.reports.generate');
});