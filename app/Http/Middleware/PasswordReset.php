<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordReset
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
        // Periksa apakah pengguna harus mengganti password
        if (auth()->check() && auth()->user()->password_reset) {
            return redirect()->route('user.viewUbahPassword');
        }
        return $next($request);
    }
}
