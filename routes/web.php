<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', fn() => view('home'))->name('home');

    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');


    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',             [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread');
        Route::patch('/{id}/read',  [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all',   [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::delete('/{id}',      [NotificationController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',          [AdminController::class, 'dashboard'])->name('dashboard');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('role.update');
});

Route::middleware(['auth', 'role:admin,staff_gate'])->prefix('staff')->name('staff.')->group(function () {
    // fitur untuk staff gate
});
