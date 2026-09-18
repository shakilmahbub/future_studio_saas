<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\Role\StoreRoleRequest;
use App\Http\Requests\Tenant\Role\UpdateRoleRequest;
use App\Models\Role;
class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function show(Role $role)
    {
        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = Role::create($validatedData);
        $role->syncPermissions($request->input('permission_ids', []));
        return response()->json([
            'status' => 'success',
            'data' => $role
        ], 201);
    }


    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();
        $role->update($validatedData);
        $role->syncPermissions($request->input('permission_ids', []));
        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ]);
    }
}

