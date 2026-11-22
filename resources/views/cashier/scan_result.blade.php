@extends('layouts.cashier')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Konsol Kasir')

@section('cashier-content')
<div class="max-w-lg mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('cashier.scan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-900 min-h-[44px] p-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Scan Lagi
        </a>
        <h1 class="text-sm font-black text-stone-900 uppercase tracking-wider">Hasil Scan</h1>
    </div>

    {{-- Order Card --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="bg-stone-900 text-white p-4">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] text-emerald-400 font-black uppercase tracking-widest block leading-none">TIKET PESANAN</span>
                    <h2 class="text-lg font-black tracking-tight text-white mt-1 leading-tight">{{ $order->order_code }}</h2>
                </div>
                <div class="px-3.5 py-1.5 rounded-2xl bg-stone-800 border border-stone-700 text-right shrink-0">
                    <span class="text-[9px] text-stone-400 block uppercase font-bold tracking-wider leading-none">MEJA</span>
                    <span class="text-xl font-black text-emerald-400 leading-tight">{{ $order->table_number }}</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-stone-800 flex items-center justify-between text-xs text-stone-300">
                <span>{{ $order->customer_name ?? '-' }}</span>
                <span class="font-extrabold text-emerald-300 uppercase">{{ $order->payment_method_label }}</span>
            </div>
        </div>

        {{-- Status Badges --}}
        <div class="p-4 bg-stone-50 border-b border-stone-200 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-stone-700 uppercase tracking-wider">Status Pembayaran</span>
                @if(in_array($order->status, [\App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_PREPARING, \App\Models\Order::STATUS_COMPLETED]))
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">SUDAH DIBAYAR</span>
                @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-red-100 text-red-900 border border-red-300">DIBATALKAN</span>
                @else
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">BELUM DIBAYAR</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-stone-700 uppercase tracking-wider">Status Proses</span>
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold
                    @switch($order->status)
                        @case(\App\Models\Order::STATUS_PENDING_PAYMENT) bg-amber-100 text-amber-900 border border-amber-300 @break
                        @case(\App\Models\Order::STATUS_PAID) bg-blue-100 text-blue-900 border border-blue-300 @break
                        @case(\App\Models\Order::STATUS_PREPARING) bg-indigo-100 text-indigo-900 border border-indigo-300 @break
                        @case(\App\Models\Order::STATUS_COMPLETED) bg-emerald-100 text-emerald-900 border border-emerald-300 @break
                        @case(\App\Models\Order::STATUS_CANCELLED) bg-red-100 text-red-900 border border-red-300 @break
                    @endswitch
                ">{{ $order->status_label }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div class="p-4 space-y-2">
            <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Rincian Menu</h3>
            <div class="divide-y divide-dashed divide-stone-200 text-xs">
                @foreach($order->items as $item)
                    <div class="py-2.5 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-stone-900">{{ $item->quantity }}x</span>
                                <span class="font-bold text-stone-800">{{ $item->menu_name }}</span>
                            </div>
                            @if($item->spicy_level > 0)
                                <span class="inline-block px-2 py-0.5 rounded-full bg-orange-100 text-orange-900 text-[10px] font-bold mt-1">Pedas Lv.{{ $item->spicy_level }}</span>
                            @endif
                            @if($item->notes)
                                <p class="text-[11px] text-stone-500 italic mt-0.5">{{ $item->notes }}</p>
                            @endif
                        </div>
                        <span class="font-bold text-stone-900 tabular-nums shrink-0">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>
            <div class="pt-3 border-t-2 border-dashed border-stone-200 flex items-center justify-between">
                <span class="text-sm font-bold text-stone-600">Total</span>
                <span class="text-lg font-black text-stone-900 tabular-nums">{{ $order->formatted_total }}</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="p-4 bg-stone-50 border-t border-stone-200 space-y-2">
            @if($order->status === \App\Models\Order::STATUS_PENDING_PAYMENT)
                <form action="{{ route('cashier.approvePayment', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full min-h-[44px] py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-black cursor-pointer transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Setujui Pembayaran ({{ $order->formatted_total }})
                    </button>
                </form>
                <form action="{{ route('cashier.rejectOrder', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="w-full min-h-[44px] py-2.5 px-4 rounded-xl bg-white hover:bg-red-50 text-red-600 border border-red-200 text-xs font-bold cursor-pointer transition-colors flex items-center justify-center gap-1.5">
                        Tolak / Batalkan
                    </button>
                </form>
            @elseif($order->status === \App\Models\Order::STATUS_PAID)
                <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="PREPARING">
                    <button type="submit" class="w-full min-h-[44px] py-2.5 px-4 rounded-xl bg-blue-700 hover:bg-blue-600 text-white text-xs font-black cursor-pointer transition-colors">Mulai Masak</button>
                </form>
            @elseif($order->status === \App\Models\Order::STATUS_PREPARING)
                <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="COMPLETED">
                    <button type="submit" class="w-full min-h-[44px] py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-black cursor-pointer transition-colors">Selesai Disajikan</button>
                </form>
            @elseif($order->status === \App\Models\Order::STATUS_COMPLETED)
                <p class="text-center text-xs font-bold text-emerald-700 py-2">✓ Pesanan selesai</p>
            @elseif($order->status === \App\Models\Order::STATUS_CANCELLED)
                <p class="text-center text-xs font-bold text-red-600 py-2">✕ Pesanan dibatalkan</p>
            @endif
        </div>

    </div>

</div>
@endsection
