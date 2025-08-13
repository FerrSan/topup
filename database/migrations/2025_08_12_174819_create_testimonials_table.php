<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('game_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable(); // for guest
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['is_approved', 'rating']);
            $table->index('game_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
