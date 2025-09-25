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
        Schema::table('schedules', function (Blueprint $table) {
            $table->decimal('total_volume', 8, 2)->nullable();
            $table->string('disposal_site')->nullable();
            $table->enum('disposal_type', ['sorting_facility', 'landfill'])->nullable();
            $table->text('disposal_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['total_volume', 'disposal_site', 'disposal_type', 'disposal_notes']);
        });
    }
};
