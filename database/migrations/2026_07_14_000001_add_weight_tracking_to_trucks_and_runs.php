<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Weight tracking for waste quantification:
     *  - trucks.tare_weight_kg: the empty (tare) weight of the vehicle, captured
     *    at registration. Net waste = weighbridge gross reading - tare.
     *  - collection_runs weighbridge fields: the gross reading taken at the
     *    dumping site when the trip ends, and the derived net waste weight.
     *  - collection_run_stops.prorated_weight_kg: the trip's net weight shared
     *    across that run's collected stops (equal share per collected stop),
     *    giving a per-client waste estimate for reports and billing.
     */
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            if (!Schema::hasColumn('trucks', 'tare_weight_kg')) {
                $table->decimal('tare_weight_kg', 8, 1)->nullable()->after('truck_type');
            }
        });

        Schema::table('collection_runs', function (Blueprint $table) {
            if (!Schema::hasColumn('collection_runs', 'gross_weight_kg')) {
                $table->decimal('gross_weight_kg', 8, 1)->nullable()->after('blocked_count');
            }
            if (!Schema::hasColumn('collection_runs', 'tare_weight_kg')) {
                $table->decimal('tare_weight_kg', 8, 1)->nullable()->after('gross_weight_kg');
            }
            if (!Schema::hasColumn('collection_runs', 'net_weight_kg')) {
                $table->decimal('net_weight_kg', 8, 1)->nullable()->after('tare_weight_kg');
            }
            if (!Schema::hasColumn('collection_runs', 'weighed_at')) {
                $table->timestamp('weighed_at')->nullable()->after('net_weight_kg');
            }
        });

        Schema::table('collection_run_stops', function (Blueprint $table) {
            if (!Schema::hasColumn('collection_run_stops', 'prorated_weight_kg')) {
                $table->decimal('prorated_weight_kg', 8, 1)->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_run_stops', function (Blueprint $table) {
            if (Schema::hasColumn('collection_run_stops', 'prorated_weight_kg')) {
                $table->dropColumn('prorated_weight_kg');
            }
        });

        Schema::table('collection_runs', function (Blueprint $table) {
            foreach (['gross_weight_kg', 'tare_weight_kg', 'net_weight_kg', 'weighed_at'] as $col) {
                if (Schema::hasColumn('collection_runs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('trucks', function (Blueprint $table) {
            if (Schema::hasColumn('trucks', 'tare_weight_kg')) {
                $table->dropColumn('tare_weight_kg');
            }
        });
    }
};
