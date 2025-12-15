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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->foreignId('specializations_id')->references('id')->on('specializations')->onUpdate('cascade')->onDelete('set null')->nullable();
            $table->Integer('mobile_number');
            $table->string('license_number');
            $table->float('session_price');
            $table->json('availability_slots');
            $table->json('clinic_location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
