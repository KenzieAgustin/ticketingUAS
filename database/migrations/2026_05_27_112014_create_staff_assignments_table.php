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
        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gate_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->date('assignment_date');
            $table->enum('shift', ['morning', 'afternoon', 'evening', 'full_day']);
            $table->time('shift_start');
            $table->time('shift_end');
            $table->enum('status', ['scheduled', 'active', 'completed', 'absent'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_assignments');
    }
};
