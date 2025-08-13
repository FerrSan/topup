<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('game_product_id')->nullable()->constrained('game_products')->cascadeOnDelete();

            // field dinamis untuk form checkout
            $table->string('key');                 // player_uid, server, zone, etc
            $table->string('label');               // "Player ID", "Server", dst
            $table->string('type')->default('text'); // text, select, number
            $table->boolean('is_required')->default(true);
            $table->json('options')->nullable();   // untuk select (array)
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['game_id', 'game_product_id']);
            $table->index(['key', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_requirements');
    }
};
