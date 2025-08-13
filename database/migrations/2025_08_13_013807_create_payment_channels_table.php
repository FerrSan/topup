<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Jika tabel sudah ada, jangan buat lagi (agar idempotent)
        if (! Schema::hasTable('payment_channels')) {
            Schema::create('payment_channels', function (Blueprint $table) {
                $table->id();
                $table->string('provider'); // midtrans, xendit
                $table->string('code')->unique();       // qris, gopay, ovo, bca_va
                $table->string('name');
                $table->string('type');                 // e-wallet, va, qris, credit_card
                $table->string('logo_url')->nullable();
                $table->decimal('fee_flat', 10, 2)->default(0);
                $table->decimal('fee_percent', 5, 2)->default(0);
                $table->decimal('min_amount', 12, 2)->nullable();
                $table->decimal('max_amount', 12, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['provider', 'is_active']);
                $table->index('type');
            });
        } else {
            // Opsional: kalau mau, di sini bisa tambahkan alter kolom/index
            // untuk menyamakan schema dengan definisi di atas, contoh:
            // Schema::table('payment_channels', function (Blueprint $table) { ... });
        }
    }

    public function down(): void
    {
        // Down standar — akan drop saat migrate:rollback
        Schema::dropIfExists('payment_channels');
    }
};
