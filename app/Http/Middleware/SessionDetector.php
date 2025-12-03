<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SessionDetector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $sessionIp = session('auth_ip');
            $sessionUa = session('auth_user_agent');

            $currentIp = $request->ip();
            $currentUa = $request->userAgent();

            // Check if IP or User-Agent has changed
            if ($sessionIp && $sessionUa &&
                ($sessionIp !== $currentIp || $sessionUa !== $currentUa)) {

                // Log the security incident
                Log::warning('Session hijacking detected', [
                    'user_id' => Auth::id(),
                    'original_ip' => $sessionIp,
                    'current_ip' => $currentIp,
                    'original_ua' => $sessionUa,
                    'current_ua' => $currentUa,
                    'timestamp' => now(),
                ]);

                // Session appears stolen - logout and invalidate
                Auth::logout();

                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Revoke all tokens for this user
                Auth::user()->tokens()->delete();

                // For API, return 401 with specific error
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Session security violation detected. Please login again.',
                        'error' => 'session_hijacked'
                    ], 401);
                }

                // For web, redirect to login
                return redirect('/login')->withErrors([
                    'hijack' => 'Your session was compromised. Please login again.'
                ]);
            }
        }

        return $next($request);
    }
}