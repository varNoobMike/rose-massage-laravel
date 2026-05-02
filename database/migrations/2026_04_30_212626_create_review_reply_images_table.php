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
        Schema::create('review_reply_images', function (Blueprint $table) {
            $table->id();

            // 🔗 link to reply
            $table->foreignId('review_reply_id')
                ->constrained('review_replies')
                ->onDelete('cascade');

            // 🖼️ image path
            $table->string('path');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_reply_images');
    }
};
