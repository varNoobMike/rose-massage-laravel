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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // 🔗 relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            // ⭐ review data
            $table->tinyInteger('rating'); // 1 to 5 stars
            $table->text('comment')->nullable();

            // optional (if you want review per service later)
            // $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();

            // 🚨 prevent duplicate review per booking
            $table->unique('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
