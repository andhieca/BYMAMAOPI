<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pesanan Berhasil #{{ $order->order_code }} - BYMAMAOPI</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-bymamaopi.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-bymamaopi.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f3ec] text-[#211612] antialiased">

@include('partials.page-loader', ['loaderBrand' => 'BYMAMAOPI', 'loaderTagline' => 'Nota Pemesanan'])

<div class="app-container" x-data="receiptApp()">

    <!-- Header -->
    <header class="bg-[#211612] text-[#fdfbf7] px-4 py-3 border-b border-[#3d2a23] flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-[#e2c159] hover:underline text-xs flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Beranda</span>
        </a>
        <div class="flex items-center space-x-2">
            <div class="w-6 h-6 rounded-full overflow-hidden bg-black border border-[#c89e2b]/60 flex-shrink-0">
                <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="BYMAMAOPI" class="w-full h-full object-cover">
            </div>
            <span class="font-serif-title text-sm font-bold text-[#e2c159]">BYMAMAOPI</span>
        </div>
    </header>

    <main class="p-4 space-y-4 flex-1 pb-12">

        <!-- Success Banner Card -->
        <div class="bg-gradient-to-br from-[#211612] to-[#33231c] text-[#fdfbf7] rounded-3xl p-5 text-center shadow-lg border border-[#c89e2b]/50 relative overflow-hidden">
            <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#c89e2b] to-[#e2c159] text-[#140d0a] flex items-center justify-center mx-auto mb-3 shadow-md">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <span class="text-[10px] uppercase font-bold tracking-widest text-[#e2c159]">Pesanan Diterima</span>
            <h2 class="font-serif-title text-xl font-bold text-[#fffdfa] mt-0.5">Terima Kasih, {{ $order->customer_name }}!</h2>
            
            <!-- Order Number & Copy Action -->
            <div class="mt-3 flex justify-center">
                <div class="inline-flex items-center space-x-2 bg-[#140d0a]/80 border border-[#c89e2b]/60 px-3.5 py-1.5 rounded-full shadow-inner">
                    <span class="text-[11px] text-[#dac9a3]">Nomor Pesanan:</span>
                    <strong class="text-[#fdfbf7] font-mono tracking-wider text-xs">{{ $order->order_code }}</strong>
                    <button
                        type="button"
                        @click="copyOrderCode('{{ $order->order_code }}')"
                        class="ml-1 px-2 py-0.5 rounded-full bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] text-[10px] font-bold shadow-sm hover:brightness-105 active:scale-90 transition flex items-center space-x-1"
                        title="Salin Nomor Pesanan">
                        <template x-if="!copied">
                            <span class="flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </span>
                        </template>
                        <template x-if="copied">
                            <span class="flex items-center space-x-1">
                                <svg class="w-3 h-3 text-[#140d0a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Tersalin!</span>
                            </span>
                        </template>
                    </button>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#4a342b] flex items-center justify-around text-xs">
                <div>
                    <span class="text-[10px] text-[#dac9a3] block">Metode Bayar:</span>
                    <span class="font-bold text-[#e2c159]">{{ $order->payment_method_label }}</span>
                </div>
                <div class="h-6 w-px bg-[#4a342b]"></div>
                <div>
                    <span class="text-[10px] text-[#dac9a3] block">Status Bayar:</span>
                    <span class="font-bold text-emerald-400">
                        Lunas (Wajib di Awal)
                    </span>
                </div>
            </div>
        </div>

        <!-- Warning / Important Tracking Reminder Banner -->
        <div class="bg-gradient-to-r from-amber-50 to-amber-100/80 border border-amber-300 rounded-2xl p-3.5 shadow-sm flex items-start space-x-3">
            <div class="w-8 h-8 rounded-xl bg-amber-500 text-amber-950 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs font-bold text-sm">
                ⚠️
            </div>
            <div class="flex-1 text-xs">
                <h4 class="font-bold text-amber-950 uppercase tracking-wider text-[11px]">PENTING: SIMPAN NOMOR PESANAN ANDA</h4>
                <p class="text-amber-900 mt-1 leading-relaxed">
                    Catat atau salin nomor pesanan <strong class="font-mono bg-amber-200/90 px-1.5 py-0.5 rounded text-amber-950">{{ $order->order_code }}</strong>. Anda dapat menggunakannya untuk mengecek kembali progres pesanan & antrean dapur di Beranda toko.
                </p>
                <div class="mt-2.5 pt-2 border-t border-amber-200 flex items-center justify-between">
                    <span class="text-[10px] text-amber-800">Menu <strong>"Cek Pesanan"</strong> tersedia di halaman depan.</span>
                    <button
                        type="button"
                        @click="copyOrderCode('{{ $order->order_code }}')"
                        class="px-2.5 py-1 bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] rounded-lg shadow-xs transition active:scale-95 flex items-center space-x-1">
                        <span>📋 Salin Nomor</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast Warning Modal Triggered On Copy -->
        <div
            x-show="showWarningModal"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 left-4 right-4 z-50 max-w-md mx-auto bg-[#211612] text-white p-4 rounded-2xl shadow-2xl border-2 border-[#c89e2b] flex items-start space-x-3"
            x-cloak>
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 font-bold text-sm">
                ✓
            </div>
            <div class="flex-1 text-xs">
                <p class="font-bold text-[#e2c159] text-sm">Nomor Pesanan Berhasil Disalin!</p>
                <p class="text-stone-300 mt-1 leading-relaxed">
                    Nomor: <strong class="font-mono text-white">{{ $order->order_code }}</strong>. Jangan lupa simpan kode ini untuk mengecek status pesanan Anda kembali di halaman utama toko.
                </p>
            </div>
            <button @click="showWarningModal = false" class="text-stone-400 hover:text-white p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Order Flow Status Timeline -->
        <div class="bg-[#fffdfa] rounded-2xl p-4 border border-[#ebe0c6] shadow-sm">
            <h3 class="font-bold text-xs uppercase tracking-wider text-[#6b4d40] mb-3">Alur Proses Pesanan:</h3>
            <div class="space-y-3">
                
                <!-- Step 1 -->
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-[#211612]">Pesanan Dibuat di Sistem</h4>
                        <p class="text-[10px] text-[#6b4d40]">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 rounded-full {{ in_array($order->status, ['processing', 'ready', 'completed']) ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950 font-bold animate-pulse' }} flex items-center justify-center text-xs">
                        {{ in_array($order->status, ['processing', 'ready', 'completed']) ? '✓' : '2' }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-[#211612]">Konfirmasi Admin & Dapur Memasak</h4>
                        <p class="text-[10px] text-[#6b4d40]">Pesanan disiapkan fresh sesuai slot jam yang dipilih</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 rounded-full {{ in_array($order->status, ['ready', 'completed']) ? 'bg-emerald-500 text-white' : 'bg-stone-200 text-stone-500' }} flex items-center justify-center text-xs font-bold">
                        {{ in_array($order->status, ['ready', 'completed']) ? '✓' : '3' }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-[#211612]">
                            {{ $order->delivery_method === 'pickup' ? 'Siap Diambil di Kitchen' : 'Siap Dikirim via Kurir' }}
                        </h4>
                        <p class="text-[10px] text-[#6b4d40]">Tanggal: {{ $order->delivery_date->format('d M Y') }} ({{ $order->delivery_time_slot }})</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp Re-send / Chat Admin Button -->
        <a href="{{ $order->whatsapp_url ?? $order->generateWhatsAppUrl() }}" target="_blank" class="w-full py-3 bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-xs rounded-2xl shadow-md flex items-center justify-center space-x-2 transition active:scale-95">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>Buka / Kirim Ulang Format WhatsApp ke Admin</span>
        </a>

        <!-- Payment Proof or Payment Instruction Card -->
        @if($order->payment_proof)
        <div class="bg-[#fffdfa] rounded-2xl p-4 border border-emerald-300 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-emerald-800">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs">✓</span>
                    <h3 class="font-bold text-xs uppercase tracking-wider">Bukti Pembayaran Terunggah</h3>
                </div>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold border border-emerald-200">
                    {{ $order->payment_method_label }}
                </span>
            </div>

            <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100 flex items-center space-x-3">
                <a href="{{ $order->payment_proof_url }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden bg-stone-100 border border-emerald-200 flex-shrink-0 group">
                    <img src="{{ $order->payment_proof_url }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition">
                </a>
                <div class="flex-1 min-w-0 text-xs">
                    <p class="font-semibold text-emerald-950">Bukti Transfer Berhasil Disimpan</p>
                    <p class="text-[11px] text-emerald-700 mt-0.5">Admin akan memverifikasi dan menyiapkan pesanan Anda.</p>
                    <a href="{{ $order->payment_proof_url }}" target="_blank" class="text-[10px] text-[#c89e2b] font-bold hover:underline inline-block mt-1">
                        Lihat Gambar Bukti Penuh &rarr;
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Unpaid / Missing Proof Instructions Card -->
        <div class="bg-[#fffdfa] rounded-2xl p-4 border border-[#ebe0c6] shadow-sm space-y-3">
            <h3 class="font-bold text-xs uppercase tracking-wider text-[#6b4d40] flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-[#c89e2b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Petunjuk Pembayaran ({{ $order->payment_method_label }})</span>
            </h3>

            @if($order->payment_method === 'qris')
            <div class="bg-[#fbf7ee] rounded-xl p-3.5 border border-[#ebe0c6] text-center space-y-2">
                <div class="bg-white p-3 rounded-xl border border-[#ebe0c6] inline-block shadow-sm max-w-[280px] w-full mx-auto">
                    <img src="{{ $settings['qris_image'] }}" alt="QRIS {{ $settings['store_name'] }}" class="w-full h-auto max-h-[320px] mx-auto object-contain rounded-lg">
                    <div class="mt-2 pt-2 border-t border-stone-100 flex items-center justify-between text-[10px]">
                        <span class="font-bold text-[#211612]">BY MAMA OPI KITCHEN</span>
                        <span class="font-mono text-stone-500">ID1024335755285</span>
                    </div>
                </div>
                <p class="text-xs font-bold text-[#211612]">Scan QRIS di atas melalui m-Banking / E-Wallet</p>
                <p class="text-sm font-bold text-[#c89e2b]">Total: {{ $order->formatted_total }}</p>
            </div>
            @else
            <div class="bg-[#fbf7ee] rounded-xl p-3 border border-[#ebe0c6] space-y-1.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-[#6b4d40]">Bank Tujuan:</span>
                    <span class="font-bold text-[#211612]">{{ $settings['bank_name'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[#6b4d40]">No. Rekening:</span>
                    <span class="font-mono font-bold text-sm text-[#c89e2b]">{{ $settings['bank_account_number'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#6b4d40]">Atas Nama:</span>
                    <span class="font-semibold text-[#211612]">{{ $settings['bank_account_holder'] }}</span>
                </div>
                <div class="flex justify-between pt-1 border-t border-[#ebe0c6] font-bold">
                    <span>Jumlah Transfer:</span>
                    <span class="text-sm text-[#c89e2b]">{{ $order->formatted_total }}</span>
                </div>
            </div>
            @endif

            <p class="text-[10px] text-[#6b4d40] italic leading-relaxed">
                *Kirimkan bukti transfer / screenshot scan QRIS ke WhatsApp admin agar pesanan segera masuk ke jadwal baking kitchen.
            </p>
        </div>
        @endif

        <!-- Order Items Detail Card -->
        <div class="bg-[#fffdfa] rounded-2xl p-4 border border-[#ebe0c6] shadow-sm space-y-2">
            <h3 class="font-bold text-xs uppercase tracking-wider text-[#6b4d40]">Rincian Menu Pesanan:</h3>
            <div class="divide-y divide-[#ebe0c6]">
                @foreach($order->items as $item)
                <div class="py-2 flex justify-between items-center text-xs">
                    <div>
                        <h4 class="font-bold text-[#211612]">{{ $item->product_name }}</h4>
                        <span class="text-[10px] text-[#6b4d40]">{{ $item->formatted_price }} x {{ $item->quantity }}</span>
                    </div>
                    <span class="font-bold text-[#211612]">{{ $item->formatted_subtotal }}</span>
                </div>
                @endforeach
            </div>

            <div class="pt-2 border-t border-[#ebe0c6] space-y-1 text-xs">
                <div class="flex justify-between text-[#6b4d40]">
                    <span>Metode:</span>
                    <span class="font-bold text-[#211612]">{{ $order->delivery_method === 'pickup' ? 'Self Pickup' : 'Pesan Kurir' }}</span>
                </div>
                <div class="flex justify-between text-[#6b4d40]">
                    <span>Tanggal & Jam:</span>
                    <span class="font-bold text-[#211612]">{{ $order->delivery_date->format('d M Y') }} • {{ $order->delivery_time_slot }}</span>
                </div>
                @if(!empty($settings['store_maps_url']))
                <div class="flex justify-between items-center text-[11px] pt-0.5">
                    <span class="text-[#6b4d40]">Titik Penjemputan:</span>
                    <a href="{{ $settings['store_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="text-[#c89e2b] font-bold hover:underline flex items-center space-x-1">
                        <span>📍 Buka Google Maps</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
                @endif
                @if($order->greeting_notes)
                <div class="pt-1 text-[#6b4d40]">
                    <span class="font-bold block text-[#211612]">Catatan / Ucapan:</span>
                    <p class="italic text-[11px] bg-[#fbf7ee] p-2 rounded-lg mt-0.5 border border-[#ebe0c6]">"{{ $order->greeting_notes }}"</p>
                </div>
                @endif
                <div class="pt-2 border-t border-[#ebe0c6] flex justify-between items-center font-bold text-sm">
                    <span>Total Pembayaran:</span>
                    <span class="text-[#c89e2b] text-base">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        <div class="pt-2">
            <a href="{{ route('home') }}" class="block text-center w-full py-2.5 bg-[#fbf7ee] border border-[#dac9a3] text-[#211612] font-bold text-xs rounded-xl hover:bg-[#ebe0c6] transition">
                Kembali ke Katalog Toko
            </a>
        </div>

    </main>

</div>

<script>
function receiptApp() {
    return {
        copied: false,
        showWarningModal: false,
        copyOrderCode(code) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(code);
            }
            this.copied = true;
            this.showWarningModal = true;
            setTimeout(() => {
                this.copied = false;
            }, 3000);
            setTimeout(() => {
                this.showWarningModal = false;
            }, 7000);
        }
    }
}
</script>

</body>
</html>
