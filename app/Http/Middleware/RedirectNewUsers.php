<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectNewUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->user()->is_new);
        if ($request->user() && $request->user()->is_new && !$request->routeIs('get-started') && !$request->routeIs('password.onboarding')) {
            return redirect()->route('get-started');
        }

        return $next($request);
    }
} 