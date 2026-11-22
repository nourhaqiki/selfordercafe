@extends('layouts.manager')

@section('title', 'Manajemen Kategori - Kopi & Mie Nusantara')

@section('manager-content')
<div class="space-y-4">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Kategori Menu</h2>
            <p class="text-xs text-stone-500 mt-0.5">Pengelompokan menu pada navigasi pill pelanggan.</p>
        </div>
        <a href="{{ route('manager.menu.index') }}" class="min-h-[44px] px-3.5 py-2 rounded-xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200 text-xs font-bold inline-flex items-center gap-1">
            ← Kembali ke Katalog Menu
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        <!-- Form Add Category (4 cols) -->
        <div class="md:col-span-4 bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Tambah Kategori</h3>

            <form action="{{ route('manager.categories.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-stone-700 uppercase mb-1">Nama Kategori *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        required 
                        placeholder="Contoh: Aneka Dimsum" 
                        class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-stone-700 uppercase mb-1">Deskripsi Singkat</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="2" 
                        placeholder="Keterangan singkat kelompok menu..." 
                        class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 resize-none"></textarea>
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-bold text-stone-700 uppercase mb-1">Urutan Tampil</label>
                    <input 
                        type="number" 
                        name="sort_order" 
                        id="sort_order" 
                        value="0" 
                        class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                </div>

                <button type="submit" class="w-full min-h-[44px] py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-colors">
                    Simpan Kategori Baru
                </button>
            </form>
        </div>

        <!-- Categories List (8 cols) -->
        <div class="md:col-span-8 bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 border-b border-stone-200 uppercase tracking-wider font-bold">
                        <th class="py-3 px-4">Urutan</th>
                        <th class="py-3 px-4">Nama & Slug</th>
                        <th class="py-3 px-4">Jumlah Menu</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-3 px-4 font-bold text-stone-400">{{ $cat->sort_order }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-stone-900 block text-sm">{{ $cat->name }}</span>
                                <span class="text-[11px] text-stone-400">{{ $cat->slug }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-stone-700">
                                {{ $cat->menus_count }} Menu Aktif
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if($cat->menus_count === 0)
                                    <form action="{{ route('manager.categories.destroy', ['id' => $cat->id]) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-stone-400 italic">Ada menu terkait</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-stone-400">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

