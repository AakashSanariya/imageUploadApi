<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ResponseTrait;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class RolePermissionMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissionKey)
    {
        $user = Auth::user();
        if($user->role != "SUPER_ADMIN"){
            if(!User::hasPermission($permissionKey, $user->id)){
                return $this->error('UNAUTHORIZED', 401, $permissionKey);
            }
        }
        return $next($request);
    }
}
