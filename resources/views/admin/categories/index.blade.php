@extends('admin.layout')

@section('title', 'Kategori Menu')
@section('header_title', 'Manajemen Kategori Menu')

@section('content')
<div class="space-y-5">

    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-wrap items-center gap-2 flex-1">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau deskripsi kategori..."
                class="text-xs p-2 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none flex-1 min-w-[200px]">

            <button type="submit" class="px-3.5 py-2 bg-[#33231c] hover:bg-[#4a342b] text-[#e2c159] text-xs font-semibold rounded-xl transition">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 bg-[#261914] text-stone-400 hover:text-white text-xs rounded-xl transition">
                Reset
            </a>
            @endif
        </form>

        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow hover:brightness-105 active:scale-95 transition flex items-center justify-center space-x-1.5 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Kategori Baru</span>
        </a>
    </div>

    <!-- Error Alert if Any -->
    @if(session('error'))
    <div class="p-3.5 bg-rose-950/80 border border-rose-600/40 text-rose-300 rounded-xl text-xs font-medium flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 flex-shrink-0 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">&times;</button>
    </div>
    @endif

    <!-- Categories Grid / Table -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-[10px] text-[#dac9a3] uppercase border-b border-[#33231c] bg-[#140d0a]/60">
                    <tr>
                        <th class="py-3 px-3.5">Banner / Foto</th>
                        <th class="py-3 px-3.5">Nama Kategori</th>
                        <th class="py-3 px-3.5">Slug URL</th>
                        <th class="py-3 px-3.5">Jumlah Produk</th>
                        <th class="py-3 px-3.5">Urutan</th>
                        <th class="py-3 px-3.5">Status</th>
                        <th class="py-3 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#33231c] text-stone-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-[#261914] transition">
                        <!-- Image -->
                        <td class="py-3 px-3.5">
                            <div class="w-14 h-10 rounded-xl overflow-hidden bg-[#261914] border border-[#382621]">
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                        </td>

                        <!-- Name & Description -->
                        <td class="py-3 px-3.5">
                            <span class="font-bold text-white block text-sm">{{ $category->name }}</span>
                            <span class="text-[10px] text-stone-400 line-clamp-1 max-w-[280px]">{{ $category->description ?? '-' }}</span>
                        </td>

                        <!-- Slug -->
                        <td class="py-3 px-3.5 font-mono text-[11px] text-[#dac9a3]">
                            {{ $category->slug }}
                        </td>

                        <!-- Products Count -->
                        <td class="py-3 px-3.5">
                            <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-[#261914] hover:bg-[#33231c] text-[#e2c159] border border-[#382621] text-[10px] font-semibold transition" title="Lihat produk di kategori ini">
                                <span>{{ $category->products_count }} Produk</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>

                        <!-- Sort Order -->
                        <td class="py-3 px-3.5 text-stone-300 font-semibold">
                            #{{ $category->sort_order }}
                        </td>

                        <!-- Status Toggle -->
                        <td class="py-3 px-3.5">
                            <form action="{{ route('admin.categories.toggle', $category->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $category->is_active ? 'bg-emerald-950 text-emerald-300 border border-emerald-700/50 hover:bg-emerald-900' : 'bg-rose-950 text-rose-300 border border-rose-700/50 hover:bg-rose-900' }}" title="Klik untuk ubah status aktif/nonaktif">
                                    {{ $category->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="py-3 px-3.5 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-1.5 inline-block text-stone-300 hover:text-[#e2c159] bg-[#261914] border border-[#382621] rounded-lg transition" title="Edit Kategori">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($category->name) }}\'?{{ $category->products_count > 0 ? ' Perhatian: Kategori ini masih memiliki ' . $category->products_count . ' produk!' : '' }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-400 hover:text-rose-200 bg-[#261914] border border-[#382621] hover:border-rose-700 rounded-lg transition" title="Hapus Kategori">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-stone-500">
                            Tidak ada kategori ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="p-4 border-t border-[#33231c]">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
