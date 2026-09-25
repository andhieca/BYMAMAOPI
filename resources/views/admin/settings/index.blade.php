@extends('admin.layout')

@section('title', 'Pengaturan Toko')
@section('header_title', 'Pengaturan Informasi & Operasional Toko')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-6 shadow-sm">
        
        <div class="pb-4 mb-5 border-b border-[#33231c]">
            <h3 class="font-serif-title font-bold text-base text-[#fffdfa]">Konfigurasi Toko & Pemesanan</h3>
            <p class="text-xs text-[#dac9a3] mt-0.5">Informasi ini langsung ditampilkan pada website pemesanan dan format pesan WhatsApp pelanggan.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
            @csrf

            <!-- Section 1: Identitas Brand -->
            <div class="space-y-3">
                <h4 class="font-bold uppercase tracking-wider text-[#c89e2b] text-[11px]">1. Identitas Brand</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Nama Toko / Brand *</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name']->value ?? 'BYMAMAOPI') }}" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Tagline / Slogan</label>
                        <input type="text" name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline']->value ?? 'Artisan Cookies, Cake, Bolu & Brownies') }}" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 2: Kontak & WhatsApp -->
            <div class="space-y-3 pt-4 border-t border-[#33231c]">
                <h4 class="font-bold uppercase tracking-wider text-[#c89e2b] text-[11px]">2. Nomor WhatsApp & Kontak</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Nomor WhatsApp Admin (Tujuan Pesanan) *</label>
                        <input type="text" name="store_whatsapp" value="{{ old('store_whatsapp', $settings['store_whatsapp']->value ?? '6281234567890') }}" placeholder="Contoh: 6281234567890" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                        <p class="text-[10px] text-stone-400 mt-1">Gunakan kode negara (62). Format pesan otomatis dikirimkan ke nomor ini.</p>
                    </div>
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Kota Kitchen</label>
                        <input type="text" name="store_city" value="{{ old('store_city', $settings['store_city']->value ?? 'Bandung') }}" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Alamat Lengkap Base / Kitchen Toko *</label>
                    <textarea name="store_address" rows="2" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">{{ old('store_address', $settings['store_address']->value ?? '') }}</textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-semibold text-stone-300">Link Titik Google Maps (Titik Lokasi Toko)</label>
                        @if(!empty($settings['store_maps_url']->value ?? ''))
                        <a href="{{ $settings['store_maps_url']->value }}" target="_blank" class="text-xs text-[#c89e2b] hover:text-[#e2c159] hover:underline flex items-center space-x-1">
                            <span>Uji Coba Titik Maps</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        @endif
                    </div>
                    <input
                        type="url"
                        name="store_maps_url"
                        value="{{ old('store_maps_url', $settings['store_maps_url']->value ?? '') }}"
                        placeholder="Contoh: https://maps.app.goo.gl/... atau https://maps.google.com/?q=..."
                        class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none text-xs">
                    <p class="text-[10px] text-stone-400 mt-1">Masukkan URL link Google Maps titik toko. Tombol <strong>"Buka di Google Maps"</strong> akan otomatis tampil pada info kitchen & penjemputan kurir/pickup.</p>
                </div>

                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Jam Operasional Kitchen</label>
                    <input type="text" name="store_operating_hours" value="{{ old('store_operating_hours', $settings['store_operating_hours']->value ?? 'Senin - Minggu: 08.00 - 18.00 WIB') }}" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                </div>
            </div>

            <!-- Section 3: Pengaturan Kuota & PO -->
            <div class="space-y-3 pt-4 border-t border-[#33231c]">
                <h4 class="font-bold uppercase tracking-wider text-[#c89e2b] text-[11px]">3. Sistem Kuota & Pre-Order (PO)</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Kapasitas Kuota Default Harian *</label>
                        <input type="number" name="default_daily_quota" value="{{ old('default_daily_quota', $settings['default_daily_quota']->value ?? 15) }}" min="1" max="500" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                        <p class="text-[10px] text-stone-400 mt-1">Jumlah maksimal pesanan yang bisa diterima per hari.</p>
                    </div>
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Minimum Lead Time Pre-Order (Hari) *</label>
                        <input type="number" name="min_order_lead_days" value="{{ old('min_order_lead_days', $settings['min_order_lead_days']->value ?? 1) }}" min="0" max="30" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                        <p class="text-[10px] text-stone-400 mt-1">Contoh: 1 = H+1 (pesan hari ini untuk besok), 2 = H+2.</p>
                    </div>
                </div>
            </div>

            <!-- Section 4: Pembayaran Transfer Bank & QRIS -->
            <div class="space-y-4 pt-4 border-t border-[#33231c]">
                <h4 class="font-bold uppercase tracking-wider text-[#c89e2b] text-[11px]">4. Rekening Pembayaran & Barcode QRIS</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name']->value ?? 'BCA') }}" placeholder="Contoh: BCA / Mandiri" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $settings['bank_account_number']->value ?? '8290123456') }}" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-stone-300 mb-1">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $settings['bank_account_holder']->value ?? 'MAMA OPI ARTISAN BAKERY') }}" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    </div>
                </div>

                <!-- QRIS Image Upload -->
                <div class="p-3.5 bg-[#261914] rounded-xl border border-[#4a342b] space-y-2">
                    <label class="block font-semibold text-stone-300">Gambar Barcode QRIS Toko</label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        @php
                            $qrisVal = $settings['qris_image']->value ?? 'images/qris-sample.svg';
                        @endphp
                        <div class="w-24 h-24 bg-white p-2 rounded-xl border border-stone-600 flex-shrink-0 flex items-center justify-center">
                            <img src="{{ asset($qrisVal) }}" alt="QRIS Toko" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1 space-y-1.5 w-full">
                            <input type="file" name="qris_image" accept="image/*" class="w-full text-xs text-stone-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#c89e2b] file:text-[#140d0a] hover:file:bg-[#e2c159] cursor-pointer">
                            <p class="text-[10px] text-stone-400">Unggah file foto / gambar QRIS toko Anda (format JPG, PNG, atau SVG, maks. 4MB). Gambar ini akan otomatis muncul pada saat pelanggan checkout dan nota pelacakan pesanan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Petunjuk Pickup & Kurir -->
            <div class="space-y-3 pt-4 border-t border-[#33231c]">
                <h4 class="font-bold uppercase tracking-wider text-[#c89e2b] text-[11px]">5. Catatan Pengiriman & Penjemputan</h4>
                
                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Petunjuk Self Pickup</label>
                    <textarea name="pickup_note" rows="2" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">{{ old('pickup_note', $settings['pickup_note']->value ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Petunjuk Pesan Kurir</label>
                    <textarea name="courier_note" rows="2" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">{{ old('courier_note', $settings['courier_note']->value ?? '') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-[#33231c] flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold rounded-xl shadow hover:brightness-105 active:scale-95 transition">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
