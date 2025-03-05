<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 1) {
            return $next($request);
        }

        return response()->json([
            'error'   => 'Forbidden',
            'message' => 'You do not have permission to access this resource.',
        ], 403);
    }
}
