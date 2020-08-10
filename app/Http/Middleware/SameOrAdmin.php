<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class SameOrAdmin
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
        if ((Auth::user() && Auth::user()->is('admin')) || 
            $request->route('id') == Auth::id()) {
            return $next($request);
        }

        return response()->view('errors.403');
    }
}
