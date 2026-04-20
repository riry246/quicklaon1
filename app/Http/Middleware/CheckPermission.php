<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }
        // Check if the user has the required permission
        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Permission denied');
        }

        return $next($request);
    }
}
