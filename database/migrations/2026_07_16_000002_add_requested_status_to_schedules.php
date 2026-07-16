<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add 'requested' to the schedule status enum so client service
     * requests land as pending requests the contractor must assign
     * to a route before they become scheduled pickups.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE schedules MODIFY COLUMN status ENUM('requested', 'scheduled', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled'");
    }

    public function down(): void
    {
        DB::statement("UPDATE schedules SET status = 'scheduled' WHERE status = 'requested'");
        DB::statement("ALTER TABLE schedules MODIFY COLUMN status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled'");
    }
};
