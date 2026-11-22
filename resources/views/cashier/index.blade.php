@extends('layouts.cashier')

@section('title', 'Kasir Operasional & Approval - Kopi & Mie Nusantara')

@section('cashier-content')
<div class="space-y-3 sm:space-y-4">

    {{-- Metrics Bar: 2 col mobile, 4 col desktop --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3">
        {{-- Revenue --}}
        <div class="p-3 sm:p-4 rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-between gap-2">
            <div class="min-w-0">
                <span class="text-[10px] sm:text-xs font-bold text-stone-500 uppercase tracking-wider block truncate">Pendapatan Hari Ini</span>
                <span class="text-base sm:text-xl font-black text-stone-900 tracking-tight tabular-nums">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-xs shadow-2xs shrink-0">Rp</div>
        </div>

        {{-- Pending --}}
        <div class="p-3 sm:p-4 rounded-2xl {{ $counts['pending'] > 0 ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-400/40' : 'bg-white border-stone-200' }} border shadow-2xs flex items-center justify-between gap-2">
            <div class="min-w-0">
                <span class="text-[10px] sm:text-xs font-bold {{ $counts['pending'] > 0 ? 'text-amber-900' : 'text-stone-500' }} uppercase tracking-wider block truncate">Menunggu Validasi</span>
                <span class="text-base sm:text-xl font-black {{ $counts['pending'] > 0 ? 'text-amber-900' : 'text-stone-900' }} tracking-tight tabular-nums">
                    {{ $counts['pending'] }} Tiket
                </span>
            </div>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl {{ $counts['pending'] > 0 ? 'bg-amber-500 text-white animate-bounce' : 'bg-stone-100 text-stone-600' }} flex items-center justify-center font-black text-sm shrink-0">!</div>
        </div>

        {{-- Kitchen --}}
        <div class="p-3 sm:p-4 rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-between gap-2">
            <div class="min-w-0">
                <span class="text-[10px] sm:text-xs font-bold text-stone-500 uppercase tracking-wider block truncate">Antrean Dapur</span>
                <span class="text-base sm:text-xl font-black text-stone-900 tracking-tight tabular-nums">{{ $counts['paid'] + $counts['preparing'] }} Masak</span>
            </div>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/></svg>
            </div>
        </div>

        {{-- Completed --}}
        <div class="p-3 sm:p-4 rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-between gap-2">
            <div class="min-w-0">
                <span class="text-[10px] sm:text-xs font-bold text-stone-500 uppercase tracking-wider block truncate">Selesai Hari Ini</span>
                <span class="text-base sm:text-xl font-black text-stone-900 tracking-tight tabular-nums">{{ $counts['completed'] }} Tiket</span>
            </div>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center font-black text-sm shrink-0">&#10003;</div>
        </div>
    </div>

    {{-- Filter Tabs - horizontal scrollable --}}
    <div class="flex items-center bg-white p-1.5 sm:p-2 rounded-2xl border border-stone-200 shadow-2xs overflow-x-auto no-scrollbar gap-1 sm:gap-1.5">
        <div class="flex items-center gap-1 sm:gap-1.5 shrink-0">
            <a href="{{ route('cashier.index', ['status' => 'PENDING_PAYMENT']) }}"
               class="px-2.5 sm:px-3.5 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1 sm:gap-1.5 whitespace-nowrap min-h-[36px] {{ $statusFilter === 'PENDING_PAYMENT' ? 'bg-amber-500 text-white shadow-xs' : 'text-stone-700 hover:bg-stone-100' }}">
                <span class="hidden sm:inline">Menunggu Validasi</span>
                <span class="sm:hidden">Pending</span>
                @if($counts['pending'] > 0)
                    <span class="px-1.5 rounded-full text-[10px] bg-white text-amber-900 font-black">{{ $counts['pending'] }}</span>
                @endif
            </a>

            <a href="{{ route('cashier.index', ['status' => 'PAID']) }}"
               class="px-2.5 sm:px-3.5 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1 sm:gap-1.5 whitespace-nowrap min-h-[36px] {{ $statusFilter === 'PAID' ? 'bg-emerald-700 text-white shadow-xs' : 'text-stone-700 hover:bg-stone-100' }}">
                <span class="hidden sm:inline">Terbayar (Siap Masak)</span>
                <span class="sm:hidden">Terbayar</span>
                @if($counts['paid'] > 0)
                    <span class="px-1.5 rounded-full text-[10px] bg-emerald-100 text-emerald-900 font-black">{{ $counts['paid'] }}</span>
                @endif
            </a>

            <a href="{{ route('cashier.index', ['status' => 'PREPARING']) }}"
               class="px-2.5 sm:px-3.5 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1 sm:gap-1.5 whitespace-nowrap min-h-[36px] {{ $statusFilter === 'PREPARING' ? 'bg-blue-600 text-white shadow-xs' : 'text-stone-700 hover:bg-stone-100' }}">
                <span class="hidden sm:inline">Sedang Dimasak</span>
                <span class="sm:hidden">Masak</span>
                @if($counts['preparing'] > 0)
                    <span class="px-1.5 rounded-full text-[10px] bg-blue-100 text-blue-900 font-black">{{ $counts['preparing'] }}</span>
                @endif
            </a>

            <a href="{{ route('cashier.index', ['status' => 'COMPLETED']) }}"
               class="px-2.5 sm:px-3.5 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1 sm:gap-1.5 whitespace-nowrap min-h-[36px] {{ $statusFilter === 'COMPLETED' ? 'bg-stone-800 text-white shadow-xs' : 'text-stone-700 hover:bg-stone-100' }}">
                <span>Selesai</span>
            </a>

            <a href="{{ route('cashier.index', ['status' => 'all']) }}"
               class="px-2.5 sm:px-3.5 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1 sm:gap-1.5 whitespace-nowrap min-h-[36px] {{ $statusFilter === 'all' ? 'bg-stone-800 text-white shadow-xs' : 'text-stone-700 hover:bg-stone-100' }}">
                <span>Semua</span>
            </a>
        </div>

        <div class="ml-auto shrink-0 flex items-center gap-1.5 sm:gap-2">
            <a href="{{ route('cashier.order.create') }}" class="text-xs font-black text-white bg-emerald-700 hover:bg-emerald-800 px-3 sm:px-3.5 py-2 rounded-xl inline-flex items-center gap-1.5 min-h-[36px] shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline">Pesanan Langsung</span>
                <span class="sm:hidden">Walk-in</span>
            </a>
            <button type="button" onclick="window.location.reload()" class="text-xs font-bold text-stone-600 hover:text-stone-900 px-2.5 sm:px-3.5 py-2 rounded-xl border border-stone-200 hover:bg-stone-50 inline-flex items-center gap-1.5 min-h-[36px]">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span class="hidden sm:inline">Refresh</span>
            </button>
        </div>
    </div>

    {{--
        LAYOUT STRATEGY:
        - Mobile/Tablet portrait (< lg): Order list full-width. Clicking a ticket opens a bottom sheet detail panel.
        - Desktop (>= lg): Side-by-side. Left 5 cols = list, Right 7 cols = sticky detail.
    --}}

    {{-- Mobile: Detail drawer shown when order is selected --}}
    @if($selectedOrder)
        <div class="lg:hidden" id="mobile-detail-panel">
            <div class="bg-white rounded-2xl border border-emerald-700 shadow-md overflow-hidden ring-2 ring-emerald-600/20">
                {{-- Header --}}
                <div class="p-4 bg-stone-900 text-white">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="text-[10px] text-emerald-400 font-extrabold uppercase tracking-widest block">DETAIL PESANAN TERPILIH</span>
                            <h3 class="text-xl font-black tracking-tight text-white mt-0.5">{{ $selectedOrder->order_code }}</h3>
                            <span class="text-xs text-stone-300 mt-0.5 block">{{ $selectedOrder->customer_name }} &bull; {{ $selectedOrder->created_at->format('d M, H:i') }}</span>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <div class="p-2.5 bg-stone-800 rounded-xl border border-stone-700 text-center min-w-[64px]">
                                <span class="text-[9px] text-stone-400 uppercase font-bold tracking-widest block">MEJA</span>
                                <span class="text-2xl font-black text-emerald-400 leading-none">{{ $selectedOrder->table_number }}</span>
                            </div>
                            <a href="{{ route('cashier.index', ['status' => $statusFilter]) }}" class="text-xs text-stone-400 hover:text-white underline">Tutup</a>
                        </div>
                    </div>
                </div>

                {{-- Status / Method --}}
                <div class="px-4 py-3 bg-stone-50 border-b border-stone-200 flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] text-stone-500 font-bold uppercase block">Pembayaran</span>
                        <span class="text-sm font-extrabold text-stone-900">{{ $selectedOrder->payment_method_label }}</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold {{ $selectedOrder->status === 'PENDING_PAYMENT' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-900 border border-emerald-300' }}">
                        <span class="w-2 h-2 rounded-full {{ $selectedOrder->status === 'PENDING_PAYMENT' ? 'bg-amber-600' : 'bg-emerald-700' }}"></span>
                        {{ $selectedOrder->status_label }}
                    </span>
                </div>

                {{-- Items --}}
                <div class="p-4 space-y-3">
                    <h4 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Daftar Hidangan Meja {{ $selectedOrder->table_number }}</h4>
                    <div class="divide-y divide-dashed divide-stone-200 border border-stone-200 rounded-xl overflow-hidden bg-stone-50/30">
                        @foreach($selectedOrder->items as $item)
                            <div class="p-3 flex items-start justify-between gap-3 text-sm bg-white">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="w-6 h-6 rounded-lg bg-stone-100 text-stone-900 font-black text-xs flex items-center justify-center shrink-0">{{ $item->quantity }}x</span>
                                        <span class="font-extrabold text-stone-900 truncate">{{ $item->menu_name }}</span>
                                        @if($item->spicy_level > 0)
                                            <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-900 text-xs font-bold">Level {{ $item->spicy_level }}</span>
                                        @endif
                                    </div>
                                    @if($item->notes)
                                        <p class="text-xs text-amber-900 bg-amber-50 px-2.5 py-1 rounded-md mt-1.5 inline-block font-medium border border-amber-200">{{ $item->notes }}</p>
                                    @endif
                                </div>
                                <span class="font-black text-stone-900 tabular-nums shrink-0">{{ $item->formatted_subtotal }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($selectedOrder->notes)
                        <div class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-200 text-xs text-stone-700">
                            <span class="font-bold text-amber-900 uppercase tracking-wider text-[11px] block">Catatan dari Pelanggan:</span>
                            <p class="mt-0.5 italic">"{{ $selectedOrder->notes }}"</p>
                        </div>
                    @endif

                    {{-- Grand Total --}}
                    <div class="p-4 rounded-xl bg-stone-100/80 border border-stone-300 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Total Tagihan</span>
                            <span class="text-xs text-stone-500">Cocokkan dengan mutasi/uang fisik</span>
                        </div>
                        <span class="text-3xl font-black text-stone-900 tracking-tight tabular-nums">{{ $selectedOrder->formatted_total }}</span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-2.5">
                        @if($selectedOrder->status === \App\Models\Order::STATUS_PENDING_PAYMENT)
                            <form action="{{ route('cashier.approvePayment', ['id' => $selectedOrder->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full min-h-[56px] py-4 px-6 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-base font-black tracking-wide transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>APPROVE PEMBAYARAN</span>
                                </button>
                            </form>
                            <form action="{{ route('cashier.rejectOrder', ['id' => $selectedOrder->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pesanan ini?')">
                                @csrf
                                <input type="hidden" name="reason" value="Pembayaran tidak valid atau dibatalkan di kasir">
                                <button type="submit" class="w-full min-h-[44px] py-2 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-800 text-xs font-bold border border-red-200 cursor-pointer transition-colors">
                                    Tolak / Batalkan Pesanan
                                </button>
                            </form>
                        @elseif($selectedOrder->status === \App\Models\Order::STATUS_PAID)
                            <form action="{{ route('cashier.updateStatus', ['id' => $selectedOrder->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="PREPARING">
                                <button type="submit" class="w-full min-h-[48px] py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold tracking-wide cursor-pointer shadow-xs flex items-center justify-center gap-2">
                                    Mulai Dimasak di Dapur (PREPARING)
                                </button>
                            </form>
                        @elseif($selectedOrder->status === \App\Models\Order::STATUS_PREPARING)
                            <form action="{{ route('cashier.updateStatus', ['id' => $selectedOrder->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="COMPLETED">
                                <button type="submit" class="w-full min-h-[48px] py-3 px-4 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-sm font-bold tracking-wide cursor-pointer shadow-xs flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Selesai Disajikan (COMPLETED)
                                </button>
                            </form>
                        @else
                            <div class="p-3.5 bg-stone-100 rounded-xl text-center text-xs text-stone-600 font-bold">
                                Pesanan selesai disajikan pada {{ $selectedOrder->completed_at?->format('H:i') }} WIB.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Two-Column for Desktop, Full-Width list for mobile --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-5 items-start">

        {{-- Order Queue List --}}
        <div class="lg:col-span-5 space-y-2">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-xs font-bold text-stone-500 uppercase tracking-wider">
                    Antrean Tiket ({{ $orders->total() }})
                </h2>
                <span class="text-[11px] text-stone-400 hidden sm:block">Ketuk tiket untuk proses</span>
            </div>

            @forelse($orders as $ord)
                @php
                    $isSelected = $selectedOrder && $selectedOrder->id === $ord->id;
                    $isPending = $ord->status === \App\Models\Order::STATUS_PENDING_PAYMENT;
                @endphp
                <a href="{{ route('cashier.index', ['status' => $statusFilter, 'selected' => $ord->id]) }}#mobile-detail-panel"
                   class="block p-3 sm:p-4 rounded-2xl border transition-all duration-150 {{ $isSelected ? 'bg-white border-emerald-700 shadow-md ring-2 ring-emerald-600/30' : ($isPending ? 'bg-amber-50/70 border-amber-300 hover:border-amber-400' : 'bg-white border-stone-200 hover:border-stone-300 shadow-2xs') }}">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex flex-col items-center justify-center shrink-0 {{ $isPending ? 'bg-amber-500 text-white' : 'bg-stone-900 text-emerald-400' }}">
                            <span class="text-[8px] uppercase tracking-widest font-bold leading-none">MEJA</span>
                            <span class="text-lg sm:text-xl leading-tight font-black">{{ $ord->table_number }}</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-extrabold text-stone-900 truncate">{{ $ord->order_code }}</h3>
                                <span class="text-sm font-black text-stone-900 tabular-nums shrink-0">{{ $ord->formatted_total }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2 mt-0.5">
                                <span class="text-xs text-stone-500 truncate">{{ $ord->customer_name ?? 'Pelanggan Meja ' . $ord->table_number }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold shrink-0 {{ $isPending ? 'bg-amber-200 text-amber-900' : ($ord->status === 'PAID' ? 'bg-emerald-100 text-emerald-900' : ($ord->status === 'PREPARING' ? 'bg-blue-100 text-blue-900' : 'bg-stone-100 text-stone-700')) }}">
                                    {{ $ord->status_label }}
                                </span>
                            </div>
                            <span class="text-[10px] text-stone-400 mt-0.5 block">{{ $ord->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-6 sm:p-8 bg-white rounded-2xl border border-stone-200 text-center">
                    <p class="text-sm font-bold text-stone-700">Tidak ada pesanan pada filter ini.</p>
                    <p class="text-xs text-stone-500 mt-1">Pesanan baru muncul otomatis setiap 5 detik.</p>
                </div>
            @endforelse

            <div class="pt-1">
                {{ $orders->links() }}
            </div>
        </div>

        {{-- Desktop-only: Sticky Detail Panel (Right, 7 cols) --}}
        <div class="hidden lg:block lg:col-span-7">
            @if($selectedOrder)
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden sticky top-20">
                    <div class="p-5 bg-stone-900 text-white">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="text-[10px] text-emerald-400 font-extrabold uppercase tracking-widest block">DETAIL VERIFIKASI KASIR</span>
                                <h3 class="text-2xl font-black tracking-tight text-white mt-0.5">{{ $selectedOrder->order_code }}</h3>
                                <span class="text-xs text-stone-300 mt-1 block">
                                    {{ $selectedOrder->customer_name }} &bull; {{ $selectedOrder->created_at->format('d M Y, H:i') }} WIB
                                </span>
                            </div>
                            <div class="p-3 bg-stone-800 rounded-xl border border-stone-700 text-center min-w-[85px]">
                                <span class="text-[9px] text-stone-400 uppercase font-bold tracking-widest block">NOMOR MEJA</span>
                                <span class="text-3xl font-black text-emerald-400 leading-none mt-0.5 block">{{ $selectedOrder->table_number }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-stone-50 border-b border-stone-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-stone-500 font-bold uppercase tracking-wider block">Metode Pembayaran</span>
                            <span class="text-sm font-extrabold text-stone-900">{{ $selectedOrder->payment_method_label }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold {{ $selectedOrder->status === 'PENDING_PAYMENT' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-900 border border-emerald-300' }}">
                            <span class="w-2 h-2 rounded-full {{ $selectedOrder->status === 'PENDING_PAYMENT' ? 'bg-amber-600' : 'bg-emerald-700' }}"></span>
                            {{ $selectedOrder->status_label }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <h4 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Daftar Hidangan Meja {{ $selectedOrder->table_number }}</h4>

                        <div class="divide-y divide-dashed divide-stone-200 border border-stone-200 rounded-xl overflow-hidden bg-stone-50/30">
                            @foreach($selectedOrder->items as $item)
                                <div class="p-3.5 flex items-start justify-between gap-3 text-sm bg-white hover:bg-stone-50/50">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg bg-stone-100 text-stone-900 font-black text-xs flex items-center justify-center shrink-0">{{ $item->quantity }}x</span>
                                            <span class="font-extrabold text-stone-900 truncate">{{ $item->menu_name }}</span>
                                            @if($item->spicy_level > 0)
                                                <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-900 text-xs font-bold">Level {{ $item->spicy_level }}</span>
                                            @endif
                                        </div>
                                        @if($item->notes)
                                            <p class="text-xs text-amber-900 bg-amber-50 px-2.5 py-1 rounded-md mt-1.5 inline-block font-medium border border-amber-200">
                                                Catatan: {{ $item->notes }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="font-black text-stone-900 tabular-nums shrink-0">{{ $item->formatted_subtotal }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($selectedOrder->notes)
                            <div class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-200 text-xs text-stone-700">
                                <span class="font-bold text-amber-900 uppercase tracking-wider text-[11px] block">Catatan Pelanggan:</span>
                                <p class="mt-0.5 italic">"{{ $selectedOrder->notes }}"</p>
                            </div>
                        @endif

                        <div class="p-4 rounded-xl bg-stone-100/80 border border-stone-300 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Total Tagihan Harus Divalidasi</span>
                                <span class="text-xs text-stone-500">Pastikan uang fisik / mutasi QRIS sesuai nominal</span>
                            </div>
                            <span class="text-3xl font-black text-stone-900 tracking-tight tabular-nums">{{ $selectedOrder->formatted_total }}</span>
                        </div>

                        <div class="pt-2 space-y-2.5">
                            @if($selectedOrder->status === \App\Models\Order::STATUS_PENDING_PAYMENT)
                                <form action="{{ route('cashier.approvePayment', ['id' => $selectedOrder->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full min-h-[56px] py-4 px-6 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-base font-black tracking-wide transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span>APPROVE PEMBAYARAN (VALIDASI &amp; KIRIM KE DAPUR)</span>
                                    </button>
                                </form>
                                <form action="{{ route('cashier.rejectOrder', ['id' => $selectedOrder->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pesanan ini?')">
                                    @csrf
                                    <input type="hidden" name="reason" value="Pembayaran tidak valid atau dibatalkan di kasir">
                                    <button type="submit" class="w-full min-h-[44px] py-2 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-800 text-xs font-bold border border-red-200 cursor-pointer transition-colors">
                                        Tolak / Batalkan Pesanan Meja Ini
                                    </button>
                                </form>
                            @elseif($selectedOrder->status === \App\Models\Order::STATUS_PAID)
                                <form action="{{ route('cashier.updateStatus', ['id' => $selectedOrder->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="PREPARING">
                                    <button type="submit" class="w-full min-h-[48px] py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold tracking-wide transition-colors cursor-pointer shadow-xs flex items-center justify-center gap-2">
                                        Mulai Dimasak di Dapur (Set ke PREPARING)
                                    </button>
                                </form>
                            @elseif($selectedOrder->status === \App\Models\Order::STATUS_PREPARING)
                                <form action="{{ route('cashier.updateStatus', ['id' => $selectedOrder->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="COMPLETED">
                                    <button type="submit" class="w-full min-h-[48px] py-3 px-4 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-sm font-bold tracking-wide transition-colors cursor-pointer shadow-xs flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span>Makanan Siap / Selesai Disajikan (COMPLETED)</span>
                                    </button>
                                </form>
                            @else
                                <div class="p-3.5 bg-stone-100 rounded-xl text-center text-xs text-stone-600 font-bold">
                                    Pesanan selesai disajikan pada {{ $selectedOrder->completed_at?->format('H:i') }} WIB.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center text-stone-500">
                    <p class="font-bold text-base text-stone-800">Pilih pesanan di sisi kiri</p>
                    <p class="text-xs mt-1">Klik tiket pesanan untuk melihat rincian dan memvalidasi pembayaran.</p>
                </div>
            @endif
        </div>

    </div>

</div>

@push('scripts')
<script>
    const pollUrl = "{{ route('cashier.pollPending') }}";
    let lastPendingCount = {{ $counts['pending'] }};

    setInterval(() => {
        fetch(pollUrl)
            .then(res => res.json())
            .then(data => {
                if (data.pending_count > lastPendingCount) {
                    lastPendingCount = data.pending_count;
                    window.location.reload();
                }
            })
            .catch(() => {});
    }, 5000);
</script>
@endpush
@endsection
