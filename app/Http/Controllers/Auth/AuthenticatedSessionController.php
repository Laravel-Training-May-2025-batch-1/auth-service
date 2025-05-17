<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $user = Auth::user();

        $cookie = $this->login($user, $request->remember);

        return response()->noContent()->cookie($cookie);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $request->user()->token()->revoke();

        $cookie = Cookie::forget('_token');

        return response()->noContent()->withCookie($cookie);
    }

    /**
     * Verify the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
