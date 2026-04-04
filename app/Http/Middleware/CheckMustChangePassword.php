<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If the user must change password and is not already on the change password page
            if ($user->must_change_password && !$request->routeIs('auth.change-password') && !$request->routeIs('logout')) {
                return redirect()->route('auth.change-password');
            }
        }

        return $next($request);
    }
}
