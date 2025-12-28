<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniActivityController;
use App\Http\Controllers\AlumniProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumniSkpiController;
use App\Http\Controllers\PimpinanSkpiController;
use App\Http\Controllers\SkpiMasterContentController;
use App\Http\Controllers\SkpiVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/',[LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::get('/alumni/profile', [AlumniProfileController::class, 'edit'])->name('alumni.profile.edit');
    Route::put('/alumni/profile', [AlumniProfileController::class, 'update'])->name('alumni.profile.update');
    Route::get('/alumni/activities', [AlumniActivityController::class, 'index'])->name('alumni.activities.index');
    Route::post('/alumni/activities', [AlumniActivityController::class, 'store'])->name('alumni.activities.store');
    Route::get('/alumni/activities/{activity}/edit', [AlumniActivityController::class, 'edit'])->name('alumni.activities.edit');
    Route::put('/alumni/activities/{activity}', [AlumniActivityController::class, 'update'])->name('alumni.activities.update');
    Route::delete('/alumni/activities/{activity}', [AlumniActivityController::class, 'destroy'])->name('alumni.activities.destroy');
    Route::patch('/alumni/activities/{activity}/confirmation', [AlumniActivityController::class, 'toggleConfirmation'])->name('alumni.activities.confirmation');
    Route::get('/alumni/skpi', [AlumniSkpiController::class, 'index'])->name('alumni.skpi.index');
    Route::post('/alumni/skpi', [AlumniSkpiController::class, 'store'])->name('alumni.skpi.store');
    Route::get('/alumni/skpi/download', [AlumniSkpiController::class, 'download'])->name('alumni.skpi.download');
    Route::get('/alumni/skpi/preview', [AlumniSkpiController::class, 'preview'])->name('alumni.skpi.preview');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/validation-requests', [AdminController::class, 'validationRequests'])->name('validation.requests');
        Route::post('/validation-requests/{id}/approve', [AdminController::class, 'approveValidation'])->name('validation.approve');
        Route::post('/validation-requests/{id}/reject', [AdminController::class, 'rejectValidation'])->name('validation.reject');

        // Activity validation routes
        Route::post('/activities/{id}/confirm', [AdminController::class, 'confirmActivity'])->name('activity.confirm');
        Route::post('/activities/{id}/approve', [AdminController::class, 'approveActivity'])->name('activity.approve');
        Route::post('/activities/{id}/reject', [AdminController::class, 'rejectActivity'])->name('activity.reject');

        // SKPI submission routes
        Route::get('/skpi-submissions', [AdminController::class, 'skpiSubmissions'])->name('skpi.submissions');
        Route::post('/skpi-submissions/{alumniProfile}/generate', [AdminController::class, 'generateSkpi'])->name('generate.skpi');
        Route::post('/submit-skpi/{id}', [AdminController::class, 'submitSkpi'])->name('submit.skpi');
        Route::post('/approve-skpi/{id}', [AdminController::class, 'approveSkpi'])->name('approve.skpi');
        Route::post('/reject-skpi/{id}', [AdminController::class, 'rejectSkpi'])->name('reject.skpi');
        Route::get('/skpi-master', [SkpiMasterContentController::class, 'index'])->name('skpi-master.index');
        Route::get('/skpi-master/{skpiMasterContent}/edit', [SkpiMasterContentController::class, 'edit'])->name('skpi-master.edit');
        Route::put('/skpi-master/{skpiMasterContent}', [SkpiMasterContentController::class, 'update'])->name('skpi-master.update');

        // User management routes (super admin only)
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/users', [AdminController::class, 'users'])->name('users.index');
            Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
            Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
            Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
        });
    });

    Route::middleware(['role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::post('/skpi-requests/{skpiRequest}/approve', [PimpinanSkpiController::class, 'approve'])->name('skpi.approve');
    });
});

// Google oAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::get('/skpi/verify/{hash}', [SkpiVerificationController::class, 'show'])->name('skpi.verify');
