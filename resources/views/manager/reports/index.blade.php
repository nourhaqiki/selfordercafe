@extends('layouts.manager')

@section('title', 'Laporan Keuangan & Pendapatan - Kopi & Mie Nusantara')

@section('manager-content')
<div class="space-y-6">
    
    <!-- Top Action & Range Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Laporan Keuangan & Pendapatan</h2>
            <p class="text-xs text-stone-500 mt-0.5">Analisis omzet penjualan terbayar dan rincian transaksi tervalidasi.</p>
        </div>

        <!-- Range Filter Buttons -->
        <div class="flex items-center gap-1.5 bg-white p-1.5 rounded-xl border border-stone-200 shadow-2xs">
            <a href="{{ route('manager.reports.index', ['range' => 'today']) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $range === 'today' ? 'bg-emerald-800 text-white shadow-xs' : 'text-stone-600 hover:bg-stone-100' }}">
                Hari Ini
            </a>
            <a href="{{ route('manager.reports.index', ['range' => 'week']) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $range === 'week' ? 'bg-emerald-800 text-white shadow-xs' : 'text-stone-600 hover:bg-stone-100' }}">
                7 Hari Terakhir
            </a>
            <a href="{{ route('manager.reports.index', ['range' => 'month']) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $range === 'month' ? 'bg-emerald-800 text-white shadow-xs' : 'text-stone-600 hover:bg-stone-100' }}">
                30 Hari Terakhir
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Revenue -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Total Omzet Bersih</span>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </span>
            <span class="text-xs text-emerald-700 font-semibold mt-1 block">Status terbayar & tervalidasi</span>
        </div>

        <!-- Total Transactions -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Total Transaksi</span>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                {{ $totalTransactions }} Transaksi
            </span>
            <span class="text-xs text-stone-400 mt-1 block">Volume tiket meja cafe</span>
        </div>

        <!-- Average Ticket Size -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Rata-Rata Per Pesanan (AOV)</span>
            <span class="text-2xl font-black text-stone-900 tracking-tight mt-1 block">
                Rp {{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 0, ',', '.') : '0' }}
            </span>
            <span class="text-xs text-stone-400 mt-1 block">Rata-rata belanja per meja</span>
        </div>
    </div>

    <!-- Payment Methods Breakdown Cards -->
    <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-2xs space-y-4">
        <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Pecahan Berdasarkan Kanal Pembayaran</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            @forelse($paymentBreakdown as $pb)
                @php
                    $percentage = $totalRevenue > 0 ? round(($pb->amount / $totalRevenue) * 100, 1) : 0;
                @endphp
                <div class="p-4 rounded-xl border border-stone-100 bg-stone-50/70 space-y-1">
                    <span class="text-[11px] font-bold text-stone-500 uppercase block">
                        {{ strtoupper($pb->payment_method) }}
                    </span>
                    <span class="text-lg font-black text-stone-900 block">
                        Rp {{ number_format($pb->amount, 0, ',', '.') }}
                    </span>
                    <div class="flex items-center justify-between text-xs text-stone-500 pt-1 border-t border-stone-200/50">
                        <span>{{ $pb->count }} Transaksi</span>
                        <span class="font-bold text-emerald-800">{{ $percentage }}%</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-stone-400 col-span-full py-4 text-center">Belum ada data pembayaran pada periode ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Transactions Audit Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-stone-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Log Riwayat Transaksi</h3>
            <span class="text-xs text-stone-500">{{ $orders->total() }} Data Ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 border-b border-stone-200 uppercase tracking-wider font-bold">
                        <th class="py-3 px-4">Waktu & Tanggal</th>
                        <th class="py-3 px-4">Kode Tiket</th>
                        <th class="py-3 px-4">Nomor Meja</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Kanal Bayar</th>
                        <th class="py-3 px-4 text-right">Nominal Omzet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-3 px-4 text-stone-500">
                                {{ $ord->paid_at ? $ord->paid_at->format('d/m/Y H:i') : $ord->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 font-bold text-stone-900">{{ $ord->order_code }}</td>
                            <td class="py-3 px-4 font-extrabold text-emerald-800">Meja {{ $ord->table_number }}</td>
                            <td class="py-3 px-4 text-stone-700">{{ $ord->customer_name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-800 font-semibold uppercase text-[10px]">
                                    {{ $ord->payment_method }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-black text-stone-900 text-right text-sm">
                                {{ $ord->formatted_total }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-stone-400">
                                Tidak ada transaksi terbayar pada rentang waktu ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-stone-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection

