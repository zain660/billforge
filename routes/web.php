<?php

use Illuminate\Support\Facades\Route;
use Zain\BillForge\Http\Controllers\Admin\DashboardController;
use Zain\BillForge\Http\Controllers\Admin\PackageController;
use Zain\BillForge\Http\Controllers\Admin\GatewayController;
use Zain\BillForge\Http\Controllers\Admin\SettingsController;
use Zain\BillForge\Http\Controllers\Admin\SubscriberController;
use Zain\BillForge\Http\Controllers\SubscriptionController;

Route::group([
    'prefix' => config('subscriptions.route_prefix', 'admin/subscriptions'),
    'middleware' => config('subscriptions.middleware', ['web', 'auth']),
    'as' => 'subscriptions.admin.'
], function () {

    Route::get('/', [DashboardController::class , 'index'])->name('dashboard');

    Route::resource('packages', PackageController::class);
    Route::resource('coupons', \Zain\BillForge\Http\Controllers\Admin\CouponController::class);

    Route::get('gateways', [GatewayController::class , 'index'])->name('gateways.index');
    Route::post('gateways/activate/{key}', [GatewayController::class , 'activate'])->name('gateways.activate');
    Route::post('gateways/update/{key}', [GatewayController::class , 'update'])->name('gateways.update');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Subscribers
    Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('subscribers/{id}/cancel', [SubscriberController::class, 'cancel'])->name('subscribers.cancel');
    Route::post('subscribers/{id}/activate', [SubscriberController::class, 'activate'])->name('subscribers.activate');
    Route::post('subscribers/{id}/block', [SubscriberController::class, 'block'])->name('subscribers.block');

});

Route::group([
    'prefix' => config('subscriptions.user_route_prefix', 'subscriptions'),
    'middleware' => config('subscriptions.user_middleware', ['web', 'auth']),
    'as' => 'subscriptions.'
], function () {
    Route::get('/', [SubscriptionController::class , 'pricing'])->name('pricing');
    Route::get('/my-subscription', [SubscriptionController::class , 'mySubscription'])->name('my');
    Route::post('/checkout/{package}', [SubscriptionController::class , 'checkout'])->name('checkout');
    Route::get('/checkout/success', [SubscriptionController::class , 'success'])->name('success');
    Route::get('/checkout/cancel', [SubscriptionController::class , 'cancel'])->name('cancel');
    Route::post('/billing-portal', [SubscriptionController::class , 'billingPortal'])->name('billing-portal');
});
