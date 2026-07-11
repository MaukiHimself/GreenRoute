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
        // 1. Add stop_statuses column to trucks table
        if (!Schema::hasColumn('trucks', 'stop_statuses')) {
            Schema::table('trucks', function (Blueprint $table) {
                $table->json('stop_statuses')->nullable()->after('assigned_route_id');
            });
        }

        // 2. Create truck_locations_history table
        if (!Schema::hasTable('truck_locations_history')) {
            Schema::create('truck_locations_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('truck_id')->constrained('trucks')->onDelete('cascade');
                $table->decimal('latitude', 10, 8);
                $table->decimal('longitude', 11, 8);
                $table->timestamp('recorded_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_locations_history');
        
        if (Schema::hasColumn('trucks', 'stop_statuses')) {
            Schema::table('trucks', function (Blueprint $table) {
                $table->dropColumn('stop_statuses');
            });
        }
    }
};
