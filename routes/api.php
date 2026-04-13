<?php

use App\Http\Controllers\Admin\SchedulingController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teacher\ScheduleRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get ('/me',      [AuthController::class, 'me'])->name('admin.me');

        // Dashboard APIs
        Route::get('/dashboard/stats',           [DashboardController::class, 'getStats']);
        Route::get('/dashboard/charts',           [DashboardController::class, 'getCharts']);
        Route::get('/dashboard/student-status',   [DashboardController::class, 'getStudentStatus']);
        Route::get('/dashboard/pending-actions',   [DashboardController::class, 'getPendingActions']);

        Route::get('/courses-stats',  [CourseController::class, 'stats']);
        Route::apiResource('courses', CourseController::class)->parameters([
            'courses' => 'course',
        ]);

        Route::get('/teachers/export', [TeacherController::class, 'export']);
        Route::get('/teachers/{teacher}/classes', [TeacherController::class, 'getClasses']);
        Route::delete('/teachers', [TeacherController::class, 'destroyMany']);
        Route::apiResource('teachers', TeacherController::class)->parameters([
            'teachers' => 'teacher',
        ]);

        // Scheduling APIs
        Route::prefix('scheduling')->group(function () {
            Route::get   ('calendar',             [SchedulingController::class, 'calendar']);
            Route::get   ('rooms',                [SchedulingController::class, 'rooms']);
            Route::get   ('stats',                [SchedulingController::class, 'stats']);
            Route::post  ('assign',               [SchedulingController::class, 'assign']);
            Route::get   ('requests',             [SchedulingController::class, 'listRequests']);
            Route::patch ('approve-request/{id}',  [SchedulingController::class, 'approveRequest']);
        });
    });
});


Route::prefix('teacher')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('teacher.register');
    Route::post('/login',    [AuthController::class, 'login'])->name('teacher.login');

    Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('teacher.logout');
        Route::get ('/me',     [AuthController::class, 'me'])->name('teacher.me');

        // Schedule requests
        Route::get ('/schedule-requests', [ScheduleRequestController::class, 'index']);
        Route::post('/schedule-requests', [ScheduleRequestController::class, 'store']);
    });
});


Route::prefix('student')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('student.register');
    Route::post('/login',    [AuthController::class, 'login'])->name('student.login');

    Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('student.logout');
        Route::get ('/me',     [AuthController::class, 'me'])->name('student.me');
    });
});
