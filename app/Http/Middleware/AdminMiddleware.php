<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AdminMiddleware::handle - Checking admin access');
        
        if (!Auth::check()) {
            Log::warning('AdminMiddleware::handle - User not authenticated');
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        Log::info('AdminMiddleware::handle - User: ' . $user->email . ', Role: ' . $user->role);
        
        if ($user->role !== 'admin') {
            Log::warning('AdminMiddleware::handle - Access denied. User role: ' . $user->role);
            return abort(403, 'Bạn không có quyền truy cập trang này.');
        }
        
        Log::info('AdminMiddleware::handle - Access granted to admin');
        return $next($request);
    }
} 