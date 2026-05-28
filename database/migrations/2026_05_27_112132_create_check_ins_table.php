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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->index();
            $table->unsignedBigInteger('ticket_token_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('gate_id');
            $table->unsignedBigInteger('checked_by');
            $table->enum('method', ['qr_scan', 'manual_code'])->default('qr_scan');
            $table->enum('status', ['success', 'failed', 'duplicate'])->default('success');
            $table->string('failure_reason')->nullable();
            $table->timestamp('checked_at');
            $table->timestamps();

            $table->foreign('gate_id')->references('id')->on('gates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
