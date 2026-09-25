@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $order->order_code)
@section('header_title', 'Detail Pesanan #' . $order->order_code)

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-xl bg-[#261914] text-stone-300 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="font-mono font-bold text-base text-[#e2c159]">{{ $order->order_code }}</h3>
                    <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $order->status_color }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <span class="text-[11px] text-stone-400">Masuk: {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Hubungi WhatsApp Pelanggan -->
            <a href="{{ $customerWaUrl }}" target="_blank" class="px-3 py-2 bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                <span>Chat Pelanggan</span>
            </a>

            <!-- Delete Form -->
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 bg-[#33231c] hover:bg-rose-950 text-rose-300 rounded-xl transition" title="Hapus Pesanan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Main 2-Column Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column (Items & Notes) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Ordered Items Table -->
            <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm space-y-3">
                <h4 class="font-serif-title font-bold text-sm text-[#fffdfa]">Daftar Menu yang Dipesan</h4>
                
                <div class="divide-y divide-[#33231c]">
                    @foreach($order->items as $item)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-[#261914] flex-shrink-0">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#c89e2b]">🍰</div>
                                @endif
                            </div>
                            <div>
                                <h5 class="font-bold text-[#fffdfa]">{{ $item->product_name }}</h5>
                                <span class="text-[10px] text-stone-400">{{ $item->formatted_price }} &times; {{ $item->quantity }} pcs</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-[#e2c159]">{{ $item->formatted_subtotal }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-[#33231c] space-y-1.5 text-xs">
                    <div class="flex justify-between text-stone-300">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-white">{{ $order->formatted_subtotal }}</span>
                    </div>
                    <div class="flex justify-between text-stone-300">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-emerald-400">{{ $order->delivery_fee > 0 ? $order->formatted_delivery_fee : 'Gratis / Bayar di Tempat' }}</span>
                    </div>
                    <div class="pt-2 border-t border-[#33231c] flex justify-between items-center text-sm font-bold">
                        <span class="text-white">Total Tagihan:</span>
                        <span class="text-base text-[#e2c159]">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Cake Greeting / Notes Card -->
            @if($order->greeting_notes)
            <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm space-y-2">
                <div class="flex items-center space-x-2 text-[#e2c159]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <h4 class="font-serif-title font-bold text-sm">Catatan Khusus / Tulisan di Cake</h4>
                </div>
                <div class="p-3.5 bg-[#261914] rounded-xl border border-[#4a342b] text-xs text-[#dac9a3] leading-relaxed">
                    "{{ $order->greeting_notes }}"
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column (Customer & Status Management) -->
        <div class="space-y-6">

            <!-- Customer & Delivery Info Card -->
            <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm space-y-3 text-xs">
                <h4 class="font-serif-title font-bold text-sm text-[#fffdfa]">Informasi Pengantaran</h4>
                
                <div class="p-3 bg-[#261914] rounded-xl space-y-2 border border-[#382621]">
                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase">Nama Pemesan:</span>
                        <span class="font-bold text-white text-sm">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase">Nomor WhatsApp:</span>
                        <a href="{{ $customerWaUrl }}" target="_blank" class="font-mono text-[#e2c159] hover:underline font-semibold">{{ $order->customer_phone }}</a>
                    </div>
                </div>

                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between">
                        <span class="text-stone-400">Metode:</span>
                        <span class="font-bold text-white">{{ $order->delivery_method === 'pickup' ? 'Self Pickup (Ambil Sendiri)' : 'Pesan Kurir' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-400">Tanggal PO:</span>
                        <span class="font-bold text-[#e2c159]">{{ $order->delivery_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-400">Slot Jam:</span>
                        <span class="font-semibold text-white">{{ $order->delivery_time_slot }}</span>
                    </div>
                    @if($order->delivery_address)
                    <div class="pt-2 border-t border-[#33231c]">
                        <span class="text-[10px] text-stone-400 block uppercase mb-1">Alamat Tujuan Kurir:</span>
                        <p class="text-stone-200 bg-[#261914] p-2.5 rounded-lg border border-[#382621] leading-relaxed">{{ $order->delivery_address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment & Proof Verification Card -->
            <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm space-y-3 text-xs">
                <div class="flex items-center justify-between">
                    <h4 class="font-serif-title font-bold text-sm text-[#fffdfa]">Bukti & Metode Pembayaran</h4>
                    <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-emerald-950 text-emerald-300 border border-emerald-800 flex items-center space-x-1">
                        <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>LUNAS (WAJIB DI AWAL)</span>
                    </span>
                </div>

                <div class="space-y-1.5 p-3 bg-[#261914] rounded-xl border border-[#382621]">
                    <div class="flex justify-between">
                        <span class="text-stone-400">Metode Bayar:</span>
                        <span class="font-bold text-[#e2c159]">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-400">Total Tagihan:</span>
                        <span class="font-bold text-white">{{ $order->formatted_total }}</span>
                    </div>
                </div>

                @if($order->payment_proof)
                <div class="space-y-2 pt-1">
                    <span class="text-[10px] uppercase font-bold text-stone-400 block">Lampiran Bukti Transfer:</span>
                    <div class="p-2 bg-[#261914] rounded-xl border border-[#4a342b] flex items-center space-x-3">
                        <a href="{{ $order->payment_proof_url }}" target="_blank" class="block w-20 h-20 rounded-lg overflow-hidden bg-stone-900 border border-[#4a342b] flex-shrink-0 group relative">
                            <img src="{{ $order->payment_proof_url }}" alt="Bukti Transfer {{ $order->order_code }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition text-xs">🔍</span>
                        </a>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-emerald-400 text-xs">File Bukti Terlampir</p>
                            <p class="text-[10px] text-stone-400 truncate mt-0.5">{{ basename($order->payment_proof) }}</p>
                            <a href="{{ $order->payment_proof_url }}" target="_blank" class="inline-block mt-2 px-2.5 py-1 bg-[#33231c] hover:bg-[#4a342b] text-[#e2c159] font-bold text-[10px] rounded-lg transition">
                                Buka Ukuran Penuh &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="p-3 bg-[#261914] rounded-xl border border-dashed border-[#4a342b] text-center text-stone-400 text-[11px]">
                    Belum ada bukti pembayaran yang diunggah pelanggan.
                </div>
                @endif
            </div>

            <!-- Status Updater Card -->
            <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="font-serif-title font-bold text-sm text-[#fffdfa]">Perbarui Status Pesanan</h4>

                <!-- Order Status Form -->
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-semibold text-stone-300 mb-1">Status Pengerjaan Dapur:</label>
                        <select name="status" class="w-full text-xs p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses (Dapur Memasak)</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Siap Diambil / Dikirim</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Pesanan Tuntas)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 bg-[#c89e2b] hover:bg-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl transition">
                        Simpan Status Pesanan
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
