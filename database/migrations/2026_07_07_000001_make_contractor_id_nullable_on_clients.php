<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Allow self-registered clients to exist without a contractor yet.
 *
 * When a client signs up in an area no active contractor route covers, we save
 * them as pending with contractor_id = null and surface them in the admin
 * "unassigned clients" queue for manual assignment.
 */
return new class extends Migration
{
    public function up(): void
    {
        // The original FK constraint (clients_contractor_id_foreign) references
        // users.id and is NOT NULL. Make the column nullable while keeping the FK.
        // Doctrine DBAL is not installed, so use a raw driver-appropriate statement.
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE clients MODIFY contractor_id BIGINT UNSIGNED NULL');
        } elseif (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE clients ALTER COLUMN contractor_id DROP NOT NULL');
        }
        // sqlite (tests): columns are nullable-friendly; nothing required.
    }

    public function down(): void
    {
        // Backfilling NULLs would be destructive; leave nullable on rollback for
        // mysql/pgsql. Only re-tighten if there are no null rows.
        $hasNulls = DB::table('clients')->whereNull('contractor_id')->exists();
        if ($hasNulls) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE clients MODIFY contractor_id BIGINT UNSIGNED NOT NULL');
        } elseif (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE clients ALTER COLUMN contractor_id SET NOT NULL');
        }
    }
};
