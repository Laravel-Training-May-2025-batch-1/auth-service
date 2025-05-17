<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAuthHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->token();

        if ($token?->revoked || $token?->expires_at?->isPast()) {
            return response()->json([
                'message' => 'Token is invalid or expired.',
            ], 401);
        }

        return $next($request);
    }
}
