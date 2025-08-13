<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('product_id')->references('id')->on('game_products');
            $table->foreignId('coupon_id')->nullable()->constrained();
            $table->integer('qty')->default(1);
            $table->text('buyer_note')->nullable();

            // Player info
            $table->string('player_uid');
            $table->string('player_server')->nullable();
            $table->string('player_name')->nullable();

            // Pricing
            $table->decimal('price', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);

            // Status
            $table->enum('status', [
                'PENDING','WAITING_PAYMENT','PAID','PROCESSING',
                'SUCCESS','FAILED','REFUNDED','EXPIRED','CANCELLED'
            ])->default('PENDING');

            // Payment info
            $table->string('payment_provider')->nullable(); // midtrans, xendit
            $table->string('payment_method')->nullable();   // qris, gopay, ovo, va
            $table->string('payment_ref')->nullable();      // reference from provider
            $table->string('payment_token')->nullable();    // snap token
            $table->text('payment_url')->nullable();        // redirect url
            $table->json('payment_data')->nullable();       // raw response

            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            // Tracking
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();

            // Additional
            $table->json('metadata')->nullable();
            $table->json('vendor_response')->nullable();
            $table->integer('retry_count')->default(0);
            $table->string('idempotency_key')->nullable();

            $table->timestamps();

            $table->index('invoice_no');
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index('payment_ref');
            $table->index('idempotency_key');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
