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
        Schema::create('wait_lists', function (Blueprint $table) {
            $table->id();
            //pake unsignedBigInteger dulu biar ga error karena belum dibuat tabel user
            $table->unsignedBigInteger('user_id');
            //relasi ke table ticket_zones
            $table->foreignId('ticket_zone_id')->constrained('ticket_zones')->onDelete('cascade');
            //waiting=user ada dalam antrian
            //notified=user sudah diberitahu untuk membeli tiket karena ada kuota yang tersedia
            //expired=user sudah diberitahu tapi tidak membeli tiket dalam waktu tertentu, sehingga statusnya menjadi expired
            $table->enum('status',['waiting', 'notified', 'expired'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wait_lists');
    }
};
