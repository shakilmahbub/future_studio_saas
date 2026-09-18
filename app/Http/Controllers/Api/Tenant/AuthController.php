<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::claims(['ctx' => 'tenant', 'tenant_id' => tenant('id')])->attempt(credentials: $credentials);

        if ($token ) {
            $user = Auth::user();
            
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authentication' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
            ], 401);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }
}
