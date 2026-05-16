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

            // Link directly to your bookings table
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            // Financial details using decimal for absolute currency precision
            $table->decimal('amount', 10, 2);
            $table->decimal('gateway_fee', 10, 2)->default(0.00);

            // Payment method tracking (e.g., 'gcash', 'paymaya', 'stripe', 'cash')
            $table->string('payment_method');

            // Core transaction states ('pending', 'successful', 'failed', 'refunded')
            $table->string('status')->default('pending');

            // Reference token unique string from your chosen provider payment gateway
            $table->string('transaction_id')->nullable()->unique();

            // JSON metadata bucket for payment webhook payload archiving/auditing
            $table->json('gateway_response')->nullable();

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
