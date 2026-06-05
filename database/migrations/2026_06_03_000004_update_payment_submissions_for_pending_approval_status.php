<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payment_submissions MODIFY status ENUM('pending', 'pending_approval', 'approved', 'rejected') DEFAULT 'pending_approval'");
        }

        DB::table('payment_submissions')
            ->where('status', 'pending')
            ->update(['status' => 'pending_approval']);
    }

    public function down(): void
    {
        DB::table('payment_submissions')
            ->where('status', 'pending_approval')
            ->update(['status' => 'pending']);

        if (DB::connection()->getDriverName() === 'mysql' && Schema::hasTable('payment_submissions')) {
            DB::statement("ALTER TABLE payment_submissions MODIFY status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        }
    }
};
