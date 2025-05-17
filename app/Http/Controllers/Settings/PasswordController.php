<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): JsonResponse|Response
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user = $request->user();

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            $user->tokens()->each(function ($token) {
                $token->revoke();
            });

            $cookie = Cookie::forget('_token');

            return response()->noContent()->withCookie($cookie);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
