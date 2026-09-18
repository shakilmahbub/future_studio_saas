<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\RefreshTokenController;
use App\Http\Controllers\Api\Tenant\UserController;
use App\Http\Controllers\Api\Tenant\CustomerController;
use App\Http\Controllers\Api\Tenant\SubscriptionController;
use App\Http\Controllers\Api\Tenant\PermissionController;
use App\Http\Controllers\Api\Tenant\RoleController;
use App\Http\Controllers\Api\Tenant\UserRoleController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api/v1')->group(function () {
    Route::post('login',[AuthController::class,'login'])->name('tenant.login');
    Route::post('refresh', RefreshTokenController::class)->name('tenant.refresh');
    Route::middleware(['auth:api','context:tenant'])->group(function () {
        Route::post('logout',[AuthController::class,'logout'])->name('tenant.logout');
        Route::apiResource('customers',CustomerController::class);
        Route::apiResource('users',UserController::class);
        Route::get('subscription',[SubscriptionController::class,'show'])->name('tenant.subscription.show');
        Route::get('subscription/usage',[SubscriptionController::class,'usage'])->name('tenant.subscription.usage');
        Route::get('permissions',PermissionController::class)->name('tenant.permissions');
        Route::apiResource('roles',RoleController::class);
        Route::post('/users/{user}/roles',[UserRoleController::class,'assignRoles'])->name('tenant.users.assignRoles');
        Route::delete('/users/{user}/roles',[UserRoleController::class,'revokeRoles'])->name('tenant.users.revokeRoles');
        Route::get('/', function () {
            return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        });
    });
    
});
