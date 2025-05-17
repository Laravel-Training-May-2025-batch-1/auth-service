<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    /**
     * Login the user and create a personal access token.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $rememberMe
     * @return \Illuminate\Http\Cookie
     */
    protected function login(User $user, $rememberMe = false)
    {
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($rememberMe) {
            $token->expires_at = now()->addMonths(12);
            $token->save();
        }

        return cookie(
            '_token', // name
            $tokenResult->accessToken, // value
            $token->expires_at->diffInMinutes(now(), true), // expires_in to minutes: (ex: 7200 seconds to 120 minutes)
            '/', // path
            'localhost', // domain
            app()->environment('local') ? false : true, // secure,
            true, // httpOnly
            false, // raw
            'strict' // sameSite
        );
    }
}
