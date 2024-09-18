<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class premiumcoursesmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        //check-fi-user-is-admin-or-has-premium-subscrribtion
        // 
        if (Auth::check() && Auth::user()->user_type === "premium") {
            return $next($request);
        }

        return redirect('dashboard')->with('error', 'Not a Premium User!!');
    }
}
