<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
class UserRoleController extends Controller
{
    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);
        
        $user->roles()->syncWithoutDetaching($request->input('role_ids'));

        return response()->json([
            'status' => 'success',
            'message' => 'Roles assigned successfully',
        ]);
    }

    public function revokeRoles(Request $request, User $user)
    {
        $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $user->roles()->detach($request->input('role_ids'));

        return response()->json([
            'status' => 'success',
            'message' => 'Roles revoked successfully',
        ]);
    }
}
