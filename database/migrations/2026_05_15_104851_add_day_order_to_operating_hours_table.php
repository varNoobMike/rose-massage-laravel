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

            $table->unsignedTinyInteger('day_order')
                ->after('day_of_week')
                ->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operating_hours', function (Blueprint $table) {

            $table->dropColumn('day_order');
        });
    }
};
