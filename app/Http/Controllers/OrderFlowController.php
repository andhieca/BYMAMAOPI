<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DailyQuota;
use App\Models\Order;
use App\Models\Product;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderFlowController extends Controller
{
    public function index(): View
    {
        $categories = Category::with(['activeProducts'])->where('is_active', true)->orderBy('sort_order')->get();
        $featuredProducts = Product::where('is_featured', true)->where('is_available', true)->take(6)->get();

        $settings = [
            'store_name' => StoreSetting::get('store_name', 'BYMAMAOPI'),
            'store_tagline' => StoreSetting::get('store_tagline', 'Artisan Cookies, Cake, Bolu & Brownies'),
            'store_whatsapp' => StoreSetting::get('store_whatsapp', '6281234567890'),
            'store_address' => StoreSetting::get('store_address', 'Jl. Ranca Manyar No. 88, Bandung'),
            'store_maps_url' => StoreSetting::get('store_maps_url') ?: 'https://www.google.com/maps/search/?api=1&query='.urlencode(StoreSetting::get('store_address', 'BYMAMAOPI')),
            'store_city' => StoreSetting::get('store_city', 'Bandung'),
            'store_operating_hours' => StoreSetting::get('store_operating_hours', 'Senin - Minggu: 08.00 - 18.00 WIB'),
            'min_lead_days' => (int) StoreSetting::get('min_order_lead_days', 0),
            'default_daily_quota' => (int) StoreSetting::get('default_daily_quota', 15),
            'bank_name' => StoreSetting::get('bank_name', 'BCA'),
            'bank_account_number' => StoreSetting::get('bank_account_number', '8290123456'),
            'bank_account_holder' => StoreSetting::get('bank_account_holder', 'MAMA OPI BAKERY'),
            'qris_image' => asset(StoreSetting::get('qris_image', 'images/qris-bymamaopi.jpg')),
            'pickup_note' => StoreSetting::get('pickup_note', 'Ambil langsung di Kitchen House kami.'),
            'courier_note' => StoreSetting::get('courier_note', 'Pesan Gosend/GrabExpress/Paxel.'),
            'server_today' => Carbon::today()->toDateString(),
            'server_hour' => (int) Carbon::now()->format('H'),
            'server_minute' => (int) Carbon::now()->format('i'),
        ];

        return view('welcome', compact('categories', 'featuredProducts', 'settings'));
    }

    public function getCalendarData(Request $request): JsonResponse
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $firstDay = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $daysInMonth = $firstDay->daysInMonth;
        $today = Carbon::today();
        $currentHour = (int) Carbon::now()->format('H');

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

        $defaultMax = (int) StoreSetting::get('default_daily_quota', 15);
        $days = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day)->startOfDay();
            $dateString = $date->toDateString();

            $quotaRecord = $quotas->get($dateString);
            $maxQuota = $quotaRecord ? $quotaRecord->max_quota : $defaultMax;
            $bookedCount = (int) ($ordersCountByDate[$dateString] ?? 0);
            $isClosed = $quotaRecord ? $quotaRecord->is_closed : false;
            $closeReason = $quotaRecord?->close_reason;

            // Keep DailyQuota synced with actual orders
            if ($quotaRecord && $quotaRecord->booked_count !== $bookedCount) {
                $quotaRecord->update(['booked_count' => $bookedCount]);
            }

            $isPast = $date->lt($today);
            // If today, check if all pickup slots have ended (last slot ends at 17:00)
            $isToday = $date->isToday();
            $isTooSoon = false;
            if ($isToday && $currentHour >= 17) {
                $isTooSoon = true; // All slots today have passed
            }

            $isFull = $isClosed || ($bookedCount >= $maxQuota);
            $remaining = max(0, $maxQuota - $bookedCount);

            $days[] = [
                'day' => $day,
                'date' => $dateString,
                'day_name' => $date->translatedFormat('l'),
                'day_short' => $date->translatedFormat('D'),
                'is_today' => $isToday,
                'is_past' => $isPast,
                'is_too_soon' => $isTooSoon,
                'is_closed' => $isClosed,
                'is_full' => $isFull,
                'is_selectable' => (! $isPast && ! $isTooSoon && ! $isFull),
                'max_quota' => $maxQuota,
                'booked_count' => $bookedCount,
                'remaining' => $remaining,
                'close_reason' => $closeReason,
            ];
        }

        return response()->json([
            'status' => 'success',
            'month' => $month,
            'year' => $year,
            'month_name' => $firstDay->translatedFormat('F Y'),
            'start_day_of_week' => $firstDay->dayOfWeek, // 0 = Sunday
            'server_today' => $today->toDateString(),
            'server_hour' => $currentHour,
            'days' => $days,
        ]);
    }

    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:25'],
            'delivery_method' => ['required', 'in:pickup,courier'],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'delivery_date' => ['required', 'date_format:Y-m-d'],
            'delivery_time_slot' => ['required', 'string', 'max:50'],
            'payment_method' => ['required', 'in:qris,transfer,whatsapp_manual'],
            'payment_proof' => ['nullable', 'image', 'max:5120'],
            'greeting_notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'string'], // Sent as JSON string via FormData
        ]);

        $items = json_decode($validated['items'], true);
        if (! is_array($items) || empty($items)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang pesanan masih kosong.',
            ], 422);
        }

        $orderDate = Carbon::parse($validated['delivery_date'])->startOfDay();
        $today = Carbon::today();

        if ($orderDate->lt($today)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal pengiriman tidak boleh di masa lampau.',
            ], 422);
        }

        // Validate time slot if order is for TODAY (Hari H)
        if ($orderDate->isToday()) {
            if (Carbon::now()->hour >= 17) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jam operasional & pengambilan pesanan untuk hari ini sudah terlewat. Silakan pilih tanggal besok atau hari berikutnya.',
                ], 422);
            }

            if (preg_match('/-\s*(\d{1,2})[\.:](\d{2})/', $validated['delivery_time_slot'], $matches)) {
                $endHour = (int) $matches[1];
                $endMinute = (int) $matches[2];
                $slotEndTime = Carbon::today()->setTime($endHour, $endMinute);
                if (Carbon::now()->gt($slotEndTime)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Slot jam {$validated['delivery_time_slot']} sudah terlewat untuk pemesanan hari ini. Silakan pilih slot jam yang masih tersedia.",
                    ], 422);
                }
            }
        }

        // Check quota in transaction
        return DB::transaction(function () use ($validated, $orderDate, $items, $request) {
            $defaultMax = (int) StoreSetting::get('default_daily_quota', 15);
            $quota = DailyQuota::firstOrCreate(
                ['date' => $orderDate->toDateString()],
                [
                    'max_quota' => $defaultMax,
                    'booked_count' => 0,
                    'is_closed' => false,
                ]
            );

            if ($quota->is_closed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mohon maaf, dapur kami tutup pada tanggal tersebut: '.($quota->close_reason ?? 'Libur produksi'),
                ], 422);
            }

            $actualBooked = Order::whereDate('delivery_date', $orderDate->toDateString())
                ->where('status', '!=', 'cancelled')
                ->count();

            if ($actualBooked >= $quota->max_quota) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mohon maaf, kuota pemesanan untuk tanggal ini sudah penuh (Maksimal '.$quota->max_quota.' pesanan).',
                ], 422);
            }

            // Calculate subtotal & prepare items
            $subtotal = 0;
            $itemsData = [];

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            $deliveryFee = 0;
            $totalAmount = $subtotal + $deliveryFee;

            $dateCode = Carbon::now()->format('Ymd');
            $uniqueCode = 'BMO-'.$dateCode.'-'.strtoupper(Str::random(4));

            // Handle payment proof upload
            $paymentProofPath = null;
            $paymentStatus = 'paid';

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = 'proof_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('images/payments'), $fileName);
                $paymentProofPath = 'images/payments/'.$fileName;
            }

            $order = Order::create([
                'order_code' => $uniqueCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_method' => $validated['delivery_method'],
                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_date' => $validated['delivery_date'],
                'delivery_time_slot' => $validated['delivery_time_slot'],
                'greeting_notes' => $validated['greeting_notes'] ?? null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'status' => 'processing',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'payment_proof' => $paymentProofPath,
            ]);

            foreach ($itemsData as $itemRow) {
                $order->items()->create($itemRow);
            }

            // Sync booked quota with actual orders count
            $quota->update(['booked_count' => $actualBooked + 1]);

            $whatsappUrl = $order->generateWhatsAppUrl();
            $order->update(['whatsapp_url' => $whatsappUrl]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat!',
                'order_code' => $order->order_code,
                'total_formatted' => $order->formatted_total,
                'whatsapp_url' => $whatsappUrl,
                'redirect_url' => route('order.success', $order->order_code),
            ]);
        });
    }

    public function success(string $orderCode): View
    {
        $order = Order::with('items')->where('order_code', $orderCode)->firstOrFail();
        $settings = [
            'store_name' => StoreSetting::get('store_name', 'BYMAMAOPI'),
            'store_whatsapp' => StoreSetting::get('store_whatsapp', '6281234567890'),
            'store_address' => StoreSetting::get('store_address', 'Jl. Ranca Manyar No. 88, Bandung'),
            'store_maps_url' => StoreSetting::get('store_maps_url') ?: 'https://www.google.com/maps/search/?api=1&query='.urlencode(StoreSetting::get('store_address', 'BYMAMAOPI')),
            'bank_name' => StoreSetting::get('bank_name', 'BCA'),
            'bank_account_number' => StoreSetting::get('bank_account_number', '8290123456'),
            'bank_account_holder' => StoreSetting::get('bank_account_holder', 'MAMA OPI BAKERY'),
            'qris_image' => asset(StoreSetting::get('qris_image', 'images/qris-bymamaopi.jpg')),
        ];

        return view('order-success', compact('order', 'settings'));
    }

    public function trackOrder(Request $request): JsonResponse
    {
        $code = trim(strtoupper($request->input('order_code', '')));
        if (empty($code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan masukkan nomor pesanan Anda.',
            ], 422);
        }

        $order = Order::with('items')->where('order_code', $code)->first();
        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => "Nomor pesanan '{$code}' tidak ditemukan. Mohon pastikan nomor pesanan Anda benar (Contoh: BMO-2026...)",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order_code' => $order->order_code,
            'redirect_url' => route('order.success', $order->order_code),
            'order' => [
                'customer_name' => $order->customer_name,
                'status_label' => $order->status_label,
                'payment_status' => $order->payment_status,
                'delivery_date' => $order->delivery_date->format('d M Y'),
                'delivery_time_slot' => $order->delivery_time_slot,
                'formatted_total' => $order->formatted_total,
            ],
        ]);
    }
}
