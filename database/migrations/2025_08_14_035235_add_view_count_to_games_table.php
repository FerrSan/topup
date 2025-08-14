<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // pakai bigInteger biasa di PostgreSQL; unsigned tidak ada di PG
            $table->bigInteger('view_count')->default(0)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
};
