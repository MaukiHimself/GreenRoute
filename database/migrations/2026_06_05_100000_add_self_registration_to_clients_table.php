<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // 'pending' = self-registered, awaiting contractor approval
            // 'active'  = approved and active
            // 'inactive' = disabled
            // Add pending to the status enum if not already there
            // We handle this via a string column update (MySQL)
            $table->boolean('self_registered')->default(false)->after('status');
            $table->timestamp('verified_at')->nullable()->after('self_registered');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['self_registered', 'verified_at']);
        });
    }
};
