<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - BYMAMAOPI</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-bymamaopi.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-bymamaopi.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#140d0a] text-stone-100 min-h-screen flex items-center justify-center p-4 antialiased">

@include('partials.page-loader', ['loaderBrand' => 'BYMAMAOPI', 'loaderTagline' => 'Portal Masuk Administrator'])

    <div class="w-full max-w-sm bg-[#1a120f] border border-[#382621] rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
        
        <!-- Ambient Gold Glow Accent -->
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-[#c89e2b]/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full overflow-hidden bg-black border border-[#c89e2b]/70 flex items-center justify-center mx-auto shadow-xl shadow-[#c89e2b]/20 mb-3 p-1">
                <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="BYMAMAOPI Logo" class="w-full h-full object-cover rounded-full">
            </div>
            <h1 class="font-serif-title font-bold text-xl text-[#e2c159] tracking-wider">BYMAMAOPI</h1>
            <p class="text-xs text-[#dac9a3] mt-0.5">Admin Management Studio</p>
        </div>

        @if(session('info'))
        <div class="p-3 bg-[#261914] border border-[#4a342b] text-[#dac9a3] rounded-xl text-xs mb-4">
            {{ session('info') }}
        </div>
        @endif

        @if($errors->any())
        <div class="p-3 bg-rose-950/80 border border-rose-600/40 text-rose-300 rounded-xl text-xs mb-4 space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-stone-300 mb-1">Email Administrator</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', 'admin@bymamaopi.com') }}"
                    required
                    class="w-full text-xs p-3 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] focus:ring-1 focus:ring-[#c89e2b] outline-none transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-stone-300 mb-1">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    value="password123"
                    required
                    class="w-full text-xs p-3 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] focus:ring-1 focus:ring-[#c89e2b] outline-none transition">
            </div>

            <div class="flex items-center justify-between text-xs text-stone-400">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-[#261914] border-[#4a342b] text-[#c89e2b] focus:ring-[#c89e2b]">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button
                type="submit"
                class="w-full py-3 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] hover:brightness-110 text-[#140d0a] font-bold text-xs rounded-xl shadow-lg active:scale-95 transition">
                Masuk ke Dasbor
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-[#33231c] text-center">
            <a href="{{ route('home') }}" class="text-xs text-[#dac9a3] hover:text-[#e2c159] transition">
                &larr; Kembali ke Website Utama
            </a>
        </div>

    </div>

</body>
</html>
