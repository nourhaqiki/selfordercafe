@extends('layouts.manager')

@section('title', 'Pantau Kasir - Manager')

@section('manager-content')
@php
    $tabs = [
        'all' => 'Semua',
        'PENDING_PAYMENT' => 'Menunggu Validasi',
        'PAID' => 'Terbayar',
        'PREPARING' => 'Sedang Dimasak',
        'COMPLETED' => 'Selesai',
    ];
    $statusClasses = [
        'PENDING_PAYMENT' => 'bg-amber-100 text-amber-900 border-amber-300',
        'PAID' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
        'PREPARING' => 'bg-blue-100 text-blue-900 border-blue-300',
        'COMPLETED' => 'bg-stone-100 text-stone-700 border-stone-300',
        'CANCELLED' => 'bg-red-100 text-red-900 border-red-300',
    ];
@endphp

<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-xl font-black text-stone-900">Pantau Kegiatan Kasir</h1>
            <p class="mt-1 text-sm text-stone-600">Antrean dan detail pesanan kasir diperbarui otomatis.</p>
        </div>
        <span class="text-xs font-semibold text-stone-500" role="status" aria-live="polite">
            Pembaruan otomatis setiap 15 detik
        </span>
    </div>

    <section class="grid grid-cols-2 gap-2 sm:gap-3 md:grid-cols-4" aria-label="Ringkasan kasir">
        <div class="rounded-xl border border-stone-200 bg-white p-3 sm:p-4">
            <p class="text-xs font-bold uppercase tracking-wide text-stone-500">Pendapatan Hari Ini</p>
            <p class="mt-1 text-lg font-black tabular-nums text-stone-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-3 sm:p-4">
            <p class="text-xs font-bold uppercase tracking-wide text-amber-900">Menunggu Validasi</p>
            <p class="mt-1 text-lg font-black tabular-nums text-amber-950">{{ $counts['pending'] }} pesanan</p>
        </div>
        <div class="rounded-xl border border-blue-200 bg-white p-3 sm:p-4">
            <p class="text-xs font-bold uppercase tracking-wide text-stone-500">Antrean Dapur</p>
            <p class="mt-1 text-lg font-black tabular-nums text-stone-900">{{ $counts['paid'] + $counts['preparing'] }} pesanan</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-3 sm:p-4">
            <p class="text-xs font-bold uppercase tracking-wide text-stone-500">Selesai Hari Ini</p>
            <p class="mt-1 text-lg font-black tabular-nums text-stone-900">{{ $counts['completed'] }} pesanan</p>
        </div>
    </section>

    <nav class="flex gap-1 overflow-x-auto rounded-xl border border-stone-200 bg-white p-1.5" aria-label="Filter status pesanan">
        @foreach($tabs as $status => $label)
            <a href="{{ route('manager.cashier.monitor', ['status' => $status]) }}"
               @if($statusFilter === $status) aria-current="page" @endif
               class="min-h-10 shrink-0 rounded-lg px-3 py-2 text-xs font-bold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 {{ $statusFilter === $status ? 'bg-stone-900 text-white' : 'text-stone-700 hover:bg-stone-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>

    <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-12 lg:gap-5">
        <section class="space-y-2 lg:col-span-5" aria-label="Antrean pesanan kasir">
            <h2 class="px-1 text-xs font-bold uppercase tracking-wide text-stone-500">
                Antrean Pesanan ({{ $orders->total() }})
            </h2>

            @forelse($orders as $order)
                @php
                    $isSelected = $selectedOrder && $selectedOrder->id === $order->id;
                    $statusClass = $statusClasses[$order->status] ?? 'bg-stone-100 text-stone-700 border-stone-300';
                @endphp
                <a href="{{ route('manager.cashier.monitor', ['status' => $statusFilter, 'selected' => $order->id]) }}"
                   class="block rounded-xl border p-3 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 {{ $isSelected ? 'border-emerald-700 bg-white ring-2 ring-emerald-600/20' : 'border-stone-200 bg-white hover:border-stone-400' }}">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-lg bg-stone-900 text-emerald-300">
                            <span class="text-[8px] font-bold uppercase">Meja</span>
                            <span class="text-lg font-black leading-tight">{{ $order->table_number }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="truncate text-sm font-extrabold text-stone-900">{{ $order->order_code }}</span>
                                <span class="shrink-0 text-sm font-black tabular-nums text-stone-900">{{ $order->formatted_total }}</span>
                            </div>
                            <div class="mt-1 flex flex-wrap items-center justify-between gap-2">
                                <span class="truncate text-xs text-stone-600">{{ $order->customer_name ?? 'Pelanggan Meja ' . $order->table_number }}</span>
                                <span class="rounded-md border px-2 py-0.5 text-[10px] font-bold {{ $statusClass }}">{{ $order->status_label }}</span>
                            </div>
                            <span class="mt-1 block text-[10px] text-stone-500">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-xl border border-stone-200 bg-white p-6 text-center">
                    <p class="text-sm font-bold text-stone-800">Tidak ada pesanan pada filter ini.</p>
                    <p class="mt-1 text-xs text-stone-600">Pesanan baru akan tampil otomatis.</p>
                </div>
            @endforelse

            <div class="pt-1">{{ $orders->links() }}</div>
        </section>

        <section class="order-first lg:order-last lg:col-span-7" aria-label="Detail pesanan terpilih">
            @if($selectedOrder)
                @php
                    $selectedStatusClass = $statusClasses[$selectedOrder->status] ?? 'bg-stone-100 text-stone-700 border-stone-300';
                @endphp
                <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
                    <div class="bg-stone-900 p-4 text-white sm:p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">Detail Pesanan Kasir</p>
                        <h2 class="mt-1 text-xl font-black">{{ $selectedOrder->order_code }}</h2>
                        <p class="mt-1 text-xs text-stone-300">{{ $selectedOrder->customer_name }} &bull; {{ $selectedOrder->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 bg-stone-50 p-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase text-stone-500">Meja {{ $selectedOrder->table_number }}</p>
                            <p class="mt-0.5 text-sm font-extrabold text-stone-900">{{ $selectedOrder->payment_method_label }}</p>
                        </div>
                        <span class="rounded-md border px-3 py-1 text-xs font-bold {{ $selectedStatusClass }}">{{ $selectedOrder->status_label }}</span>
                    </div>
                    <div class="space-y-3 p-4 sm:p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wide text-stone-700">Daftar Hidangan</h3>
                        <div class="divide-y divide-stone-200 rounded-lg border border-stone-200">
                            @foreach($selectedOrder->items as $item)
                                <div class="flex items-start justify-between gap-3 p-3 text-sm">
                                    <div class="min-w-0">
                                        <p class="font-bold text-stone-900">{{ $item->quantity }}x {{ $item->menu_name }}</p>
                                        @if($item->spicy_level > 0)
                                            <p class="mt-1 text-xs text-stone-600">Level pedas {{ $item->spicy_level }}</p>
                                        @endif
                                        @if($item->notes)
                                            <p class="mt-1 text-xs text-amber-900">Catatan: {{ $item->notes }}</p>
                                        @endif
                                    </div>
                                    <span class="shrink-0 font-bold tabular-nums text-stone-900">{{ $item->formatted_subtotal }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if($selectedOrder->notes)
                            <p class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-stone-700">
                                Catatan pesanan: {{ $selectedOrder->notes }}
                            </p>
                        @endif
                        <div class="flex items-center justify-between gap-3 rounded-lg bg-stone-100 p-3">
                            <span class="text-xs font-bold uppercase text-stone-600">Total Pesanan</span>
                            <span class="text-lg font-black tabular-nums text-stone-900">{{ $selectedOrder->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-stone-200 bg-white p-8 text-center">
                    <h2 class="font-bold text-stone-900">Belum ada pesanan untuk ditampilkan</h2>
                    <p class="mt-1 text-sm text-stone-600">Detail pesanan akan muncul di sini saat tersedia.</p>
                </div>
            @endif
        </section>
    </div>
</div>

<script>
    window.setInterval(() => window.location.reload(), 15000);
</script>
@endsection
