<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GateController;
use App\Http\Controllers\StaffAssignmentController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;

Route::get('/gates-public', [GateController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Gates
    Route::get('/gates', [GateController::class, 'index']);
    Route::get('/gates/{gate}', [GateController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/gates', [GateController::class, 'store']);
        Route::put('/gates/{gate}', [GateController::class, 'update']);
        Route::delete('/gates/{gate}', [GateController::class, 'destroy']);
    });

    // Staff Assignments
    Route::middleware('role:admin')->group(function () {
        Route::get('/staff-assignments', [StaffAssignmentController::class, 'index']);
        Route::post('/staff-assignments', [StaffAssignmentController::class, 'store']);
        Route::delete('/staff-assignments/{staffAssignment}', [StaffAssignmentController::class, 'destroy']);
        Route::patch('/staff-assignments/{staffAssignment}/status', [StaffAssignmentController::class, 'updateStatus']);
    });

    // Check-in
    Route::middleware('role:admin,staff_gate')->group(function () {
        Route::post('/check-ins/scan', [CheckInController::class, 'scan']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/check-ins', [CheckInController::class, 'index']);
    });

    // Review
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::get('/reviews/event/{eventId}/summary', [ReviewController::class, 'eventSummary']);
    Route::get('/reviews/{review}', [ReviewController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/reviews', [ReviewController::class, 'adminIndex']);
        Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve']);
        Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject']);
    });

    // Dashboard
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
        Route::get('/dashboard/sales-report', [DashboardController::class, 'salesReport']);
        Route::get('/dashboard/check-in-report', [DashboardController::class, 'checkInReport']);
    });
});