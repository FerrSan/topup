<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('game_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('name');                // "60 Diamonds", "Weekly Pass"
            $table->string('nominal_code');        // vendor code
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable(); // modal/cost price
            $table->string('currency', 3)->default('IDR');
            $table->boolean('is_hot')->default(false);
            $table->boolean('is_promo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('process_time')->nullable(); // "Instant", "1-5 Minutes"
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['game_id', 'is_active']);
            $table->index('nominal_code');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_products');
    }
};
