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
        Schema::create('ticket_tokens', function (Blueprint $table) {
            $table->id();
            //pake unsignedBigInteger dulu biar ga error karena belum dibuat tabel order_items
            $table->unsignedBigInteger('order_item_id');
            $table->string('booking_code')->unique(); //kode unik untuk setiap tiket yang dibeli
            $table->string('qr_code_path'); //path untuk menyimpan file QR code yang di-generate
            $table->enum('status', ['valid', 'used'])->default('valid');//status tiket, valid berarti bisa digunakan untuk masuk, used berarti sudah digunakan untuk masuk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_tokens');
    }
};
