@extends('layouts.customer')

@section('title', 'Status Pesanan ' . $order->order_code . ' - Kopi & Mie Nusantara')

@section('customer-content')
<div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 max-w-2xl mx-auto">
    
    {{-- Top Action / New Order Link --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.catalog') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-900 min-h-[44px] p-1">
            <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke Menu</span>
        </a>
        <a href="{{ route('customer.catalog') }}" class="text-xs font-bold text-emerald-800 hover:underline inline-flex items-center gap-1 min-h-[44px] p-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>Pesan Menu Lain</span>
        </a>
    </div>

    {{-- Bistro Ticket Motif Container --}}
    <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        
        {{-- Ticket Header --}}
        <div class="bg-stone-900 text-white p-4 sm:p-5">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] text-emerald-400 font-black uppercase tracking-widest block leading-none">TIKET PESANAN MEJA</span>
                    <h1 class="text-lg sm:text-xl font-black tracking-tight text-white mt-1 leading-tight truncate">{{ $order->order_code }}</h1>
                </div>
                <div class="px-3.5 py-1.5 rounded-2xl bg-stone-800 border border-stone-700 text-right shrink-0">
                    <span class="text-[9px] text-stone-400 block uppercase font-bold tracking-wider leading-none">NOMOR MEJA</span>
                    <span class="text-xl sm:text-2xl font-black text-emerald-400 leading-tight">{{ $order->table_number }}</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-stone-800 flex items-center justify-between text-xs text-stone-300">
                <span>{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                <span class="font-extrabold text-emerald-300 uppercase tracking-wide">{{ $order->payment_method_label }}</span>
            </div>
        </div>

        {{-- 4-Step Visual Progress Stepper --}}
        <div class="p-4 sm:p-5 bg-stone-50/80 border-b border-stone-200">
            <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider mb-3.5">Status Pemrosesan Dapur</h2>
            
            <div class="space-y-4">
                @php
                    $isPending = $order->status === \App\Models\Order::STATUS_PENDING_PAYMENT;
                    $isPaid = in_array($order->status, [\App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_PREPARING, \App\Models\Order::STATUS_COMPLETED]);
                    $isPreparing = in_array($order->status, [\App\Models\Order::STATUS_PREPARING, \App\Models\Order::STATUS_COMPLETED]);
                    $isCompleted = $order->status === \App\Models\Order::STATUS_COMPLETED;
                    $isCancelled = $order->status === \App\Models\Order::STATUS_CANCELLED;
                @endphp

                @if($isCancelled)
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 flex items-center gap-3">
                        <svg class="w-6 h-6 text-red-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <div>
                            <span class="text-xs sm:text-sm font-bold block">Pesanan Dibatalkan</span>
                            <span class="text-[11px] sm:text-xs text-red-700">{{ $order->notes ?? 'Verifikasi pembayaran tidak berhasil atau pesanan dibatalkan oleh kasir.' }}</span>
                        </div>
                    </div>
                @else
                    {{-- Step 1: Menunggu Pembayaran --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $isPaid ? 'bg-emerald-700 text-white' : ($isPending ? 'bg-amber-500 text-white ring-4 ring-amber-100 animate-pulse' : 'bg-stone-200 text-stone-500') }}">
                            @if($isPaid)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-xs font-black">1</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs sm:text-sm font-bold {{ $isPending ? 'text-amber-900' : 'text-stone-900' }}">1. Menunggu Pembayaran</span>
                                @if($isPending)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">Menunggu Kasir</span>
                                @else
                                    <span class="text-[10px] sm:text-xs text-emerald-700 font-extrabold">Terverifikasi</span>
                                @endif
                            </div>
                            <p class="text-[11px] sm:text-xs text-stone-500 mt-0.5 leading-relaxed">
                                @if($isPending)
                                    Selesaikan pembayaran via {{ $order->payment_method_label }}. Kasir akan menekan approve setelah dana masuk.
                                @else
                                    Pembayaran telah divalidasi oleh kasir pada {{ $order->paid_at?->format('H:i') }} WIB.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Step 2: Diteruskan ke Dapur --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $isPreparing ? 'bg-emerald-700 text-white' : ($order->status === \App\Models\Order::STATUS_PAID ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 animate-pulse' : 'bg-stone-200 text-stone-500') }}">
                            @if($isPreparing)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-xs font-black">2</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs sm:text-sm font-bold text-stone-900 block">2. Antrean Masak Dapur</span>
                            <p class="text-[11px] sm:text-xs text-stone-500 mt-0.5 leading-relaxed">
                                Tiket pesanan otomatis dikirim ke display dapur cafe setelah pembayaran terkonfirmasi.
                            </p>
                        </div>
                    </div>

                    {{-- Step 3: Sedang Dimasak --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $isCompleted ? 'bg-emerald-700 text-white' : ($order->status === \App\Models\Order::STATUS_PREPARING ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-stone-200 text-stone-500') }}">
                            @if($isCompleted)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-xs font-black">3</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs sm:text-sm font-bold text-stone-900 block">3. Sedang Dimasak</span>
                            <p class="text-[11px] sm:text-xs text-stone-500 mt-0.5 leading-relaxed">
                                Koki sedang merebus mie segar dan menyiapkan minuman pesanan Anda.
                            </p>
                        </div>
                    </div>

                    {{-- Step 4: Selesai Disajikan --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $isCompleted ? 'bg-emerald-800 text-white ring-4 ring-emerald-100' : 'bg-stone-200 text-stone-500' }}">
                            <span class="text-xs font-black">4</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs sm:text-sm font-bold text-stone-900 block">4. Selesai Disajikan</span>
                            <p class="text-[11px] sm:text-xs text-stone-500 mt-0.5 leading-relaxed">
                                @if($isCompleted)
                                    Pesanan telah disajikan di Meja {{ $order->table_number }}. Selamat menikmati santapan Anda!
                                @else
                                    Pelayan akan mengantarkan hidangan panas langsung ke meja Anda.
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment Action Instruction (If Pending) --}}
        @if($isPending)
            <div class="p-4 sm:p-5 bg-amber-50/70 border-b border-amber-200/80 space-y-3.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-amber-900 uppercase tracking-wide">Instruksi Pembayaran</span>
                    <span class="text-sm sm:text-base font-black text-amber-900 tabular-nums">{{ $order->formatted_total }}</span>
                </div>

                @if($order->payment_method === 'qris')
                    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl border border-amber-200 text-center">
                        <div class="w-44 h-44 sm:w-48 sm:h-48 bg-stone-900 p-2.5 rounded-2xl flex items-center justify-center relative shadow-xs">
                            <svg viewBox="0 0 100 100" class="w-full h-full text-white" fill="currentColor">
                                <rect x="5" y="5" width="25" height="25" fill="none" stroke="white" stroke-width="4"/>
                                <rect x="11" y="11" width="13" height="13" fill="white"/>
                                <rect x="70" y="5" width="25" height="25" fill="none" stroke="white" stroke-width="4"/>
                                <rect x="76" y="11" width="13" height="13" fill="white"/>
                                <rect x="5" y="70" width="25" height="25" fill="none" stroke="white" stroke-width="4"/>
                                <rect x="11" y="76" width="13" height="13" fill="white"/>
                                <rect x="36" y="10" width="10" height="10"/>
                                <rect x="52" y="15" width="8" height="18"/>
                                <rect x="36" y="36" width="28" height="28" fill="white"/>
                                <rect x="42" y="42" width="16" height="16" fill="black"/>
                                <rect x="70" y="40" width="10" height="25"/>
                                <rect x="10" y="45" width="15" height="10"/>
                                <rect x="40" y="75" width="20" height="15"/>
                                <rect x="75" y="75" width="15" height="15"/>
                            </svg>
                            <span class="absolute inset-x-0 bottom-1.5 text-[8px] font-bold text-center text-emerald-400 uppercase tracking-wider">QRIS STANDAR DIGITAL</span>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-stone-900 mt-3">Pindai dengan GoPay / OVO / Dana / BCA</span>
                        <span class="text-[11px] sm:text-xs text-stone-500 tabular-nums">Nominal Tagihan: {{ $order->formatted_total }}</span>
                    </div>
                @elseif($order->payment_method === 'cash')
                    <div class="p-3.5 bg-white rounded-2xl border border-amber-200 text-xs sm:text-sm text-stone-700 leading-relaxed">
                        Silakan menuju ke meja kasir dan sebutkan <strong>Meja {{ $order->table_number }}</strong> atau kode tiket <strong>{{ $order->order_code }}</strong> untuk membayar secara tunai.
                    </div>
                @else
                    <div class="p-3.5 bg-white rounded-2xl border border-amber-200 text-xs sm:text-sm text-stone-700 leading-relaxed">
                        Transfer ke rekening BCA Cafe: <strong>8920-1284-9900</strong> a/n Kopi &amp; Mie Nusantara sebesar <strong>{{ $order->formatted_total }}</strong>.
                    </div>
                @endif

                <form action="{{ route('customer.simulatePay', ['orderCode' => $order->order_code]) }}" method="POST">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full min-h-[48px] py-3 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white text-xs sm:text-sm font-bold cursor-pointer transition-colors flex items-center justify-center gap-2 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Saya Sudah Menyelesaikan Pembayaran</span>
                    </button>
                </form>

                <form action="{{ route('customer.cancelOrder', ['orderCode' => $order->order_code]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full min-h-[44px] py-2.5 px-4 rounded-xl bg-white hover:bg-red-50 text-red-600 hover:text-red-700 border border-red-200 text-xs sm:text-sm font-bold cursor-pointer transition-colors flex items-center justify-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Batal Pemesanan</span>
                    </button>
                </form>
            </div>
        @endif

        {{-- Order Items Breakdown --}}
        <div class="p-4 sm:p-5 space-y-3">
            <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider">Rincian Hidangan</h2>

            <div class="divide-y divide-dashed divide-stone-200 text-xs sm:text-sm">
                @foreach($order->items as $item)
                    <div class="py-2.5 sm:py-3 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-stone-900">{{ $item->quantity }}x</span>
                                <span class="font-bold text-stone-800">{{ $item->menu_name }}</span>
                            </div>
                            @if($item->spicy_level > 0)
                                <span class="inline-block px-2 py-0.5 rounded-full bg-orange-100 text-orange-900 text-[10px] font-bold mt-1">
                                    Pedas: Level {{ $item->spicy_level }}
                                </span>
                            @endif
                            @if($item->notes)
                                <p class="text-[11px] text-stone-500 italic mt-0.5">{{ $item->notes }}</p>
                            @endif
                        </div>
                        <span class="font-bold text-stone-900 tabular-nums shrink-0">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-3.5 border-t-2 border-dashed border-stone-200 flex items-center justify-between">
                <span class="text-xs sm:text-sm font-bold text-stone-600">Total Tagihan</span>
                <span class="text-base sm:text-lg font-black text-stone-900 tabular-nums">{{ $order->formatted_total }}</span>
            </div>
        </div>

        {{-- Receipt / Struk with QR Barcode --}}
        <div class="p-4 sm:p-5 bg-stone-50 border-t border-stone-200">
            <h2 class="text-xs font-black text-stone-900 uppercase tracking-wider mb-3">Struk Digital</h2>

            <div class="bg-white rounded-2xl border border-stone-200 p-4 text-center space-y-3">
                {{-- QR Code --}}
                <div id="receipt-qrcode" class="inline-flex items-center justify-center mx-auto"></div>

                <div class="text-[10px] text-stone-500 font-bold tabular-nums">{{ $order->order_code }}</div>

                {{-- Payment Status Badge --}}
                @if(in_array($order->status, [\App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_PREPARING, \App\Models\Order::STATUS_COMPLETED]))
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">SUDAH DIBAYAR</span>
                @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-red-100 text-red-900 border border-red-300">DIBATALKAN</span>
                @else
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">BELUM DIBAYAR</span>
                @endif

                {{-- Cash payment: prominent instruction --}}
                @if($order->payment_method === 'cash' && $order->status === \App\Models\Order::STATUS_PENDING_PAYMENT)
                    <div class="p-3 rounded-xl bg-amber-50 border-2 border-amber-400 text-amber-900">
                        <span class="text-sm font-black block">📋 TUNJUKKAN KE KASIR</span>
                        <span class="text-[11px] font-bold block mt-1">Meja {{ $order->table_number }} · {{ $order->formatted_total }}</span>
                    </div>
                @endif

                {{-- Compact items summary --}}
                <div class="text-left text-[11px] text-stone-600 divide-y divide-dashed divide-stone-100">
                    @foreach($order->items as $item)
                        <div class="py-1 flex justify-between">
                            <span>{{ $item->quantity }}x {{ $item->menu_name }}</span>
                            <span class="tabular-nums font-bold">{{ $item->formatted_subtotal }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2 border-t border-stone-200 flex justify-between text-xs">
                    <span class="font-bold text-stone-600">Total</span>
                    <span class="font-black text-stone-900 tabular-nums">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Live Auto-Refresh Polling Bar --}}
    <div class="flex items-center justify-between text-xs text-stone-500 px-1 pt-1">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
            <span>Memperbarui status otomatis</span>
        </div>
        <button type="button" onclick="window.location.reload()" class="text-emerald-800 font-bold hover:underline cursor-pointer min-h-[36px] flex items-center">
            Muat Ulang
        </button>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    'use strict';
    // Generate QR Code for receipt
    (function() {
        const el = document.getElementById('receipt-qrcode');
        if (el) {
            new QRCode(el, {
                text: "{{ route('cashier.scan.lookup', ['code' => $order->order_code]) }}",
                width: 160,
                height: 160,
                colorDark: "#1c1917",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    })();

    const checkUrl = "{{ route('api.order.status', ['orderCode' => $order->order_code]) }}";
    const currentStatus = "{{ $order->status }}";

    const pollInterval = setInterval(() => {
        fetch(checkUrl)
            .then(res => res.json())
            .then(data => {
                if (data.status && data.status !== currentStatus) {
                    window.location.reload();
                }
            })
            .catch(() => {});
    }, 4000);
</script>
@endpush
@endsection
