<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOfferAccess
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
        if (
            isset(request()->route()->nurse->id)
            && request()->route()->nurse->id === Auth::user()->nurse->id
        ) {
            return $next($request);            
        } else {
            abort(404);
            exit;
        }
    }
}
