<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyQuota;
use App\Models\Order;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuotaController extends Controller
{
    public function index(Request $request): View
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $currentMonthDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $daysInMonth = $currentMonthDate->daysInMonth;
        $startDayOfWeek = $currentMonthDate->dayOfWeek; // 0 = Sunday

        $defaultMax = (int) StoreSetting::get('default_daily_quota', 15);

        // Fetch quotas for this month
        $quotas = DailyQuota::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(fn ($q) => Carbon::parse($q->date)->format('Y-m-d'));

        // Count actual non-cancelled orders for each date in this month
        $ordersCountByDate = Order::whereYear('delivery_date', $year)
            ->whereMonth('delivery_date', $month)
            ->where('status', '!=', 'cancelled')
            ->groupBy('delivery_date')
            ->selectRaw('DATE(delivery_date) as order_date, count(*) as total_orders')
            ->pluck('total_orders', 'order_date');

        $today = Carbon::today();
        $calendarDays = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day)->startOfDay();
            $dateStr = $date->toDateString();

            $record = $quotas->get($dateStr);
            $maxQuota = $record ? $record->max_quota : $defaultMax;
            $bookedCount = (int) ($ordersCountByDate[$dateStr] ?? 0);
            $isClosed = $record ? $record->is_closed : false;
            $closeReason = $record?->close_reason;
            $remaining = max(0, $maxQuota - $bookedCount);

            // Keep DailyQuota synced with actual orders
            if ($record && $record->booked_count !== $bookedCount) {
                $record->update(['booked_count' => $bookedCount]);
            }

            $calendarDays[] = [
                'day' => $day,
                'date' => $dateStr,
                'is_today' => $date->isToday(),
                'is_past' => $date->lt($today),
                'max_quota' => $maxQuota,
                'booked_count' => $bookedCount,
                'remaining' => $remaining,
                'is_closed' => $isClosed,
                'close_reason' => $closeReason,
                'percent' => $maxQuota > 0 ? min(100, round(($bookedCount / $maxQuota) * 100)) : 0,
            ];
        }

        // Navigation dates
        $prevMonth = $currentMonthDate->copy()->subMonth();
        $nextMonth = $currentMonthDate->copy()->addMonth();

        return view('admin.quotas.index', compact(
            'calendarDays',
            'currentMonthDate',
            'startDayOfWeek',
            'defaultMax',
            'prevMonth',
            'nextMonth'
        ));
    }

    public function updateDate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'max_quota' => ['required', 'integer', 'min:0', 'max:500'],
            'is_closed' => ['nullable', 'boolean'],
            'close_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $isClosed = $request->has('is_closed');

        DailyQuota::updateOrCreate(
            ['date' => $validated['date']],
            [
                'max_quota' => $validated['max_quota'],
                'is_closed' => $isClosed,
                'close_reason' => $isClosed ? ($validated['close_reason'] ?? 'Pesanan ditutup oleh admin') : null,
            ]
        );

        return back()->with('success', "Pengaturan kuota tanggal {$validated['date']} berhasil disimpan.");
    }

    public function bulkClose(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'action' => ['required', 'in:close,open,update_quota'],
            'max_quota' => ['nullable', 'integer', 'min:0'],
            'close_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $period = CarbonPeriod::create($validated['start_date'], $validated['end_date']);
        $defaultMax = (int) StoreSetting::get('default_daily_quota', 15);

        foreach ($period as $date) {
            $dateStr = $date->toDateString();
            $existing = DailyQuota::where('date', $dateStr)->first();
            $max = $validated['max_quota'] ?? ($existing ? $existing->max_quota : $defaultMax);

            $updateData = ['max_quota' => $max];

            if ($validated['action'] === 'close') {
                $updateData['is_closed'] = true;
                $updateData['close_reason'] = $validated['close_reason'] ?? 'Libur produksi';
            } elseif ($validated['action'] === 'open') {
                $updateData['is_closed'] = false;
                $updateData['close_reason'] = null;
            }

            DailyQuota::updateOrCreate(['date' => $dateStr], $updateData);
        }

        return back()->with('success', 'Pembaruan kuota rentang tanggal berhasil diterapkan!');
    }
}
