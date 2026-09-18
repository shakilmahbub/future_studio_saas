<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Central\Subscription\UpdateSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $subscriptions
        ]);
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $validatedData = $request->validated();
        $subscription = Subscription::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $subscription
        ], 201);
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan']);
        return response()->json([
            'status' => 'success',
            'data' => $subscription
        ]);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $validatedData = $request->validated();
        $subscription->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $subscription
        ]);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription deleted successfully'
        ]);
    }
}
