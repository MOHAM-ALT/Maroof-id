<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CommissionController;

Route::get('/health', function () {
    return response()->json([
        'message' => 'API is running',
        'status' => 'success',
        'timestamp' => now(),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    
    /**
     * User Profile Routes
     */
    Route::get('/me', function (Request $request) {
        return response()->json([
            'message' => 'بيانات المستخدم',
            'data' => $request->user(),
            'status' => 'success'
        ]);
    });

    Route::put('/me', function (Request $request) {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($validated);

        return response()->json([
            'message' => 'تم تحديث البيانات بنجاح',
            'data' => auth()->user(),
            'status' => 'success'
        ]);
    });

    /**
     * Cards API Routes
     * GET     /api/v1/cards                    - List all public cards
     * GET     /api/v1/cards/{id}               - Get single card
     * POST    /api/v1/cards                    - Create new card
     * PUT     /api/v1/cards/{id}               - Update card
     * DELETE  /api/v1/cards/{id}               - Delete card
     * GET     /api/v1/cards/{id}/analytics    - Get card analytics
     * POST    /api/v1/cards/{id}/publish      - Publish card
     * GET     /api/v1/my-cards                 - Get user's cards
     */
    Route::apiResource('cards', CardController::class);
    Route::get('/my-cards', [CardController::class, 'myCards']);
    Route::get('/cards/{card}/analytics', [CardController::class, 'analytics']);
    Route::post('/cards/{card}/publish', [CardController::class, 'publish']);

    /**
     * Orders API Routes
     * GET     /api/v1/orders                   - List user's orders
     * GET     /api/v1/orders/{id}              - Get single order
     * POST    /api/v1/orders                   - Create new order
     * PUT     /api/v1/orders/{id}              - Update order
     * POST    /api/v1/orders/{id}/cancel       - Cancel order
     * POST    /api/v1/orders/{id}/apply-coupon - Apply coupon
     * GET     /api/v1/my-orders                - Get user's orders
     */
    Route::apiResource('orders', OrderController::class);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::post('/orders/{order}/apply-coupon', [OrderController::class, 'applyCoupon']);

    /**
     * Payments API Routes
     * POST    /api/v1/payments                 - Process payment
     * GET     /api/v1/transactions/{id}        - Get transaction details
     * GET     /api/v1/my-transactions          - Get user's transactions
     * POST    /api/v1/payments/{id}/refund     - Refund payment
     * GET     /api/v1/payment-methods          - Get available payment methods
     */
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/transactions/{transaction}', [PaymentController::class, 'show']);
    Route::get('/my-transactions', [PaymentController::class, 'myTransactions']);
    Route::post('/payments/{transaction}/refund', [PaymentController::class, 'refund']);
    Route::get('/payment-methods', [PaymentController::class, 'paymentMethods']);

    /**
     * Commissions API Routes
     * GET     /api/v1/commissions/dashboard    - Commission dashboard
     * GET     /api/v1/commissions/history      - Payout history
     * GET     /api/v1/commissions/payouts      - Get payouts
     * POST    /api/v1/commissions/request-payout - Request payout
     * GET     /api/v1/commissions/levels       - Get performance levels
     * GET     /api/v1/commissions/performance  - Get user's performance
     */
    Route::prefix('commissions')->group(function () {
        Route::get('/dashboard', [CommissionController::class, 'dashboard']);
        Route::get('/history', [CommissionController::class, 'history']);
        Route::get('/payouts', [CommissionController::class, 'payouts']);
        Route::post('/request-payout', [CommissionController::class, 'requestPayout']);
        Route::get('/levels', [CommissionController::class, 'levels']);
        Route::get('/performance', [CommissionController::class, 'performance']);
    });

});

/**
 * Public API Routes (No Auth Required)
 */
Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/{card}', [CardController::class, 'show']);
Route::get('/payment-methods', [PaymentController::class, 'paymentMethods']);
Route::get('/commissions/levels', [CommissionController::class, 'levels']);
