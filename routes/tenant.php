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
use App\Http\Controllers\Api\Tenant\DashboardController;

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
    Route::middleware(['auth:api','context:tenant','throttle:60,1'])->group(function () {
        Route::post('logout',[AuthController::class,'logout'])->name('tenant.logout');
        Route::apiResource('customers',CustomerController::class)
        ->middlewareFor('index', 'permission:customers.view')
        ->middlewareFor('show', 'permission:customers.view')
        ->middlewareFor('store', 'permission:customers.create')
        ->middlewareFor('update', 'permission:customers.update')
        ->middlewareFor('destroy', 'permission:customers.delete');
        Route::apiResource('users',UserController::class) 
        ->middlewareFor('show', 'permission:users.view')
        ->middlewareFor('index', 'permission:users.view')
        ->middlewareFor('store', 'permission:users.create')
        ->middlewareFor('update', 'permission:users.update')
        ->middlewareFor('destroy', 'permission:users.delete');
        Route::get('subscription',[SubscriptionController::class,'show'])
        ->name('tenant.subscription.show')
        ->middleware('permission:subscription.view');
        Route::get('subscription/usage',[SubscriptionController::class,'usage'])
        ->name('tenant.subscription.usage')
        ->middleware('permission:subscription.usage');
        Route::get('permissions',PermissionController::class)
        ->name('tenant.permissions')
        ->middleware('permission:permissions.view');
        Route::apiResource('roles',RoleController::class)
        ->middlewareFor('show', 'permission:users.view')
        ->middlewareFor('index', 'permission:roles.view')
        ->middlewareFor('store', 'permission:roles.create')
        ->middlewareFor('update', 'permission:roles.update')
        ->middlewareFor('destroy', 'permission:roles.delete');
        Route::post('/users/{user}/roles',[UserRoleController::class,'assignRoles'])->name('tenant.users.assignRoles');
        Route::delete('/users/{user}/roles',[UserRoleController::class,'revokeRoles'])->name('tenant.users.revokeRoles');
        Route::get('dashboard',[DashboardController::class,'index'])
        ->name('tenant.dashboard')
        ->middleware('permission:dashboard.view');
        Route::get('/', function () {
            return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        });
    });
    
});
