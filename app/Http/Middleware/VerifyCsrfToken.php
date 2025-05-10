<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'payment/callback', // Exclude payment callback URLs from CSRF protection
    ];

    /**
     * Determine if the request has a valid CSRF token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $request->header('X-CSRF-TOKEN') ?: $request->input('_token');

        if (!$token && $request->header('Content-Type') === 'application/json') {
            $token = $request->header('X-CSRF-TOKEN');
        }

        return is_string($token) && hash_equals($request->session()->token(), $token);
    }
} 