<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Disposal records are now filled by the DRIVER at the dumping site (via
     * the token-based driver terminal) and then confirmed by the contractor:
     *  - disposal_recorded_by: 'driver' or 'contractor' (who filled the record)
     *  - disposal_confirmed_at: set when the contractor confirms a
     *    driver-submitted record (contractor's own records confirm instantly)
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'disposal_recorded_by')) {
                $table->string('disposal_recorded_by', 20)->nullable()->after('waste_category');
            }
            if (!Schema::hasColumn('schedules', 'disposal_confirmed_at')) {
                $table->timestamp('disposal_confirmed_at')->nullable()->after('disposal_recorded_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            foreach (['disposal_recorded_by', 'disposal_confirmed_at'] as $col) {
                if (Schema::hasColumn('schedules', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
