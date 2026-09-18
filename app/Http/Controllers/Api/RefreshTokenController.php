<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class RefreshTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $token = Auth::refresh();
        Auth::setToken($token);
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
}
