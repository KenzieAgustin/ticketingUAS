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
use App\Http\Controllers\ForgotPasswordController;
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

    Route::get('/verify-register-otp', [AuthController::class, 'showVerifyRegisterOtp'])->name('register.verify-otp.show');
    Route::post('/verify-register-otp', [AuthController::class, 'verifyRegisterOtp'])->name('register.verify-otp');
    Route::post('/resend-register-otp', [AuthController::class, 'resendRegisterOtp'])->name('register.resend-otp');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgot'])->name('password.forgot');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');

    Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyOtp'])->name('password.verify-otp.show');
    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp');

    Route::get('/reset-password', [ForgotPasswordController::class, 'showReset'])->name('password.reset.show');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.reset');
});

// Auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', fn() => view('home'))->name('home');

    // Profile
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [UserController::class, 'showPassword'])->name('profile.password.show');
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
    Route::get('/tickets', [TicketController::class, 'indexWeb'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}/buy', [TicketController::class, 'buyWeb'])->name('tickets.buy');
    Route::post('/tokens/validate', [TicketTokenController::class, 'validateToken'])->name('tokens.validate');
    Route::post('/tokens/generate', [TicketTokenController::class, 'generateToken'])->name('tokens.generate');
    Route::get('/zones/{ticket_id}', [TicketZoneController::class, 'getZoneByTicket'])->name('zones.by-ticket');
    Route::post('/zones/reduce', [TicketZoneController::class, 'reduceQuota'])->name('zones.reduce');
    Route::get('/tickets/{id}/calculate-price', [PricingRuleController::class, 'calculateFinalPrice'])->name('tickets.calculate-price');
    Route::post('/waitlist/join', [WaitListController::class, 'joinWaitList'])->name('waitlist.join');

    // Order & Payment
    Route::get('/my-orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');
    Route::get('/checkout', fn() => view('checkout'))->name('checkout.index');
    Route::post('/check-voucher', [OrderController::class, 'checkVoucher'])->name('check.voucher');
    Route::post('/order/{id}/refund', [RefundController::class, 'store'])->name('refund.store');
    Route::get('/points', [RedeemController::class, 'myPoints'])->name('points.index');
    Route::post('/redeem', [RedeemController::class, 'redeem'])->name('redeem.store');

    // Event & Konser
    Route::resource('stages', StageWebController::class)->names('web.stages');
    Route::resource('event-categories', EventCategoryWebController::class)->names('web.event-categories');
    Route::resource('events', EventWebController::class)->names('web.events');
    Route::resource('performers', PerformerWebController::class)->names('web.performers');
    Route::resource('event-schedules', EventScheduleWebController::class)->names('web.event-schedules');
    Route::resource('event-media', EventMediaWebController::class)->names('web.event-media');

    // Reviews (customer)
    Route::get('/reviews', [ReviewController::class, 'customerIndex'])->name('reviews.index');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('role.update');
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities.index');

    // Ticket admin
    Route::get('/tickets', [TicketController::class, 'adminWeb'])->name('tickets.index');
    Route::get('/tracker', [QuotaTrackerController::class, 'indexWeb'])->name('tracker.index');

    // Refund
    Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::post('/refunds/{id}/approve', [RefundController::class, 'approve'])->name('refunds.approve');
    Route::post('/refunds/{id}/reject', [RefundController::class, 'reject'])->name('refunds.reject');

    // Operational & Report
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('/operational', [DashboardController::class, 'index'])->name('operational');

    // Gates — pakai resource, manual routes dihapus karena redundan
    Route::resource('gates', GateController::class)->except(['create', 'edit'])->names('gates');

    // Staff assignments — pakai resource, manual routes dihapus karena redundan
    Route::resource('staff-assignments', StaffAssignmentController::class)
        ->except(['create', 'edit', 'show'])
        ->names('staff-assignments');
    Route::patch('/staff-assignments/{staffAssignment}/status', [StaffAssignmentController::class, 'updateStatus'])
        ->name('staff-assignments.updateStatus');

    // Check-ins
    Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
    Route::post('/check-ins/scan', [CheckInController::class, 'scan'])->name('check-ins.scan');

    // Reviews (admin)
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
});

// Staff gate
Route::middleware(['auth', 'role:admin,staff_gate'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/scan', [TicketTokenController::class, 'scanWeb'])->name('scan');
    Route::get('/gates', [GateController::class, 'staffIndex'])->name('gates.index');
    Route::get('/check-ins/scan', [CheckInController::class, 'staffScan'])->name('check-ins.scan');
    Route::post('/check-ins/scan', [CheckInController::class, 'scan'])->name('check-ins.scan.post');
});

// Debug  emergency tool khusus admin, hanya aktif di environment local/development.
// Gunakan ini kalau ada order yang tokennya gagal generate (misal webhook Midtrans timeout).
// Contoh: /debug/fix-tokens/PRJ-XXXXXX
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/debug/fix-tokens/{orderNumber}', function ($orderNumber) {
        if (!app()->isLocal()) {
            abort(404);
        }

        $order = App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
        $controller = new App\Http\Controllers\PaymentController();
        $method = new ReflectionMethod($controller, 'generateTokensForOrder');
        $method->setAccessible(true);
        $method->invoke($controller, $order);

        $count = $order->items->load('tokens')->flatMap(fn($i) => $i->tokens)->count();
        return 'Done. Tokens generated: ' . $count . ' untuk order ' . $orderNumber;
    })->name('debug.fix-tokens');
});