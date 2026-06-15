<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Web\StageWebController;
use App\Http\Controllers\Web\EventCategoryWebController;
use App\Http\Controllers\Web\EventWebController;
use App\Http\Controllers\Web\PerformerWebController;
use App\Http\Controllers\Web\EventScheduleWebController;
use App\Http\Controllers\Web\EventMediaWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\StaffAssignmentController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketZoneController;
use App\Http\Controllers\WaitListController;
use App\Http\Controllers\TicketTokenController;
use App\Http\Controllers\PricingRuleController;
use App\Http\Controllers\QuotaTrackerController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/my-orders', [OrderController::class, 'index'])->name('order.index');
Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('order.show');


Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'checkout']);

Route::get('/checkout', function () {
    return view('checkout');
});

Route::post('/check-voucher', [OrderController::class, 'checkVoucher'])->name('check.voucher');

Route::post('/order/{id}/refund', [RefundController::class, 'store'])->name('refund.store');
Route::get('/admin/refunds', [\App\Http\Controllers\RefundController::class, 'index'])->name('admin.refunds.index');
Route::post('/admin/refunds/{id}/approve', [\App\Http\Controllers\RefundController::class, 'approve'])->name('admin.refunds.approve');
Route::post('/admin/refunds/{id}/reject', [\App\Http\Controllers\RefundController::class, 'reject'])->name('admin.refunds.reject');

Route::get('/perbaiki-kolom-status', function () {
    DB::statement("ALTER TABLE orders MODIFY status VARCHAR(255) DEFAULT 'pending'");
    return 'Kolom status di tabel orders berhasil diperbaiki! Sekarang bisa nampung refund_pending.';
});


Route::get('/points', [RedeemController::class, 'myPoints']);
Route::post('/redeem', [RedeemController::class, 'redeem']);


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', fn() => view('home'))->name('home');

    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Operational & Report
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
    Route::resource('gates', GateController::class)->except(['create', 'edit']);
    Route::resource('staff-assignments', StaffAssignmentController::class)->except(['create', 'edit', 'show']);
    Route::patch('/staff-assignments/{staffAssignment}/status', [StaffAssignmentController::class, 'updateStatus'])->name('staff-assignments.updateStatus');
    Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
    Route::post('/check-ins/scan', [CheckInController::class, 'scan'])->name('check-ins.scan');
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');

    // Ticket & Token module
    Route::get('/tickets', [TicketController::class, 'indexWeb']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{id}/buy', [TicketController::class, 'buyWeb']);

    Route::get('/scan', [TicketTokenController::class, 'scanWeb']);
    Route::post('/tokens/validate', [TicketTokenController::class, 'validateToken']);
    Route::post('/tokens/generate', [TicketTokenController::class, 'generateToken']);

    Route::get('/zones/{ticket_id}', [TicketZoneController::class, 'getZoneByTicket']);
    Route::post('/zones/reduce', [TicketZoneController::class, 'reduceQuota']);

    Route::get('/tickets/{id}/calculate-price', [PricingRuleController::class, 'calculateFinalPrice']);

    Route::post('/waitlist/join', [WaitListController::class, 'joinWaitList']);

    Route::get('/admin/tickets', [TicketController::class, 'adminWeb']);
    Route::get('/tracker', [QuotaTrackerController::class, 'indexWeb']);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('role.update');
});

Route::middleware(['auth', 'role:admin,staff_gate'])->prefix('staff')->name('staff.')->group(function () {
    // fitur untuk staff gate
});

Route::resource('stages', StageWebController::class)->names('web.stages');
Route::resource('event-categories', EventCategoryWebController::class)->names('web.event-categories');
Route::resource('events', EventWebController::class)->names('web.events');
Route::resource('performers', PerformerWebController::class)->names('web.performers');
Route::resource('event-schedules', EventScheduleWebController::class)->names('web.event-schedules');
Route::resource('event-media', EventMediaWebController::class)->names('web.event-media');
