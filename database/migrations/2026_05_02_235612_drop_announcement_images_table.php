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
        Schema::dropIfExists('announcement_images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('announcement_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('announcement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('path');
            $table->integer('sort_order')->default(0);
            $table->string('alt_text')->nullable();

            $table->timestamps();
        });
    }
};
