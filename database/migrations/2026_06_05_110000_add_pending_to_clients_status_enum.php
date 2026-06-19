<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `clients` MODIFY `status` ENUM('active','inactive','pending') NOT NULL DEFAULT 'active'");
        }
    }

    public function down(): void
    {
        if (in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("UPDATE `clients` SET `status` = 'inactive' WHERE `status` = 'pending'");
            DB::statement("ALTER TABLE `clients` MODIFY `status` ENUM('active','inactive') NOT NULL DEFAULT 'active'");
        }
    }
};
