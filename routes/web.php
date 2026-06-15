<?php

use Illuminate\Http\Request;
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

// Root
Route::get('/', fn() => redirect()->route('login'));

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', fn() => view('home'))->name('home');

    // Profile
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Ticket & Token module
    Route::get('/tickets', [TicketController::class, 'indexWeb']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{id}/buy', [TicketController::class, 'buyWeb']);
    Route::post('/tokens/validate', [TicketTokenController::class, 'validateToken']);
    Route::post('/tokens/generate', [TicketTokenController::class, 'generateToken']);
    Route::get('/zones/{ticket_id}', [TicketZoneController::class, 'getZoneByTicket']);
    Route::post('/zones/reduce', [TicketZoneController::class, 'reduceQuota']);
    Route::get('/tickets/{id}/calculate-price', [PricingRuleController::class, 'calculateFinalPrice']);
    Route::post('/waitlist/join', [WaitListController::class, 'joinWaitList']);

    // Order payment
    Route::get('/my-orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/checkout', fn() => view('checkout'));
    Route::post('/check-voucher', [OrderController::class, 'checkVoucher'])->name('check.voucher');
    Route::post('/order/{id}/refund', [RefundController::class, 'store'])->name('refund.store');
    Route::get('/points', [RedeemController::class, 'myPoints']);
    Route::post('/redeem', [RedeemController::class, 'redeem']);

    // Event & Konser
    Route::resource('stages', StageWebController::class)->names('web.stages');
    Route::resource('event-categories', EventCategoryWebController::class)->names('web.event-categories');
    Route::resource('events', EventWebController::class)->names('web.events');
    Route::resource('performers', PerformerWebController::class)->names('web.performers');
    Route::resource('event-schedules', EventScheduleWebController::class)->names('web.event-schedules');
    Route::resource('event-media', EventMediaWebController::class)->names('web.event-media');

    // Operational & Report
    Route::get('/gates', [GateController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews', [ReviewController::class, 'index']);
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('role.update');
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities.index');

    // Ticket admin
    Route::get('/tickets', [TicketController::class, 'adminWeb']);
    Route::get('/tracker', [QuotaTrackerController::class, 'indexWeb']);

    // Refund
    Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::post('/refunds/{id}/approve', [RefundController::class, 'approve'])->name('refunds.approve');
    Route::post('/refunds/{id}/reject', [RefundController::class, 'reject'])->name('refunds.reject');

    // Operational & Report
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/dashboard/sales-report', [DashboardController::class, 'salesReport'])->name('sales-report.index');

    // Gates
    Route::get('/gates', [GateController::class, 'index'])->name('gates.index');
    Route::post('/gates', [GateController::class, 'store']);
    Route::put('/gates/{gate}', [GateController::class, 'update']);
    Route::delete('/gates/{gate}', [GateController::class, 'destroy']);

    // Staff assignment
    Route::get('/staff-assignments', [StaffAssignmentController::class, 'index'])->name('staff-assignments.index');
    Route::post('/staff-assignments', [StaffAssignmentController::class, 'store']);
    Route::delete('/staff-assignments/{staffAssignment}', [StaffAssignmentController::class, 'destroy']);
    Route::patch('/staff-assignments/{staffAssignment}/status', [StaffAssignmentController::class, 'updateStatus']);

    // Check-ins
    Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve']);
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject']);

});

// Staff gate
Route::middleware(['auth', 'role:admin,staff_gate'])->prefix('staff')->name('staff.')->group(function () {
    // Ticket & Token module
    Route::get('/scan', [TicketTokenController::class, 'scanWeb']);

    // Operational & Report
    Route::post('/check-ins/scan', [CheckInController::class, 'scan']);
});