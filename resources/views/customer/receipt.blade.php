<?php
/** @var \App\Models\Order $order */
?>
@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Struk Pesanan {{ $order->order_code }}</h2>
    <p>Meja: {{ $order->table_number }}</p>
    <p>Total: {{ number_format($order->total_amount, 0, ',', '.') }}</p>
    <p>Status: {{ $order->status_label }}</p>

    @php
        $barcodeData = (new \App\Services\BarcodeService())->generate(
            $order->order_code.'|'.$order->total_amount.'|'.$order->status
        );
    @endphp
    <div class="my-4">
        <img src="{{ $barcodeData }}" alt="Barcode" />
    </div>

    <a href="{{ route('customer.catalog') }}" class="btn btn-primary mt-4">Kembali ke Menu</a>
</div>
@endsection
