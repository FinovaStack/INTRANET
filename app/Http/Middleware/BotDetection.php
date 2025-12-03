<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BotDetection
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
        // Basic API rate limiting per IP to prevent abuse
        $key = 'api_requests:' . $request->ip();
        $requests = cache()->get($key, 0) + 1;
        cache()->put($key, $requests, 60); // 1 minute window

        if ($requests > 100) { // Reasonable limit for legitimate API usage
            return response()->json(['message' => 'Too many requests. Please try again later.'], 429);
        }

        return $next($request);
    }
}