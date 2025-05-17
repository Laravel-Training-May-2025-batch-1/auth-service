<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): Response
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->noContent();
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): JsonResponse|Response
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
                'deactivate' => ['required', 'boolean'],
            ]);

            $user = $request->user();

            $user->tokens()->each(function ($token) {
                $token->revoke();
            });

            $user->delete();

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
