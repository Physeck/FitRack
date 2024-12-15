<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanningMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is not logged in
        if (!User::find(Auth::id())) {
            // The user is not authenticated, redirect to registration page
            return redirect()->route('signup')
                 ->with('error', 'Please register or log in');
        }

        // If user is authenticated, allow to proceed
        return $next($request);
    }
}
