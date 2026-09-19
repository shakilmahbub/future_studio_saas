<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Models\Permission;
class SubscriptionController extends Controller
{
    public function show()
    {
        $tenant = tenant();
        $tenant->load('subscription');
        
        $subscription = $tenant->subscription;

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'No subscription found for this tenant.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $subscription
        ]);
    }

    public function usage()
    {
        $tenant = tenant();
        $tenant->load('subscription');
        $subscription = $tenant->subscription;

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'No subscription found for this tenant.'
            ], 404);
        }

        $usageDetails = Cache::remember('subscription_usage', now()->addMinutes(30), function () use ($subscription) {
            return $this->getUsageDetails($subscription);
        });

        return response()->json([
            'status' => 'success',
            'data' => $usageDetails
        ]);
    }

    private function getUsageDetails(object $subscription)
    {
        $totalusers = User::count();

        $plan = Plan::findOrFail($subscription->plan_id);

        $usepercentage = ($totalusers / $plan->max_users) * 100;
        
        return [
            'total_users' => $totalusers,
            'user_limit' => $plan->max_users,
            'usage_percentage' => $usepercentage
        ];
    }
}
