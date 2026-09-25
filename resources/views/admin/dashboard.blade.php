@extends('admin.layout')

@section('title', 'Dasbor Manajemen')
@section('header_title', 'Ringkasan & Metrik Bisnis')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        
        <!-- Total Orders -->
        <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#dac9a3] font-medium">Total Pesanan</span>
                <span class="p-1.5 rounded-lg bg-[#c89e2b]/15 text-[#e2c159]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-[#fffdfa]">{{ $stats['total_orders'] }}</span>
                <span class="text-[10px] text-stone-400">semua transaksi</span>
            </div>
        </div>

        <!-- Siap Diambil / Kirim -->
        <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs text-emerald-300 font-medium">Siap Diambil / Kirim</span>
                <span class="p-1.5 rounded-lg bg-emerald-500/15 text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-emerald-400">{{ $stats['ready'] }}</span>
                <span class="text-[10px] text-stone-400">pesanan tuntas dibuat</span>
            </div>
        </div>

        <!-- Sedang Diproses (Dapur) -->
        <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs text-blue-300 font-medium">Sedang Diproses</span>
                <span class="p-1.5 rounded-lg bg-blue-500/15 text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-blue-400">{{ $stats['processing'] }}</span>
                <span class="text-[10px] text-stone-400">sedang dimasak/packing</span>
            </div>
        </div>

        <!-- Total Omset Lunas -->
        <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs text-[#dac9a3] font-medium">Total Omset Lunas</span>
                <span class="p-1.5 rounded-lg bg-emerald-500/15 text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-2">
                <span class="text-xl font-bold text-[#e2c159]">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

    <!-- Middle Section: Today's Quota & Upcoming Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Today Quota Utilization Card -->
        <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-serif-title font-bold text-sm text-[#fffdfa] flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-[#c89e2b]"></span>
                        <span>Kuota Dapur Hari Ini</span>
                    </h3>
                    @if($quotaToday['is_closed'])
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-900/60 text-rose-300 border border-rose-700/50">TUTUP</span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-900/60 text-emerald-300 border border-emerald-700/50">BUKA</span>
                    @endif
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-baseline text-xs text-stone-300">
                        <span>Terpesan: <strong class="text-[#e2c159]">{{ $quotaToday['booked'] }}</strong> pesanan</span>
                        <span>Kapasitas: <strong>{{ $quotaToday['max'] }}</strong> pesanan</span>
                    </div>

                    <!-- Progress bar -->
                    <div class="w-full bg-[#261914] h-2.5 rounded-full overflow-hidden border border-[#382621]">
                        <div class="h-full bg-gradient-to-r from-[#c89e2b] to-[#e2c159] transition-all" style="width: {{ $quotaToday['percent'] }}%"></div>
                    </div>

                    <div class="p-3 bg-[#261914] rounded-xl border border-[#382621] text-xs flex justify-between items-center">
                        <span class="text-stone-300">Sisa Slot Tersedia:</span>
                        <span class="font-bold text-base text-[#e2c159]">{{ $quotaToday['remaining'] }} Slot</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#33231c]">
                <a href="{{ route('admin.quotas.index') }}" class="w-full py-2 bg-[#261914] hover:bg-[#33231c] text-[#e2c159] border border-[#4a342b] rounded-xl text-xs font-semibold flex items-center justify-center space-x-1.5 transition">
                    <span>Buka Kalender Kuota & PO &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Upcoming Deliveries / Kitchen Queue (2 Columns on Desktop) -->
        <div class="lg:col-span-2 bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-serif-title font-bold text-sm text-[#fffdfa]">Jadwal Pengiriman / Pengambilan Terdekat</h3>
                    <p class="text-[11px] text-[#dac9a3]">Pesanan aktif untuk hari ini dan 3 hari ke depan</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#e2c159] hover:underline">Semua Pesanan &rarr;</a>
            </div>

            @if($upcomingOrders->isEmpty())
                <div class="py-8 text-center text-xs text-stone-400">
                    Belum ada antrean pesanan dalam 3 hari ke depan.
                </div>
            @else
                <div class="space-y-2.5">
                    @foreach($upcomingOrders as $order)
                    <div class="p-3 bg-[#261914] rounded-xl border border-[#382621] flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:border-[#4a342b] transition">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-mono font-bold text-xs text-[#e2c159]">{{ $order->order_code }}</span>
                                <span class="text-xs font-bold text-[#fffdfa]">{{ $order->customer_name }}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded font-semibold {{ $order->delivery_method === 'pickup' ? 'bg-[#c89e2b]/20 text-[#e2c159]' : 'bg-blue-900/40 text-blue-300' }}">
                                    {{ $order->delivery_method === 'pickup' ? 'Pickup' : 'Kurir' }}
                                </span>
                            </div>
                            <div class="text-[11px] text-stone-300 mt-1 flex flex-wrap gap-x-3">
                                <span>🗓️ {{ $order->delivery_date->format('d M Y') }} ({{ $order->delivery_time_slot }})</span>
                                <span class="text-[#e2c159] font-semibold">{{ $order->formatted_total }}</span>
                            </div>
                            @if($order->greeting_notes)
                            <p class="text-[10px] text-[#dac9a3] italic mt-0.5">"{{ Str::limit($order->greeting_notes, 60) }}"</p>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2 self-end sm:self-center">
                            <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 bg-[#33231c] hover:bg-[#4a342b] text-[#e2c159] text-xs font-semibold rounded-lg transition">
                                Kelola
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Bottom Section: Recent Orders Table -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-serif-title font-bold text-sm text-[#fffdfa]">Pesanan Terbaru Masuk</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#e2c159] hover:underline">Lihat Semua Data &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-[10px] text-[#dac9a3] uppercase border-b border-[#33231c] bg-[#140d0a]/40">
                    <tr>
                        <th class="py-2.5 px-3">Kode Order</th>
                        <th class="py-2.5 px-3">Pelanggan</th>
                        <th class="py-2.5 px-3">Tanggal PO</th>
                        <th class="py-2.5 px-3">Total</th>
                        <th class="py-2.5 px-3">Status Pesanan</th>
                        <th class="py-2.5 px-3">Pembayaran</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#33231c] text-stone-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-[#261914] transition">
                        <td class="py-3 px-3 font-mono font-bold text-[#e2c159]">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                {{ $order->order_code }}
                            </a>
                        </td>
                        <td class="py-3 px-3">
                            <span class="font-semibold block text-[#fffdfa]">{{ $order->customer_name }}</span>
                            <span class="text-[10px] text-stone-400">{{ $order->customer_phone }}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span>{{ $order->delivery_date->format('d M Y') }}</span>
                            <span class="block text-[10px] text-stone-400">{{ $order->delivery_time_slot }}</span>
                        </td>
                        <td class="py-3 px-3 font-bold text-[#e2c159]">
                            {{ $order->formatted_total }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="text-[10px] font-semibold text-stone-300 block">{{ $order->payment_method_label }}</span>
                            <span class="text-[9px] text-emerald-400 font-bold">✓ Lunas di Awal</span>
                        </td>
                        <td class="py-3 px-3 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 bg-[#33231c] hover:bg-[#c89e2b] hover:text-[#140d0a] text-[#e2c159] rounded-lg font-semibold text-[11px] transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-stone-400">Belum ada transaksi pesanan tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
