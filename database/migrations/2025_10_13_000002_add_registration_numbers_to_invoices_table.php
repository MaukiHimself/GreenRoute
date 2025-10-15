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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('contractor_registration_number')->nullable()->after('contractor_id');
            $table->string('client_registration_number')->nullable()->after('client_id');
            
            // Add indexes for faster filtering
            $table->index('contractor_registration_number');
            $table->index('client_registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['contractor_registration_number']);
            $table->dropIndex(['client_registration_number']);
            $table->dropColumn(['contractor_registration_number', 'client_registration_number']);
        });
    }
};
