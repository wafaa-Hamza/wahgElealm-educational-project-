<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$usertype): Response
    {
        if (Auth::check() && Auth::user()->user_type == $usertype) {
        return $next($request);
    }

    return response()->json(['error' => 'Unauthorized access.'], 403);

        return $next($request);
    }
}

