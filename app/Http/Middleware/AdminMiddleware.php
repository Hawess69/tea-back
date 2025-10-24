<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Middleware to ensure user has admin privileges
 * 
 * Checks if authenticated user has admin or moderator role
 * before allowing access to admin routes.
 * 
 * @package App\Http\Middleware
 */
final class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): RedirectResponse|JsonResponse|Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Authentication required',
                ], 401);
            }
            
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'moderator'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Admin privileges required',
                ], 403);
            }
            
            return redirect()->route('login')->with('error', 'Admin access required');
        }

        return $next($request);
    }
}
