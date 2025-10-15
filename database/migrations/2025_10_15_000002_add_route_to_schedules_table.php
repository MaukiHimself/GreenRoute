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
            $table->string('route')->nullable()->after('contractor_id');
            $table->string('route_group_id')->nullable()->after('route')->comment('Groups schedules for same route/date');
            $table->index(['contractor_id', 'route', 'pickup_date']);
            $table->index('route_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['contractor_id', 'route', 'pickup_date']);
            $table->dropIndex(['route_group_id']);
            $table->dropColumn(['route', 'route_group_id']);
        });
    }
};
