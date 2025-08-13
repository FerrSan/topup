<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // kolom baru
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_url')->nullable();
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users'); // self reference
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->text('ban_reason')->nullable();
            $table->json('preferences')->nullable();

            // index bantuan
            $table->index('username');
            $table->index('phone');
            $table->index('referral_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // drop FK dulu jika DB kamu butuh eksplisit
            if (Schema::hasColumn('users', 'referred_by')) {
                $table->dropConstrainedForeignId('referred_by');
            }

            // drop unique + column dengan aman
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique('users_username_unique');
                $table->dropIndex(['username']);
                $table->dropColumn('username');
            }

            if (Schema::hasColumn('users', 'phone')) {
                $table->dropIndex(['phone']);
                $table->dropColumn('phone');
            }

            if (Schema::hasColumn('users', 'avatar_url')) $table->dropColumn('avatar_url');
            if (Schema::hasColumn('users', 'balance')) $table->dropColumn('balance');

            if (Schema::hasColumn('users', 'referral_code')) {
                $table->dropUnique('users_referral_code_unique');
                $table->dropIndex(['referral_code']);
                $table->dropColumn('referral_code');
            }

            if (Schema::hasColumn('users', 'two_factor_secret')) $table->dropColumn('two_factor_secret');
            if (Schema::hasColumn('users', 'two_factor_recovery_codes')) $table->dropColumn('two_factor_recovery_codes');
            if (Schema::hasColumn('users', 'two_factor_confirmed_at')) $table->dropColumn('two_factor_confirmed_at');
            if (Schema::hasColumn('users', 'last_login_ip')) $table->dropColumn('last_login_ip');
            if (Schema::hasColumn('users', 'last_login_at')) $table->dropColumn('last_login_at');
            if (Schema::hasColumn('users', 'is_banned')) $table->dropColumn('is_banned');
            if (Schema::hasColumn('users', 'ban_reason')) $table->dropColumn('ban_reason');
            if (Schema::hasColumn('users', 'preferences')) $table->dropColumn('preferences');
        });
    }
};
