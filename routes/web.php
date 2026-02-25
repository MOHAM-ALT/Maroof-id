<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleSwitchController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PricingController;
use App\Http\Controllers\Public\TemplateGalleryController;
use App\Http\Controllers\Public\CardViewController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\CardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\AnalyticsController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\InvoiceController;
use App\Http\Controllers\Customer\CardBuilderController;
use App\Http\Controllers\Customer\BrandKitController;
use App\Http\Controllers\Partner\DashboardController as PartnerDashboardController;
use App\Http\Controllers\Partner\OrderController as PartnerOrderController;
use App\Http\Controllers\Reseller\DashboardController as ResellerDashboardController;
use App\Http\Controllers\Reseller\SalesController as ResellerSalesController;
use App\Http\Controllers\Designer\DashboardController as DesignerDashboardController;
use App\Http\Controllers\Designer\TemplateController as DesignerTemplateController;
use App\Http\Controllers\Affiliate\DashboardController as AffiliateDashboardController;
use App\Http\Controllers\Affiliate\ClickController as AffiliateClickController;
use App\Http\Controllers\PayoutController;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/templates', [TemplateGalleryController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}', [TemplateGalleryController::class, 'show'])->name('templates.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Dashboard Route (redirect to role-specific dashboard)
Route::get('/dashboard', function() {
    $user = auth()->user();
    $activeRole = session('active_role', $user->roles->first()?->name ?? 'customer');

    return match ($activeRole) {
        'super_admin' => redirect('/admin'),
        'print_partner' => redirect()->route('partner.dashboard'),
        'reseller' => redirect()->route('reseller.dashboard'),
        'designer' => redirect()->route('designer.dashboard'),
        'affiliate' => redirect()->route('affiliate.dashboard'),
        default => redirect()->route('customer.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// Public Card View
Route::get('/card/{slug}', [CardViewController::class, 'show'])->name('public.cards.show');
Route::post('/card/{slug}/unlock', [CardViewController::class, 'unlock'])->name('public.cards.unlock');
Route::get('/card/{slug}/download-vcard', [CardViewController::class, 'downloadVCard'])->name('public.cards.download-vcard');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    
    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Logout
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');

// Email Verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerifyEmailController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend'])->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Cards
    Route::resource('cards', CardController::class);
    Route::post('/cards/{card}/toggle-publish', [CardController::class, 'togglePublish'])->name('cards.toggle-publish');
    Route::get('/cards/{card}/download-qr', [CardController::class, 'downloadQR'])->name('cards.download-qr');
    Route::post('/cards/{card}/duplicate', [CardController::class, 'duplicate'])->name('cards.duplicate');
    Route::get('/cards/{card}/share', [CardController::class, 'share'])->name('cards.share');
    
    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/apply-coupon', [OrderController::class, 'applyCoupon'])->name('orders.apply-coupon');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/card/{card}', [AnalyticsController::class, 'card'])->name('analytics.card');
    Route::get('/analytics/sales-report', [AnalyticsController::class, 'salesReport'])->name('analytics.sales-report');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/notification-preferences', [ProfileController::class, 'updateNotificationPreferences'])->name('profile.notification-preferences');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification_id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification_id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    
    // Payment
    Route::get('/payment/checkout/{order}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{transaction}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{transaction}', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::post('/payment/{transaction}/refund', [PaymentController::class, 'refund'])->name('payment.refund');
    Route::get('/payment/methods', [PaymentController::class, 'paymentMethods'])->name('payment.methods');

    // Invoices
    Route::get('/invoices/{order}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('/invoices/{order}/view', [InvoiceController::class, 'view'])->name('invoices.view');

    // Brand Kit
    Route::resource('brand-kit', BrandKitController::class)->except(['show']);

    // Card Builder Studio
    Route::get('/builder/create', [CardBuilderController::class, 'create'])->name('builder.create');
    Route::get('/builder/{card}/edit', [CardBuilderController::class, 'edit'])->name('builder.edit');
    Route::post('/builder/save', [CardBuilderController::class, 'save'])->name('builder.save');
    Route::post('/builder/upload-image', [CardBuilderController::class, 'uploadImage'])->name('builder.upload-image');
    Route::post('/builder/preview-template', [CardBuilderController::class, 'previewTemplate'])->name('builder.preview-template');
});

// Partner Routes
Route::middleware(['auth', 'role:print_partner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [PartnerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [PartnerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/update-status', [PartnerOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
});

// Reseller Routes
Route::middleware(['auth', 'role:reseller'])->prefix('reseller')->name('reseller.')->group(function () {
    Route::get('/dashboard', [ResellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [ResellerSalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [ResellerSalesController::class, 'store'])->name('sales.store');
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
});

// Designer Routes
Route::middleware(['auth', 'role:designer'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/dashboard', [DesignerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/templates', [DesignerTemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [DesignerTemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates', [DesignerTemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{template}/edit', [DesignerTemplateController::class, 'edit'])->name('templates.edit');
    Route::put('/templates/{template}', [DesignerTemplateController::class, 'update'])->name('templates.update');
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
});

// Affiliate Routes
Route::middleware(['auth', 'role:affiliate'])->prefix('affiliate')->name('affiliate.')->group(function () {
    Route::get('/dashboard', [AffiliateDashboardController::class, 'index'])->name('dashboard');
    Route::get('/clicks', [AffiliateClickController::class, 'index'])->name('clicks.index');
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
});

// Webhook Routes (Public)
Route::post('/webhooks/payment', [PaymentController::class, 'webhook'])->name('webhooks.payment');

// Role Switching
Route::middleware('auth')->post('/switch-role', [RoleSwitchController::class, 'switch'])->name('switch-role');

