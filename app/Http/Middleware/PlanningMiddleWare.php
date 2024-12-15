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

        // Allow logged-out users to see `prompt_user_data`
        if ($request->route()->getName() === 'prompt_user_data') {
            return $next($request);
        }

        if ($user && ($user->height == 0 || $user->weight == 0 || !$user->gender)) {
            return redirect()->route('prompt_user_data', ['redirect' => $request->path()]);
        }

        // If user is not logged in, allow access to the prompt but enforce login later
        if (!$user) {
            return redirect()->route('prompt_user_data', ['redirect' => $request->path()]);
        }

        // If user is authenticated, allow to proceed
        return $next($request);
    }
}
