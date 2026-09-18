<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
class PermissionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $permissions = Cache::remember('all_permissions', now()->addMinutes(30), function () {
            return Permission::all(['id', 'name', 'display_name']);;
        });

        return response()->json([
            'status' => 'success',
            'data' => $permissions
        ]);
    }
}
