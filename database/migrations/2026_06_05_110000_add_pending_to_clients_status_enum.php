<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend the enum to include 'pending'
        DB::statement("ALTER TABLE `clients` MODIFY `status` ENUM('active','inactive','pending') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        // Revert pending rows to inactive before shrinking enum
        DB::statement("UPDATE `clients` SET `status` = 'inactive' WHERE `status` = 'pending'");
        DB::statement("ALTER TABLE `clients` MODIFY `status` ENUM('active','inactive') NOT NULL DEFAULT 'active'");
    }
};
