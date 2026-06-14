<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\StaffAssignmentController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SalesReportController;
 
Route::middleware([])->group(function () { // ['auth'] di-comment sementara
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sales Report
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');

    // Gates
    Route::resource('gates', GateController::class)->except(['create', 'edit']);

    // Staff Assignments
    Route::resource('staff-assignments', StaffAssignmentController::class)->except(['create', 'edit', 'show']);
    Route::patch('/staff-assignments/{staffAssignment}/status', [StaffAssignmentController::class, 'updateStatus'])->name('staff-assignments.updateStatus');

    // Check-ins
    Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
    Route::post('/check-ins/scan', [CheckInController::class, 'scan'])->name('check-ins.scan');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
});