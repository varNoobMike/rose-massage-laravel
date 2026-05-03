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
        Schema::create('announcement_images', function (Blueprint $table) {
            $table->id();

            // link to announcement
            $table->foreignId('announcement_id')
                ->constrained()
                ->cascadeOnDelete();

            // image path
            $table->string('path');

            // optional ordering
            $table->integer('sort_order')->default(0);

            // optional alt text
            $table->string('alt_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_images');
    }
};
