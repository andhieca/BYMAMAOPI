<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('delivery_method', ['pickup', 'courier'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->date('delivery_date');
            $table->string('delivery_time_slot');
            $table->text('greeting_notes')->nullable(); // Misal: Tulisan di cake atau lilin
            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('pending_payment'); // pending_payment, processing, ready, completed, cancelled
            $table->string('payment_method')->default('whatsapp_manual'); // whatsapp_manual, qris, transfer
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->text('admin_notes')->nullable();
            $table->text('whatsapp_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
