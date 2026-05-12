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
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->integer('total_price');
            $table->text('notes')->nullable();
            $table->enum('order_status', ['pending_admin', 'processing', 'delivering', 'completed', 'cancelled'])->default('pending_admin');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'expired'])->default('unpaid');
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('snap_token')->nullable();
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
