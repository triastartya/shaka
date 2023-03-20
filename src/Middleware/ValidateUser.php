<?php

namespace Viershaka\Shaka\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {                
        if (!$request->has('auth_user_kong') && !$request->filled('auth_user_kong')) {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthenticated, User not found.'
            ]);
        }

        return $next($request);
    }
}
