<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin' || auth()->check() && auth()->user()->role === 'super') {
            return $next($request);
        } else if(auth()->check() && auth()->user()->role === 'admin_sekolah') {
            return redirect()->route("pelajar");
        }
    
        return redirect()->route("dashboard");
    }
}
