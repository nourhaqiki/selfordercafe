@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-stone-100 flex flex-col">
    <!-- Top Administrative Navigation -->
    <header class="sticky top-0 z-40 bg-stone-900 text-white border-b border-stone-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand & Role Indicator -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-2.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 rounded-md">
                        <div class="w-8 h-8 rounded-lg bg-emerald-700 flex items-center justify-center text-white font-bold text-sm shadow-xs">
                            KM
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold tracking-tight text-white">Kopi & Mie Nusantara</span>
                            <span class="text-[11px] text-emerald-400 font-semibold tracking-wide">OPERATIONS PORTAL</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <nav class="hidden md:flex items-center gap-1" aria-label="Menu Navigasi Staf">
                        <a href="{{ route('cashier.index') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ request()->routeIs('cashier.*') ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Kasir (POS & Approve)</span>
                        </a>

                        <a href="{{ route('manager.dashboard') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ request()->routeIs('manager.dashboard') ? 'bg-stone-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                            <span>Manager Overview</span>
                        </a>

                        <a href="{{ route('manager.menu.index') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ request()->routeIs('manager.menu.*') ? 'bg-stone-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            <span>Kelola Menu</span>
                        </a>

                        <a href="{{ route('manager.tables.index') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ request()->routeIs('manager.tables.*') ? 'bg-stone-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            <span>Meja & QR Code</span>
                        </a>

                        <a href="{{ route('manager.reports.index') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ request()->routeIs('manager.reports.*') ? 'bg-stone-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            <span>Laporan Omzet</span>
                        </a>
                    </nav>
                </div>

                <!-- Right Quick Actions -->
                <div class="flex items-center gap-3">
                    <!-- Traffic Status Pill -->
                    @php $isPaused = \App\Models\StoreSetting::isOrderPaused(); @endphp
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold {{ $isPaused ? 'bg-amber-900/80 text-amber-200 border border-amber-700' : 'bg-emerald-900/80 text-emerald-200 border border-emerald-700' }}">
                        <span class="w-2 h-2 rounded-full {{ $isPaused ? 'bg-amber-400' : 'bg-emerald-400 animate-pulse' }}"></span>
                        <span>{{ $isPaused ? 'Order Dijeda' : 'Order Dibuka' }}</span>
                    </div>

                    <!-- Jump to Customer View -->
                    <a href="{{ route('customer.catalog') }}" target="_blank" class="min-h-[44px] px-3 py-1.5 rounded-lg bg-stone-800 hover:bg-stone-700 border border-stone-700 text-stone-200 hover:text-white text-xs font-bold transition-colors inline-flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        <span class="hidden sm:inline">Lihat Tampilan Pelanggan</span>
                        <span class="sm:hidden">Mode HP</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Sub-navigation -->
        <div class="md:hidden border-t border-stone-800 bg-stone-950 px-4 py-2 flex items-center justify-between gap-1 overflow-x-auto no-scrollbar">
            <a href="{{ route('cashier.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-bold whitespace-nowrap {{ request()->routeIs('cashier.*') ? 'bg-emerald-800 text-white' : 'text-stone-400' }}">Kasir</a>
            <a href="{{ route('manager.dashboard') }}" class="px-2.5 py-1.5 rounded-md text-xs font-bold whitespace-nowrap {{ request()->routeIs('manager.dashboard') ? 'bg-stone-800 text-white' : 'text-stone-400' }}">Overview</a>
            <a href="{{ route('manager.menu.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-bold whitespace-nowrap {{ request()->routeIs('manager.menu.*') ? 'bg-stone-800 text-white' : 'text-stone-400' }}">Menu</a>
            <a href="{{ route('manager.tables.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-bold whitespace-nowrap {{ request()->routeIs('manager.tables.*') ? 'bg-stone-800 text-white' : 'text-stone-400' }}">Meja & QR</a>
            <a href="{{ route('manager.reports.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-bold whitespace-nowrap {{ request()->routeIs('manager.reports.*') ? 'bg-stone-800 text-white' : 'text-stone-400' }}">Laporan</a>
        </div>
    </header>

    <!-- Main Administrative Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        @yield('admin-content')
    </main>
</div>
@endsection

