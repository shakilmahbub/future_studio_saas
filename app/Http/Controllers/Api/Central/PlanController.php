<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Paln\UpdatePlanRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Central\PlanRequest;
use App\Models\Plan;
class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $plans
        ]);
    }

    public function store(PlanRequest $request)
    {
        $validatedData = $request->validated();
        $plan = Plan::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $plan
        ], 201);
    }

    public function show(Plan $plan)
    {
        return response()->json([
            'status' => 'success',
            'data' => $plan
        ]);
    }


    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $validatedData = $request->validated();
        $plan->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $plan
        ]);
    }


    public function destroy(Plan $plan)
    {
        $plan->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Plan deleted successfully'
        ]);
    }
}
