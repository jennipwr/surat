<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMahasiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'mahasiswa') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Akses hanya untuk Mahasiswa.');
    }

}
