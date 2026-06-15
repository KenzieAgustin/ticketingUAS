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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            //pake unsignedBigInteger dulu biar ga error karena belum dibuat tabel events
            $table->unsignedBigInteger('event_id');
            $table->enum('ticket_type',['entry_only', 'entry_concert']);//enum untuk jenis tiket, karena ada 2 yaitu entry only dan entry concert
            $table->decimal('price', 12, 2);//decimal untuk harga tiket, dengan total 12 digit dan 2 digit dibelakang koma  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
