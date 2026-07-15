<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * New disposal record fields for completed schedules, aligned with the
     * weighbridge (kg) measurement used by collection runs so contractor
     * reports can aggregate all waste in one unit:
     *  - weight_kg: waste weight recorded for the completed schedule.
     *  - waste_category: what kind of waste it mostly was (drives the
     *    recycling breakdown on reports).
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'weight_kg')) {
                $table->decimal('weight_kg', 8, 1)->nullable()->after('total_volume');
            }
            if (!Schema::hasColumn('schedules', 'waste_category')) {
                $table->string('waste_category', 30)->nullable()->after('weight_kg');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            foreach (['weight_kg', 'waste_category'] as $col) {
                if (Schema::hasColumn('schedules', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
