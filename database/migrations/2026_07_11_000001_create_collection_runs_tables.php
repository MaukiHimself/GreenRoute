<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Records a "collection run": one dispatch of a truck along a route, with a
     * per-client audit of what was collected / skipped / blocked. Powers the
     * contractor completion alert and the Collection History drawer.
     */
    public function up(): void
    {
        if (!Schema::hasTable('collection_runs')) {
            Schema::create('collection_runs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('truck_id')->constrained('trucks')->cascadeOnDelete();
                $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('route_id')->nullable()->constrained('contractor_routes')->nullOnDelete();
                $table->string('route_name')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->unsignedInteger('total_stops')->default(0);
                $table->unsignedInteger('collected_count')->default(0);
                $table->unsignedInteger('skipped_count')->default(0);
                $table->unsignedInteger('blocked_count')->default(0);
                $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('collection_run_stops')) {
            Schema::create('collection_run_stops', function (Blueprint $table) {
                $table->id();
                $table->foreignId('collection_run_id')->constrained('collection_runs')->cascadeOnDelete();
                $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
                $table->string('client_name')->nullable();
                $table->enum('status', ['collected', 'skipped', 'blocked']);
                $table->timestamp('actioned_at')->nullable();
                $table->timestamps();

                $table->unique(['collection_run_id', 'client_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_run_stops');
        Schema::dropIfExists('collection_runs');
    }
};
