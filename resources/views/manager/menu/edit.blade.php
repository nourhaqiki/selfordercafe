@extends('layouts.manager')

@section('title', 'Edit Menu: ' . $menu->name . ' - Kopi & Mie Nusantara')

@section('manager-content')
<div class="max-w-2xl mx-auto space-y-4">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('manager.menu.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-stone-600 hover:text-stone-900">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke Daftar Menu</span>
        </a>
        <span class="text-xs text-stone-400">Edit Menu #{{ $menu->id }}</span>
    </div>

    <div class="p-6 bg-white rounded-2xl border border-stone-200 shadow-2xs">
        <h2 class="text-lg font-bold text-stone-900 pb-3 border-b border-stone-100">Ubah Data Menu</h2>

        <form action="{{ route('manager.menu.update', ['id' => $menu->id]) }}" method="POST" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase mb-1">Nama Menu *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $menu->name) }}" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
            </div>

            <!-- Category & Price -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-stone-700 uppercase mb-1">Kategori *</label>
                    <select 
                        name="category_id" 
                        id="category_id" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold text-stone-700 uppercase mb-1">Harga Jual (Rp) *</label>
                    <input 
                        type="number" 
                        name="price" 
                        id="price" 
                        value="{{ old('price', (int)$menu->price) }}" 
                        required 
                        min="0"
                        step="500" 
                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-stone-700 uppercase mb-1">Deskripsi Porsi & Rasa</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    class="w-full px-3.5 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 resize-none">{{ old('description', $menu->description) }}</textarea>
            </div>

            <!-- Image URL -->
            <div>
                <label for="image_url" class="block text-xs font-bold text-stone-700 uppercase mb-1">URL Gambar Produk</label>
                <input 
                    type="url" 
                    name="image_url" 
                    id="image_url" 
                    value="{{ old('image_url', $menu->image_url) }}" 
                    class="w-full px-3.5 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-emerald-700" />
            </div>

            <!-- Checkbox Options -->
            <div class="pt-2 border-t border-stone-100 space-y-2.5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_spicy_levels" value="1" {{ old('has_spicy_levels', $menu->has_spicy_levels) ? 'checked' : '' }} class="w-4 h-4 text-emerald-700 rounded">
                    <span class="text-xs font-semibold text-stone-800">Aktifkan Pilihan Level Pedas (Level 0 sampai Level 8)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_favorite" value="1" {{ old('is_favorite', $menu->is_favorite) ? 'checked' : '' }} class="w-4 h-4 text-emerald-700 rounded">
                    <span class="text-xs font-semibold text-stone-800">Tandai sebagai Menu Favorit (Tampil menonjol di katalog)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }} class="w-4 h-4 text-emerald-700 rounded">
                    <span class="text-xs font-semibold text-stone-800">Status Stok Tersedia (Dapat dipesan pelanggan)</span>
                </label>
            </div>

            <div class="pt-4 flex gap-3">
                <a href="{{ route('manager.menu.index') }}" class="min-h-[44px] px-4 py-2.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold">Batal</a>
                <button type="submit" class="flex-1 min-h-[44px] py-2.5 px-4 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold tracking-wide shadow-xs">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>
@endsection

