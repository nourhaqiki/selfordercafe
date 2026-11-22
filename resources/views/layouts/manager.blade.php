@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-stone-100 flex flex-col">

    {{-- Manager Header --}}
    <header class="sticky top-0 z-40 bg-stone-900 text-white border-b border-stone-800 shadow-sm">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-14 sm:h-16 gap-3">

                {{-- Brand + Desktop Nav --}}
                <div class="flex items-center gap-4 sm:gap-6 min-w-0">
                    <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-2 shrink-0">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-800 flex items-center justify-center text-white font-black text-sm shadow-xs">
                            KM
                        </div>
                        <div class="flex flex-col hidden sm:flex">
                            <span class="text-sm font-extrabold tracking-tight text-white leading-tight">Kopi &amp; Mie Nusantara</span>
                            <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider">MANAGER PORTAL</span>
                        </div>
                    </a>

                    {{-- Desktop Nav Links --}}
                    <nav class="hidden md:flex items-center gap-1" aria-label="Menu Manager">
                        <a href="{{ route('manager.dashboard') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request()->routeIs('manager.dashboard') ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            Overview
                        </a>
                        <a href="{{ route('manager.menu.index') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request()->routeIs('manager.menu.*') ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            Kelola Menu
                        </a>
                        <a href="{{ route('manager.categories.index') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request()->routeIs('manager.categories.*') ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            Kategori
                        </a>
                        <a href="{{ route('manager.tables.index') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request()->routeIs('manager.tables.*') ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            Meja &amp; QR
                        </a>
                        <a href="{{ route('manager.reports.index') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request()->routeIs('manager.reports.*') ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white hover:bg-stone-800' }}">
                            Laporan
                        </a>
                        <a href="{{ route('manager.cashier.monitor') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request()->routeIs('manager.cashier.monitor') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                            Pantau Kasir
                        </a>
                    </nav>
                </div>

                {{-- Right: User + Actions --}}
                <div class="flex items-center gap-2 shrink-0">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs font-bold text-white">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-amber-400 font-semibold uppercase">Manager</span>
                    </div>

                    <a href="{{ route('customer.catalog') }}"
                       target="_blank"
                       class="min-h-[36px] sm:min-h-[40px] px-2.5 py-1.5 rounded-xl bg-stone-800 hover:bg-stone-700 border border-stone-700 text-stone-200 text-xs font-bold inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        <span class="hidden md:inline">Mode Meja</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            title="Keluar"
                            class="min-h-[36px] sm:min-h-[40px] px-2.5 py-1.5 rounded-xl bg-red-950/80 hover:bg-red-900 border border-red-800 text-red-200 text-xs font-bold inline-flex items-center gap-1.5 cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile Sub-nav (< md): full scrollable tab bar --}}
        <div class="md:hidden border-t border-stone-800 bg-stone-950">
            <div class="max-w-screen-2xl mx-auto px-2 py-1.5 flex items-center gap-0.5 overflow-x-auto no-scrollbar">
                <a href="{{ route('manager.dashboard') }}"
                   class="px-3 py-2 rounded-md text-xs font-bold whitespace-nowrap min-h-[36px] flex items-center {{ request()->routeIs('manager.dashboard') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                    Overview
                </a>
                <a href="{{ route('manager.menu.index') }}"
                   class="px-3 py-2 rounded-md text-xs font-bold whitespace-nowrap min-h-[36px] flex items-center {{ request()->routeIs('manager.menu.*') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                    Menu
                </a>
                <a href="{{ route('manager.categories.index') }}"
                   class="px-3 py-2 rounded-md text-xs font-bold whitespace-nowrap min-h-[36px] flex items-center {{ request()->routeIs('manager.categories.*') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                    Kategori
                </a>
                <a href="{{ route('manager.tables.index') }}"
                   class="px-3 py-2 rounded-md text-xs font-bold whitespace-nowrap min-h-[36px] flex items-center {{ request()->routeIs('manager.tables.*') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                    Meja &amp; QR
                </a>
                <a href="{{ route('manager.reports.index') }}"
                   class="px-3 py-2 rounded-md text-xs font-bold whitespace-nowrap min-h-[36px] flex items-center {{ request()->routeIs('manager.reports.*') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                    Laporan
                </a>
                <a href="{{ route('manager.cashier.monitor') }}"
                   class="px-3 py-2 rounded-md text-xs font-bold whitespace-nowrap min-h-[36px] flex items-center {{ request()->routeIs('manager.cashier.monitor') ? 'bg-emerald-800 text-white' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                    Kasir
                </a>
            </div>
        </div>
    </header>

    {{-- Manager Body --}}
    <main class="flex-1 max-w-screen-2xl w-full mx-auto p-3 sm:p-4 lg:p-6 xl:p-8">
        @yield('manager-content')
    </main>
</div>
@endsection
