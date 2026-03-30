<?php

use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Guest Route
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Grouped Dashboard Modules
    Route::prefix('dashboard')->name('admin.')->group(function () {

        Route::get('/api/programmes/{programme_id}/courses', [CourseController::class, 'getCoursesByProgramme'])->name('api.courses');


        // 1. Batch Master
        Route::prefix('batches')->name('batch.')->group(function () {
            Route::get('/', [BatchController::class, 'index'])->name('index');
            Route::post('/store', [BatchController::class, 'store'])->name('store');
            Route::patch('/{id}', [BatchController::class, 'update'])->name('update');
            Route::patch('/{id}/status', [BatchController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-status', [BatchController::class, 'bulkStatus'])->name('bulkStatus');
        });

        // 2. Programme Master
        Route::prefix('programmes')->name('programme.')->group(function () {
            Route::get('/', [ProgrammeController::class, 'index'])->name('index');
            Route::post('/store', [ProgrammeController::class, 'store'])->name('store');
            Route::patch('/{id}', [ProgrammeController::class, 'update'])->name('update');
            Route::patch('/{id}/status', [ProgrammeController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-status', [ProgrammeController::class, 'bulkStatus'])->name('bulkStatus');
        });

        // 3. Course Master
        Route::prefix('courses')->name('course.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::post('/store', [CourseController::class, 'store'])->name('store');
            Route::patch('/{id}', [CourseController::class, 'update'])->name('update');
            Route::patch('/{id}/status', [CourseController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-status', [CourseController::class, 'bulkStatus'])->name('bulkStatus');
        });

        // 4. Subject Master
        Route::prefix('subjects')->name('subject.')->group(function () {
            Route::get('/', [SubjectController::class, 'index'])->name('index');
            Route::post('/store', [SubjectController::class, 'store'])->name('store');
            Route::patch('/{id}', [SubjectController::class, 'update'])->name('update');
            Route::patch('/{id}/status', [SubjectController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-status', [SubjectController::class, 'bulkStatus'])->name('bulkStatus');
        });

        // 5. Student Registrations
        Route::prefix('students')->name('student.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');

            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/store', [StudentController::class, 'store'])->name('store');

            Route::prefix('{id}')->group(function () {
                // Tab 1: Personal
                Route::get("/personal", [StudentController::class, 'editPersonal'])->name('edit.personal');
                Route::patch("/personal", [StudentController::class, 'updatePersonal'])->name('update.personal');

                // Tab 2: Education
                Route::get('/education', [StudentController::class, 'editEducation'])->name('edit.education');
                Route::patch('/education', [StudentController::class, 'updateEducation'])->name('update.education');

                Route::patch('/status', [StudentController::class, 'updateStatus'])->name('updateStatus');
            });

            Route::post('/bulk-status', [StudentController::class, 'bulkStatus'])->name('bulkStatus');
        });
    });
});
