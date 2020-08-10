<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ErrorController;
use Illuminate\Support\Facades\Auth;

use Closure;

class IsAdmin
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
        if (!Auth::user()->is('admin')) {
            return response()->view('errors.403');
        }
        
        return $next($request);
    }
}
