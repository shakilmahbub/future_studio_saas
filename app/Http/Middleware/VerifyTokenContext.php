<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTokenContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $context): Response
    {
        $payload = auth('api')->payload();

        if ($payload->get('ctx') !== $context) {
            return response()->json([
                'message' => 'Token not valid',
            ], 403);
        }

        if ($context === 'tenant' && $payload->get('tenant_id') !== tenant('id')) {
            return response()->json([
                'message' => 'Token not valid for this tenant',
            ], 403);
        }
        return $next($request);
    }
}
