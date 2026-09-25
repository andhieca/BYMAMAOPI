<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyQuota;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $todayStr = $today->toDateString();

        $stats = [
            'total_orders' => Order::count(),
            'pending_payment' => Order::where('status', 'pending_payment')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_products' => Product::count(),
            'active_products' => Product::where('is_available', true)->count(),
        ];

        // Today quota status
        $todayQuota = DailyQuota::where('date', $todayStr)->first();
        $todayMax = $todayQuota ? $todayQuota->max_quota : 15;
        $todayBooked = $todayQuota ? $todayQuota->booked_count : 0;
        $todayRemaining = max(0, $todayMax - $todayBooked);
        $todayIsClosed = $todayQuota ? $todayQuota->is_closed : false;

        $quotaToday = [
            'max' => $todayMax,
            'booked' => $todayBooked,
            'remaining' => $todayRemaining,
            'is_closed' => $todayIsClosed,
            'percent' => $todayMax > 0 ? min(100, round(($todayBooked / $todayMax) * 100)) : 0,
        ];

        // Upcoming delivery orders (today and next 3 days)
        $upcomingOrders = Order::with('items')
            ->whereDate('delivery_date', '>=', $today)
            ->whereDate('delivery_date', '<=', $today->copy()->addDays(3))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('delivery_date')
            ->orderBy('delivery_time_slot')
            ->take(6)
            ->get();

        // Recent orders
        $recentOrders = Order::with('items')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'quotaToday', 'upcomingOrders', 'recentOrders'));
    }
}
