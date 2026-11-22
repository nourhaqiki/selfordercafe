@extends('layouts.customer')

@section('title', 'Checkout Pembayaran - Kopi & Mie Nusantara')

@section('customer-content')
<div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 max-w-5xl mx-auto">

    {{-- Top Back Link & Step Indicator --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.catalog') }}"
           class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-stone-600 hover:text-stone-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 rounded-xl p-2 -ml-2 min-h-[44px]">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke Menu</span>
        </a>
        <span class="text-xs font-extrabold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
            Langkah 2: Checkout
        </span>
    </div>

    {{-- Table Confirmation Banner --}}
    <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-stone-900 text-white shadow-xs flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-700 text-white font-black text-xl sm:text-2xl flex items-center justify-center shadow-xs shrink-0">
                {{ $tableNumber }}
            </div>
            <div>
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block leading-none">PESANAN TERIKAT PADA</span>
                <h1 class="text-base sm:text-lg font-black text-white mt-1 leading-tight">Meja Nomor {{ $tableNumber }}</h1>
            </div>
        </div>
    </div>

    {{-- Checkout Form --}}
    <form action="{{ route('customer.storeOrder') }}" method="POST">
        @csrf

        {{-- Single Column on Mobile / Two Columns on Desktop --}}
        <div class="flex flex-col lg:grid lg:grid-cols-12 lg:gap-8 gap-4">

            {{-- LEFT COLUMN: Identity, notes, payment methods (7 of 12 cols) --}}
            <div class="lg:col-span-7 space-y-4">

                {{-- Customer Info Card --}}
                <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-white border border-stone-200 shadow-2xs space-y-3.5">
                    <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider">Identitas &amp; Catatan Tambahan</h2>

                    <div>
                        <label for="customer_name" class="block text-xs font-bold text-stone-700 mb-1.5">Nama Pemesan (Opsional)</label>
                        <input
                            type="text"
                            name="customer_name"
                            id="customer_name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full h-12 px-4 bg-stone-50 border border-stone-200 rounded-2xl text-xs sm:text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all" />
                    </div>

                    <div>
                        <label for="order_notes" class="block text-xs font-bold text-stone-700 mb-1.5">Catatan Tambahan untuk Dapur (Opsional)</label>
                        <textarea
                            name="order_notes"
                            id="order_notes"
                            rows="2"
                            placeholder="Contoh: Minta sendok garpu lebih, sambal dipisah..."
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-2xl text-xs sm:text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white resize-none transition-all"></textarea>
                    </div>
                </div>

                {{-- Payment Method Selection Card --}}
                <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-white border border-stone-200 shadow-2xs space-y-3.5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider">Metode Pembayaran</h2>
                        <span class="text-[10px] text-amber-900 bg-amber-100 px-2.5 py-0.5 rounded-full font-bold border border-amber-300">Validasi Kasir</span>
                    </div>

                    <div class="grid grid-cols-1 gap-2.5" role="radiogroup" aria-label="Metode Pembayaran">
                        {{-- QRIS --}}
                        <label class="relative flex items-center justify-between p-3.5 sm:p-4 rounded-2xl border-2 border-stone-200 cursor-pointer hover:bg-stone-50 has-checked:border-emerald-700 has-checked:bg-emerald-50/70 has-checked:ring-2 has-checked:ring-emerald-700/20 transition-all select-none">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="qris" checked class="w-4 h-4 text-emerald-700 focus:ring-emerald-700">
                                <div>
                                    <span class="text-xs sm:text-sm font-bold text-stone-900 block">QRIS Dinamis Cafe</span>
                                    <span class="text-[11px] sm:text-xs text-stone-500">GoPay, OVO, DANA, BCA Mobile, ShopeePay</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-900 text-[10px] font-black shrink-0">Instan</span>
                        </label>

                        {{-- Tunai --}}
                        <label class="relative flex items-center justify-between p-3.5 sm:p-4 rounded-2xl border-2 border-stone-200 cursor-pointer hover:bg-stone-50 has-checked:border-emerald-700 has-checked:bg-emerald-50/70 has-checked:ring-2 has-checked:ring-emerald-700/20 transition-all select-none">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cash" class="w-4 h-4 text-emerald-700 focus:ring-emerald-700">
                                <div>
                                    <span class="text-xs sm:text-sm font-bold text-stone-900 block">Tunai di Kasir</span>
                                    <span class="text-[11px] sm:text-xs text-stone-500">Bayar langsung di meja kasir setelah order terkirim</span>
                                </div>
                            </div>
                            <span class="text-[11px] text-stone-400 font-semibold shrink-0">Uang Pas</span>
                        </label>

                        {{-- Transfer Bank --}}
                        <label class="relative flex items-center justify-between p-3.5 sm:p-4 rounded-2xl border-2 border-stone-200 cursor-pointer hover:bg-stone-50 has-checked:border-emerald-700 has-checked:bg-emerald-50/70 has-checked:ring-2 has-checked:ring-emerald-700/20 transition-all select-none">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="transfer" class="w-4 h-4 text-emerald-700 focus:ring-emerald-700">
                                <div>
                                    <span class="text-xs sm:text-sm font-bold text-stone-900 block">Transfer Rekening Bank</span>
                                    <span class="text-[11px] sm:text-xs text-stone-500">Rekening Resmi BCA Kopi &amp; Mie Nusantara</span>
                                </div>
                            </div>
                        </label>

                        {{-- Kartu Debit --}}
                        <label class="relative flex items-center justify-between p-3.5 sm:p-4 rounded-2xl border-2 border-stone-200 cursor-pointer hover:bg-stone-50 has-checked:border-emerald-700 has-checked:bg-emerald-50/70 has-checked:ring-2 has-checked:ring-emerald-700/20 transition-all select-none">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="debit" class="w-4 h-4 text-emerald-700 focus:ring-emerald-700">
                                <div>
                                    <span class="text-xs sm:text-sm font-bold text-stone-900 block">Kartu Debit / EDC Kasir</span>
                                    <span class="text-[11px] sm:text-xs text-stone-500">Gesek kartu di mesin EDC kasir</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: Sticky receipt & submit button (5 of 12 cols) --}}
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-20 space-y-4">

                    {{-- Bistro Receipt Card --}}
                    <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-white border border-stone-200 shadow-2xs space-y-3.5">
                        <div class="flex items-center justify-between pb-2.5 border-b border-stone-100">
                            <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider">Rincian Hidangan ({{ $cartCount }} Item)</h2>
                            <a href="{{ route('customer.catalog') }}" class="text-xs font-bold text-emerald-800 hover:underline min-h-[36px] flex items-center">
                                Tambah Menu
                            </a>
                        </div>

                        <div class="divide-y divide-dashed divide-stone-200">
                            @foreach($cart as $item)
                                <div class="py-3 flex items-start justify-between gap-3 text-xs sm:text-sm">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="font-black text-stone-900 tabular-nums">{{ $item['quantity'] }}x</span>
                                            <span class="font-bold text-stone-800">{{ $item['name'] }}</span>
                                        </div>
                                        @if(isset($item['spicy_level']) && $item['spicy_level'] > 0)
                                            <span class="inline-block px-2 py-0.5 rounded-full bg-orange-100 text-orange-900 text-[10px] font-bold mt-1">
                                                Pedas: Level {{ $item['spicy_level'] }}
                                            </span>
                                        @endif
                                        @if(!empty($item['notes']))
                                            <p class="text-[11px] text-stone-500 italic mt-0.5">"{{ $item['notes'] }}"</p>
                                        @endif
                                    </div>
                                    <span class="font-black text-stone-900 tabular-nums shrink-0">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-3.5 border-t-2 border-dashed border-stone-200 flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-stone-600">Total Pembayaran</span>
                            <span class="text-lg sm:text-xl font-black text-stone-900 tabular-nums">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Submit Action Button --}}
                    <div>
                        <button
                            type="submit"
                            class="w-full min-h-[56px] py-4 px-5 rounded-2xl bg-emerald-700 hover:bg-emerald-800 active:scale-[0.99] text-white text-sm sm:text-base font-extrabold tracking-wide transition-all cursor-pointer shadow-md flex items-center justify-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2">
                            <span>Konfirmasi &amp; Kirim Pesanan Meja</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <p class="mt-2 text-center text-[11px] sm:text-xs text-stone-500 leading-relaxed">
                            Hidangan mulai dimasak setelah pembayaran divalidasi oleh kasir cafe.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </form>

    {{-- Batal Pemesanan Form (Sebelum Checkout) --}}
    <div class="max-w-md mx-auto pt-2">
        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
            @csrf
            <button type="submit" class="w-full min-h-[44px] py-2.5 px-4 rounded-2xl bg-stone-100 hover:bg-red-50 hover:text-red-700 hover:border-red-200 text-stone-600 text-xs sm:text-sm font-bold border border-stone-200 cursor-pointer transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Batal Pemesanan (Kosongkan Pilihan)</span>
            </button>
        </form>
    </div>

</div>
@endsection
