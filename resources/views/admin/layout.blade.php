<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - BYMAMAOPI</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-bymamaopi.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-bymamaopi.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#140d0a] text-stone-100 min-h-screen antialiased flex flex-col md:flex-row">

@include('partials.page-loader', ['loaderBrand' => 'BYMAMAOPI', 'loaderTagline' => 'Admin Management Studio'])

    <!-- Mobile Top Header Bar -->
    <div class="md:hidden bg-[#211612] px-4 py-3 border-b border-[#3d2a23] flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-full overflow-hidden bg-black border border-[#c89e2b]/70 flex-shrink-0 p-0.5">
                <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="BYMAMAOPI Logo" class="w-full h-full object-cover rounded-full">
            </div>
            <div>
                <h1 class="font-serif-title font-bold text-sm text-[#e2c159]">BYMAMAOPI</h1>
                <p class="text-[9px] text-[#dac9a3] tracking-widest uppercase">Admin CMS</p>
            </div>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="text-xs text-[#e2c159] bg-[#33231c] px-2.5 py-1 rounded-lg border border-[#4a342b]">Lihat Toko</a>
    </div>

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-[#1a120f] border-r border-[#33231c] flex flex-col md:min-h-screen md:sticky md:top-0">
        
        <!-- Brand Desktop Header -->
        <div class="hidden md:flex items-center space-x-3 p-5 border-b border-[#33231c]">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-black border border-[#c89e2b]/70 flex-shrink-0 shadow-md p-0.5">
                <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="BYMAMAOPI Logo" class="w-full h-full object-cover rounded-full">
            </div>
            <div>
                <h2 class="font-serif-title font-bold text-base text-[#e2c159] tracking-wide">BYMAMAOPI</h2>
                <p class="text-[10px] text-[#dac9a3] tracking-widest uppercase font-semibold">Management Studio</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-3 md:p-4 space-y-1 overflow-x-auto md:overflow-visible flex md:flex-col gap-1 md:gap-0 no-scrollbar">
            
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#c89e2b] text-[#140d0a] font-bold shadow-sm' : 'text-stone-300 hover:bg-[#261914] hover:text-[#e2c159]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.orders.*') ? 'bg-[#c89e2b] text-[#140d0a] font-bold shadow-sm' : 'text-stone-300 hover:bg-[#261914] hover:text-[#e2c159]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Pesanan Masuk</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.products.*') ? 'bg-[#c89e2b] text-[#140d0a] font-bold shadow-sm' : 'text-stone-300 hover:bg-[#261914] hover:text-[#e2c159]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Katalog Produk</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.categories.*') ? 'bg-[#c89e2b] text-[#140d0a] font-bold shadow-sm' : 'text-stone-300 hover:bg-[#261914] hover:text-[#e2c159]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Kategori Menu</span>
            </a>

            <a href="{{ route('admin.quotas.index') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.quotas.*') ? 'bg-[#c89e2b] text-[#140d0a] font-bold shadow-sm' : 'text-stone-300 hover:bg-[#261914] hover:text-[#e2c159]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Kuota & PO Kalender</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#c89e2b] text-[#140d0a] font-bold shadow-sm' : 'text-stone-300 hover:bg-[#261914] hover:text-[#e2c159]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pengaturan Toko</span>
            </a>

        </nav>

        <!-- Bottom User Profile & Logout -->
        <div class="p-3 md:p-4 border-t border-[#33231c] bg-[#140d0a]/60 flex items-center justify-between">
            <div class="hidden md:block">
                <span class="text-xs font-bold text-[#e2c159] block">{{ Auth::user()->name ?? 'Admin' }}</span>
                <span class="text-[10px] text-stone-400 block truncate max-w-[130px]">{{ Auth::user()->email ?? '' }}</span>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-2.5 py-1.5 bg-[#33231c] hover:bg-rose-900/60 text-stone-300 hover:text-rose-200 text-xs rounded-lg transition flex items-center space-x-1" title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="hidden md:inline">Keluar</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Workspace Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        <!-- Desktop Header Bar -->
        <header class="hidden md:flex bg-[#1a120f]/80 backdrop-blur-md px-6 py-3.5 border-b border-[#33231c] items-center justify-between sticky top-0 z-40">
            <div>
                <h2 class="font-serif-title font-bold text-lg text-[#fffdfa]">@yield('header_title', 'Dasbor Manajemen')</h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#261914] text-[#e2c159] border border-[#4a342b] hover:bg-[#33231c] transition flex items-center space-x-1.5">
                    <span>Kunjungi Toko Pelanggan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 md:px-6 pt-4">
            @if(session('success'))
            <div class="p-3 bg-emerald-950/80 border border-emerald-600/40 text-emerald-300 rounded-xl text-xs font-medium flex items-center justify-between shadow-sm mb-3">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div class="p-3 bg-rose-950/80 border border-rose-600/40 text-rose-300 rounded-xl text-xs font-medium flex items-center justify-between shadow-sm mb-3">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">&times;</button>
            </div>
            @endif

            @if($errors->any())
            <div class="p-3 bg-rose-950/80 border border-rose-600/40 text-rose-300 rounded-xl text-xs font-medium space-y-1 mb-3">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Page Specific Content -->
        <main class="flex-1 p-4 md:p-6 pb-16">
            @yield('content')
        </main>

    </div>

</body>
</html>
