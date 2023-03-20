<?php

namespace Viershaka\Shaka\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateUserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {        
        if (!$request->has('auth_user_kong') && !$request->filled('auth_user_kong')) {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthenticated.'
            ]);
        }

        if (empty($role)) {
            return $next($request);
        }

        $roles = is_array($role)
                ? $role
                : explode('|', $role);
            
        $userRoles = collect($request->input('auth_user_kong.roles'));

        foreach ($roles as $role) {
            if ($userRoles->contains($role)) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "Your role has not access to this process"
        ], 403);
    }
}
