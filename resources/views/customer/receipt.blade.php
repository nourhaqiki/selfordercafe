@extends('layouts.customer')

@section('title', 'Struk Pesanan - ' . $order->order_code)

@section('customer-content')
<div class="max-w-md mx-auto py-6 px-4">
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-5 bg-stone-900 text-white text-center">
            <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-400">Struk Pesanan</p>
            <h1 class="text-xl font-black mt-1">{{ $order->order_code }}</h1>
        </div>

        <div class="p-5 space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-stone-600">Meja</span>
                <span class="font-bold text-stone-900">{{ $order->table_number }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-stone-600">Status</span>
                <span class="font-bold {{ in_array($order->status, ['PAID', 'PREPARING', 'COMPLETED']) ? 'text-emerald-700' : 'text-amber-700' }}">{{ $order->status_label }}</span>
            </div>

            <div class="divide-y divide-stone-200 border border-stone-200 rounded-lg">
                @foreach($order->items as $item)
                    <div class="flex justify-between p-3 text-sm">
                        <span>{{ $item->quantity }}x {{ $item->menu_name }}</span>
                        <span class="font-bold tabular-nums">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center bg-stone-100 rounded-lg p-3">
                <span class="text-xs font-bold uppercase text-stone-600">Total</span>
                <span class="text-lg font-black text-stone-900 tabular-nums">{{ $order->formatted_total }}</span>
            </div>

            @php
                $barcodeData = (new \App\Services\BarcodeService())->generate($order->order_code);
            @endphp
            <div class="flex justify-center py-2">
                <img src="{{ $barcodeData }}" alt="Barcode {{ $order->order_code }}" class="max-w-full" />
            </div>
        </div>

        <div class="p-4 border-t border-stone-200">
            <a href="{{ route('customer.catalog') }}" class="block w-full text-center min-h-[44px] py-3 rounded-xl bg-stone-900 text-white text-sm font-bold hover:bg-stone-800 transition-colors">
                Kembali ke Menu
            </a>
        </div>
    </div>
</div>
@endsection
