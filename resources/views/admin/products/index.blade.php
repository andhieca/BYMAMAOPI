@extends('admin.layout')

@section('title', 'Katalog Produk')
@section('header_title', 'Manajemen Produk & Kategori')

@section('content')
<div class="space-y-5">

    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-2 flex-1">
            <select name="category_id" onchange="this.form.submit()" class="text-xs p-2 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama kue / cookies..."
                class="text-xs p-2 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none flex-1 min-w-[150px]">

            <button type="submit" class="px-3 py-2 bg-[#33231c] hover:bg-[#4a342b] text-[#e2c159] text-xs font-semibold rounded-xl transition">
                Cari
            </button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow hover:brightness-105 active:scale-95 transition flex items-center justify-center space-x-1.5 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Produk Baru</span>
        </a>
    </div>

    <!-- Products Grid / Table -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-[10px] text-[#dac9a3] uppercase border-b border-[#33231c] bg-[#140d0a]/60">
                    <tr>
                        <th class="py-3 px-3.5">Foto</th>
                        <th class="py-3 px-3.5">Nama Produk</th>
                        <th class="py-3 px-3.5">Kategori</th>
                        <th class="py-3 px-3.5">Harga</th>
                        <th class="py-3 px-3.5">Badge</th>
                        <th class="py-3 px-3.5">Ketersediaan</th>
                        <th class="py-3 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#33231c] text-stone-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-[#261914] transition">
                        <td class="py-3 px-3.5">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-[#261914] border border-[#382621]">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="font-bold text-white block">{{ $product->name }}</span>
                            <span class="text-[10px] text-stone-400 line-clamp-1 max-w-[250px]">{{ $product->description }}</span>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="px-2 py-0.5 rounded-lg bg-[#261914] text-[#dac9a3] border border-[#382621] text-[10px] font-semibold">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-3.5 font-bold text-[#e2c159]">
                            {{ $product->formatted_price }}
                        </td>
                        <td class="py-3 px-3.5">
                            @if($product->badge)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-[#c89e2b] text-[#140d0a]">
                                    {{ $product->badge }}
                                </span>
                            @else
                                <span class="text-[10px] text-stone-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-3.5">
                            <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $product->is_available ? 'bg-emerald-950 text-emerald-300 border border-emerald-700/50 hover:bg-emerald-900' : 'bg-rose-950 text-rose-300 border border-rose-700/50 hover:bg-rose-900' }}" title="Klik untuk ubah status">
                                    {{ $product->is_available ? '✓ Tersedia' : '✕ Habis' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-3.5 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="px-2.5 py-1 bg-[#33231c] hover:bg-[#4a342b] text-[#e2c159] rounded-lg text-xs font-semibold transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 bg-[#33231c] hover:bg-rose-950 text-rose-300 rounded-lg transition" title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-stone-400">Belum ada produk yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-[#33231c] bg-[#140d0a]/40">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
