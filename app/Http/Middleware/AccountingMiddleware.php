<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccountingMiddleware
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
        if (auth()->user()->hasRole('Accounting/FAD')) {
            return $next($request);
        }
    
        return abort(403);
    }
}
