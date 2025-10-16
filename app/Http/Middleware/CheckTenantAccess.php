<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user is a developer (can access all tenants)
        if ($user->hasRole('developer')) {
            return $next($request);
        }

        // Check if user belongs to the current tenant
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            // If no tenant context, allow access for business users
            if ($user->hasRole(['super-admin', 'admin'])) {
                return $next($request);
            }
            
            abort(403, 'Access denied. No tenant context available.');
        }

        // Check if user belongs to the current tenant
        if ($user->tenant_id !== $currentTenant->id) {
            abort(403, 'Access denied. You do not have access to this tenant.');
        }

        return $next($request);
    }
}
