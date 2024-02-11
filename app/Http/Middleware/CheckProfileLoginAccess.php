<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;

class CheckProfileLoginAccess
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
        $users = [
            Role::getKey(Role::NURSE),
			Role::getKey(Role::FACILITY),
            Role::getKey(Role::FACILITYADMIN)
        ];
        if (
            isset(request()->route()->nurse->id)
            && request()->route()->nurse->id === Auth::user()->nurse->id
        ) {
            return $next($request);            
        } elseif (
            isset(request()->route()->facility->id)
            && request()->route()->facility->id === Auth::user()->facilities()->first()->id
        ) {
            return $next($request);            
        } elseif ( 
			in_array(Auth::user()->role, $users) &&
			in_array(
				$request->getRequestUri(),
				[
					'/profile-setup'
				]
			)
        ) {
            return $next($request);            
        } else {
            abort(404);
            exit;
        }
    }
}
