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
        Schema::create('ticket_zones', function (Blueprint $table) {
            $table->id();
            //relasi tabel tickets dengan tabel ticket_zones
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->string('zone_name');//ffestival, VIP, VVIP
            $table->integer('quota_total');//total kapasitas kuota awal
            $table->integer('quota_remaining');//sisa kuota yg akan berkurang ketika dibeli atau scan masuk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_zones');
    }
};
