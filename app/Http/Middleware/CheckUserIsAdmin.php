<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserIsAdmin
{

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->type === 'admin') {
            return $next($request);
        }
        return back();
    }
}
