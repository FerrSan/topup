<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('price_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_product_id')->constrained('game_products')->cascadeOnDelete();

            // harga modal dari provider (sinkron dari Digiflazz/APIGAMES)
            $table->decimal('cost_price', 12, 2)->nullable();

            // aturan markup
            $table->decimal('markup_flat', 12, 2)->default(0);
            $table->decimal('markup_percent', 6, 3)->default(0); // 0–100.000 (max 100%)

            // harga final yang dijual ke user (boleh di-cache di sini)
            $table->decimal('final_price', 12, 2)->nullable();

            // kontrol & periode
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            // info provider / sku mapping (opsional)
            $table->string('provider')->nullable();      // digiflazz, apigames
            $table->string('provider_sku')->nullable();  // buyer_sku_code, dsb
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['game_product_id', 'is_active']);
            $table->index(['start_at', 'end_at']);
            $table->index('provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_tiers');
    }
};
