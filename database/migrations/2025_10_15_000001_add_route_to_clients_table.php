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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('route')->nullable()->after('contractor_id');
            $table->integer('route_sequence')->nullable()->after('route')->comment('Order in route');
            $table->index(['contractor_id', 'route']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['contractor_id', 'route']);
            $table->dropColumn(['route', 'route_sequence']);
        });
    }
};
