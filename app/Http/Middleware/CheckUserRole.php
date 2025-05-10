<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            // Check if user has the role
            if ($request->user()->role === $role) {
                return $next($request);
            }
        }

        return abort(403, 'Bạn không có quyền truy cập trang này.');
    }
} 