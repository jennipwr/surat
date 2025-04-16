<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTU
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        $user = auth()->user();

        if (
            $user &&
            $user->role === 'karyawan' &&
            $user->karyawan &&
            $user->karyawan->jabatan === 'tu'
        ) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Akses hanya untuk TU.');
    }
}
