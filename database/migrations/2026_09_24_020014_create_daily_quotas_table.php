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
        Schema::create('daily_quotas', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('max_quota')->default(15);
            $table->integer('booked_count')->default(0);
            $table->boolean('is_closed')->default(false);
            $table->string('close_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_quotas');
    }
};
