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
        Schema::table('contractors', function (Blueprint $table) {
            $table->string('registration_number')->unique()->nullable()->after('user_id');
            $table->string('client_registration_number')->nullable()->after('registration_number');
            
            // Add index for faster lookups
            $table->index('client_registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropIndex(['client_registration_number']);
            $table->dropColumn(['registration_number', 'client_registration_number']);
        });
    }
};
