<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::claims(['ctx' => 'central'])->attempt(credentials: $credentials);

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
