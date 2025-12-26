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
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('specializations_id')->references('id')->on('specializations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('license_number');
            $table->float('session_price');
            $table->json('availability_slots')->nullable(); // انا عملتها هنا جيسون عشان نختار القيم جواها بسهولة
            $table->json('clinic_location')->nullable();   // متنساش تروح فى المودل بتاع الدكتور وتعمل البرودكاست كاستنج للجيسون دة
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
