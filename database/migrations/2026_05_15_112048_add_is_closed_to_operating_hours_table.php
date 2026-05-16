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
        Schema::table('operating_hours', function (Blueprint $table) {

            $table->boolean('is_closed')
                ->default(false)
                ->after('day_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operating_hours', function (Blueprint $table) {

            $table->dropColumn('is_closed');
        });
    }
};
