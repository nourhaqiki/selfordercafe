<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === User::ROLE_MANAGER) {
                return redirect()->route('manager.dashboard');
            }
            return redirect()->route('cashier.index');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === User::ROLE_MANAGER) {
                return redirect()->intended(route('manager.dashboard'))
                    ->with('success', "Selamat datang di Portal Manager, {$user->name}!");
            }

            return redirect()->intended(route('cashier.index'))
                ->with('success', "Selamat bertugas di meja kasir, {$user->name}!");
        }

        return back()->withInput($request->only('email'))
            ->with('error', 'Kombinasi email atau kata sandi tidak cocok. Silakan periksa kembali akun staf Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem secara aman.');
    }
}

