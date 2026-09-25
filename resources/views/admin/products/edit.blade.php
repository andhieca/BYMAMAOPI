@extends('admin.layout')

@section('title', 'Edit Produk: ' . $product->name)
@section('header_title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-6 shadow-sm">
        
        <div class="flex items-center space-x-2 pb-4 mb-4 border-b border-[#33231c]">
            <a href="{{ route('admin.products.index') }}" class="p-1.5 rounded-lg bg-[#261914] text-stone-300 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h3 class="font-serif-title font-bold text-base text-[#fffdfa]">Edit Produk</h3>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <!-- Category -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Kategori Produk *</label>
                <select name="category_id" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Name -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
            </div>

            <!-- Price & Badge in 2 cols -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Harga (Rupiah) *</label>
                    <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" min="0" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Badge Highlight (Opsional)</label>
                    <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" placeholder="Contoh: Best Seller / Signature" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Deskripsi Lengkap *</label>
                <textarea name="description" rows="3" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Current Image & Photo Upload -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Foto Produk</label>
                @if($product->image_url)
                <div class="mb-2 flex items-center space-x-3">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover border border-[#382621]">
                    <span class="text-[10px] text-stone-400">Pilih file baru di bawah ini jika ingin mengganti gambar.</span>
                </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full p-2 bg-[#261914] text-stone-300 rounded-xl border border-[#4a342b] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#c89e2b] file:text-[#140d0a] hover:file:brightness-105">
            </div>

            <!-- Toggles -->
            <div class="flex items-center space-x-6 pt-2">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }} class="rounded bg-[#261914] border-[#4a342b] text-[#c89e2b] focus:ring-[#c89e2b]">
                    <span class="font-semibold text-stone-300">Produk Tersedia</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded bg-[#261914] border-[#4a342b] text-[#c89e2b] focus:ring-[#c89e2b]">
                    <span class="font-semibold text-stone-300">Tampilkan di Menu Unggulan</span>
                </label>
            </div>

            <div class="pt-4 border-t border-[#33231c] flex justify-end space-x-2">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-[#261914] hover:bg-[#33231c] text-stone-300 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold rounded-xl shadow hover:brightness-105 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
