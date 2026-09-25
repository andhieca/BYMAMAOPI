@extends('admin.layout')

@section('title', 'Manajemen Pesanan')
@section('header_title', 'Daftar Semua Pesanan Masuk')

@section('content')
<div class="space-y-5">

    <!-- Status Tabs Nav -->
    <div class="flex items-center space-x-1.5 overflow-x-auto no-scrollbar pb-1">
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ request('status', 'all') === 'all' ? 'bg-[#c89e2b] text-[#140d0a]' : 'bg-[#1a120f] border border-[#33231c] text-stone-300 hover:text-white' }}">
            Semua ({{ $counts['all'] }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => 'processing'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ request('status') === 'processing' ? 'bg-blue-400 text-blue-950' : 'bg-[#1a120f] border border-[#33231c] text-blue-400 hover:bg-[#261914]' }}">
            Sedang Diproses ({{ $counts['processing'] }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => 'ready'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ request('status') === 'ready' ? 'bg-emerald-400 text-emerald-950' : 'bg-[#1a120f] border border-[#33231c] text-emerald-400 hover:bg-[#261914]' }}">
            Siap Diambil/Kirim ({{ $counts['ready'] }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ request('status') === 'completed' ? 'bg-stone-300 text-stone-900' : 'bg-[#1a120f] border border-[#33231c] text-stone-300 hover:bg-[#261914]' }}">
            Selesai ({{ $counts['completed'] }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => 'cancelled'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition {{ request('status') === 'cancelled' ? 'bg-rose-400 text-rose-950' : 'bg-[#1a120f] border border-[#33231c] text-rose-400 hover:bg-[#261914]' }}">
            Dibatalkan ({{ $counts['cancelled'] }})
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="md:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kode order, nama pemesan, no WhatsApp..."
                    class="w-full text-xs p-2.5 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
            </div>

            <div>
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="w-full text-xs p-2.5 bg-[#261914] text-stone-100 rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2 bg-[#c89e2b] text-[#140d0a] font-bold text-xs rounded-xl hover:brightness-105 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date']))
                <a href="{{ route('admin.orders.index', ['status' => request('status', 'all')]) }}" class="px-3 py-2 bg-[#33231c] text-stone-300 text-xs font-semibold rounded-xl hover:bg-[#4a342b] transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table List -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-[10px] text-[#dac9a3] uppercase border-b border-[#33231c] bg-[#140d0a]/60">
                    <tr>
                        <th class="py-3 px-3.5">Kode Order</th>
                        <th class="py-3 px-3.5">Pelanggan & Kontak</th>
                        <th class="py-3 px-3.5">Jadwal PO</th>
                        <th class="py-3 px-3.5">Metode</th>
                        <th class="py-3 px-3.5">Menu / Items</th>
                        <th class="py-3 px-3.5">Total Biaya</th>
                        <th class="py-3 px-3.5">Status Pesanan</th>
                        <th class="py-3 px-3.5">Metode Bayar</th>
                        <th class="py-3 px-3.5 text-right">Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#33231c] text-stone-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-[#261914] transition">
                        <td class="py-3 px-3.5 font-mono font-bold text-[#e2c159]">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                {{ $order->order_code }}
                            </a>
                            <span class="block text-[9px] text-stone-400 font-normal font-sans">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="font-bold block text-white">{{ $order->customer_name }}</span>
                            <span class="text-[10px] text-stone-400">{{ $order->customer_phone }}</span>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="font-semibold">{{ $order->delivery_date->format('d M Y') }}</span>
                            <span class="block text-[10px] text-[#dac9a3]">{{ $order->delivery_time_slot }}</span>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="text-[10px] px-2 py-0.5 rounded font-semibold {{ $order->delivery_method === 'pickup' ? 'bg-[#c89e2b]/20 text-[#e2c159]' : 'bg-blue-900/40 text-blue-300' }}">
                                {{ $order->delivery_method === 'pickup' ? 'Self Pickup' : 'Kurir' }}
                            </span>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="font-medium text-stone-300">{{ $order->items->count() }} item</span>
                            <span class="block text-[10px] text-stone-400 line-clamp-1 max-w-[150px]">
                                {{ $order->items->pluck('product_name')->implode(', ') }}
                            </span>
                        </td>
                        <td class="py-3 px-3.5 font-bold text-[#e2c159]">
                            {{ $order->formatted_total }}
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="py-3 px-3.5">
                            <span class="text-[10px] font-semibold text-stone-300 block">{{ $order->payment_method_label }}</span>
                            <span class="text-[9px] text-emerald-400 font-bold">✓ Lunas di Awal</span>
                        </td>
                        <td class="py-3 px-3.5 text-right space-x-1">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2.5 py-1 bg-[#33231c] hover:bg-[#c89e2b] hover:text-[#140d0a] text-[#e2c159] rounded-lg font-bold text-[11px] transition">
                                Buka
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-stone-400">
                            Tidak ditemukan data pesanan yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-[#33231c] bg-[#140d0a]/40">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
