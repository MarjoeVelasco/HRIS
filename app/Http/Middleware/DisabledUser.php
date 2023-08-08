<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class DisabledUser
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->is_disabled == 1) {

            Auth::logout();

            return redirect('/login')->with('error','This account has been disabled, please contact the administrator.');
        }

        return $next($request);
    }
}   