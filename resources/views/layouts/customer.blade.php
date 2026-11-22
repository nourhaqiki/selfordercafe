@extends('layouts.app')

@section('content')
{{--
    Customer Shell Layout
    Breakpoints & Devices:
      - Smartphone (iPhone & Android, 320px - 480px): Edge-to-edge fluid, bottom nav bar, safe area insets.
      - Tablet Portrait (iPad / Android Tab, 768px - 1023px): Fluid max-w-4xl, 3-column menu grid, bottom nav.
      - Tablet Landscape & Desktop (1024px+):
          * With cart sidebar (Catalog): 2-pane split (menu left + sticky cart right).
          * Without cart sidebar (Checkout, Track): Centered max-w-4xl / max-w-5xl spacious view.
--}}
@php
    $hasSidebar = View::hasSection('cart-sidebar');
@endphp

<div class="w-full min-h-screen min-h-[100dvh] bg-stone-100 flex flex-col antialiased selection:bg-emerald-700 selection:text-white">

    {{-- App Shell Container --}}
    <div class="w-full mx-auto min-h-screen min-h-[100dvh] bg-white flex flex-col relative
                {{ $hasSidebar
                    ? 'lg:max-w-7xl xl:max-w-screen-2xl lg:grid lg:grid-cols-[1fr_380px] xl:grid-cols-[1fr_420px] lg:border-x lg:border-stone-200 lg:shadow-sm'
                    : 'max-w-3xl lg:max-w-5xl sm:shadow-sm sm:border-x sm:border-stone-200' }}">

        {{-- MAIN / LEFT PANE --}}
        <div class="flex flex-col min-h-screen min-h-[100dvh] {{ $hasSidebar ? 'lg:border-r lg:border-stone-100' : '' }}">

            {{-- Sticky Top Header: Safe area padded for iPhone notch / Dynamic Island --}}
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-stone-100 transition-shadow"
                    style="padding-top: max(0.25rem, env(safe-area-inset-top));"
                    role="banner">
                <div class="px-3 sm:px-4 md:px-6 py-2.5 flex items-center justify-between gap-2 h-14 sm:h-16">

                    {{-- Left: Fixed Table Number --}}
                    <div class="flex items-center gap-2 min-w-0 p-1 -ml-1">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-emerald-700 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col text-left min-w-0">
                            <span class="text-[10px] font-semibold text-stone-400 leading-none">Nomor Meja</span>
                            <span class="text-xs sm:text-sm font-black text-stone-900 leading-tight mt-0.5">
                                Meja {{ session('table_number', '01') }}
                            </span>
                        </div>
                    </div>

                    {{-- Center: Cafe Brand --}}
                    <a href="{{ route('customer.catalog') }}"
                       class="text-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-lg px-2 py-1 max-w-[170px] sm:max-w-none truncate"
                       title="Kopi & Mie Nusantara">
                        <span class="text-xs sm:text-sm md:text-base font-black text-stone-900 tracking-tight whitespace-nowrap">Kopi &amp; Mie Nusantara</span>
                    </a>

                    {{-- Right: Staff Portal Link (44px min touch target) --}}
                    {{-- <a href="{{ route('login') }}"
                       title="Portal Masuk Staf & Kasir"
                       aria-label="Portal Masuk Staf dan Kasir"
                       class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-stone-100 hover:bg-stone-200 active:bg-stone-300 flex items-center justify-center transition-colors shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 -mr-1">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </a> --}}
                </div>
            </header>

            {{-- Main Scroll Area: Extra bottom padding on mobile/tablet so floating bars never obscure content --}}
            <main class="flex-1 pb-44 lg:pb-8" id="main-content">
                @yield('customer-content')
            </main>

            {{-- Fixed Bottom Navigation (Smartphones & Tablets) --}}
            <nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-stone-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]"
                 style="padding-bottom: env(safe-area-inset-bottom);"
                 aria-label="Navigasi Utama">
                <div class="max-w-md md:max-w-xl mx-auto grid grid-cols-4 px-2">

                    {{-- Tab 1: Menu --}}
                    <a href="{{ route('customer.catalog') }}"
                       class="flex flex-col items-center justify-center gap-1 py-2 min-h-[56px]
                              {{ request()->routeIs('customer.catalog') ? 'text-emerald-700 font-extrabold' : 'text-stone-400 hover:text-stone-700 font-semibold' }}
                              transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="{{ request()->routeIs('customer.catalog') ? 'currentColor' : 'none' }}"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('customer.catalog') ? '0' : '2' }}" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span class="text-[10px] leading-none">Menu</span>
                    </a>

                    {{-- Tab 2: Keranjang --}}
                    <button type="button"
                            onclick="document.getElementById('cart-modal').classList.remove('hidden')"
                            class="flex flex-col items-center justify-center gap-1 py-2 min-h-[56px] relative
                                   text-stone-400 hover:text-emerald-700 transition-colors cursor-pointer
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-xl">
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="absolute top-1.5 right-[calc(50%-18px)] min-w-[18px] h-[18px] px-1 rounded-full bg-emerald-700 text-white text-[10px] font-black flex items-center justify-center shadow-xs border-2 border-white">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                        </svg>
                        <span class="text-[10px] font-semibold leading-none">Keranjang</span>
                    </button>

                    {{-- Tab 3: Checkout / Status Pesanan --}}
                    <a href="{{ session('active_order_code') && empty(session('cart')) ? route('customer.track', ['orderCode' => session('active_order_code')]) : route('customer.checkout') }}"
                       class="flex flex-col items-center justify-center gap-1 py-2 min-h-[56px]
                              {{ request()->routeIs('customer.checkout') || request()->routeIs('customer.track') ? 'text-emerald-700 font-extrabold' : 'text-stone-400 hover:text-stone-700 font-semibold' }}
                              transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        <span class="text-[10px] leading-none">{{ session('active_order_code') && empty(session('cart')) ? 'Status Order' : 'Checkout' }}</span>
                    </a>

                    {{-- Tab 4: Staf --}}
                    <a href="{{ route('login') }}"
                       class="flex flex-col items-center justify-center gap-1 py-2 min-h-[56px]
                              text-stone-400 hover:text-stone-700 font-semibold transition-colors
                              focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <span class="text-[10px] leading-none">Staf</span>
                    </a>

                </div>
            </nav>
        </div>

        @if($hasSidebar)
            {{-- RIGHT PANE: Cart sidebar for desktop / laptop only --}}
            <aside class="hidden lg:flex flex-col sticky top-0 h-screen border-l border-stone-100 bg-stone-50/60 overflow-hidden" aria-label="Ringkasan Keranjang Pesanan">
                @yield('cart-sidebar')
            </aside>
        @endif

    </div>
</div>

@endsection
