@extends('layouts.cashier')

@section('title', 'Pesanan Langsung di Kasir (Walk-in) - Kopi & Mie Nusantara')

@section('cashier-content')
<div class="max-w-5xl mx-auto space-y-4">

    {{-- Top Back Link & Step Indicator --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('cashier.index') }}"
           class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-stone-600 hover:text-stone-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 rounded-xl p-2 -ml-2 min-h-[44px]">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke Konsol Kasir</span>
        </a>
        <span class="text-xs font-extrabold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
            Kasir POS: Pesanan Walk-in
        </span>
    </div>

    {{-- Banner Petunjuk --}}
    <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-stone-900 text-white shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block leading-none">ORDER LANGSUNG DI MEJA KASIR</span>
            <h1 class="text-base sm:text-lg font-black text-white mt-1 leading-tight">Pemesanan Walk-in &amp; Pemilihan Meja Kosong</h1>
            <p class="text-xs text-stone-300 mt-1">Pilih meja yang sedang kosong untuk diarahkan ke pelanggan, lalu input hidangan yang dipesan.</p>
        </div>
        <div class="px-3.5 py-2 rounded-2xl bg-stone-800 border border-stone-700 shrink-0 text-center">
            <span class="text-[9px] text-stone-400 uppercase font-bold tracking-wider block">MEJA KOSONG TERSEDIA</span>
            <span class="text-xl sm:text-2xl font-black text-emerald-400 leading-tight">{{ $availableTables->count() }} Meja</span>
        </div>
    </div>

    @if(session('error'))
        <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('cashier.order.store') }}" method="POST" id="cashier-order-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 items-start">

            {{-- LEFT: Info Meja & Pelanggan (Col 5/12) --}}
            <div class="lg:col-span-5 space-y-4">

                {{-- Pilih Meja Kosong Card --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-white border border-stone-200 shadow-2xs space-y-3.5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider">1. Pilih / Arahkan Meja</h2>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Rekomendasi Otomatis</span>
                    </div>

                    @if($availableTables->isNotEmpty())
                        <div class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-xl text-xs text-emerald-900">
                            Meja yang disarankan: <strong class="text-emerald-800">Meja {{ $recommendedTable }}</strong> (Status: KOSONG). Pelanggan dapat diarahkan langsung ke meja ini.
                        </div>
                    @else
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900">
                            Semua meja sedang terisi pesanan aktif. Pilih meja di bawah ini untuk konfirmasi penggunaan meja bersama / pergantian pelanggan.
                        </div>
                    @endif

                    <div>
                        <label for="table_number" class="block text-xs font-bold text-stone-700 mb-1.5">Nomor Meja Pelanggan</label>
                        <select name="table_number" id="table_number" required
                                class="w-full h-12 px-4 bg-stone-50 border border-stone-200 rounded-2xl text-xs sm:text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white font-bold">
                            @if($availableTables->isNotEmpty())
                                <optgroup label="-- Meja yang Sedang Kosong (Tersedia) --">
                                    @foreach($availableTables as $tbl)
                                        <option value="{{ $tbl->table_number }}" {{ $tbl->table_number == $recommendedTable ? 'selected' : '' }}>
                                            Meja {{ $tbl->table_number }} (Kapasitas {{ $tbl->capacity }} Kursi - KOSONG)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            <optgroup label="-- Semua Nomor Meja Cafe --">
                                @foreach($allTables as $tbl)
                                    @if(!$availableTables->contains('table_number', $tbl->table_number))
                                        <option value="{{ $tbl->table_number }}">
                                            Meja {{ $tbl->table_number }} (Kapasitas {{ $tbl->capacity }} Kursi - Sedang Digunakan)
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label for="customer_name" class="block text-xs font-bold text-stone-700 mb-1.5">Nama Pelanggan (Opsional)</label>
                        <input type="text" name="customer_name" id="customer_name" placeholder="Contoh: Bapak Hendra"
                               class="w-full h-11 px-4 bg-stone-50 border border-stone-200 rounded-2xl text-xs sm:text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1.5">Metode Pembayaran di Kasir</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="p-2.5 rounded-xl border-2 border-stone-200 cursor-pointer text-xs font-bold text-center flex items-center justify-center gap-1.5 has-checked:border-emerald-700 has-checked:bg-emerald-50">
                                <input type="radio" name="payment_method" value="cash" checked class="sr-only">
                                <span>Tunai</span>
                            </label>
                            <label class="p-2.5 rounded-xl border-2 border-stone-200 cursor-pointer text-xs font-bold text-center flex items-center justify-center gap-1.5 has-checked:border-emerald-700 has-checked:bg-emerald-50">
                                <input type="radio" name="payment_method" value="qris" class="sr-only">
                                <span>QRIS Kasir</span>
                            </label>
                            <label class="p-2.5 rounded-xl border-2 border-stone-200 cursor-pointer text-xs font-bold text-center flex items-center justify-center gap-1.5 has-checked:border-emerald-700 has-checked:bg-emerald-50">
                                <input type="radio" name="payment_method" value="debit" class="sr-only">
                                <span>Kartu Debit</span>
                            </label>
                            <label class="p-2.5 rounded-xl border-2 border-stone-200 cursor-pointer text-xs font-bold text-center flex items-center justify-center gap-1.5 has-checked:border-emerald-700 has-checked:bg-emerald-50">
                                <input type="radio" name="payment_method" value="transfer" class="sr-only">
                                <span>Transfer</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="order_notes" class="block text-xs font-bold text-stone-700 mb-1.5">Catatan Dapur (Opsional)</label>
                        <textarea name="order_notes" id="order_notes" rows="2" placeholder="Catatan pesanan walk-in..."
                                  class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white resize-none"></textarea>
                    </div>
                </div>

                {{-- Ringkasan Biaya & Tombol Submit --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-white border border-stone-200 shadow-2xs space-y-3.5">
                    <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Total Item Dipilih</span>
                        <span id="summary-item-count" class="text-sm font-black text-stone-900">0 Porsi</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-stone-700">Total Pembayaran</span>
                        <span id="summary-total-amount" class="text-2xl font-black text-emerald-800 tabular-nums">Rp 0</span>
                    </div>

                    <button type="submit"
                            class="w-full min-h-[52px] py-3.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:scale-[0.99] text-white text-sm font-black tracking-wide shadow-md flex items-center justify-center gap-2 cursor-pointer transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan &amp; Teruskan ke Dapur</span>
                    </button>
                    <a href="{{ route('cashier.index') }}"
                       class="w-full min-h-[40px] py-2 px-4 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold flex items-center justify-center transition-colors">
                        Batal &amp; Kembali ke Konsol Kasir
                    </a>
                </div>

            </div>

            {{-- RIGHT: Daftar Menu Pilihan Kasir (Col 7/12) --}}
            <div class="lg:col-span-7 space-y-4">
                <div class="p-4 sm:p-5 rounded-2xl bg-white border border-stone-200 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider">2. Pilih Menu Pesanan Pelanggan</h2>
                        <span class="text-xs text-stone-400 font-semibold">Gunakan tombol + untuk menambah</span>
                    </div>

                    @php $itemIndex = 0; @endphp
                    @foreach($categories as $category)
                        @if($category->activeMenus->isNotEmpty())
                            <div class="space-y-2 pt-2 border-t border-stone-100 first:border-0 first:pt-0">
                                <h3 class="text-xs font-black text-emerald-800 uppercase tracking-wider">{{ $category->name }}</h3>
                                <div class="divide-y divide-dashed divide-stone-200">
                                    @foreach($category->activeMenus as $menu)
                                        <div class="py-3 flex items-start justify-between gap-3 menu-row" data-price="{{ $menu->price }}">
                                            <input type="hidden" name="items[{{ $itemIndex }}][menu_id]" value="{{ $menu->id }}">

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs sm:text-sm font-bold text-stone-900">{{ $menu->name }}</span>
                                                    <span class="text-xs font-black text-emerald-700 tabular-nums">{{ $menu->formatted_price }}</span>
                                                </div>
                                                @if($menu->has_spicy_levels)
                                                    <div class="flex items-center gap-1.5 mt-1.5">
                                                        <span class="text-[10px] text-stone-500 font-bold">Level:</span>
                                                        <select name="items[{{ $itemIndex }}][spicy_level]" class="text-[11px] font-bold py-0.5 px-2 bg-stone-50 border border-stone-200 rounded-lg text-stone-800">
                                                            <option value="0">Level 0 (0)</option>
                                                            <option value="1" selected>Pedas Sedang (1)</option>
                                                            <option value="2">Pedas Pas (2)</option>
                                                            <option value="3">Ekstra Pedas (3)</option>
                                                            <option value="4">Level 4</option>
                                                            <option value="6">Level 6</option>
                                                            <option value="8">Level 8</option>
                                                        </select>
                                                    </div>
                                                @endif
                                                <input type="text" name="items[{{ $itemIndex }}][notes]" placeholder="Catatan hidangan (opsional)..."
                                                       class="w-full mt-1.5 px-3 py-1 bg-stone-50 border border-stone-200 rounded-xl text-[11px] text-stone-800 placeholder:text-stone-400 focus:outline-none focus:bg-white">
                                            </div>

                                            <div class="inline-flex items-center border border-stone-200 rounded-xl bg-stone-50 overflow-hidden shrink-0 shadow-2xs">
                                                <button type="button" onclick="adjustQty(this, -1)" class="w-8 h-8 flex items-center justify-center text-stone-600 hover:bg-stone-200 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                                </button>
                                                <input type="number" name="items[{{ $itemIndex }}][quantity]" value="0" min="0" max="50"
                                                       class="w-9 text-center text-xs font-black text-stone-900 bg-transparent border-none focus:ring-0 p-0 tabular-nums qty-input"
                                                       onchange="recalculateTotal()">
                                                <button type="button" onclick="adjustQty(this, 1)" class="w-8 h-8 flex items-center justify-center text-emerald-700 hover:bg-emerald-100 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        @php $itemIndex++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </form>

</div>

@push('scripts')
<script>
    'use strict';

    function adjustQty(btn, delta) {
        const input = btn.closest('.inline-flex').querySelector('.qty-input');
        if (input) {
            let current = parseInt(input.value) || 0;
            let next = Math.max(0, Math.min(50, current + delta));
            input.value = next;
            recalculateTotal();
        }
    }

    function recalculateTotal() {
        let totalCount = 0;
        let totalAmount = 0;

        document.querySelectorAll('.menu-row').forEach(row => {
            const price = parseFloat(row.dataset.price) || 0;
            const qtyInput = row.querySelector('.qty-input');
            const qty = parseInt(qtyInput?.value) || 0;

            if (qty > 0) {
                totalCount += qty;
                totalAmount += (price * qty);
                row.classList.add('bg-emerald-50/40');
            } else {
                row.classList.remove('bg-emerald-50/40');
            }
        });

        document.getElementById('summary-item-count').textContent = totalCount + ' Porsi';
        document.getElementById('summary-total-amount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', recalculateTotal);
</script>
@endpush
@endsection
