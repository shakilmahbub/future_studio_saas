<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Tenant\StoreTenantRequest;
use Illuminate\Http\Request;
use App\Services\TenantService;
use App\Models\Tenant;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class TenantController extends Controller
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index()
    {
        $tenants = Tenant::with('domains')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $tenants
        ]);
    }


    public function show(Tenant $tenant)
    {
        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ]);
    }

    public function store(StoreTenantRequest $request)
    {
    
        $tenant = $this->tenantService->createTenant($request->validated());
        
        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ], 201);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:tenants,name,' . $tenant->id,
        ]);

        $tenant->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ]);
    }

    public function destroy(Tenant $tenant)
    {
        $this->tenantService->deleteTenant($tenant);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant deleted successfully'
        ]);
    }

}
