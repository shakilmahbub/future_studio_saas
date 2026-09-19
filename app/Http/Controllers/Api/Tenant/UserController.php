<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\User\StoreUserRequest;
use App\Http\Requests\Tenant\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function show(User $user)
    {
        $user->load('roles');
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function store(StoreUserRequest $request)
    {

        $totaluser = User::count();
        $subscription = tenant()->subscription;
        $maxUsers = $subscription?->plan?->max_users;

        if ($maxUsers !== null && $totaluser >= $maxUsers) {
            return response()->json([
                'status' => 'error',
                'message' => "User limit reached. You can only create up to {$maxUsers} users."
            ], 403);
        }
        $validatedData = $request->validated();

        $user = User::create($validatedData);
        $user->syncRoles($request->validated('role_ids', []));
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user->update($validatedData);
        $user->roles()->detach($request->input('role_ids'));
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }


    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}
