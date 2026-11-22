@extends('layouts.customer')

@section('title', 'Menu - Kopi & Mie Nusantara')

@section('customer-content')
<div class="px-3 sm:px-4 md:px-6 pt-3 pb-4 space-y-3.5 sm:space-y-4">

    {{-- Store Paused Warning Banner --}}
    @if($isOrderPaused)
        <div class="p-3.5 sm:p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 shadow-2xs" role="alert">
            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 mt-0.5" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h2 class="text-xs sm:text-sm font-bold text-amber-900">Pemesanan Dijeda Sementara</h2>
                <p class="mt-0.5 text-xs text-amber-800 leading-relaxed">Dapur sedang memproses pesanan massal. Anda tetap dapat memilih menu, tombol checkout dibuka kembali sesaat lagi.</p>
            </div>
        </div>
    @endif

    {{-- Active Order Banner (If customer has an ongoing order) --}}
    @if(isset($activeOrder) && $activeOrder)
        <div class="p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-300 flex items-center justify-between gap-3 shadow-2xs" role="status">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping shrink-0"></span>
                <div class="min-w-0">
                    <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider block leading-none">PESANAN ANDA SEDANG AKTIF</span>
                    <p class="text-xs sm:text-sm font-extrabold text-stone-900 truncate mt-0.5">{{ $activeOrder->order_code }} &bull; {{ $activeOrder->status_label }}</p>
                </div>
            </div>
            <a href="{{ route('customer.track', ['orderCode' => $activeOrder->order_code]) }}"
               class="min-h-[36px] px-3.5 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold inline-flex items-center gap-1 shrink-0 shadow-xs transition-colors">
                <span>Lihat Pesanan</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif

    {{-- Greeting & Active Table Banner --}}
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 rounded-2xl p-3.5 sm:p-4 flex items-center justify-between text-white shadow-sm">
        <div class="min-w-0 pr-2">
            <p class="text-emerald-200 text-xs font-semibold tracking-wide">Selamat datang!</p>
            <h1 class="text-sm sm:text-base md:text-lg font-black leading-tight truncate mt-0.5">Kopi &amp; Mie Nusantara</h1>
            <p class="text-[11px] sm:text-xs text-emerald-100/80 mt-0.5 hidden xs:block">Pesan langsung dari meja Anda, cepat &amp; praktis</p>
        </div>
        <div
            class="flex items-center gap-2 bg-white/15 rounded-xl px-3 py-2 min-h-[44px] shrink-0">
            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-left">
                <span class="block text-[10px] text-emerald-200 font-semibold leading-none">Meja</span>
                <span class="block text-sm sm:text-base font-black text-white leading-tight mt-0.5">{{ $tableNumber }}</span>
            </div>
        </div>
    </div>

    {{-- Search Input --}}
    <form action="{{ route('customer.catalog') }}" method="GET" role="search" class="relative">
        <label for="catalog-search" class="sr-only">Cari menu favorit</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-400" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </span>
            <input
                type="search"
                name="q"
                id="catalog-search"
                value="{{ $searchKeyword }}"
                placeholder="Cari mie pedas, kopi susu aren, dimsum..."
                autocomplete="off"
                class="w-full pl-11 pr-10 h-12 sm:h-13 bg-stone-100 hover:bg-stone-150/70 border-0 rounded-2xl text-xs sm:text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition-all shadow-2xs" />
            @if($searchKeyword !== '')
                <a href="{{ route('customer.catalog', ['category' => $activeCategorySlug]) }}"
                   class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-stone-400 hover:text-stone-700"
                   aria-label="Hapus kata kunci pencarian">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
        @if($activeCategorySlug !== 'all')
            <input type="hidden" name="category" value="{{ $activeCategorySlug }}">
        @endif
    </form>

    {{-- Category Pills - Smooth horizontal scrolling --}}
    <nav aria-label="Kategori Menu"
         class="-mx-3 sm:-mx-4 md:-mx-6 px-3 sm:px-4 md:px-6 overflow-x-auto no-scrollbar flex items-center gap-2 py-0.5"
         style="-webkit-overflow-scrolling: touch;">
        <a href="{{ route('customer.catalog', ['q' => $searchKeyword]) }}"
           class="shrink-0 min-h-[44px] px-4 sm:px-5 rounded-full text-xs font-bold transition-all inline-flex items-center justify-center gap-1.5 active:scale-95
                  {{ $activeCategorySlug === 'all'
                       ? 'bg-emerald-700 text-white shadow-xs'
                       : 'bg-white text-stone-700 border border-stone-200 hover:border-emerald-400 hover:text-emerald-700 active:bg-stone-50' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>Semua Menu</span>
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('customer.catalog', ['category' => $cat->slug, 'q' => $searchKeyword]) }}"
               class="shrink-0 min-h-[44px] px-4 sm:px-5 rounded-full text-xs font-bold transition-all inline-flex items-center justify-center whitespace-nowrap active:scale-95
                      {{ $activeCategorySlug === $cat->slug
                           ? 'bg-emerald-700 text-white shadow-xs'
                           : 'bg-white text-stone-700 border border-stone-200 hover:border-emerald-400 hover:text-emerald-700 active:bg-stone-50' }}">
                <span>{{ $cat->name }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Signature Hero Highlight Card --}}
    @if($signatureMenu && $activeCategorySlug === 'all' && $searchKeyword === '')
        <article class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-stone-900 shadow-md group cursor-pointer transition-transform active:scale-[0.99]"
                 onclick="openModifierModal({{ $signatureMenu->id }}, '{{ addslashes($signatureMenu->name) }}', {{ $signatureMenu->price }}, {{ $signatureMenu->has_spicy_levels ? 'true' : 'false' }})"
                 role="button"
                 tabindex="0"
                 aria-label="Pesan hidangan terpopuler {{ $signatureMenu->name }} seharga {{ $signatureMenu->formatted_price }}">
            <div class="relative w-full aspect-[16/8] sm:aspect-[16/7] md:aspect-[21/9] overflow-hidden">
                <img
                    src="{{ $signatureMenu->image_url }}"
                    alt="{{ $signatureMenu->name }}"
                    width="900" height="400"
                    loading="eager"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-900/40 to-transparent"></div>

                {{-- Badges --}}
                <div class="absolute top-3 inset-x-3 flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full bg-amber-400 text-stone-900 text-[11px] font-black shadow-sm flex items-center gap-1">
                        <svg class="w-3 h-3 fill-stone-900" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Rekomendasi Terpopuler
                    </span>
                    @if($signatureMenu->has_spicy_levels)
                        <span class="px-2.5 py-1 rounded-full bg-orange-600 text-white text-[10px] font-extrabold shadow-sm">
                            Pilihan Level 0 - 8
                        </span>
                    @endif
                </div>

                {{-- Content Overlay --}}
                <div class="absolute bottom-0 inset-x-0 p-3.5 sm:p-5 text-white">
                    <h2 class="text-base sm:text-lg md:text-xl font-black leading-snug">{{ $signatureMenu->name }}</h2>
                    <p class="text-xs text-stone-200 mt-0.5 line-clamp-1 max-w-xl">{{ $signatureMenu->description }}</p>
                    <div class="mt-2.5 flex items-center justify-between gap-3">
                        <span class="text-sm sm:text-base font-black text-emerald-300 tabular-nums">{{ $signatureMenu->formatted_price }}</span>
                        <span class="min-h-[40px] px-4 rounded-full bg-emerald-600 group-hover:bg-emerald-500 text-white text-xs font-black inline-flex items-center gap-1.5 shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>Pesan Menu Ini</span>
                        </span>
                    </div>
                </div>
            </div>
        </article>
    @endif

    {{-- Section Title & Counter --}}
    <div class="flex items-center justify-between pt-1">
        <h2 class="text-xs sm:text-sm font-black text-stone-900 uppercase tracking-wider">
            @if($searchKeyword !== '')
                Pencarian: "{{ $searchKeyword }}"
            @elseif($activeCategorySlug === 'all')
                Daftar Menu Pilihan
            @else
                {{ $categories->firstWhere('slug', $activeCategorySlug)?->name ?? 'Menu' }}
            @endif
        </h2>
        <span class="text-xs text-stone-400 font-bold tabular-nums">{{ $menus->count() }} item</span>
    </div>

    {{-- Responsive Menu Grid
         Mobile (phones): 2 columns
         Tablet portrait: 3 columns
         Desktop / wide screen: 3-4 columns
    --}}
    @if($menus->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-3.5 md:gap-4">
            @foreach($menus as $menu)
                <article class="flex flex-col bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs border border-stone-200/80 hover:border-emerald-300 hover:shadow-md transition-all duration-200 group">
                    {{-- Food Image: Perfect square aspect ratio --}}
                    <div class="relative w-full aspect-square bg-stone-100 overflow-hidden">
                        <img
                            src="{{ $menu->image_url }}"
                            alt="{{ $menu->name }}"
                            width="400" height="400"
                            loading="lazy"
                            decoding="async"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />

                        {{-- Spicy Badge --}}
                        @if($menu->has_spicy_levels)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-orange-600/95 text-white text-[9px] sm:text-[10px] font-black tracking-wide shadow-xs backdrop-blur-xs">
                                Level Pedas
                            </span>
                        @endif

                        {{-- Favorite / Rating Pill --}}
                        @if($menu->is_favorite)
                            <span class="absolute top-2 right-2 flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-amber-400 text-stone-900 text-[10px] font-black shadow-xs">
                                <svg class="w-3 h-3 fill-stone-900 shrink-0" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.8
                            </span>
                        @endif
                    </div>

                    {{-- Card Info Body --}}
                    <div class="p-3 sm:p-3.5 flex flex-col flex-1 justify-between gap-2">
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-stone-900 leading-snug line-clamp-2" title="{{ $menu->name }}">
                                {{ $menu->name }}
                            </h3>
                            @if($menu->description)
                                <p class="mt-0.5 text-[10px] sm:text-xs text-stone-400 line-clamp-1 leading-relaxed">
                                    {{ $menu->description }}
                                </p>
                            @endif
                        </div>

                        {{-- Price and Accessible 44px+ Add Button --}}
                        <div class="flex items-center justify-between gap-1 pt-1 border-t border-stone-100">
                            <span class="text-xs sm:text-sm md:text-base font-black text-stone-900 tabular-nums leading-none">
                                {{ $menu->formatted_price }}
                            </span>

                            <button
                                type="button"
                                onclick="openModifierModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, {{ $menu->has_spicy_levels ? 'true' : 'false' }})"
                                aria-label="Tambah {{ $menu->name }} ke pesanan meja"
                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white
                                       flex items-center justify-center shadow-xs cursor-pointer transition-all shrink-0
                                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-1">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center p-8 sm:p-12 text-center bg-white rounded-3xl border border-stone-200/80 my-4 shadow-2xs">
            <div class="w-14 h-14 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3" aria-hidden="true">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <h3 class="text-sm sm:text-base font-bold text-stone-900">Menu Tidak Ditemukan</h3>
            <p class="mt-1 text-xs text-stone-500 max-w-xs leading-relaxed">
                @if($searchKeyword !== '')
                    Tidak ada hidangan yang cocok dengan kata kunci "{{ $searchKeyword }}".
                @else
                    Belum ada menu yang tersedia di kategori ini.
                @endif
            </p>
            <a href="{{ route('customer.catalog') }}"
               class="mt-4 min-h-[44px] px-5 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold inline-flex items-center justify-center transition-colors shadow-xs">
                Tampilkan Semua Menu
            </a>
        </div>
    @endif

</div>

{{-- Floating Cart Summary Bar for Mobile & Tablet (Positions above bottom nav, respects safe areas) --}}
@if($cartCount > 0)
    <div class="lg:hidden fixed inset-x-0 z-30 pointer-events-none"
         style="bottom: calc(56px + env(safe-area-inset-bottom, 0px));">
        <div class="max-w-md md:max-w-xl mx-auto px-3 sm:px-4 pb-2 pt-1 pointer-events-auto">
            <div class="bg-stone-900 text-white rounded-2xl shadow-xl px-3.5 py-2.5 flex items-center justify-between gap-3 border border-stone-800">
                <button
                    type="button"
                    onclick="document.getElementById('cart-modal').classList.remove('hidden')"
                    class="flex items-center gap-2.5 text-left cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 rounded-xl p-1 min-h-[44px] active:scale-98 transition-transform">
                    <div class="relative w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 font-black">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-white text-emerald-900 text-[10px] font-black flex items-center justify-center border border-stone-900">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-stone-400 font-semibold uppercase tracking-wider leading-none">{{ $cartCount }} Hidangan</span>
                        <span class="block text-sm sm:text-base font-black text-white tabular-nums leading-tight mt-0.5">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>
                </button>

                <a href="{{ route('customer.checkout') }}"
                   class="min-h-[44px] px-5 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white text-xs sm:text-sm font-black
                          inline-flex items-center gap-1.5 cursor-pointer shadow-sm transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
                    <span>Checkout</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
@endif

{{-- Item Customization Modal (Bottom Sheet on Mobile, Centered on Tablet/Desktop) --}}
<div id="modifier-modal"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-stone-900/60 backdrop-blur-sm"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-menu-name">
    <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl max-h-[90dvh] overflow-y-auto">
        {{-- Handle bar for mobile gestures --}}
        <div class="flex justify-center pt-3 pb-1 sm:hidden">
            <div class="w-12 h-1.5 rounded-full bg-stone-300"></div>
        </div>

        <div class="px-4 sm:px-6 pb-6 pt-2 sm:pt-5 space-y-4">
            {{-- Modal Header --}}
            <div class="flex items-start justify-between gap-3 pb-3 border-b border-stone-100">
                <div class="flex-1 min-w-0 pr-2">
                    <h3 id="modal-menu-name" class="text-base sm:text-lg font-black text-stone-900 leading-snug">Pilihan Menu</h3>
                    <span id="modal-menu-price" class="text-sm sm:text-base font-black text-emerald-700 mt-0.5 block tabular-nums">Rp 0</span>
                </div>
                <button type="button"
                        onclick="closeModifierModal()"
                        class="w-10 h-10 rounded-full bg-stone-100 hover:bg-stone-200 active:bg-stone-300 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                        aria-label="Tutup kustomisasi menu">
                    <svg class="w-4 h-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="menu_id" id="modal-menu-id" value="">

                {{-- Spicy Level: High contrast JS-driven selected state --}}
                <div id="spicy-level-section" class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-black text-stone-900 uppercase tracking-wider">Tingkat Kepedasan</label>
                        <span class="text-[10px] text-stone-500 font-semibold">Cabai rawit segar</span>
                    </div>

                    <div class="grid grid-cols-4 gap-2" id="spicy-grid" role="radiogroup" aria-label="Tingkat Kepedasan">
                        @foreach([0, 1, 2, 3, 4, 6, 8] as $lvl)
                            <label class="spicy-label relative flex flex-col items-center justify-center min-h-[54px] p-2 rounded-2xl border-2 cursor-pointer transition-all select-none active:scale-95">
                                <input type="radio"
                                       name="spicy_level"
                                       value="{{ $lvl }}"
                                       {{ $lvl === 1 ? 'checked' : '' }}
                                       class="sr-only spicy-radio"
                                       onchange="updateSpicyUI()">
                                <span class="spicy-title text-xs font-black leading-tight">{{ $lvl === 0 ? 'Lv0' : 'Lvl ' . $lvl }}</span>
                                <span class="spicy-subtitle text-[9px] font-semibold leading-tight mt-0.5">
                                    {{ $lvl === 0 ? 'No Cabai' : ($lvl <= 2 ? 'Pedas Pas' : ($lvl <= 4 ? 'Ekstra' : 'Nusantara')) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Notes for Kitchen --}}
                <div class="space-y-1.5">
                    <label for="modal-notes" class="block text-xs font-black text-stone-900 uppercase tracking-wider">Catatan Khusus Dapur</label>
                    <textarea
                        name="notes"
                        id="modal-notes"
                        rows="2"
                        maxlength="200"
                        placeholder="Contoh: Kuah dipisah, jangan pakai daun bawang..."
                        class="w-full px-4 py-3 rounded-2xl border border-stone-200 text-xs sm:text-sm text-stone-900
                               focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-stone-50 resize-none
                               transition-all placeholder:text-stone-400"></textarea>

                    {{-- Quick Note Chips --}}
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-0.5" style="-webkit-overflow-scrolling: touch;">
                        <button type="button" onclick="appendNote('Tanpa daun bawang')"
                                class="shrink-0 min-h-[36px] px-3 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 text-[10px] font-semibold cursor-pointer transition-colors active:scale-95">
                            + Tanpa daun bawang
                        </button>
                        <button type="button" onclick="appendNote('Saus dipisah')"
                                class="shrink-0 min-h-[36px] px-3 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 text-[10px] font-semibold cursor-pointer transition-colors active:scale-95">
                            + Saus dipisah
                        </button>
                        <button type="button" onclick="appendNote('Es sedikit')"
                                class="shrink-0 min-h-[36px] px-3 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 text-[10px] font-semibold cursor-pointer transition-colors active:scale-95">
                            + Es sedikit
                        </button>
                    </div>
                </div>

                {{-- Quantity Stepper (44px min touch target) --}}
                <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                    <div>
                        <span class="text-xs font-bold text-stone-900 block">Jumlah Porsi</span>
                        <span id="modal-subtotal-preview" class="text-xs font-bold text-emerald-700 tabular-nums">Subtotal: Rp 0</span>
                    </div>
                    <div class="inline-flex items-center border-2 border-stone-200 rounded-2xl bg-stone-50 overflow-hidden shadow-2xs">
                        <button type="button" onclick="stepQty(-1)" aria-label="Kurangi satu porsi"
                                class="w-11 h-11 flex items-center justify-center text-stone-600 hover:text-stone-900 hover:bg-stone-200 active:bg-stone-300 cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                        </button>
                        <input type="number" name="quantity" id="modal-qty" value="1" min="1" max="50" readonly
                               class="w-11 text-center text-sm font-black text-stone-900 bg-transparent border-none focus:ring-0 p-0 tabular-nums" />
                        <button type="button" onclick="stepQty(1)" aria-label="Tambah satu porsi"
                                class="w-11 h-11 flex items-center justify-center text-emerald-700 hover:text-emerald-900 hover:bg-emerald-100 active:bg-emerald-200 cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Submit Button (56px touch target) --}}
                <button type="submit"
                        class="w-full h-14 rounded-2xl bg-emerald-700 hover:bg-emerald-800 active:scale-[0.99] text-white text-sm sm:text-base font-extrabold
                               tracking-wide transition-all cursor-pointer shadow-md flex items-center justify-center gap-2
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
                    <span>Tambah ke Pesanan Meja</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Cart Review Full Modal for Mobile/Tablet --}}
<div id="cart-modal"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-stone-900/60 backdrop-blur-sm"
     role="dialog"
     aria-modal="true"
     aria-labelledby="cart-modal-title">
    <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl max-h-[88dvh] flex flex-col">
        {{-- Handle bar --}}
        <div class="flex justify-center pt-3 pb-1 sm:hidden">
            <div class="w-12 h-1.5 rounded-full bg-stone-300"></div>
        </div>

        <div class="px-4 sm:px-6 pt-2 sm:pt-4 pb-3 flex items-center justify-between border-b border-stone-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <h3 id="cart-modal-title" class="text-sm sm:text-base font-black text-stone-900">Keranjang Pesanan</h3>
                    <span class="text-[10px] text-stone-500 font-semibold">Pesanan Meja {{ $tableNumber }}</span>
                </div>
            </div>
            <button type="button"
                    onclick="document.getElementById('cart-modal').classList.add('hidden')"
                    class="w-10 h-10 rounded-full bg-stone-100 hover:bg-stone-200 active:bg-stone-300 flex items-center justify-center transition-colors cursor-pointer"
                    aria-label="Tutup keranjang">
                <svg class="w-4 h-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Items List --}}
        <div class="flex-1 overflow-y-auto divide-y divide-stone-100 px-4 sm:px-6">
            @forelse($cart as $key => $item)
                <div class="py-3.5 flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-stone-900 leading-snug">{{ $item['name'] }}</h4>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <span class="text-xs font-black text-emerald-700 tabular-nums">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            @if(isset($item['spicy_level']) && $item['spicy_level'] > 0)
                                <span class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 text-[10px] font-bold">Level {{ $item['spicy_level'] }}</span>
                            @endif
                        </div>
                        @if(!empty($item['notes']))
                            <p class="text-[10px] sm:text-[11px] text-stone-400 mt-1 italic leading-tight">"{{ $item['notes'] }}"</p>
                        @endif
                    </div>

                    {{-- Quantity Modifiers --}}
                    <div class="inline-flex items-center border-2 border-stone-200 rounded-xl bg-stone-50 overflow-hidden shrink-0 shadow-2xs">
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                            <button type="submit" aria-label="Kurangi porsi {{ $item['name'] }}"
                                    class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center text-stone-600 hover:bg-stone-200 active:bg-stone-300 cursor-pointer transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                            </button>
                        </form>
                        <span class="w-8 text-center text-xs sm:text-sm font-black text-stone-900 tabular-nums">{{ $item['quantity'] }}</span>
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button type="submit" aria-label="Tambah porsi {{ $item['name'] }}"
                                    class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center text-emerald-700 hover:bg-emerald-100 active:bg-emerald-200 cursor-pointer transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-14 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-stone-600">Keranjang Masih Kosong</p>
                    <p class="text-xs text-stone-400 mt-1">Pilih menu dari katalog untuk menambahkan hidangan</p>
                </div>
            @endforelse
        </div>

        {{-- Modal Footer --}}
        @if($cartCount > 0)
            <div class="px-4 sm:px-6 pt-3 pb-5 border-t border-stone-100 space-y-3 bg-white">
                <div class="flex items-center justify-between">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold cursor-pointer transition-colors min-h-[36px] flex items-center">
                            Kosongkan Keranjang
                        </button>
                    </form>
                    <div class="text-right">
                        <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold">Total Tagihan</span>
                        <span class="text-base sm:text-lg font-black text-stone-900 block tabular-nums">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('customer.checkout') }}"
                   class="w-full h-14 rounded-2xl bg-emerald-700 hover:bg-emerald-800 active:scale-[0.99] text-white text-sm sm:text-base font-extrabold
                          flex items-center justify-center gap-2 cursor-pointer shadow-md transition-all
                          focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
                    <span>Lanjut ke Checkout Pembayaran</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Desktop / Laptop Right Sidebar: Sticky Cart Summary --}}
@section('cart-sidebar')
<div class="flex flex-col h-full bg-stone-50/70">
    {{-- Sidebar Top --}}
    <div class="px-5 py-4 border-b border-stone-200/80 bg-white">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <h2 class="text-sm font-black text-stone-900 leading-tight">Keranjang Pesanan</h2>
                <span class="text-xs text-stone-500 font-semibold">Meja {{ $tableNumber }}</span>
            </div>
        </div>
    </div>

    {{-- Items List --}}
    <div class="flex-1 overflow-y-auto divide-y divide-stone-200/60 px-5">
        @forelse($cart as $key => $item)
            <div class="py-3.5 flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-stone-900 leading-snug">{{ $item['name'] }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-black text-emerald-700 tabular-nums">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                        @if(isset($item['spicy_level']) && $item['spicy_level'] > 0)
                            <span class="px-1.5 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[9px] font-bold">Lvl {{ $item['spicy_level'] }}</span>
                        @endif
                    </div>
                    @if(!empty($item['notes']))
                        <p class="text-[10px] text-stone-400 mt-1 italic leading-tight">"{{ $item['notes'] }}"</p>
                    @endif
                </div>

                <div class="inline-flex items-center border border-stone-200 rounded-lg bg-white overflow-hidden shrink-0 shadow-2xs">
                    <form action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_key" value="{{ $key }}">
                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                        <button type="submit" aria-label="Kurangi" class="w-8 h-8 flex items-center justify-center text-stone-600 hover:bg-stone-100 cursor-pointer transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                        </button>
                    </form>
                    <span class="w-7 text-center text-xs font-black text-stone-900 tabular-nums">{{ $item['quantity'] }}</span>
                    <form action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_key" value="{{ $key }}">
                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                        <button type="submit" aria-label="Tambah" class="w-8 h-8 flex items-center justify-center text-emerald-700 hover:bg-emerald-50 cursor-pointer transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-12 h-12 rounded-full bg-stone-200/60 flex items-center justify-center text-stone-400 mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <p class="text-xs font-bold text-stone-500">Keranjang Masih Kosong</p>
                <p class="text-[11px] text-stone-400 mt-0.5 max-w-[170px] leading-relaxed">Klik tombol tambah pada menu di samping</p>
            </div>
        @endforelse
    </div>

    {{-- Sidebar Footer --}}
    @if($cartCount > 0)
        <div class="p-5 border-t border-stone-200/80 bg-white space-y-3">
            <div class="flex items-center justify-between">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold cursor-pointer transition-colors">Kosongkan</button>
                </form>
                <div class="text-right">
                    <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold">Total Tagihan</span>
                    <span class="text-base font-black text-stone-900 block tabular-nums">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                </div>
            </div>
            <a href="{{ route('customer.checkout') }}"
               class="w-full h-12 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:scale-[0.99] text-white text-sm font-extrabold
                      flex items-center justify-center gap-2 cursor-pointer shadow-sm transition-all
                      focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
                <span>Checkout Pesanan</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    'use strict';

    let currentPrice = 0;

    // ---- High-Contrast Spicy Level Selection ----
    function updateSpicyUI() {
        document.querySelectorAll('.spicy-label').forEach(function(label) {
            var radio = label.querySelector('.spicy-radio');
            var title = label.querySelector('.spicy-title');
            var subtitle = label.querySelector('.spicy-subtitle');
            if (!radio) return;

            if (radio.checked) {
                // High contrast chosen state: solid emerald green background, white bold text, elevated shadow
                label.style.borderColor = '#047857';
                label.style.backgroundColor = '#047857';
                label.style.transform = 'scale(1.05)';
                label.style.boxShadow = '0 6px 14px rgba(4, 120, 87, 0.45)';
                if (title) title.style.color = '#ffffff';
                if (subtitle) {
                    subtitle.style.color = '#d1fae5'; // emerald-100
                    subtitle.style.fontWeight = '700';
                }
            } else {
                // Clean inactive state
                label.style.borderColor = '#e7e5e4'; // stone-200
                label.style.backgroundColor = '#ffffff';
                label.style.transform = 'scale(1)';
                label.style.boxShadow = 'none';
                if (title) title.style.color = '#1c1917'; // stone-900
                if (subtitle) {
                    subtitle.style.color = '#78716c'; // stone-500
                    subtitle.style.fontWeight = '600';
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.spicy-label').forEach(function(label) {
            label.style.transition = 'all 0.18s cubic-bezier(0.4, 0, 0.2, 1)';
            label.addEventListener('click', function() {
                var radio = this.querySelector('.spicy-radio');
                if (radio) {
                    radio.checked = true;
                    updateSpicyUI();
                }
            });
        });
        updateSpicyUI();
    });

    // ---- Modifier Modal Handling ----
    function openModifierModal(id, name, price, hasSpicy) {
        document.getElementById('modal-menu-id').value = id;
        document.getElementById('modal-menu-name').textContent = name;
        currentPrice = Number(price);
        document.getElementById('modal-menu-price').textContent = 'Rp ' + currentPrice.toLocaleString('id-ID');
        document.getElementById('modal-notes').value = '';
        document.getElementById('modal-qty').value = 1;
        updateSubtotalPreview(1);

        var spicySection = document.getElementById('spicy-level-section');
        spicySection.classList.toggle('hidden', !hasSpicy);

        // Reset spicy to level 1 for spicy items, level 0 for non-spicy
        document.querySelectorAll('.spicy-radio').forEach(function(r) {
            r.checked = (parseInt(r.value) === (hasSpicy ? 1 : 0));
        });
        updateSpicyUI();

        document.getElementById('modifier-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModifierModal() {
        document.getElementById('modifier-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function stepQty(delta) {
        var input = document.getElementById('modal-qty');
        var val = Math.max(1, Math.min(50, (parseInt(input.value) || 1) + delta));
        input.value = val;
        updateSubtotalPreview(val);
    }

    function updateSubtotalPreview(qty) {
        document.getElementById('modal-subtotal-preview').textContent =
            'Subtotal: Rp ' + (currentPrice * qty).toLocaleString('id-ID');
    }

    function appendNote(text) {
        var ta = document.getElementById('modal-notes');
        ta.value = ta.value.trim() === '' ? text : ta.value + ', ' + text;
        ta.focus();
    }

    // ---- Modal Backdrop & Keyboard Dismissal ----
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModifierModal();
            document.getElementById('cart-modal')?.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    document.getElementById('cart-modal')?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
    document.getElementById('modifier-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModifierModal();
    });
</script>
@endpush

@endsection
