<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = StoreSetting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_tagline' => ['nullable', 'string', 'max:255'],
            'store_whatsapp' => ['required', 'string', 'max:30'],
            'store_address' => ['required', 'string', 'max:500'],
            'store_maps_url' => ['nullable', 'string', 'max:500'],
            'store_city' => ['nullable', 'string', 'max:100'],
            'store_operating_hours' => ['nullable', 'string', 'max:255'],
            'default_daily_quota' => ['required', 'integer', 'min:1', 'max:500'],
            'min_order_lead_days' => ['required', 'integer', 'min:0', 'max:30'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_holder' => ['nullable', 'string', 'max:100'],
            'pickup_note' => ['nullable', 'string'],
            'courier_note' => ['nullable', 'string'],
            'qris_image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('qris_image')) {
            $file = $request->file('qris_image');
            $fileName = 'qris_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/settings'), $fileName);
            StoreSetting::set('qris_image', 'images/settings/'.$fileName, 'Gambar Barcode QRIS Toko', 'payment');
        }

        unset($validated['qris_image']);

        foreach ($validated as $key => $value) {
            StoreSetting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}
