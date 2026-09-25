@extends('admin.layout')

@section('title', 'Kalender Kuota & Pre-Order')
@section('header_title', 'Manajemen Kuota Harian & Tanggal Pre-Order')

@section('content')
<div class="space-y-6" x-data="quotaManager()">

    <!-- Month Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
        <div class="flex items-center space-x-3">
            <span class="p-2.5 rounded-xl bg-[#c89e2b]/15 text-[#e2c159]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            <div>
                <h3 class="font-serif-title font-bold text-base text-[#fffdfa]">{{ $currentMonthDate->translatedFormat('F Y') }}</h3>
                <p class="text-[11px] text-[#dac9a3]">Kapasitas Default: <strong>{{ $defaultMax }} pesanan / hari</strong></p>
            </div>
        </div>

        <!-- Month Navigation Buttons -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.quotas.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
               class="px-3 py-1.5 bg-[#261914] hover:bg-[#33231c] text-[#e2c159] border border-[#4a342b] rounded-xl text-xs font-semibold flex items-center space-x-1 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Bulan Sebelumnya</span>
            </a>
            <a href="{{ route('admin.quotas.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
               class="px-3 py-1.5 bg-[#261914] hover:bg-[#33231c] text-[#e2c159] border border-[#4a342b] rounded-xl text-xs font-semibold flex items-center space-x-1 transition">
                <span>Bulan Berikutnya</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- Calendar Grid View -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-4 shadow-sm">
        
        <!-- Day of Week Headers -->
        <div class="grid grid-cols-7 gap-1.5 text-center text-[11px] font-bold text-[#dac9a3] uppercase mb-2">
            <div>Minggu</div><div>Senin</div><div>Selasa</div><div>Rabu</div><div>Kamis</div><div>Jumat</div><div>Sabtu</div>
        </div>

        <!-- Days Grid -->
        <div class="grid grid-cols-7 gap-1.5 text-xs">
            <!-- Empty offset days -->
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="min-h-[100px] rounded-xl bg-[#140d0a]/40 border border-transparent"></div>
            @endfor

            <!-- Month Days -->
            @foreach($calendarDays as $day)
            <div
                @click="openEditModal('{{ $day['date'] }}', {{ $day['max_quota'] }}, {{ $day['is_closed'] ? 'true' : 'false' }}, '{{ addslashes($day['close_reason'] ?? '') }}', {{ $day['booked_count'] }})"
                class="min-h-[100px] p-2 rounded-xl border cursor-pointer transition hover:border-[#c89e2b] flex flex-col justify-between relative group {{ $day['is_today'] ? 'ring-2 ring-[#c89e2b]' : '' }}"
                style="background-color: {{ $day['is_closed'] ? '#281412' : ($day['percent'] >= 100 ? '#261b12' : '#211612') }}; border-color: {{ $day['is_closed'] ? '#5c1d1a' : '#382621' }}">
                
                <div class="flex items-center justify-between">
                    <span class="font-bold text-xs {{ $day['is_today'] ? 'text-[#e2c159]' : 'text-stone-300' }}">
                        {{ $day['day'] }}
                    </span>
                    
                    @if($day['is_closed'])
                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-rose-950 text-rose-300 border border-rose-800">TUTUP</span>
                    @elseif($day['remaining'] <= 0)
                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-amber-950 text-amber-300 border border-amber-800">PENUH</span>
                    @else
                        <span class="text-[9px] font-semibold text-emerald-400">{{ $day['remaining'] }} slot</span>
                    @endif
                </div>

                <!-- Middle Info -->
                <div class="my-1.5 space-y-1">
                    <div class="flex justify-between text-[10px] text-stone-400">
                        <span>Terisi:</span>
                        <span class="font-bold text-white">{{ $day['booked_count'] }} / {{ $day['max_quota'] }}</span>
                    </div>
                    <div class="w-full bg-[#140d0a] h-1.5 rounded-full overflow-hidden">
                        <div class="h-full {{ $day['is_closed'] ? 'bg-rose-500' : ($day['percent'] >= 100 ? 'bg-amber-500' : 'bg-[#c89e2b]') }}" style="width: {{ $day['percent'] }}%"></div>
                    </div>
                </div>

                <!-- Footer Status -->
                <div class="text-[9px] text-[#dac9a3] truncate">
                    @if($day['is_closed'])
                        <span class="text-rose-400 italic">{{ $day['close_reason'] ?? 'Dapur Tutup' }}</span>
                    @else
                        <span class="group-hover:text-[#e2c159] transition">Klik untuk ubah &rarr;</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bulk Closure Tool Box -->
    <div class="bg-[#1a120f] border border-[#33231c] rounded-2xl p-5 shadow-sm">
        <h4 class="font-serif-title font-bold text-sm text-[#fffdfa] mb-2">Tutup / Buka Kuota Rentang Tanggal Sekaligus (Libur / Perbaikan Dapur)</h4>
        
        <form action="{{ route('admin.quotas.bulk') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs items-end">
            @csrf
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Dari Tanggal *</label>
                <input type="date" name="start_date" required class="w-full p-2 bg-[#261914] text-white rounded-xl border border-[#4a342b] outline-none">
            </div>
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Sampai Tanggal *</label>
                <input type="date" name="end_date" required class="w-full p-2 bg-[#261914] text-white rounded-xl border border-[#4a342b] outline-none">
            </div>
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Tindakan *</label>
                <select name="action" class="w-full p-2 bg-[#261914] text-white rounded-xl border border-[#4a342b] outline-none">
                    <option value="close">Tutup Pesanan (Libur)</option>
                    <option value="open">Buka Kembali Pesanan</option>
                    <option value="update_quota">Ubah Kapasitas Kuota</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-stone-300 mb-1">Alasan Penutupan (Jika Tutup)</label>
                <input type="text" name="close_reason" placeholder="Misal: Libur Idul Fitri" class="w-full p-2 bg-[#261914] text-white rounded-xl border border-[#4a342b] outline-none">
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-[#33231c] hover:bg-[#c89e2b] hover:text-[#140d0a] text-[#e2c159] font-bold rounded-xl border border-[#4a342b] transition">
                    Terapkan Rentang
                </button>
            </div>
        </form>
    </div>

    <!-- Edit Modal Dialog for Single Day -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-black/70 backdrop-blur-xs" @click="isModalOpen = false"></div>

        <div class="relative w-full max-w-md bg-[#1a120f] border border-[#4a342b] rounded-3xl p-6 shadow-2xl text-xs space-y-4">
            <div class="flex items-center justify-between border-b border-[#33231c] pb-3">
                <div>
                    <span class="text-[10px] uppercase font-bold text-[#c89e2b]">Pengaturan Kuota Tanggal:</span>
                    <h3 class="font-serif-title font-bold text-base text-white" x-text="activeDate"></h3>
                </div>
                <button @click="isModalOpen = false" class="text-stone-400 hover:text-white">&times;</button>
            </div>

            <form action="{{ route('admin.quotas.update-date') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="date" :value="activeDate">

                <div class="p-3 bg-[#261914] rounded-xl border border-[#382621]">
                    <div class="flex justify-between items-center">
                        <span class="text-stone-300">Pesanan Masuk di Tanggal Ini:</span>
                        <span class="font-bold text-sm text-[#e2c159]" x-text="activeBooked + ' Pesanan'"></span>
                    </div>
                    <div class="mt-2 pt-2 border-t border-[#382621] flex justify-end">
                        <a :href="'{{ route('admin.orders.index') }}?date=' + activeDate" class="text-xs text-[#c89e2b] hover:text-[#e2c159] hover:underline font-semibold flex items-center space-x-1">
                            <span>Buka Daftar Pesanan Tanggal Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-stone-300 mb-1">Maksimal Kuota Masak (Pesanan) *</label>
                    <input type="number" name="max_quota" x-model="activeMaxQuota" min="0" required class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] focus:border-[#c89e2b] outline-none">
                    <p class="text-[10px] text-stone-400 mt-1">Jika terpesan mencapai angka ini, tanggal di kalender pelanggan otomatis tertutup.</p>
                </div>

                <div class="space-y-2 pt-2 border-t border-[#33231c]">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_closed" value="1" x-model="activeIsClosed" class="rounded bg-[#261914] border-[#4a342b] text-[#c89e2b] focus:ring-[#c89e2b]">
                        <span class="font-bold text-rose-300">Tutup Pesanan untuk Tanggal Ini (Dapur Libur / Penuh)</span>
                    </label>

                    <div x-show="activeIsClosed" class="pt-2">
                        <label class="block font-semibold text-stone-300 mb-1">Alasan Penutupan (Tampil ke Pelanggan)</label>
                        <input type="text" name="close_reason" x-model="activeCloseReason" placeholder="Misal: Dapur Tutup / Khusus Pesanan Corporate" class="w-full p-2.5 bg-[#261914] text-white rounded-xl border border-[#4a342b] outline-none">
                    </div>
                </div>

                <div class="pt-3 border-t border-[#33231c] flex justify-end space-x-2">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-[#261914] text-stone-300 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-[#c89e2b] to-[#e2c159] text-[#140d0a] font-bold rounded-xl shadow hover:brightness-105 transition">
                        Simpan Kuota
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function quotaManager() {
    return {
        isModalOpen: false,
        activeDate: '',
        activeMaxQuota: 15,
        activeIsClosed: false,
        activeCloseReason: '',
        activeBooked: 0,

        openEditModal(date, max, isClosed, reason, booked) {
            this.activeDate = date;
            this.activeMaxQuota = max;
            this.activeIsClosed = isClosed;
            this.activeCloseReason = reason;
            this.activeBooked = booked;
            this.isModalOpen = true;
        }
    }
}
</script>
@endsection
