<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // midtrans, xendit
            $table->string('event_type')->nullable();
            $table->string('reference_id')->nullable(); // order_id / invoice_no
            $table->text('signature')->nullable();
            $table->json('headers')->nullable();
            $table->json('payload');
            $table->boolean('verified')->default(false);
            $table->boolean('processed')->default(false);
            $table->text('error_message')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['provider', 'created_at']);
            $table->index('reference_id');
            $table->index(['verified', 'processed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
