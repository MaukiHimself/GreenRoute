<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The dumping/disposal site a route ends at. Stores the site name from
     * config/dumping_sites.php (coordinates are resolved from that config).
     */
    public function up(): void
    {
        Schema::table('contractor_routes', function (Blueprint $table) {
            if (!Schema::hasColumn('contractor_routes', 'dumping_site')) {
                $table->string('dumping_site')->nullable()->after('street');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contractor_routes', function (Blueprint $table) {
            $table->dropColumn('dumping_site');
        });
    }
};
