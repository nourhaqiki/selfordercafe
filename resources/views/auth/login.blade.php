@extends('layouts.app')

@section('title', 'Login Staf - Kopi & Mie Nusantara')

@section('content')
<div class="min-h-screen bg-stone-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        
        <!-- Brand Mark -->
        <div class="flex justify-center">
            <a href="{{ route('customer.catalog') }}" class="w-14 h-14 rounded-2xl bg-emerald-800 text-white font-black text-xl flex items-center justify-center shadow-md hover:bg-emerald-700 transition-colors">
                KM
            </a>
        </div>

        <h1 class="mt-4 text-center text-2xl font-black tracking-tight text-stone-900">
            Portal Masuk Staf Cafe
        </h1>
        <p class="mt-1 text-center text-xs text-stone-600">
            Akses khusus operasional Kasir dan kontrol Superadmin Manager.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow-sm border border-stone-200 rounded-2xl sm:px-10">
            
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                        Alamat Email Akun Staf *
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        placeholder="kasir@cafe.com atau manager@cafe.com" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 bg-stone-50" />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">
                        Kata Sandi *
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 bg-stone-50" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            class="w-4 h-4 rounded border-stone-300 text-emerald-700 focus:ring-emerald-700">
                        <span class="text-xs font-semibold text-stone-700">Ingat sesi login saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full min-h-[48px] py-3 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-sm font-extrabold tracking-wide transition-all shadow-xs cursor-pointer flex items-center justify-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        <span>Masuk ke Sistem</span>
                    </button>
                </div>
            </form>

            <!-- Quick Account Reference Card (For Easy Testing) -->
            <div class="mt-6 pt-5 border-t border-stone-100">
                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider block mb-2">Akun Default Terdaftar:</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <button 
                        type="button" 
                        onclick="document.getElementById('email').value='kasir@cafe.com'; document.getElementById('password').value='kasir123';"
                        class="p-2.5 rounded-xl border border-stone-200 bg-stone-50 hover:bg-emerald-50 text-left transition-colors cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-stone-900">1. Akun Kasir</span>
                            <span class="text-[10px] text-emerald-800 bg-emerald-100 px-1.5 py-0.2 rounded font-bold">POS</span>
                        </div>
                        <span class="text-[11px] text-stone-500 block mt-0.5">kasir@cafe.com</span>
                        <span class="text-[10px] text-stone-400 font-mono">kasir123</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="document.getElementById('email').value='manager@cafe.com'; document.getElementById('password').value='manager123';"
                        class="p-2.5 rounded-xl border border-stone-200 bg-stone-50 hover:bg-emerald-50 text-left transition-colors cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-stone-900">2. Akun Manager</span>
                            <span class="text-[10px] text-stone-800 bg-stone-200 px-1.5 py-0.2 rounded font-bold">Admin</span>
                        </div>
                        <span class="text-[11px] text-stone-500 block mt-0.5">manager@cafe.com</span>
                        <span class="text-[10px] text-stone-400 font-mono">manager123</span>
                    </button>
                </div>
            </div>

            <!-- Public Self-Order Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('customer.catalog') }}" class="text-xs font-bold text-emerald-800 hover:underline">
                    ← Kembali ke Halaman Pemesanan Meja Pelanggan
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

