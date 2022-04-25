<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class Admin
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next)
    {
        if (Auth::user() &&  Auth::user()->is_admin == 1) {
             return $next($request);
        }

        return redirect('home')->with('error','You have not admin access');
    }
}
