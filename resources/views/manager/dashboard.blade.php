@extends('layouts.manager')

@section('title', 'Manager Overview & Master Control - Kopi & Mie Nusantara')

@section('manager-content')
<div class="space-y-6">

    <!-- Store Traffic Emergency Switch (PRD 3.3 & 4.3) -->
    <div class="p-5 rounded-2xl {{ $isOrderPaused ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-400/40' : 'bg-white border-stone-200' }} border shadow-2xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-12 h-12 rounded-xl {{ $isOrderPaused ? 'bg-amber-500 text-white' : 'bg-emerald-100 text-emerald-800' }} flex items-center justify-center shrink-0">
                @if($isOrderPaused)
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-stone-900">Kontrol Traffic Dapur & Pemesanan Cafe</h2>
                    <span class="px-2 py-0.5 rounded text-xs font-bold {{ $isOrderPaused ? 'bg-amber-200 text-amber-900' : 'bg-emerald-100 text-emerald-900' }}">
                        {{ $isOrderPaused ? 'Status: DIJEDA (PAUSE ORDER)' : 'Status: BUKA NORMAL' }}
                    </span>
                </div>
                <p class="text-xs text-stone-600 mt-1 leading-relaxed max-w-2xl">
                    @if($isOrderPaused)
                        Pemesanan baru dari meja pelanggan saat ini sedang <strong>DIJEDA</strong>. Pelanggan akan melihat notifikasi dapur penuh di katalog menu mereka.
                    @else
                        Pemesanan meja berjalan normal. Jika antrean dapur membludak, aktifkan jeda sementara untuk menstabilkan durasi masak.
                    @endif
                </p>
            </div>
        </div>

        <form action="{{ route('manager.toggleTraffic') }}" method="POST" class="shrink-0">
            @csrf
            <button 
                type="submit" 
                class="min-h-[44px] px-5 py-2.5 rounded-xl text-xs font-extrabold tracking-wide transition-all cursor-pointer shadow-xs {{ $isOrderPaused ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-amber-600 hover:bg-amber-700 text-white' }}">
                {{ $isOrderPaused ? 'Buka Kembali Pemesanan (Resume)' : 'Jeda Pemesanan Masuk (Pause Order)' }}
            </button>
        </form>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Revenue Today -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Pendapatan Hari Ini</span>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
            </span>
            <span class="text-xs text-stone-400 mt-1 block">{{ $todayPaidOrdersCount }} transaksi berhasil</span>
        </div>

        <!-- Total Orders Today -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Tiket Masuk Hari Ini</span>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                {{ $todayOrdersCount }} Tiket
            </span>
            <span class="text-xs text-emerald-700 font-semibold mt-1 block">Volume transaksi aktif</span>
        </div>

        <!-- Menu Master Count -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Katalog Menu</span>
                <a href="{{ route('manager.menu.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">Kelola</a>
            </div>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                {{ $availableMenus }} / {{ $totalMenus }}
            </span>
            <span class="text-xs text-stone-400 mt-1 block">Menu aktif tersedia</span>
        </div>

        <!-- Tables Count -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Meja Terdaftar</span>
                <a href="{{ route('manager.tables.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">Kelola Meja</a>
            </div>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                {{ $totalTables }} Meja
            </span>
            <span class="text-xs text-stone-400 mt-1 block">Siap scan QR Code</span>
        </div>
    </div>

    <!-- Quick Operations Navigation -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <a href="{{ route('manager.menu.create') }}" class="p-4 bg-white hover:bg-stone-50/80 rounded-xl border border-stone-200 shadow-2xs flex items-center gap-3 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                +
            </div>
            <div>
                <span class="text-sm font-bold text-stone-900 block">Tambah Menu Baru</span>
                <span class="text-xs text-stone-500">Harga, varian pedas & stok</span>
            </div>
        </a>

        <a href="{{ route('manager.tables.printQRs') }}" target="_blank" class="p-4 bg-white hover:bg-stone-50/80 rounded-xl border border-stone-200 shadow-2xs flex items-center gap-3 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-stone-900 text-emerald-400 flex items-center justify-center font-bold">
                QR
            </div>
            <div>
                <span class="text-sm font-bold text-stone-900 block">Cetak QR Code Meja</span>
                <span class="text-xs text-stone-500">Kanvas siap cetak kartu meja</span>
            </div>
        </a>

        <a href="{{ route('manager.reports.index') }}" class="p-4 bg-white hover:bg-stone-50/80 rounded-xl border border-stone-200 shadow-2xs flex items-center gap-3 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold">
                Rp
            </div>
            <div>
                <span class="text-sm font-bold text-stone-900 block">Laporan Keuangan</span>
                <span class="text-xs text-stone-500">Rekap omzet harian & metode bayar</span>
            </div>
        </a>
    </div>

    <!-- Two-Column Content: Top Selling Dishes & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Top Selling Dishes (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-stone-200 p-5 shadow-2xs space-y-4">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">5 Menu Paling Laris</h3>
            <div class="divide-y divide-stone-100">
                @forelse($topItems as $idx => $item)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-stone-100 text-stone-800 font-bold text-[11px] flex items-center justify-center">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <span class="font-bold text-stone-900 block">{{ $item->menu_name }}</span>
                                <span class="text-[11px] text-stone-500">{{ $item->total_qty }} porsi terjual</span>
                            </div>
                        </div>
                        <span class="font-bold text-emerald-800">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-xs text-stone-400 py-4 text-center">Belum ada data penjualan tercatat.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-stone-200 p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Aktivitas Tiket Terkini</h3>
                <a href="{{ route('manager.cashier.monitor') }}" class="text-xs font-bold text-emerald-800 hover:underline">Pantau Kasir</a>
            </div>

            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-stone-400 border-b border-stone-100">
                            <th class="pb-2 font-bold uppercase">Tiket</th>
                            <th class="pb-2 font-bold uppercase">Meja</th>
                            <th class="pb-2 font-bold uppercase">Total</th>
                            <th class="pb-2 font-bold uppercase">Status</th>
                            <th class="pb-2 font-bold uppercase text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-stone-50/50">
                                <td class="py-2.5 font-bold text-stone-900">{{ $order->order_code }}</td>
                                <td class="py-2.5 font-bold text-emerald-800">Meja {{ $order->table_number }}</td>
                                <td class="py-2.5 font-extrabold text-stone-900">{{ $order->formatted_total }}</td>
                                <td class="py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $order->status === 'PENDING_PAYMENT' ? 'bg-amber-100 text-amber-900' : ($order->status === 'COMPLETED' ? 'bg-stone-100 text-stone-700' : 'bg-emerald-100 text-emerald-900') }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="py-2.5 text-right text-stone-400">{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

