<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds route-assignment + base-location support to trucks so every truck
     * parks at the contractor's yard and can be dispatched along a contractor
     * route: contractor base -> clients -> dumping site.
     */
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->decimal('base_latitude', 10, 8)->nullable()->after('status');
            $table->decimal('base_longitude', 11, 8)->nullable()->after('base_latitude');
            $table->foreignId('assigned_route_id')
                ->nullable()
                ->after('base_longitude')
                ->constrained('contractor_routes')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropForeign(['assigned_route_id']);
            $table->dropColumn(['base_latitude', 'base_longitude', 'assigned_route_id']);
        });
    }
};
