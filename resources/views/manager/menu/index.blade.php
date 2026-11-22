@extends('layouts.manager')

@section('title', 'Manajemen Menu - Kopi & Mie Nusantara')

@section('manager-content')
<div class="space-y-4">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Manajemen Katalog Menu</h2>
            <p class="text-xs text-stone-500 mt-0.5">Kelola item hidangan, harga jual, tingkat kepedasan, dan toggle stok langsung.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('manager.categories.index') }}" class="min-h-[44px] px-3.5 py-2 rounded-xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200 text-xs font-bold transition-colors inline-flex items-center gap-1.5 shadow-2xs">
                <span>Kelola Kategori</span>
            </a>
            <a href="{{ route('manager.menu.create') }}" class="min-h-[44px] px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-colors inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Menu Baru</span>
            </a>
        </div>
    </div>

    <!-- Search & Category Filter Filter Card -->
    <div class="p-3 bg-white rounded-xl border border-stone-200 shadow-2xs flex flex-col sm:flex-row gap-3">
        <form action="{{ route('manager.menu.index') }}" method="GET" class="flex-1 flex gap-2">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari nama menu..." 
                    class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-stone-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </span>
            </div>

            <select name="category" onchange="this.form.submit()" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 rounded-lg bg-stone-800 text-white text-xs font-semibold">Cari</button>
        </form>
    </div>

    <!-- Menu Items Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 border-b border-stone-200 uppercase tracking-wider font-bold">
                        <th class="py-3 px-4">Menu & Gambar</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Varian Pedas</th>
                        <th class="py-3 px-4 text-center">Status Stok</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-stone-50/50">
                            <!-- Image & Name -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="{{ $menu->image_url }}" 
                                        alt="{{ $menu->name }}" 
                                        class="w-12 h-12 rounded-lg object-cover bg-stone-100 border border-stone-200 shrink-0" />
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-stone-900 text-sm">{{ $menu->name }}</span>
                                            @if($menu->is_favorite)
                                                <span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-900 text-[10px] font-bold">Favorit</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-stone-500 line-clamp-1 max-w-xs mt-0.5">{{ $menu->description }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-md bg-stone-100 text-stone-700 font-semibold text-[11px]">
                                    {{ $menu->category?->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="py-3 px-4 font-bold text-stone-900 text-sm">
                                {{ $menu->formatted_price }}
                            </td>

                            <!-- Spicy Levels -->
                            <td class="py-3 px-4">
                                @if($menu->has_spicy_levels)
                                    <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-900 text-[11px] font-bold">
                                        Level 0-8
                                    </span>
                                @else
                                    <span class="text-stone-400 text-[11px]">Non-pedas</span>
                                @endif
                            </td>

                            <!-- Stock Toggle (Instant One-Click Action) -->
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('manager.menu.toggleStock', ['id' => $menu->id]) }}" method="POST">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        title="Klik untuk ubah status ketersediaan"
                                        class="px-3 py-1 rounded-full text-xs font-bold transition-colors cursor-pointer {{ $menu->is_available ? 'bg-emerald-100 text-emerald-900 border border-emerald-300 hover:bg-emerald-200' : 'bg-red-100 text-red-900 border border-red-300 hover:bg-red-200' }}">
                                        {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('manager.menu.edit', ['id' => $menu->id]) }}" class="px-2.5 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('manager.menu.destroy', ['id' => $menu->id]) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-stone-400">
                                Tidak ada menu ditemukan. Silakan tambahkan menu baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-stone-100">
            {{ $menus->links() }}
        </div>
    </div>

</div>
@endsection

