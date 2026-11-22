@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-stone-100 flex flex-col">

    {{-- Kasir Header --}}
    <header class="sticky top-0 z-40 bg-stone-900 text-white border-b border-stone-800 shadow-sm">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-14 sm:h-16">

                {{-- Brand --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-700 flex items-center justify-center text-white font-black text-xs sm:text-sm shadow-xs">
                        POS
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-extrabold tracking-tight text-white leading-tight">Kopi &amp; Mie Nusantara</span>
                        <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider hidden sm:block">KONSOL KASIR</span>
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('cashier.scan') }}"
                       class="min-h-[36px] sm:min-h-[40px] px-3 py-1.5 rounded-xl bg-stone-800 hover:bg-stone-700 border border-stone-700 text-stone-200 text-xs font-bold inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M5 21H3a2 2 0 01-2-2v-2m7-4h4m-2-2v4"/></svg>
                        <span class="hidden xs:inline">Scan</span>
                    </a>

                    <a href="{{ route('cashier.order.create') }}"
                       class="min-h-[36px] sm:min-h-[40px] px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-black inline-flex items-center gap-1.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden xs:inline">Pesan Walk-in</span>
                    </a>

                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs font-bold text-white">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-emerald-400 font-semibold uppercase">Staf Kasir</span>
                    </div>

                    <a href="{{ route('customer.catalog') }}"
                       target="_blank"
                       class="min-h-[36px] sm:min-h-[40px] px-2.5 py-1.5 rounded-xl bg-stone-800 hover:bg-stone-700 border border-stone-700 text-stone-200 text-xs font-bold inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        <span class="hidden md:inline">Tampilan Meja</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            title="Keluar dari sesi kasir"
                            class="min-h-[36px] sm:min-h-[40px] px-2.5 py-1.5 rounded-xl bg-red-950/80 hover:bg-red-900 border border-red-800 text-red-200 text-xs font-bold inline-flex items-center gap-1.5 cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Kasir Body --}}
    <main class="flex-1 max-w-screen-2xl w-full mx-auto p-3 sm:p-4 lg:p-6">
        @yield('cashier-content')
    </main>
</div>
@endsection
