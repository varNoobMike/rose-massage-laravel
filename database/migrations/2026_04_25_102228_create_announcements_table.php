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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            // main content
            $table->string('title');
            $table->text('message');

            // classification (for UI badges / filtering)
            $table->enum('type', ['promo', 'update', 'alert', 'info'])
                ->default('info');

            // optional linking system (VERY IMPORTANT)
            $table->string('link_type')->nullable();
            // service | booking | url | none

            $table->unsignedBigInteger('link_id')->nullable();
            // e.g. service_id, booking_id

            $table->string('link_url')->nullable();
            // external link (facebook, website, etc.)

            // visibility control
            $table->boolean('is_active')->default(true);

            // scheduling (optional but powerful)
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            // optional index for performance
            $table->index(['is_active', 'starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
