<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth: Get current user (Sanctum default)
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes  →  /api/admin/*
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Login (admin không có register vì chỉ có 1 tài khoản admin duy nhất)
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login');

    // Protected routes — yêu cầu token + role = admin
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get ('/me',      [AuthController::class, 'me'])->name('admin.me');
    });
});

/*
|--------------------------------------------------------------------------
| Teacher Auth Routes  →  /api/teacher/*
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('teacher.register');
    Route::post('/login',    [AuthController::class, 'login'])->name('teacher.login');

    // Protected routes — yêu cầu token + role = teacher
    Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('teacher.logout');
        Route::get ('/me',     [AuthController::class, 'me'])->name('teacher.me');
    });
});

/*
|--------------------------------------------------------------------------
| Student Auth Routes  →  /api/student/*
|--------------------------------------------------------------------------
*/
Route::prefix('student')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('student.register');
    Route::post('/login',    [AuthController::class, 'login'])->name('student.login');

    // Protected routes — yêu cầu token + role = student
    Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('student.logout');
        Route::get ('/me',     [AuthController::class, 'me'])->name('student.me');
    });
});
