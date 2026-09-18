<?php

use App\Http\Controllers\Api\Central\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Central\PlanController;
use App\Http\Controllers\Api\Central\UserController;
use App\Http\Controllers\Api\Central\SubscriptionController;
use App\Http\Controllers\Api\Central\TenantController;
use App\Http\Controllers\Api\RefreshTokenController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::prefix('v1')->group(function () {

            Route::post('login',[LoginController::class,'login'])->name('login');
            Route::post('refresh', RefreshTokenController::class)->name('refresh');
            Route::middleware(['auth:api','context:central'])->group(function () {
                Route::post('logout',[LoginController::class,'logout'])->name('logout');
                Route::apiResource('plans',PlanController::class);
                Route::apiResource('users',UserController::class);
                Route::apiResource('subscriptions',SubscriptionController::class);
                Route::apiResource('tenants',TenantController::class);
                Route::get('/', function () {
                    return 'This is your multi-tenant application. this is the central domain';
                });
            });
           

        });

    });
}