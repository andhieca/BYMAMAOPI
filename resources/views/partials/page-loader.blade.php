<!-- ================= LUXURY PAGE LOADER ================= -->
<div id="page-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#140d0a] text-stone-100 transition-opacity duration-500 ease-out pointer-events-auto">
    
    <!-- Ambient Gold Glow Aura -->
    <div class="absolute w-80 h-80 rounded-full bg-[#c89e2b]/15 blur-3xl pointer-events-none animate-pulse"></div>

    <div class="relative flex flex-col items-center select-none">
        
        <!-- Rotating Luxury Rings & Official Logo -->
        <div class="relative w-24 h-24 mb-4 flex items-center justify-center">
            <!-- Outer Gold Spinning Ring -->
            <div class="absolute inset-0 rounded-full border-2 border-transparent border-t-[#e2c159] border-r-[#c89e2b] animate-spin" style="animation-duration: 1.2s;"></div>
            
            <!-- Secondary Dashed Counter-Rotating Ring -->
            <div class="absolute inset-1 rounded-full border border-dashed border-[#856214] animate-spin" style="animation-duration: 3.5s; animation-direction: reverse;"></div>

            <!-- Central Official Logo Badge -->
            <div class="w-16 h-16 rounded-full overflow-hidden bg-black border border-[#c89e2b]/70 flex items-center justify-center shadow-xl shadow-[#c89e2b]/30 p-1">
                <img src="{{ asset('images/logo-bymamaopi.jpg') }}" alt="{{ $loaderBrand ?? 'BYMAMAOPI' }}" class="w-full h-full object-cover rounded-full">
            </div>
        </div>

        <!-- Brand Name with Golden Shimmer -->
        <h2 class="font-serif-title text-base sm:text-lg font-bold tracking-[0.25em] text-[#e2c159] uppercase drop-shadow-md">
            {{ $loaderBrand ?? 'BYMAMAOPI' }}
        </h2>
        <p class="text-[10px] tracking-[0.2em] uppercase text-[#dac9a3] mt-1 font-medium opacity-85">
            {{ $loaderTagline ?? 'Artisan Cookies & Bakery' }}
        </p>

        <!-- Micro Animated Progress Line -->
        <div class="w-36 h-0.5 bg-[#33231c] rounded-full overflow-hidden mt-4 relative">
            <div class="h-full bg-gradient-to-r from-transparent via-[#e2c159] to-transparent w-full animate-loader-progress"></div>
        </div>

        <span class="text-[9px] text-[#dac9a3]/70 tracking-wider mt-2.5 font-light">
            Menyiapkan Cita Rasa Terbaik...
        </span>

    </div>
</div>

<script>
(function() {
    const loader = document.getElementById('page-loader');
    if (!loader) return;

    function hidePageLoader() {
        if (!loader) return;
        loader.style.opacity = '0';
        loader.style.pointerEvents = 'none';
        setTimeout(() => {
            if (loader) loader.style.display = 'none';
        }, 550);
    }

    if (document.readyState === 'complete') {
        setTimeout(hidePageLoader, 350);
    } else {
        window.addEventListener('load', () => setTimeout(hidePageLoader, 300));
        // Fallback max wait
        setTimeout(hidePageLoader, 1400);
    }
})();
</script>
