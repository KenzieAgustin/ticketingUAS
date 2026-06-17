<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\Api\EventCategoryController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PerformerController;
use App\Http\Controllers\Api\EventScheduleController;
use App\Http\Controllers\Api\EventMediaController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\StaffAssignmentController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;

Route::apiResource('event-categories', EventCategoryController::class);
Route::apiResource('stages', StageController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('performers', PerformerController::class);
Route::apiResource('event-schedules', EventScheduleController::class);
Route::apiResource('event-media', EventMediaController::class);

// Order payment

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans-callback', [PaymentController::class, 'webhook']);
Route::post('/payment-callback', [PaymentController::class, 'webhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/points', [RedeemController::class, 'myPoints']);
    Route::post('/redeem', [RedeemController::class, 'redeem']);
});

//Operational & Report
Route::middleware(['auth:sanctum'])->group(function () {
    // Gates
    Route::get('/gates', [GateController::class, 'index']); // lihat semua gate
    Route::middleware('role:admin')->group(function () {
        Route::post('/gates', [GateController::class, 'store']); // tambahkan gate baru
        Route::put('/gates/{gate}', [GateController::class, 'update']); // edit gate
        Route::delete('/gates/{gate}', [GateController::class, 'destroy']); // hapus gate
    });

    // Staff Assignments
    Route::middleware('role:admin')->group(function () {
        Route::get('/staff-assignments', [StaffAssignmentController::class, 'index']); // lihat semua penugasan staff
        Route::post('/staff-assignments', [StaffAssignmentController::class, 'store']); // buat penugasan staff baru
        Route::delete('/staff-assignments/{staffAssignment}', [StaffAssignmentController::class, 'destroy']); // hapus jadwal
        Route::patch('/staff-assignments/{staffAssignment}/status', [StaffAssignmentController::class, 'updateStatus']); // update kehadiran staff
    });

    // Check-in
    Route::middleware('role:admin,staff_gate')->group(function () {
        Route::post('/check-ins/scan', [CheckInController::class, 'scan']); //  Staff scan QR atau input kode booking, tiket otomatis mark as used
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/check-ins', [CheckInController::class, 'index']); // lihat semua check-in
    });

    // Review
    Route::get('/reviews', [ReviewController::class, 'index']); // Lihat ulasan pengunjung yang approved

    Route::middleware('role:customer')->group(function () {
        Route::post('/reviews', [ReviewController::class, 'store']); // Pengunjung kirim ulasan & rating
    });

    Route::middleware('role:admin')->group(function () {
        Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve']); // Admin approve ulasan
        Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject']); // Admin reject ulasan
    });

    // Dashboard
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/summary', [DashboardController::class, 'summary']); // Total pengunjung, pendapatan, check-in hari ini, rating rata-rata
        Route::get('/dashboard/sales-report', [DashboardController::class, 'salesReport']); // Rekap penjualan per event, tipe tiket, dan hari
    });
});

