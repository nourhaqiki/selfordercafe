@extends('layouts.manager')

@section('title', 'Manajemen Meja & QR Code - Kopi & Mie Nusantara')

@section('manager-content')
<div class="space-y-6">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Manajemen Meja & Generator QR Code</h2>
            <p class="text-xs text-stone-500 mt-0.5">Binding nomor meja ke payload QR code otomatis dan ekspor siap cetak.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('manager.tables.printQRs') }}" target="_blank" class="min-h-[44px] px-4 py-2 rounded-xl bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold transition-colors inline-flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.367-2.155-.367-3.28 0-4.004 2.774-7.36 6.547-8.298M16.5 13.829c.24-1.056.367-2.155.367-3.28 0-4.004-2.774-7.36-6.547-8.298m0 0a9.02 9.02 0 010 16.596M12 21a9 9 0 01-9-9c0-1.874.57-3.616 1.547-5.06"/></svg>
                <span>Buka Kanvas Cetak QR Meja (Siap Cetak / PDF)</span>
            </a>
        </div>
    </div>

    <!-- Forms Grid: Add Single Table & Mass Generator -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Single Table Form -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Tambah Satu Meja</h3>

            <form action="{{ route('manager.tables.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="table_number" class="block text-xs font-bold text-stone-700 uppercase mb-1">Nomor Meja *</label>
                        <input 
                            type="text" 
                            name="table_number" 
                            id="table_number" 
                            required 
                            placeholder="Contoh: 21" 
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                    </div>

                    <div>
                        <label for="capacity" class="block text-xs font-bold text-stone-700 uppercase mb-1">Kapasitas Kursi</label>
                        <input 
                            type="number" 
                            name="capacity" 
                            id="capacity" 
                            value="4" 
                            min="1" 
                            max="20" 
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                    </div>
                </div>

                <p class="text-[11px] text-stone-500 leading-relaxed">
                    QR meja memakai tautan bertanda tangan agar nomor meja tidak dapat diubah pelanggan.
                </p>

                <button type="submit" class="w-full min-h-[44px] py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-colors">
                    Simpan Meja Satuan
                </button>
            </form>
        </div>

        <!-- Mass Table Generator Form (PRD 3.3 & 5.1) -->
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Generate Meja Massal</h3>
                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-900 text-[10px] font-bold">Cepat & Otomatis</span>
            </div>

            <form action="{{ route('manager.tables.massGenerate') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label for="from" class="block text-xs font-bold text-stone-700 uppercase mb-1">Dari Meja *</label>
                        <input 
                            type="number" 
                            name="from" 
                            id="from" 
                            value="1" 
                            min="1" 
                            max="100" 
                            required 
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                    </div>

                    <div>
                        <label for="to" class="block text-xs font-bold text-stone-700 uppercase mb-1">Sampai Meja *</label>
                        <input 
                            type="number" 
                            name="to" 
                            id="to" 
                            value="30" 
                            min="1" 
                            max="100" 
                            required 
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                    </div>

                    <div>
                        <label for="mass_capacity" class="block text-xs font-bold text-stone-700 uppercase mb-1">Kapasitas</label>
                        <input 
                            type="number" 
                            name="capacity" 
                            id="mass_capacity" 
                            value="4" 
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                    </div>
                </div>

                <p class="text-[11px] text-stone-500 leading-relaxed">
                    Meja yang sudah ada tidak akan diduplikasi. Meja baru akan otomatis diformat dua digit (01, 02, ..., 30).
                </p>

                <button type="submit" class="w-full min-h-[44px] py-2.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold transition-colors">
                    Generate Massal Sekarang
                </button>
            </form>
        </div>

    </div>

    <!-- Tables Roster Grid -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs p-5 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Daftar Meja Terdaftar ({{ $tables->count() }} Meja)</h3>
            <span class="text-xs text-stone-500">Klik tautan untuk uji scan pesanan meja</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-10 gap-3">
            @forelse($tables as $tbl)
                <div class="p-3 rounded-xl border border-stone-200 hover:border-emerald-700 bg-stone-50/50 hover:bg-white text-center transition-all group flex flex-col justify-between shadow-2xs">
                    <div>
                        <span class="text-[10px] text-stone-400 font-bold uppercase block">MEJA</span>
                        <span class="text-xl font-black text-stone-900 group-hover:text-emerald-700 transition-colors block">
                            {{ $tbl->table_number }}
                        </span>
                    </div>

                    <div class="mt-2 pt-2 border-t border-stone-200/60 flex flex-col gap-1">
                        <span class="text-[10px] text-stone-500">{{ $tbl->capacity }} Kursi</span>
                        <a href="{{ \App\Models\CafeTable::signedOrderUrl($tbl->table_number) }}" 
                           target="_blank" 
                           class="text-[10px] font-bold text-emerald-800 hover:underline">
                            Tes Order →
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-xs text-stone-400 col-span-full py-8 text-center">Belum ada data meja.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

