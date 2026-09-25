<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyQuota;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('items')->latest();

        // Status tab filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Delivery date filter
        if ($request->filled('date')) {
            $query->whereDate('delivery_date', $request->date);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'processing' => Order::where('status', 'processing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(int $id): View
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Pre-generate WhatsApp message to customer
        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62'.substr($cleanPhone, 1);
        }

        $customerWaUrl = "https://wa.me/{$cleanPhone}?text=".rawurlencode(
            "Halo Kak {$order->customer_name}, kami dari Admin *BYMAMAOPI* terkait pesanan #{$order->order_code} (Status: *{$order->status_label}*)..."
        );

        return view('admin.orders.show', compact('order', 'customerWaUrl'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:processing,ready,completed,cancelled'],
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // Sync quota for this delivery date
        $dateStr = $order->delivery_date->toDateString();
        $actualBooked = Order::whereDate('delivery_date', $dateStr)
            ->where('status', '!=', 'cancelled')
            ->count();
        DailyQuota::where('date', $dateStr)->update(['booked_count' => $actualBooked]);

        return back()->with('success', "Status pesanan #{$order->order_code} berhasil diubah menjadi: {$order->status_label}.");
    }

    public function updatePayment(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'payment_status' => ['required', 'in:unpaid,paid'],
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;

        $order->save();

        return back()->with('success', "Status pembayaran pesanan #{$order->order_code} diperbarui.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $deliveryDateStr = $order->delivery_date->toDateString();
        $orderCode = $order->order_code;
        $order->delete();

        // Sync quota for this delivery date
        $actualBooked = Order::whereDate('delivery_date', $deliveryDateStr)
            ->where('status', '!=', 'cancelled')
            ->count();
        DailyQuota::where('date', $deliveryDateStr)->update(['booked_count' => $actualBooked]);

        return redirect()->route('admin.orders.index')->with('success', "Pesanan #{$orderCode} telah dihapus.");
    }
}
