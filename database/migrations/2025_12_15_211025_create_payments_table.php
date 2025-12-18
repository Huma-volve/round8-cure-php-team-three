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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('booking_id')
            ->constrained('bookings')
            ->onDelete('cascade');
            
        $table->foreignId('payment_method_id')
            ->constrained('payment_methods');

        $table->double('amount');

        $table->string('transaction_id')->nullable();

        $table->enum('status', ['pending', 'success', 'failed']);

        $table->json('response')->nullable(); // رد بوابة الدفع

        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
