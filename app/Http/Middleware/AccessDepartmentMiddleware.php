<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;

class AccessDepartmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admins = [
			Role::getKey(Role::FULLADMIN),
			Role::getKey(Role::ADMIN)
        ];
        if ( in_array(Auth::user()->role, $admins) ) {
            return $next($request);
        }else{
            abort(404);
            exit;
        }
    }
}