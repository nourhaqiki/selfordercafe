<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk dengan akun staf terlebih dahulu.');
        }

        $user = Auth::user();

        // Check if user's role matches any allowed role
        if (!in_array($user->role, $roles, true)) {
            if ($user->role === 'cashier') {
                return redirect()->route('cashier.index')
                    ->with('error', 'Akses Ditolak: Akun Kasir tidak memiliki wewenang untuk membuka portal Manager.');
            }

            if ($user->role === 'manager') {
                return redirect()->route('manager.dashboard')
                    ->with('error', 'Akses Ditolak: Halaman ini khusus untuk peran kasir.');
            }

            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

