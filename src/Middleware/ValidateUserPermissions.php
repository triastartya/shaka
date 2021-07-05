<?php

namespace Att\Responisme\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {                
        if (!$request->has('auth_user_kong') && !$request->filled('auth_user_kong')) {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthenticated'
            ], 401);
        }

        if (empty($permission)) {
            return $next($request);
        }

        $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
            
        $userPermissions = collect($request->input('auth_user_kong.permissions'))->pluck('name');

        foreach ($permissions as $permission) {
            if ($userPermissions->contains($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "You don't have permission to this process"
        ], 403);
    }
}
