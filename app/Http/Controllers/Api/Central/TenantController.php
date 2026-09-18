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
    
        $validatedData = $request->validated();

        $tenant = Tenant::create(['id' => $validatedData['name']]);

        $tenant->domains()->create(['domain' => $validatedData['name'].'.'.config('tenancy.central_domains')[0]]);

        tenancy()->initialize($tenant);

        // Tenant database is active here
        User::create([
            'name' => $validatedData['admin_name'],
            'email' => $validatedData['admin_email'],
            'password' => Hash::make($validatedData['admin_password']),
        ]);

        tenancy()->end();
        
        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ], 201);
    }

}
