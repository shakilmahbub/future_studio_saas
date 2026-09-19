<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = Cache::remember('subscription_usage', now()->addMinutes(30), function () {
            return [
                'user_count' => User::count(),
                'customer_count' => Customer::count(),
                'role_count' => Role::count(),
                'new_customers_this_month' => Customer::where(
                    'created_at',
                    '>=',
                    now()->startOfMonth()
                )->count(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
