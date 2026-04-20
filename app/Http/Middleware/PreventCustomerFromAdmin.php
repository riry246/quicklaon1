<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PreventCustomerFromAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Ensure user is authenticated and has groups
        if ($user && $user->groups->isNotEmpty()) {
            // Retrieve the first group from the collection
            $group = $user->groups->first();

            // Now you can access attributes of the group
            $groupName = $group->name;
            $groupSlug = $group->slug;
            // Access other attributes as needed

            // Example usage:
            if (Auth::check() && $groupSlug === 'customer') {
                Auth::logout();
                return redirect('https://app.cashfaster.com.au'); // Redirect customers away from admin section
            }
        } else {
            return redirect('/');
        }

        return $next($request);
    }
}
