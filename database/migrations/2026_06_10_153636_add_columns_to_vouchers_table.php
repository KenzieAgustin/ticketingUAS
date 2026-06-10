<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'quota')) {
            $table->integer('quota')->nullable();
            }
            if (!Schema::hasColumn('vouchers', 'used')) {
            $table->integer('used')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vouchers', 'expired_at')) {
            $table->timestamp('expired_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'quota', 'used', 'expired_at']);
        });
    }
};
