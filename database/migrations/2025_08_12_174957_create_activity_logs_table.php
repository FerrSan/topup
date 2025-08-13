<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject'); // subject_type, subject_id
            $table->nullableMorphs('causer');  // causer_type, causer_id
            $table->json('properties')->nullable();
            $table->string('event')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('log_name');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
