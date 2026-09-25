<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'delivery_date' => 'date:Y-m-d',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format((float) $this->total_amount, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp '.number_format((float) $this->subtotal, 0, ',', '.');
    }

    public function getFormattedDeliveryFeeAttribute(): string
    {
        return 'Rp '.number_format((float) $this->delivery_fee, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'Sedang Diproses (Dapur Memasak)',
            'ready' => $this->delivery_method === 'pickup' ? 'Siap Diambil' : 'Siap Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Sedang Diproses',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
            'ready' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'completed' => 'bg-neutral-100 text-neutral-800 border-neutral-300',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-stone-100 text-stone-800 border-stone-300',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS Instant',
            'transfer' => 'Transfer Bank ('.StoreSetting::get('bank_name', 'BCA').')',
            default => 'Transfer / QRIS',
        };
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if ($this->payment_proof && file_exists(public_path($this->payment_proof))) {
            return asset($this->payment_proof);
        }

        return null;
    }

    public function generateWhatsAppUrl(): string
    {
        $adminPhone = StoreSetting::get('store_whatsapp', '6281234567890');
        // Clean phone number (replace 08 with 628, remove non-digits)
        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62'.substr($cleanPhone, 1);
        }

        $lines = [];
        $lines[] = '✨ *PESANAN BARU - BYMAMAOPI* ✨';
        $lines[] = '────────────────────────';
        $lines[] = "🔖 *No. Pesanan:* {$this->order_code}";
        $lines[] = "👤 *Nama Pelanggan:* {$this->customer_name}";
        $lines[] = "📱 *No. WhatsApp:* {$this->customer_phone}";
        $lines[] = '🗓️ *Tanggal:* '.$this->delivery_date->format('d M Y');
        $lines[] = "⏰ *Jam:* {$this->delivery_time_slot}";
        $lines[] = '🛵 *Metode:* '.($this->delivery_method === 'pickup' ? 'Self Pickup (Ambil Sendiri)' : 'Pesan Kurir / Delivery');

        if ($this->delivery_method === 'courier' && $this->delivery_address) {
            $lines[] = "📍 *Alamat:* {$this->delivery_address}";
        }

        if ($this->greeting_notes) {
            $lines[] = "💌 *Catatan / Ucapan:* \"{$this->greeting_notes}\"";
        }

        $lines[] = '────────────────────────';
        $lines[] = '📋 *DETAIL MENU:*';
        foreach ($this->items as $item) {
            $formattedPrice = 'Rp '.number_format((float) $item->subtotal, 0, ',', '.');
            $lines[] = "• {$item->product_name} x{$item->quantity} = {$formattedPrice}";
        }

        $lines[] = '────────────────────────';
        $lines[] = '💰 *Subtotal:* '.$this->formatted_subtotal;
        if ($this->delivery_fee > 0) {
            $lines[] = '🚚 *Ongkir:* '.$this->formatted_delivery_fee;
        }
        $lines[] = "⭐ *TOTAL TAGIHAN:* *{$this->formatted_total}*";
        $lines[] = '────────────────────────';
        $lines[] = "💳 *Metode Pembayaran:* {$this->payment_method_label}";
        $lines[] = '💵 *Status Pembayaran:* LUNAS (Wajib di Awal)';
        if ($this->payment_proof) {
            $lines[] = '📎 *Bukti Pembayaran:* Sudah diunggah di sistem';
        }
        $lines[] = '────────────────────────';
        $lines[] = 'Halo Admin BYMAMAOPI, saya telah melakukan pemesanan dan pembayaran di website. Mohon dicek dan diproses ya. Terima kasih banyak! 🙏';

        $message = implode("\n", $lines);

        return "https://wa.me/{$cleanPhone}?text=".rawurlencode($message);
    }
}
