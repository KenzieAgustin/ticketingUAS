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
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->string('rule_name'); //nama aturan harga, misal early bird, lastminute, dll
            $table->enum('discount_type', ['percentage', 'fixed']); //jenis diskon, bisa persentase atau nominal tetap
            $table->decimal('discount_value', 12, 2)->default(0); //nilai diskon
            $table->dateTime('start_date'); //hari mulai berlaku aturan harga
            $table->dateTime('end_date'); //hari berakhir aturan harga

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
