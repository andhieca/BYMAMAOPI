<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $settings['store_name'] }} - {{ $settings['store_tagline'] }} | Pemesanan Online</title>
    <meta name="description" content="Pesan Cookies, Cake, Bolu Jadul, dan Fudgy Brownies premium dari {{ $settings['store_name'] }}. Pre-order mudah, fresh from oven, dan bahan berkualitas terbaik.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

@include('partials.page-loader', ['loaderBrand' => $settings['store_name'], 'loaderTagline' => $settings['store_tagline']])

<div class="app-container" x-data="orderApp()" x-init="initApp()">

    <!-- ================= TOP LUXURY APP BAR ================= -->
    <header class="sticky top-0 z-40 bg-[#211612]/95 backdrop-blur-md text-[#fdfbf7] px-4 py-3 border-b border-[#3d2a23] shadow-md flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <button x-show="step > 1" @click="prevStep()" class="p-1.5 -ml-1 text-[#e2c159] hover:bg-[#33231c] rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                <div class="w-9 h-9 rounded-full overflow-hidden bg-black border border-[#c89e2b]/70 flex-shrink-0 shadow-sm p-0.5 group-hover:scale-105 transition">
                    <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="BYMAMAOPI Logo" class="w-full h-full object-cover rounded-full">
                </div>
                <div>
                    <h1 class="font-serif-title text-base font-bold tracking-wider text-[#e2c159] leading-tight group-hover:text-white transition">
                        {{ $settings['store_name'] }}
                    </h1>
                    <p class="text-[9px] text-[#dac9a3] uppercase tracking-widest leading-none mt-0.5">Artisan Bakery & Cake</p>
                </div>
            </a>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Cek Pesanan Header Trigger -->
            <button @click="focusTracking()" class="text-xs px-2.5 py-1.5 bg-[#33231c] hover:bg-[#4a342b] text-[#e2c159] border border-[#c89e2b]/40 font-medium rounded-full shadow-xs active:scale-95 transition flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span class="hidden sm:inline">Cek Pesanan</span>
                <span class="sm:hidden">Lacak</span>
            </button>
            <!-- Order Now Quick trigger -->
            <button @click="startOrder()" class="text-xs px-2.5 py-1.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#211612] font-semibold rounded-full shadow-sm hover:brightness-105 active:scale-95 transition">
                <span x-text="step === 1 ? 'Mulai Pesan' : 'Step ' + step + '/4'"></span>
            </button>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['store_whatsapp']) }}" target="_blank" class="p-1.5 text-[#e2c159] hover:bg-[#33231c] rounded-full transition" title="WhatsApp Admin">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            </a>
        </div>
    </header>

    <!-- ================= MAIN CONTENT WRAPPER ================= -->
    <main class="flex-1 pb-24">

        <!-- ================= STEP 1: LANDING & BASE KITCHEN ================= -->
        <section x-show="step === 1" x-transition.opacity.duration.300ms>
            
            <!-- Hero Banner Slider Display -->
            <div class="relative w-full aspect-[16/9] overflow-hidden bg-[#211612]">
                <img src="{{ asset('images/hero-banner.jpg') }}" alt="Artisan Cookies and Cakes by {{ $settings['store_name'] }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#211612] via-[#211612]/30 to-transparent"></div>
                <div class="absolute bottom-3 left-4 right-4">
                    <span class="inline-block px-2 py-0.5 text-[10px] uppercase font-bold tracking-wider rounded bg-[#c89e2b] text-[#140d0a] mb-1">Freshly Baked Every Day</span>
                    <h2 class="font-serif-title text-xl font-bold text-[#fffdfa] leading-tight drop-shadow-md">
                        Cita Rasa Mewah di Setiap Gigitan.
                    </h2>
                    <p class="text-xs text-[#dac9a3] line-clamp-1 drop-shadow">100% Belgian Chocolate, Wisman Butter & Resep Istimewa</p>
                </div>
            </div>

            <!-- Action Buttons (Order Now, Cek Order, WhatsApp) -->
            <div class="p-3 grid grid-cols-3 gap-2.5 bg-[#fffdfa] border-b border-[#ebe0c6] shadow-sm">
                <!-- Button 1: Order Now -->
                <button @click="goToStep(2)" class="flex flex-col items-center justify-center p-3 rounded-xl bg-gradient-to-b from-[#211612] to-[#33231c] text-[#fdfbf7] shadow-sm active:scale-95 transition group">
                    <div class="w-9 h-9 rounded-full bg-[#c89e2b]/20 flex items-center justify-center text-[#e2c159] mb-1.5 group-hover:scale-110 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-[#e2c159]">Order Now</span>
                    <span class="text-[9px] text-[#dac9a3]">Pesan Online</span>
                </button>

                <!-- Button 2: Cek Pesanan -->
                <button @click="focusTracking()" class="flex flex-col items-center justify-center p-3 rounded-xl bg-[#fbf7ee] border border-[#dac9a3]/60 text-[#211612] shadow-sm active:scale-95 transition group">
                    <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 mb-1.5 group-hover:scale-110 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <span class="text-xs font-bold text-[#211612]">Cek Order</span>
                    <span class="text-[9px] text-[#6b4d40]">Lacak Resi</span>
                </button>

                <!-- Button 3: WhatsApp Contact -->
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['store_whatsapp']) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-xl bg-[#fbf7ee] border border-[#dac9a3]/60 text-[#211612] shadow-sm active:scale-95 transition group">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-1.5 group-hover:scale-110 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-[#211612]">WhatsApp</span>
                    <span class="text-[9px] text-[#6b4d40]">Chat Admin</span>
                </a>
            </div>

            <!-- Cek Status Pesanan Interactive Card -->
            <div id="tracking-card" class="px-4 pt-3">
                <div class="bg-gradient-to-br from-[#fffdfa] to-[#fbf7ee] rounded-2xl p-3.5 border border-[#ebe0c6] shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full bg-[#c89e2b]/20 text-[#c89e2b] flex items-center justify-center text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <div>
                                <h3 class="font-bold text-xs text-[#211612]">Cek Status Pesanan Anda</h3>
                                <p class="text-[10px] text-[#6b4d40]">Lacak antrean dapur atau buka kembali nota pesanan Anda</p>
                            </div>
                        </div>
                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-[#211612] text-[#e2c159] font-bold">Lacak Resi</span>
                    </div>

                    <form @submit.prevent="checkOrderStatus()" class="flex space-x-2 mt-2">
                        <div class="relative flex-1">
                            <input
                                type="text"
                                x-model="trackOrderCode"
                                id="track_order_input"
                                placeholder="Nomor pesanan (cth: BMO-2026...)"
                                class="w-full text-xs py-2 px-3 bg-white rounded-xl border border-[#ebe0c6] focus:border-[#c89e2b] focus:ring-1 focus:ring-[#c89e2b] outline-none font-mono uppercase tracking-wider text-[#211612] placeholder:normal-case placeholder:font-sans placeholder:text-stone-400">
                        </div>
                        <button
                            type="submit"
                            :disabled="isTracking || !trackOrderCode.trim()"
                            class="px-3.5 py-2 bg-gradient-to-r from-[#211612] to-[#33231c] text-[#e2c159] font-bold text-xs rounded-xl shadow hover:brightness-110 active:scale-95 transition flex items-center space-x-1.5 disabled:opacity-50 flex-shrink-0">
                            <template x-if="!isTracking">
                                <span>Cek</span>
                            </template>
                            <template x-if="isTracking">
                                <svg class="animate-spin w-3.5 h-3.5 text-[#e2c159]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            </template>
                        </button>
                    </form>

                    <!-- Tracking Error Notice -->
                    <div x-show="trackErrorMessage" x-cloak class="mt-2.5 p-2 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs flex items-start space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span x-text="trackErrorMessage" class="leading-relaxed"></span>
                    </div>
                </div>
            </div>

            <!-- Base Kitchen Card Information -->
            <div class="p-4">
                <div class="bg-gradient-to-br from-[#211612] to-[#33231c] text-[#fdfbf7] rounded-2xl p-4 shadow-md border border-[#4a342b]">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-black border border-[#c89e2b]/70 flex-shrink-0 shadow-sm p-0.5">
                                <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="BYMAMAOPI Logo" class="w-full h-full object-cover rounded-full">
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-[#c89e2b]">Lokasi Kitchen Toko</span>
                                <h3 class="font-bold text-sm text-[#fdfbf7]">{{ $settings['store_name'] }}</h3>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-900/60 text-emerald-300 border border-emerald-600/40 rounded-full">Buka Hari Ini</span>
                    </div>

                    <p class="text-xs text-[#dac9a3] mt-2.5 leading-relaxed">
                        📍 {{ $settings['store_address'] }}
                    </p>
                    @if(!empty($settings['store_maps_url']))
                    <div class="mt-2.5">
                        <a href="{{ $settings['store_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-[#4a342b]/70 hover:bg-[#4a342b] text-[#e2c159] border border-[#c89e2b]/40 rounded-xl text-xs font-semibold transition active:scale-95 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Buka di Google Maps</span>
                            <svg class="w-3 h-3 text-[#dac9a3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    @endif
                    <div class="mt-3 pt-2.5 border-t border-[#4a342b] flex items-center justify-between text-[11px] text-[#dac9a3]">
                        <span>⏰ {{ $settings['store_operating_hours'] }}</span>
                        <span class="text-[#e2c159] font-medium">Pre-Order H-{{ $settings['min_lead_days'] }}</span>
                    </div>

                    <div class="mt-3">
                        <button @click="goToStep(2)" class="w-full py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow hover:brightness-105 active:scale-95 transition flex items-center justify-center space-x-1.5">
                            <span>Pilih Jadwal & Mulai Pesan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories Showcase Preview -->
            <div class="px-4 py-2">
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="font-serif-title font-bold text-base text-[#211612]">Menu BY Mama Opi</h3>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @foreach($categories as $cat)
                    @php
                        $catPayload = [
                            'id' => $cat->id,
                            'name' => $cat->name,
                            'slug' => $cat->slug,
                            'description' => $cat->description,
                            'image' => $cat->image_url,
                            'products' => $cat->activeProducts->map(fn($p) => [
                                'id' => $p->id,
                                'name' => $p->name,
                                'description' => $p->description,
                                'price' => $p->price,
                                'formatted_price' => $p->formatted_price,
                                'image_url' => $p->image_url,
                                'badge' => $p->badge,
                            ])->values(),
                        ];
                    @endphp
                    <div @click="openCategoryPreview({{ json_encode($catPayload) }})" class="bg-[#fffdfa] rounded-xl overflow-hidden border border-[#ebe0c6] shadow-sm hover:shadow-md cursor-pointer transition active:scale-98 group">
                        <div class="aspect-[4/3] w-full overflow-hidden bg-[#ebe0c6] relative">
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-2">
                                <span class="text-[9px] text-[#140d0a] font-bold bg-[#e2c159] px-2 py-0.5 rounded-full shadow">Lihat Menu 👁️</span>
                            </div>
                        </div>
                        <div class="p-2.5">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-xs text-[#211612] group-hover:text-[#c89e2b] transition">{{ $cat->name }}</h4>
                                <span class="text-[9px] font-semibold text-[#c89e2b] bg-[#c89e2b]/10 px-1.5 py-0.5 rounded">{{ $cat->activeProducts->count() }} Menu</span>
                            </div>
                            <p class="text-[10px] text-[#6b4d40] line-clamp-1 mt-0.5">{{ $cat->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ================= STEP-BY-STEP ORDER WIZARD ================= -->
        <div x-show="step > 1" class="px-4 py-3">

            <!-- Wizard Progress Bar -->
            <div class="bg-[#fffdfa] rounded-2xl p-3 shadow-sm border border-[#ebe0c6] mb-4">
                <div class="flex items-center justify-between text-xs font-semibold mb-2">
                    <span class="text-[#c89e2b]" x-text="'Langkah ' + (step - 1) + ' dari 3'"></span>
                    <span class="text-[#6b4d40]" x-text="stepTitle"></span>
                </div>
                <div class="w-full bg-[#f5eedc] h-1.5 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-[#c89e2b] to-[#e2c159] h-full transition-all duration-300" :style="'width: ' + (((step - 1) / 3) * 100) + '%'"></div>
                </div>
            </div>

            <!-- ================= STEP 2: INTERACTIVE CALENDAR UI & PO QUOTA ================= -->
            <div x-show="step === 2" x-transition.opacity>
                <div class="bg-[#fffdfa] rounded-2xl p-4 shadow-sm border border-[#ebe0c6]">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="w-7 h-7 rounded-full bg-[#c89e2b]/15 text-[#c89e2b] flex items-center justify-center font-bold text-xs">1</span>
                        <div>
                            <h3 class="font-serif-title font-bold text-base text-[#211612]">Pilih Tanggal Pengambilan</h3>
                            <p class="text-[11px] text-[#6b4d40]">Sistem Pre-Order dengan kuota produksi terbatas harian</p>
                        </div>
                    </div>

                    <!-- Calendar Month Navigator -->
                    <div class="flex items-center justify-between bg-[#fbf7ee] p-2.5 rounded-xl border border-[#ebe0c6] mb-3">
                        <button @click="changeMonth(-1)" class="p-1.5 rounded-lg hover:bg-[#ebe0c6] text-[#211612] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <span class="font-bold text-xs uppercase tracking-wider text-[#211612]" x-text="calendarMonthName"></span>
                        <button @click="changeMonth(1)" class="p-1.5 rounded-lg hover:bg-[#ebe0c6] text-[#211612] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-[#6b4d40] uppercase mb-1">
                        <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                    </div>

                    <!-- Calendar Grid with Real Quotas -->
                    <div class="grid grid-cols-7 gap-1 text-center" x-show="!loadingCalendar">
                        <!-- Empty start padding days -->
                        <template x-for="i in calendarStartDay" :key="'pad-' + i">
                            <div class="aspect-square"></div>
                        </template>

                        <!-- Days of Month -->
                        <template x-for="dayObj in calendarDays" :key="dayObj.date">
                            <button
                                @click="selectDate(dayObj)"
                                :disabled="!dayObj.is_selectable"
                                class="aspect-square rounded-xl p-1 flex flex-col items-center justify-between border transition relative"
                                :class="{
                                    'bg-gradient-to-b from-[#211612] to-[#33231c] text-[#e2c159] border-[#c89e2b] shadow-md ring-2 ring-[#c89e2b] font-bold': selectedDate === dayObj.date,
                                    'bg-[#fdfbf7] text-[#211612] border-[#ebe0c6] hover:border-[#c89e2b] active:scale-95': dayObj.is_selectable && selectedDate !== dayObj.date,
                                    'bg-[#ebe0c6]/20 text-stone-400 border-stone-200/50 cursor-not-allowed opacity-50': !dayObj.is_selectable,
                                    'bg-rose-50 text-rose-400 border-rose-100 cursor-not-allowed': dayObj.is_full && !dayObj.is_past && !dayObj.is_too_soon
                                }">
                                <span class="text-xs font-semibold" x-text="dayObj.day"></span>
                                
                                <template x-if="selectedDate === dayObj.date">
                                    <span class="text-[8px] font-bold text-[#e2c159]">Dipilih</span>
                                </template>

                                <template x-if="selectedDate !== dayObj.date && dayObj.is_closed">
                                    <span class="text-[7px] text-rose-500 font-semibold leading-tight">Tutup</span>
                                </template>

                                <template x-if="selectedDate !== dayObj.date && !dayObj.is_closed && dayObj.is_full && !dayObj.is_past && !dayObj.is_too_soon">
                                    <span class="text-[7px] text-rose-500 font-semibold leading-tight">Penuh</span>
                                </template>

                                <template x-if="selectedDate !== dayObj.date && !dayObj.is_selectable && (dayObj.is_too_soon || (dayObj.is_today && !dayObj.is_selectable))">
                                    <span class="text-[7px] text-stone-400 font-semibold leading-tight">Lewat Jam</span>
                                </template>

                                <template x-if="selectedDate !== dayObj.date && dayObj.is_selectable">
                                    <span class="text-[7px] text-emerald-600 font-semibold leading-tight" x-text="dayObj.remaining + ' slot'"></span>
                                </template>
                            </button>
                        </template>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loadingCalendar" class="py-12 flex flex-col items-center justify-center text-xs text-[#6b4d40]">
                        <svg class="animate-spin w-6 h-6 text-[#c89e2b] mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>Memuat kuota harian...</span>
                    </div>

                    <!-- Quota Legend -->
                    <div class="mt-3 pt-3 border-t border-[#ebe0c6] flex items-center justify-around text-[10px] text-[#6b4d40]">
                        <div class="flex items-center space-x-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Slot Tersedia</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                            <span>Penuh / Tutup</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#c89e2b]"></span>
                            <span>Tanggal Dipilih</span>
                        </div>
                    </div>

                    <!-- Selected Date Summary Info -->
                    <div x-show="selectedDate" class="mt-4 p-3 bg-[#fbf7ee] rounded-xl border border-[#c89e2b]/50 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#c89e2b]">Tanggal Terpilih:</span>
                            <p class="text-xs font-bold text-[#211612]" x-text="formatIndoDate(selectedDate)"></p>
                        </div>
                        <button @click="goToStep(3)" class="px-3.5 py-1.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow active:scale-95 transition">
                            Lanjut &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 3: METODE PENGIRIMAN & JAM ================= -->
            <div x-show="step === 3" x-transition.opacity>
                <div class="bg-[#fffdfa] rounded-2xl p-4 shadow-sm border border-[#ebe0c6] space-y-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-full bg-[#c89e2b]/15 text-[#c89e2b] flex items-center justify-center font-bold text-xs">2</span>
                        <div>
                            <h3 class="font-serif-title font-bold text-base text-[#211612]">Metode Pengiriman & Waktu</h3>
                            <p class="text-[11px] text-[#6b4d40]">Pilih cara pengambilan kue dan slot jam</p>
                        </div>
                    </div>

                    <!-- Method Selector Tabs -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Option A: Self Pickup -->
                        <div @click="deliveryMethod = 'pickup'"
                             class="p-3.5 rounded-xl border cursor-pointer transition active:scale-98 flex flex-col justify-between"
                             :class="deliveryMethod === 'pickup' ? 'bg-[#fbf7ee] border-[#c89e2b] ring-2 ring-[#c89e2b]/40 shadow-sm' : 'bg-[#fffdfa] border-[#ebe0c6] opacity-80'">
                            <div>
                                <div class="w-8 h-8 rounded-full bg-[#c89e2b]/20 flex items-center justify-center text-[#c89e2b] mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <h4 class="font-bold text-xs text-[#211612]">Self Pickup</h4>
                                <p class="text-[10px] text-[#6b4d40] mt-0.5">Ambil sendiri ke Base Kitchen Toko</p>
                            </div>
                            <span class="mt-2 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded w-max">Gratis Ongkir</span>
                        </div>

                        <!-- Option B: Pesan Kurir -->
                        <div @click="deliveryMethod = 'courier'"
                             class="p-3.5 rounded-xl border cursor-pointer transition active:scale-98 flex flex-col justify-between"
                             :class="deliveryMethod === 'courier' ? 'bg-[#fbf7ee] border-[#c89e2b] ring-2 ring-[#c89e2b]/40 shadow-sm' : 'bg-[#fffdfa] border-[#ebe0c6] opacity-80'">
                            <div>
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                </div>
                                <h4 class="font-bold text-xs text-[#211612]">Pesan Kurir</h4>
                                <p class="text-[10px] text-[#6b4d40] mt-0.5">Gosend / Grab / Paxel (Instant)</p>
                            </div>
                            <span class="mt-2 text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded w-max">Via Ekspedisi/Ojol</span>
                        </div>
                    </div>

                    <!-- Pickup Address Note -->
                    <div x-show="deliveryMethod === 'pickup'" class="p-3 bg-[#fbf7ee] rounded-xl border border-[#ebe0c6] text-xs text-[#6b4d40] leading-relaxed space-y-1.5">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-[#211612]">Alamat Penjemputan:</p>
                            @if(!empty($settings['store_maps_url']))
                            <a href="{{ $settings['store_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="text-[11px] font-bold text-[#c89e2b] hover:text-[#9e7611] flex items-center space-x-1 hover:underline">
                                <span>Buka Maps 📍</span>
                            </a>
                            @endif
                        </div>
                        <p>{{ $settings['store_address'] }}</p>
                        <p class="text-[10px] text-[#c89e2b]">{{ $settings['pickup_note'] }}</p>
                    </div>

                    <!-- Courier Information Note (Pesan Kurir Mandiri oleh Customer) -->
                    <div x-show="deliveryMethod === 'courier'" class="p-3.5 bg-blue-50/80 rounded-xl border border-blue-200/90 text-xs text-[#211612] space-y-2">
                        <div class="flex items-center space-x-2 text-blue-900 font-bold text-xs">
                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px]">🛵</span>
                            <span>Pemesanan Kurir Mandiri oleh Customer</span>
                        </div>
                        <p class="text-[11px] text-stone-700 leading-relaxed">
                            Pengiriman menggunakan <strong>Gosend / GrabExpress Instant / Paxel</strong>. Pemesanan dan ongkir kurir dilakukan sendiri oleh pembeli sesuai jam yang dipilih.
                        </p>
                        <div class="pt-2 border-t border-blue-200/70 text-[11px] space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-blue-950 block">Titik Penjemputan Driver / Kurir:</span>
                                @if(!empty($settings['store_maps_url']))
                                <a href="{{ $settings['store_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-bold text-blue-700 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-2 py-0.5 rounded-md flex items-center space-x-1 transition">
                                    <span>📍 Buka Titik Maps</span>
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                @endif
                            </div>
                            <p class="text-[#211612] font-semibold">📍Alamat {{ $settings['store_name'] }}</p>
                            <p class="text-stone-600 text-[10px]">{{ $settings['store_address'] }}</p>
                        </div>
                    </div>

                    <!-- Time Slot Selector (Chips) -->
                    <div class="space-y-2 pt-2 border-t border-[#ebe0c6]">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-[#211612]">Pilih Jam Pengambilan / Pengiriman:</label>
                            <span class="text-[10px] text-[#c89e2b] font-medium" x-show="selectedDate === serverToday">Slot Hari Ini (Real-Time)</span>
                        </div>

                        <!-- Notice if all slots today are passed -->
                        <div x-show="!hasAvailableSlots" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 flex items-start space-x-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <div>
                                <p class="font-bold">Semua Jam Pengambilan Hari Ini Telah Terlewat</p>
                                <p class="text-[11px] mt-0.5">Operasional dapur hari ini telah melewati batas jam slot. Silakan pilih tanggal besok atau hari berikutnya pada kalender.</p>
                                <button @click="goToStep(2)" type="button" class="mt-2 inline-block px-3 py-1 bg-rose-600 text-white font-bold text-[10px] rounded-lg shadow-sm">Ubah Tanggal di Kalender</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2" x-show="hasAvailableSlots">
                            <template x-for="slot in timeSlots" :key="slot">
                                <button
                                    @click="if (!isSlotDisabled(slot)) selectedTimeSlot = slot"
                                    type="button"
                                    :disabled="isSlotDisabled(slot)"
                                    class="py-2.5 px-3 rounded-xl text-xs font-semibold border transition text-center flex items-center justify-between"
                                    :class="{
                                        'bg-[#211612] text-[#e2c159] border-[#c89e2b] shadow-sm font-bold': selectedTimeSlot === slot && !isSlotDisabled(slot),
                                        'bg-[#fffdfa] text-[#211612] border-[#ebe0c6] hover:border-[#c89e2b] active:scale-95': selectedTimeSlot !== slot && !isSlotDisabled(slot),
                                        'bg-stone-100 text-stone-400 border-dashed border-stone-300 opacity-60 cursor-not-allowed line-through': isSlotDisabled(slot)
                                    }">
                                    <span x-text="slot"></span>
                                    <template x-if="isSlotDisabled(slot)">
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-stone-200 text-stone-500 font-bold no-underline">Terlewat</span>
                                    </template>
                                    <template x-if="!isSlotDisabled(slot) && selectedTimeSlot === slot">
                                        <svg class="w-3.5 h-3.5 text-[#e2c159]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button @click="goToStep(4)" :disabled="!hasAvailableSlots || isSlotDisabled(selectedTimeSlot)" class="w-full py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow disabled:opacity-50 active:scale-95 transition flex items-center justify-center space-x-1.5">
                            <span>Lanjut Pilih Menu &rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 4: PRODUCT CATALOG (MENU) ================= -->
            <div x-show="step === 4" x-transition.opacity id="catalog-section">
                
                <!-- Delivery & Date Info Pill -->
                <div class="bg-[#211612] text-[#fdfbf7] p-3 rounded-xl mb-3 flex items-center justify-between text-xs shadow-sm">
                    <div>
                        <span class="text-[10px] text-[#c89e2b] font-bold uppercase" x-text="deliveryMethod === 'pickup' ? 'Ambil Sendiri' : 'Kirim Kurir'"></span>
                        <p class="font-semibold text-xs" x-text="formatIndoDate(selectedDate) + ' • ' + selectedTimeSlot"></p>
                    </div>
                    <button @click="goToStep(2)" class="text-[11px] text-[#e2c159] underline hover:text-[#f1db8b]">Ubah</button>
                </div>

                <!-- Sticky Horizontal Category Navigation -->
                <div class="sticky top-[53px] z-30 -mx-4 px-4 py-2 bg-[#f7f3ec]/95 backdrop-blur-md border-b border-[#ebe0c6] mb-3">
                    <div class="flex space-x-2 overflow-x-auto no-scrollbar py-0.5">
                        <button
                            @click="selectCategory('all')"
                            class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition active:scale-95"
                            :class="activeCategory === 'all' ? 'bg-[#211612] text-[#e2c159] shadow-sm' : 'bg-[#fffdfa] text-[#6b4d40] border border-[#ebe0c6] hover:border-[#c89e2b]'">
                            Semua Menu
                        </button>
                        @foreach($categories as $cat)
                        <button
                            @click="selectCategory('{{ $cat->slug }}')"
                            class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition active:scale-95"
                            :class="activeCategory === '{{ $cat->slug }}' ? 'bg-[#211612] text-[#e2c159] shadow-sm' : 'bg-[#fffdfa] text-[#6b4d40] border border-[#ebe0c6] hover:border-[#c89e2b]'">
                            {{ $cat->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Grid List -->
                <div class="space-y-3.5">
                    @foreach($categories as $cat)
                    <div x-show="activeCategory === 'all' || activeCategory === '{{ $cat->slug }}'">
                        
                        <div class="flex items-center space-x-2 my-2">
                            <span class="w-1.5 h-4 bg-[#c89e2b] rounded-full"></span>
                            <h3 class="font-serif-title font-bold text-sm text-[#211612]">{{ $cat->name }}</h3>
                            <span class="text-[10px] text-[#6b4d40]">({{ $cat->activeProducts->count() }} pilihan)</span>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            @foreach($cat->activeProducts as $product)
                            <div class="bg-[#fffdfa] rounded-2xl p-3 border border-[#ebe0c6] shadow-sm flex space-x-3 items-center hover:shadow-md transition">
                                <!-- Product Image & Badge -->
                                <div class="relative w-24 h-24 rounded-xl overflow-hidden bg-[#ebe0c6] flex-shrink-0">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @if($product->badge)
                                    <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded text-[8px] font-bold bg-[#c89e2b] text-[#140d0a] shadow-xs">
                                        {{ $product->badge }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Product Info & Counter -->
                                <div class="flex-1 min-w-0 flex flex-col justify-between h-24">
                                    <div>
                                        <h4 class="font-bold text-xs text-[#211612] line-clamp-1">{{ $product->name }}</h4>
                                        <p class="text-[10px] text-[#6b4d40] line-clamp-2 mt-0.5 leading-snug">{{ $product->description }}</p>
                                    </div>

                                    <div class="flex items-center justify-between mt-2 pt-1 border-t border-[#f5eedc]">
                                        <span class="font-bold text-xs text-[#c89e2b]">{{ $product->formatted_price }}</span>

                                        <!-- Quantity Counter Button (Turns into - / +) -->
                                        <div class="flex items-center">
                                            <!-- Initial Add Button -->
                                            <button
                                                x-show="!cart['{{ $product->id }}']"
                                                @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                                                class="px-3 py-1 bg-[#211612] text-[#e2c159] hover:bg-[#33231c] text-xs font-bold rounded-lg shadow-sm active:scale-95 transition flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                <span>Tambah</span>
                                            </button>

                                            <!-- Counter Buttons -->
                                            <div x-show="cart['{{ $product->id }}']" class="flex items-center bg-[#fbf7ee] border border-[#c89e2b] rounded-lg overflow-hidden shadow-xs">
                                                <button
                                                    @click="decreaseQty({{ $product->id }})"
                                                    class="w-7 h-7 flex items-center justify-center text-[#211612] hover:bg-[#ebe0c6] active:scale-90 font-bold transition">
                                                    -
                                                </button>
                                                <span class="w-6 text-center text-xs font-bold text-[#211612]" x-text="cart['{{ $product->id }}']?.qty || 0"></span>
                                                <button
                                                    @click="increaseQty({{ $product->id }})"
                                                    class="w-7 h-7 flex items-center justify-center bg-[#c89e2b] text-[#140d0a] hover:bg-[#e2c159] active:scale-90 font-bold transition">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </main>

    <!-- ================= FLOATING CART BAR (Always visible when items added) ================= -->
    <div
        x-show="cartTotalCount > 0"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed bottom-0 left-0 right-0 z-50 flex justify-center p-3 pointer-events-none">
        
        <div class="w-full max-w-[460px] bg-[#211612] text-[#fdfbf7] rounded-2xl p-3 shadow-2xl border border-[#c89e2b]/50 pointer-events-auto flex items-center justify-between backdrop-blur-md">
            <div class="flex items-center space-x-3">
                <div class="relative w-10 h-10 rounded-xl bg-[#c89e2b]/20 flex items-center justify-center text-[#e2c159]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-[#c89e2b] text-[#140d0a] font-black text-[10px] flex items-center justify-center" x-text="cartTotalCount"></span>
                </div>
                <div>
                    <span class="text-[10px] text-[#dac9a3] uppercase font-semibold">Total Pesanan</span>
                    <p class="font-bold text-sm text-[#e2c159]" x-text="formatRupiah(cartSubtotal)"></p>
                </div>
            </div>

            <button
                @click="openCheckoutDrawer()"
                class="px-4 py-2.5 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow-md active:scale-95 transition flex items-center space-x-1.5">
                <span>Lihat Keranjang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <!-- ================= MODAL DETAIL KATALOG KATEGORI (PREVIEW TANPA TRANSAKSI) ================= -->
    <div
        x-show="categoryModalOpen"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 pointer-events-auto"
        x-cloak>
        
        <!-- Backdrop -->
        <div
            x-show="categoryModalOpen"
            x-transition.opacity.duration.300ms
            @click="closeCategoryPreview()"
            class="fixed inset-0 bg-black/75 backdrop-blur-xs"></div>

        <!-- Modal Box -->
        <div
            x-show="categoryModalOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full sm:translate-y-4 sm:opacity-0"
            x-transition:enter-end="translate-y-0 sm:translate-y-0 sm:opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0 sm:translate-y-0 sm:opacity-100"
            x-transition:leave-end="translate-y-full sm:translate-y-4 sm:opacity-0"
            class="relative w-full max-w-[480px] max-h-[88vh] sm:max-h-[82vh] bg-[#fffdfa] rounded-t-3xl sm:rounded-3xl shadow-2xl flex flex-col overflow-hidden border border-[#c89e2b]/50">

            <template x-if="selectedCategoryPreview">
                <div class="flex flex-col h-full overflow-hidden">
                    <!-- Banner Image & Header -->
                    <div class="relative w-full h-44 overflow-hidden bg-[#211612] flex-shrink-0">
                        <img :src="selectedCategoryPreview.image" :alt="selectedCategoryPreview.name" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#211612] via-[#211612]/50 to-black/30"></div>
                        
                        <!-- Close Floating Button -->
                        <button
                            @click="closeCategoryPreview()"
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white hover:bg-black/90 flex items-center justify-center transition active:scale-90 z-10 backdrop-blur-xs border border-white/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="absolute bottom-3 left-4 right-4 text-white">
                            <span class="inline-block px-2 py-0.5 text-[9px] uppercase font-bold tracking-wider rounded bg-[#c89e2b] text-[#140d0a] mb-1">Preview Menu Toko</span>
                            <h3 class="font-serif-title text-xl font-bold leading-tight drop-shadow-sm text-[#fffdfa]" x-text="selectedCategoryPreview.name"></h3>
                            <p class="text-[11px] text-[#dac9a3] line-clamp-2 mt-0.5 leading-snug drop-shadow-xs" x-text="selectedCategoryPreview.description"></p>
                        </div>
                    </div>

                    <!-- Scrollable Product List (Showcase Preview without Transaction) -->
                    <div class="p-4 space-y-3 overflow-y-auto flex-1">
                        <div class="flex items-center justify-between pb-1 border-b border-[#ebe0c6]">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#6b4d40]">Daftar Pilihan Menu:</span>
                            <span class="text-[11px] font-semibold text-[#c89e2b] bg-[#c89e2b]/10 px-2 py-0.5 rounded-full" x-text="(selectedCategoryPreview.products ? selectedCategoryPreview.products.length : 0) + ' Varian Menu'"></span>
                        </div>

                        <!-- Product List Items -->
                        <template x-for="prod in selectedCategoryPreview.products" :key="prod.id">
                            <div class="p-3 bg-[#fbf7ee] rounded-2xl border border-[#ebe0c6] flex items-center space-x-3 shadow-xs hover:border-[#c89e2b]/50 transition">
                                <div class="relative w-16 h-16 rounded-xl overflow-hidden bg-[#ebe0c6] flex-shrink-0">
                                    <img :src="prod.image_url" :alt="prod.name" class="w-full h-full object-cover">
                                    <template x-if="prod.badge">
                                        <span class="absolute top-1 left-1 px-1 py-0.2 rounded text-[7px] font-bold bg-[#c89e2b] text-[#140d0a]" x-text="prod.badge"></span>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs text-[#211612] truncate" x-text="prod.name"></h4>
                                    <p class="text-[10px] text-[#6b4d40] line-clamp-2 mt-0.5 leading-tight" x-text="prod.description"></p>
                                    <p class="text-xs font-bold text-[#c89e2b] mt-1" x-text="prod.formatted_price"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedCategoryPreview.products || selectedCategoryPreview.products.length === 0">
                            <div class="text-center py-8 text-stone-400 text-xs">
                                Belum ada menu aktif dalam kategori ini.
                            </div>
                        </template>
                    </div>

                    <!-- Bottom Action Footer with Pesan Sekarang Button -->
                    <div class="p-3.5 bg-[#fffdfa] border-t border-[#ebe0c6] flex items-center space-x-2.5">
                        <button
                            @click="closeCategoryPreview()"
                            class="px-4 py-2.5 rounded-xl border border-[#dac9a3] text-[#6b4d40] font-bold text-xs hover:bg-[#fbf7ee] active:scale-95 transition">
                            Tutup
                        </button>
                        <button
                            @click="orderFromPreview(selectedCategoryPreview.slug)"
                            class="flex-1 py-2.5 px-4 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold text-xs rounded-xl shadow hover:brightness-105 active:scale-95 transition flex items-center justify-center space-x-1.5">
                            <span>Pesan Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ================= CHECKOUT / SUMMARY BOTTOM DRAWER ================= -->
    <div
        x-show="drawerOpen"
        class="fixed inset-0 z-50 flex items-end justify-center pointer-events-auto"
        x-cloak>
        
        <!-- Backdrop -->
        <div
            x-show="drawerOpen"
            x-transition.opacity.duration.300ms
            @click="drawerOpen = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

        <!-- Drawer Panel -->
        <div
            x-show="drawerOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="relative w-full max-w-[480px] max-h-[90vh] bg-[#fffdfa] rounded-t-3xl shadow-2xl flex flex-col overflow-hidden border-t border-[#c89e2b]/50">
            
            <!-- Drawer Header -->
            <div class="px-5 py-4 border-b border-[#ebe0c6] flex items-center justify-between bg-[#fbf7ee]">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-5 bg-[#c89e2b] rounded-full"></span>
                    <h3 class="font-serif-title font-bold text-base text-[#211612]">Ringkasan Pesanan</h3>
                </div>
                <button @click="drawerOpen = false" class="p-1 rounded-full text-[#6b4d40] hover:bg-[#ebe0c6] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Drawer Scrollable Body -->
            <div class="p-5 space-y-4 overflow-y-auto flex-1">
                
                <!-- Delivery & Date Badge -->
                <div class="p-3 bg-[#fbf7ee] rounded-xl border border-[#ebe0c6] text-xs space-y-1">
                    <div class="flex items-center justify-between font-bold text-[#211612]">
                        <span x-text="deliveryMethod === 'pickup' ? 'Self Pickup (Ambil Sendiri)' : 'Pesan Kurir (Instant)'"></span>
                        <span class="text-[#c89e2b]" x-text="formatIndoDate(selectedDate)"></span>
                    </div>
                    <p class="text-[11px] text-[#6b4d40]" x-text="'Slot Waktu: ' + selectedTimeSlot"></p>
                    <p x-show="deliveryMethod === 'courier' && deliveryAddress" class="text-[11px] text-[#6b4d40] truncate" x-text="'Tujuan: ' + deliveryAddress"></p>
                </div>

                <!-- Selected Items List -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#6b4d40] mb-2">Item Terpilih:</h4>
                    <div class="space-y-2">
                        <template x-for="(item, pid) in cart" :key="pid">
                            <div class="flex items-center justify-between p-2.5 bg-[#fdfbf7] rounded-xl border border-[#ebe0c6]">
                                <div>
                                    <h5 class="text-xs font-bold text-[#211612]" x-text="item.name"></h5>
                                    <p class="text-[10px] text-[#c89e2b]" x-text="formatRupiah(item.price) + ' x ' + item.qty"></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-[#211612]" x-text="formatRupiah(item.price * item.qty)"></span>
                                    <div class="flex items-center border border-[#c89e2b] rounded-lg overflow-hidden">
                                        <button @click="decreaseQty(pid)" class="w-5 h-5 flex items-center justify-center text-xs font-bold">-</button>
                                        <span class="w-5 text-center text-[10px] font-bold" x-text="item.qty"></span>
                                        <button @click="increaseQty(pid)" class="w-5 h-5 bg-[#c89e2b] text-[#140d0a] flex items-center justify-center text-xs font-bold">+</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Customer Form -->
                <div class="space-y-3 pt-2 border-t border-[#ebe0c6]">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#6b4d40]">Data Pemesan:</h4>
                    
                    <div>
                        <label class="block text-xs font-bold text-[#211612] mb-1">Nama Lengkap *</label>
                        <input type="text" x-model="customerName" placeholder="Contoh: Sarah Angelina" class="w-full text-xs p-2.5 bg-[#fbf7ee] rounded-xl border border-[#ebe0c6] focus:border-[#c89e2b] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#211612] mb-1">Nomor WhatsApp Aktif *</label>
                        <input type="tel" x-model="customerPhone" placeholder="Contoh: 081234567890" class="w-full text-xs p-2.5 bg-[#fbf7ee] rounded-xl border border-[#ebe0c6] focus:border-[#c89e2b] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#211612] mb-1">Catatan Khusus / Tulisan di Cake (Opsional):</label>
                        <textarea x-model="greetingNotes" rows="2" placeholder="Misal: Tolong tulis 'Happy 24th Birthday Sarah' di atas cake + lilin 2 batang..." class="w-full text-xs p-2.5 bg-[#fbf7ee] rounded-xl border border-[#ebe0c6] focus:border-[#c89e2b] outline-none"></textarea>
                    </div>
                </div>

                <!-- ================= METODE PEMBAYARAN (BAYAR DI AWAL) ================= -->
                <div class="space-y-3 pt-3 border-t border-[#ebe0c6]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2 h-4 bg-[#c89e2b] rounded-full"></span>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#211612]">Metode Pembayaran</h4>
                        </div>
                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 font-bold">Wajib Bayar Dahulu</span>
                    </div>
                    <p class="text-[11px] text-[#6b4d40]">Silakan lakukan pembayaran via QRIS atau Transfer Bank, lalu lampirkan bukti pembayaran di bawah.</p>

                    <!-- Payment Method Switcher Tabs -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Option A: QRIS -->
                        <button
                            type="button"
                            @click="paymentMethod = 'qris'"
                            class="p-2.5 rounded-xl border text-left transition flex items-center space-x-2"
                            :class="paymentMethod === 'qris' ? 'bg-[#fbf7ee] border-[#c89e2b] ring-2 ring-[#c89e2b]/40 shadow-xs' : 'bg-[#fffdfa] border-[#ebe0c6] opacity-80'">
                            <div class="w-8 h-8 rounded-lg bg-[#211612] text-[#e2c159] flex items-center justify-center font-bold text-xs">
                                QR
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-[#211612]">QRIS (Instan)</h5>
                                <span class="text-[9px] text-[#6b4d40]">BCA/GoPay/OVO/Dana</span>
                            </div>
                        </button>

                        <!-- Option B: Transfer Bank -->
                        <button
                            type="button"
                            @click="paymentMethod = 'transfer'"
                            class="p-2.5 rounded-xl border text-left transition flex items-center space-x-2"
                            :class="paymentMethod === 'transfer' ? 'bg-[#fbf7ee] border-[#c89e2b] ring-2 ring-[#c89e2b]/40 shadow-xs' : 'bg-[#fffdfa] border-[#ebe0c6] opacity-80'">
                            <div class="w-8 h-8 rounded-lg bg-blue-900 text-white flex items-center justify-center font-bold text-xs">
                                BCA
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-[#211612]">Transfer Bank</h5>
                                <span class="text-[9px] text-[#6b4d40]">BCA Manual</span>
                            </div>
                        </button>
                    </div>

                    <!-- Tab Content A: QRIS -->
                    <div x-show="paymentMethod === 'qris'" class="p-3.5 bg-[#fbf7ee] rounded-2xl border border-[#c89e2b]/40 text-center space-y-2.5">
                        <div class="flex items-center justify-between text-xs px-1">
                            <span class="font-bold text-[#211612]">Scan Barcode QRIS:</span>
                            <span class="text-[10px] text-emerald-700 bg-emerald-100 font-semibold px-2 py-0.5 rounded-full">Bebas Biaya Admin</span>
                        </div>

                        <!-- QRIS Barcode Box -->
                        <div class="bg-white p-3 rounded-2xl border border-[#ebe0c6] inline-block shadow-sm mx-auto max-w-[280px] w-full">
                            <img src="{{ $settings['qris_image'] }}" alt="QRIS {{ $settings['store_name'] }}" class="w-full h-auto max-h-[320px] mx-auto object-contain rounded-xl">
                            <div class="mt-2 pt-2 border-t border-stone-100 flex items-center justify-between text-[10px]">
                                <span class="font-bold text-[#211612]">BY MAMA OPI KITCHEN</span>
                                <span class="font-mono text-stone-500">ID1024335755285</span>
                            </div>
                        </div>

                        <div class="bg-white/90 p-2 rounded-xl border border-[#ebe0c6] text-xs">
                            <span class="text-[10px] text-[#6b4d40] block">Nominal yang harus dibayar:</span>
                            <span class="text-base font-bold text-[#c89e2b]" x-text="formatRupiah(cartSubtotal)"></span>
                        </div>
                        
                        <p class="text-[10px] text-[#6b4d40] italic leading-relaxed">
                            Buka aplikasi BCA Mobile, GoPay, OVO, Dana, ShopeePay, atau m-Banking Anda, lalu scan QRIS di atas.
                        </p>
                    </div>

                    <!-- Tab Content B: Bank Transfer -->
                    <div x-show="paymentMethod === 'transfer'" class="p-3.5 bg-[#fbf7ee] rounded-2xl border border-[#c89e2b]/40 space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-[#211612]">Rekening Bank Resmi Toko:</span>
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">{{ $settings['bank_name'] }}</span>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-[#ebe0c6] space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-[#6b4d40]">Nomor Rekening:</span>
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-mono font-bold text-sm text-[#211612] tracking-wider">{{ $settings['bank_account_number'] }}</span>
                                    <button
                                        type="button"
                                        @click="copyAccount('{{ $settings['bank_account_number'] }}')"
                                        class="px-2 py-0.5 bg-[#211612] text-[#e2c159] hover:bg-[#33231c] rounded text-[10px] font-bold transition flex items-center space-x-1">
                                        <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#6b4d40]">Atas Nama:</span>
                                <span class="font-semibold text-[#211612]">{{ $settings['bank_account_holder'] }}</span>
                            </div>
                            <div class="flex justify-between pt-1.5 border-t border-[#ebe0c6] font-bold">
                                <span class="text-[#211612]">Nominal Transfer:</span>
                                <span class="text-[#c89e2b] text-sm" x-text="formatRupiah(cartSubtotal)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Payment Proof Field -->
                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-[#211612]">
                                Unggah Bukti Transfer / Screenshot QRIS *:
                            </label>
                            <span class="text-[10px] text-amber-700 font-semibold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">Wajib Lampir</span>
                        </div>

                        <div class="relative">
                            <!-- Hidden File Input -->
                            <input
                                type="file"
                                id="payment_proof_input"
                                accept="image/*"
                                @change="handleProofUpload($event)"
                                class="hidden">

                            <!-- Empty State Dropzone -->
                            <div
                                x-show="!paymentProofPreview"
                                @click="document.getElementById('payment_proof_input').click()"
                                class="border-2 border-dashed border-[#c89e2b]/60 hover:border-[#c89e2b] rounded-2xl p-4 bg-[#fbf7ee] text-center cursor-pointer transition hover:bg-[#f5eedc]/50">
                                <div class="w-10 h-10 rounded-full bg-[#c89e2b]/15 text-[#c89e2b] flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-[#211612]">Pilih / Foto Bukti Pembayaran</p>
                                <p class="text-[10px] text-[#6b4d40] mt-0.5">Format: JPG, PNG, WEBP (Maks. 5MB)</p>
                            </div>

                            <!-- Preview State -->
                            <div x-show="paymentProofPreview" class="flex items-center space-x-3 p-3 bg-emerald-50/80 border border-emerald-300 rounded-2xl">
                                <img :src="paymentProofPreview" alt="Bukti Pembayaran" class="w-14 h-14 rounded-xl object-cover border border-emerald-400">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-1 text-emerald-800 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Bukti Siap Diunggah</span>
                                    </div>
                                    <p class="text-[10px] text-emerald-700 truncate mt-0.5" x-text="paymentProofFileName"></p>
                                    <button
                                        type="button"
                                        @click="removeProof()"
                                        class="text-[10px] text-rose-600 hover:underline font-semibold mt-1">
                                        Ganti File / Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-[#6b4d40] leading-relaxed">Bukti pembayaran akan disimpan di sistem dan diteruskan ke WhatsApp Admin untuk konfirmasi pesanan instan.</p>
                    </div>
                </div>

                <!-- Price Calculation Breakdown -->
                <div class="p-3 bg-[#fbf7ee] rounded-xl space-y-1.5 text-xs border border-[#ebe0c6]">
                    <div class="flex justify-between text-[#6b4d40]">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-[#211612]" x-text="formatRupiah(cartSubtotal)"></span>
                    </div>
                    <div class="flex justify-between text-[#6b4d40]">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-emerald-600" x-text="deliveryMethod === 'pickup' ? 'Gratis (Self Pickup)' : 'Dihitung kurir terpisah'"></span>
                    </div>
                    <div class="pt-2 border-t border-[#ebe0c6] flex justify-between items-center text-sm font-bold text-[#211612]">
                        <span>Total Pembayaran:</span>
                        <span class="text-base text-[#c89e2b]" x-text="formatRupiah(cartSubtotal)"></span>
                    </div>
                </div>

                <!-- Error Notice -->
                <div x-show="errorMessage" class="p-2.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-medium" x-text="errorMessage"></div>

            </div>

            <!-- Drawer Bottom Checkout Button -->
            <div class="p-4 bg-[#fffdfa] border-t border-[#ebe0c6]">
                <button
                    @click="submitOrder()"
                    :disabled="isSubmitting"
                    class="w-full py-3 bg-gradient-to-r from-[#211612] to-[#33231c] text-[#e2c159] border border-[#c89e2b] font-bold text-sm rounded-xl shadow-lg hover:brightness-110 active:scale-95 transition flex items-center justify-center space-x-2 disabled:opacity-50">
                    <template x-if="!isSubmitting">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Konfirmasi & Kirim ke WhatsApp Admin</span>
                        </div>
                    </template>
                    <template x-if="isSubmitting">
                        <div class="flex items-center space-x-2">
                            <svg class="animate-spin w-4 h-4 text-[#e2c159]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Menyimpan Pesanan...</span>
                        </div>
                    </template>
                </button>
            </div>

        </div>
    </div>

</div>

<!-- Alpine Logic -->
<script>
function orderApp() {
    return {
        step: 1,
        activeCategory: 'all',
        cart: {},
        drawerOpen: false,
        categoryModalOpen: false,
        selectedCategoryPreview: null,
        isSubmitting: false,
        errorMessage: '',

        // Calendar state
        currentMonth: new Date().getMonth() + 1,
        currentYear: new Date().getFullYear(),
        calendarMonthName: '',
        calendarStartDay: 0,
        calendarDays: [],
        loadingCalendar: false,
        selectedDate: '',
        serverToday: '{{ $settings['server_today'] }}',

        // Delivery state
        deliveryMethod: 'pickup',
        deliveryAddress: '',
        selectedTimeSlot: '11.00 - 13.00 WIB',
        timeSlots: [
            '09.00 - 11.00 WIB',
            '11.00 - 13.00 WIB',
            '13.00 - 15.00 WIB',
            '15.00 - 17.00 WIB'
        ],

        // Customer info
        customerName: '',
        customerPhone: '',
        greetingNotes: '',

        // Payment info
        paymentMethod: 'qris',
        paymentProofFile: null,
        paymentProofPreview: null,
        paymentProofFileName: '',
        copied: false,

        // Tracking state
        trackOrderCode: '',
        isTracking: false,
        trackErrorMessage: '',

        get stepTitle() {
            switch(this.step) {
                case 2: return 'Pilih Tanggal PO';
                case 3: return 'Metode Pengiriman';
                case 4: return 'Pilih Menu';
                default: return '';
            }
        },

        get cartTotalCount() {
            return Object.values(this.cart).reduce((sum, item) => sum + item.qty, 0);
        },

        get cartSubtotal() {
            return Object.values(this.cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        get hasAvailableSlots() {
            if (this.selectedDate !== this.serverToday) return true;
            return this.timeSlots.some(s => !this.isSlotDisabled(s));
        },

        initApp() {
            this.loadCalendar(this.currentMonth, this.currentYear);
        },

        isSlotDisabled(slot) {
            if (this.selectedDate !== this.serverToday) return false;
            const match = slot.match(/-\s*(\d{1,2})[\.:](\d{2})/);
            if (!match) return false;
            const endHour = parseInt(match[1], 10);
            const endMinute = parseInt(match[2], 10);
            const now = new Date();
            if (now.getHours() > endHour) return true;
            if (now.getHours() === endHour && now.getMinutes() >= endMinute) return true;
            return false;
        },

        checkAndAutoSelectSlot() {
            if (this.isSlotDisabled(this.selectedTimeSlot)) {
                const firstAvail = this.timeSlots.find(s => !this.isSlotDisabled(s));
                if (firstAvail) {
                    this.selectedTimeSlot = firstAvail;
                }
            }
        },

        startOrder() {
            if (this.step === 1) {
                this.goToStep(2);
            }
        },

        goToStep(s) {
            this.step = s;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        prevStep() {
            if (this.step > 1) {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        jumpToMenu() {
            if (!this.selectedDate) {
                const firstAvailable = this.calendarDays.find(d => d.is_selectable);
                if (firstAvailable) {
                    this.selectedDate = firstAvailable.date;
                    this.checkAndAutoSelectSlot();
                }
            }
            this.step = 4;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        selectCategory(slug) {
            this.activeCategory = slug;
        },

        openCategoryPreview(cat) {
            this.selectedCategoryPreview = cat;
            this.categoryModalOpen = true;
        },

        closeCategoryPreview() {
            this.categoryModalOpen = false;
            this.selectedCategoryPreview = null;
        },

        orderFromPreview(slug) {
            this.categoryModalOpen = false;
            this.selectedCategoryPreview = null;
            if (slug) {
                this.selectCategory(slug);
            }
            this.goToStep(2);
        },

        async loadCalendar(month, year) {
            this.loadingCalendar = true;
            try {
                const res = await fetch(`/api/calendar-data?month=${month}&year=${year}`);
                const data = await res.json();
                if (data.status === 'success') {
                    this.calendarMonthName = data.month_name;
                    this.calendarStartDay = data.start_day_of_week;
                    this.calendarDays = data.days;
                    if (data.server_today) {
                        this.serverToday = data.server_today;
                    }

                    // Check if current time has passed all pickup slots today
                    const now = new Date();
                    const allTodayPassed = now.getHours() >= 17;

                    this.calendarDays.forEach(day => {
                        if (day.date === this.serverToday) {
                            if (day.is_too_soon || allTodayPassed) {
                                day.is_selectable = false;
                                day.is_too_soon = true;
                            }
                        }
                    });

                    // If currently selected date is not selectable or empty, pick the first available selectable date
                    const currentSelectedObj = this.calendarDays.find(d => d.date === this.selectedDate);
                    if (!this.selectedDate || (currentSelectedObj && !currentSelectedObj.is_selectable)) {
                        const avail = this.calendarDays.find(d => d.is_selectable);
                        if (avail) {
                            this.selectedDate = avail.date;
                            this.checkAndAutoSelectSlot();
                        } else {
                            this.selectedDate = '';
                        }
                    } else {
                        this.checkAndAutoSelectSlot();
                    }
                }
            } catch (e) {
                console.error('Failed to load calendar', e);
            } finally {
                this.loadingCalendar = false;
            }
        },

        changeMonth(offset) {
            this.currentMonth += offset;
            if (this.currentMonth > 12) {
                this.currentMonth = 1;
                this.currentYear++;
            } else if (this.currentMonth < 1) {
                this.currentMonth = 12;
                this.currentYear--;
            }
            this.loadCalendar(this.currentMonth, this.currentYear);
        },

        selectDate(dayObj) {
            if (dayObj.is_selectable) {
                this.selectedDate = dayObj.date;
                this.checkAndAutoSelectSlot();
            }
        },

        copyAccount(acc) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(acc);
            }
            this.copied = true;
            setTimeout(() => this.copied = false, 2500);
        },

        handleProofUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file bukti pembayaran maksimal 5MB.');
                return;
            }
            this.paymentProofFile = file;
            this.paymentProofFileName = file.name;
            const reader = new FileReader();
            reader.onload = (event) => {
                this.paymentProofPreview = event.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeProof() {
            this.paymentProofFile = null;
            this.paymentProofPreview = null;
            this.paymentProofFileName = '';
            const input = document.getElementById('payment_proof_input');
            if (input) input.value = '';
        },

        addToCart(id, name, price) {
            if (!this.selectedDate) {
                const avail = this.calendarDays.find(d => d.is_selectable);
                if (avail) {
                    this.selectedDate = avail.date;
                    this.checkAndAutoSelectSlot();
                }
            }
            if (!this.cart[id]) {
                this.cart[id] = { id, name, price, qty: 1 };
            } else {
                this.cart[id].qty++;
            }
        },

        increaseQty(id) {
            if (this.cart[id]) {
                this.cart[id].qty++;
            }
        },

        decreaseQty(id) {
            if (this.cart[id]) {
                this.cart[id].qty--;
                if (this.cart[id].qty <= 0) {
                    delete this.cart[id];
                }
            }
        },

        openCheckoutDrawer() {
            if (!this.selectedDate) {
                alert('Silakan tentukan tanggal pengambilan/pengiriman terlebih dahulu.');
                this.goToStep(2);
                return;
            }
            this.errorMessage = '';
            this.drawerOpen = true;
        },

        async submitOrder() {
            if (!this.customerName.trim()) {
                this.errorMessage = 'Mohon isi Nama Lengkap Anda.';
                return;
            }
            if (!this.customerPhone.trim()) {
                this.errorMessage = 'Mohon isi Nomor WhatsApp Anda.';
                return;
            }
            if (this.cartTotalCount === 0) {
                this.errorMessage = 'Keranjang pesanan masih kosong.';
                return;
            }
            if (!this.paymentProofFile) {
                this.errorMessage = 'Mohon unggah Bukti Transfer / Screenshot QRIS Anda terlebih dahulu.';
                return;
            }

            this.isSubmitting = true;
            this.errorMessage = '';

            const itemsPayload = Object.values(this.cart).map(i => ({
                product_id: i.id,
                quantity: i.qty
            }));

            const formData = new FormData();
            formData.append('customer_name', this.customerName);
            formData.append('customer_phone', this.customerPhone);
            formData.append('delivery_method', this.deliveryMethod);
            formData.append('delivery_address', this.deliveryAddress || '');
            formData.append('delivery_date', this.selectedDate);
            formData.append('delivery_time_slot', this.selectedTimeSlot);
            formData.append('greeting_notes', this.greetingNotes || '');
            formData.append('payment_method', this.paymentMethod);
            if (this.paymentProofFile) {
                formData.append('payment_proof', this.paymentProofFile);
            }
            formData.append('items', JSON.stringify(itemsPayload));

            try {
                const res = await fetch('/order/create', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await res.json();
                if (data.status === 'success') {
                    if (data.whatsapp_url) {
                        window.open(data.whatsapp_url, '_blank');
                    }
                    window.location.href = data.redirect_url;
                } else {
                    this.errorMessage = data.message || 'Gagal memproses pesanan. Silakan periksa kembali formulir.';
                    this.isSubmitting = false;
                }
            } catch (err) {
                this.errorMessage = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                this.isSubmitting = false;
            }
        },

        focusTracking() {
            this.goToStep(1);
            this.$nextTick(() => {
                const el = document.getElementById('track_order_input');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.focus();
                }
            });
        },

        async checkOrderStatus() {
            const code = this.trackOrderCode ? this.trackOrderCode.trim() : '';
            if (!code) {
                this.trackErrorMessage = 'Mohon masukkan nomor pesanan Anda.';
                return;
            }
            this.isTracking = true;
            this.trackErrorMessage = '';
            try {
                const tokenEl = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = tokenEl ? tokenEl.getAttribute('content') : '';
                const res = await fetch('/order/track', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ order_code: code })
                });
                const data = await res.json();
                if (data.status === 'success' && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    this.trackErrorMessage = data.message || 'Nomor pesanan tidak ditemukan.';
                    this.isTracking = false;
                }
            } catch (err) {
                this.trackErrorMessage = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                this.isTracking = false;
            }
        },

        formatRupiah(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        },

        formatIndoDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }
    }
}
</script>

</body>
</html>
