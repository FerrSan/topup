<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            // relasi ke order & (opsional) payment
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('amount', 12, 2);
            $table->string('reason')->nullable();

            // status (sinkron dengan gateway bila mendukung)
            $table->enum('status', [
                'REQUESTED',   // diminta admin/system
                'PROCESSING',  // sedang diproses gateway
                'SUCCESS',     // refund berhasil
                'FAILED'       // refund gagal
            ])->default('REQUESTED');

            $table->string('provider')->nullable();    // midtrans/xendit/tripay
            $table->string('provider_ref')->nullable(); // id refund di gateway
            $table->json('provider_payload')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index('payment_id');
            $table->index('provider_ref');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
