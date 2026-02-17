<?php


use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Guest middleware ensures logged-in users can't see the signup/signin pages again.
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// 2. Authenticated Routes (Only accessible if LOGGED IN)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Batch Routes
    Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
    Route::post('/batches/bulk', [BatchController::class, 'bulkAction'])->name('batches.bulk');

    Route::patch('/batches/{batch}/status', [BatchController::class, 'updateStatus'])->name('batches.updateStatus');
});
