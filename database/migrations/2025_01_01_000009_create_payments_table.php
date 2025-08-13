<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->string('provider');
    $table->string('method')->nullable();
    $table->string('external_id')->nullable();
    $table->string('status')->default('PENDING');
    $table->decimal('amount', 12, 2);
    $table->decimal('fee', 12, 2)->default(0);
    $table->string('currency', 3)->default('IDR');
    $table->string('token')->nullable();
    $table->text('pay_url')->nullable();
    $table->json('raw_payload')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamp('expired_at')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('payment_channels');
    }
};
