@extends('admin.layout')

@section('title', 'Tambah Kategori Baru')
@section('header_title', 'Tambah Kategori Menu Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-6 shadow-sm">
        
        <div class="flex items-center space-x-2 pb-4 mb-4 border-b border-[#33231c]">
            <a href="{{ route('admin.categories.index') }}" class="p-1.5 rounded-lg bg-[#261914] text-stone-300 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h3 class="font-serif-title font-bold text-base text-[#fffdfa]">Formulir Kategori Baru</h3>
                <p class="text-[10px] text-stone-400">Tambahkan kategori menu baru untuk ditampilkan di landing page & katalog pemesanan.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="p-3 bg-rose-950/80 border border-rose-600/40 text-rose-300 rounded-xl text-xs mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <!-- Name -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Nama Kategori *</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Cookies / Cake / Pastry"
                    required
                    class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
            </div>

            <!-- Slug & Sort Order -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Slug URL (Opsional)</label>
                    <input
                        type="text"
                        name="slug"
                        value="{{ old('slug') }}"
                        placeholder="Dikosongkan untuk otomatis dari nama"
                        class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none font-mono">
                    <p class="text-[10px] text-stone-400 mt-1">Contoh: cookies, artisan-cake</p>
                </div>
                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Urutan Tampil (Sort Order)</label>
                    <input
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                        class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    <p class="text-[10px] text-stone-400 mt-1">Angka lebih kecil tampil lebih awal (0, 1, 2, ...)</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Deskripsi Singkat Kategori</label>
                <textarea
                    name="description"
                    rows="3"
                    placeholder="Jelaskan keistimewaan kategori ini (tampil pada preview landing page)..."
                    class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">{{ old('description') }}</textarea>
            </div>

            <!-- Photo Upload -->
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Unggah Foto / Banner Kategori</label>
                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="w-full p-2 bg-[#261914] text-stone-300 rounded-xl border border-[#4a342b] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#c89e2b] file:text-[#140d0a] hover:file:brightness-105 cursor-pointer">
                <p class="text-[10px] text-stone-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 4MB. Foto ini akan tampil pada kartu "Kategori Favorit" di Beranda toko.</p>
            </div>

            <!-- Is Active -->
            <div class="pt-2">
                <label class="flex items-center space-x-2 text-stone-300 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-stone-700 text-[#c89e2b] focus:ring-[#c89e2b] bg-[#261914]">
                    <span class="font-semibold">Aktifkan Kategori (Tampilkan di Katalog & Menu Pemesanan)</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-[#33231c] flex items-center justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 rounded-xl border border-[#4a342b] text-stone-300 hover:bg-[#261914] transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold rounded-xl shadow hover:brightness-105 active:scale-95 transition">
                    Simpan Kategori Baru
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
