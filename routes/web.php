<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OmangVerificationController;
use App\Http\Controllers\FacialVerificationController;
use App\Http\Controllers\BankOfficerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

Route::prefix('admin')->group(base_path('routes/admin.php'));

if (app()->environment('local', 'testing')) {
    require_once app_path('Services/Mock/MockOmangApi.php');
}

// Authentication routes
Auth::routes();

// Add this route to serve secure selfie files
Route::middleware(['auth'])->group(function () {
    Route::get('/secure-selfie/{filename}', function ($filename) {
        $path = 'selfies/' . $filename;
        $filePath = storage_path('app/secure/' . $path);

        if (!file_exists($filePath)) {
            return response()->file(public_path('images/default-avatar.png'));
        }

        return response()->file($filePath);
    })->name('secure.selfie');

    // Add a route for other secure documents if needed
    Route::get('/secure-document/{type}/{filename}', function ($type, $filename) {
        $path = $type . '/' . $filename;
        $filePath = storage_path('app/secure/' . $path);

        if (!file_exists($filePath)) {
            abort(404, 'Document not found');
        }

        return response()->file($filePath);
    })->name('secure.document');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password update route
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Email verification routes
    Route::post('/email/verification-notification', [ProfileController::class, 'sendVerificationNotification'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// Main Application Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Registration routes
    Route::prefix('registration')->group(function () {
        Route::get('/start', [CustomerController::class, 'showRegistrationForm'])->name('registration.start');
        Route::get('/success', [CustomerController::class, 'registrationSuccess'])->name('registration.success');
    });

    // Verification routes
    Route::prefix('verification')->group(function () {
        // Omang verification
        Route::get('/omang', [OmangVerificationController::class, 'showVerificationForm'])->name('verification.omang');
        Route::post('/omang', [OmangVerificationController::class, 'verify'])->name('verification.omang.submit');
        Route::get('/omang/status', [OmangVerificationController::class, 'checkStatus'])->name('verification.omang.status');

        // Document upload
        Route::get('/documents', [CustomerController::class, 'showDocumentUpload'])->name('verification.documents');
        Route::get('/documents/{id}', [DocumentController::class, 'show'])
            ->middleware('auth')
            ->name('documents.show');
        Route::get('/document/{id}/view', [DocumentController::class, 'view'])->name('document.view');

        Route::get('/facial', function () {
            // Verification logic
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $customerProfile = $user->customerProfile;

            if (!$customerProfile) {
                return redirect()->route('registration.start')
                    ->with('error', 'Please complete your registration first.');
            }

            if ($customerProfile->verification_status !== 'verified') {
                return redirect()->route('verification.omang')
                    ->with('error', 'Please complete Omang verification first.');
            }

            // If verification passes, call the controller method
            return app()->call([app(FacialVerificationController::class), 'showVerificationPage']);
        })->name('verification.facial');

        Route::post('/facial', function () {
            // Verification logic
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $customerProfile = $user->customerProfile;

            if (!$customerProfile) {
                return redirect()->route('registration.start')
                    ->with('error', 'Please complete your registration first.');
            }

            if ($customerProfile->verification_status !== 'verified') {
                return redirect()->route('verification.omang')
                    ->with('error', 'Please complete Omang verification first.');
            }

            // If verification passes, call the controller method
            return app()->call([app(FacialVerificationController::class), 'processVerification']);
        })->name('verification.facial.submit');

        Route::get('/facial/result/{sessionId}', function ($sessionId) {
            // Verification logic
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $customerProfile = $user->customerProfile;

            if (!$customerProfile) {
                return redirect()->route('registration.start')
                    ->with('error', 'Please complete your registration first.');
            }

            if ($customerProfile->verification_status !== 'verified') {
                return redirect()->route('verification.omang')
                    ->with('error', 'Please complete Omang verification first.');
            }

            // If verification passes, call the controller method
            return app()->call([app(FacialVerificationController::class), 'getVerificationResult'], ['sessionId' => $sessionId]);
        })->name('verification.facial.result');

        // Additional information
        Route::get('/additional', [CustomerController::class, 'showAdditionalInfoForm'])->name('verification.additional');
        Route::post('/additional', [CustomerController::class, 'submitAdditionalInfo'])->name('verification.additional.submit');
    });

    // Bank officer routes 
    Route::prefix('officer')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [BankOfficerController::class, 'dashboard'])->name('officer.dashboard');
        Route::get('/queue', [BankOfficerController::class, 'reviewQueue'])->name('officer.queue');
        Route::get('/customer/{id}', [BankOfficerController::class, 'customerDetails'])->name('officer.customer.details');
        Route::post('/review', [BankOfficerController::class, 'submitReview'])->name('officer.review.submit');
        Route::get('/reports', [BankOfficerController::class, 'reports'])->name('officer.reports');
    });


});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');