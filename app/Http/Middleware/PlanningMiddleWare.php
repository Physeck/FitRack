<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $user = User::find(Auth::id());

        if ($user && ($user->height == 0 || $user->weight == 0 || !$user->gender)) {
            return redirect()->route('prompt_user_data', ['redirect' => $request->path()]);
        }

        if (!$user) {
            return redirect()->route('signup')->with('error', 'Please sign up or sign in to continue.');
        }

        // If user is authenticated, allow to proceed
        return $next($request);
    }
}
