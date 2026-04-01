<?php

use App\Http\Controllers\AuthController;
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
    });
});


Route::prefix('teacher')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('teacher.register');
    Route::post('/login',    [AuthController::class, 'login'])->name('teacher.login');

    Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('teacher.logout');
        Route::get ('/me',     [AuthController::class, 'me'])->name('teacher.me');
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
