<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('subscription_completed')->default(false);
            $table->string('business_license')->nullable();
            $table->string('certificate_incorporation')->nullable();
            $table->string('contract_document')->nullable();
            $table->decimal('initial_payment', 10, 2)->nullable();
            $table->enum('subscription_status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamp('subscription_date')->nullable();
            $table->boolean('remember_login')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_completed',
                'business_license',
                'certificate_incorporation', 
                'contract_document',
                'initial_payment',
                'subscription_status',
                'subscription_date',
                'remember_login'
            ]);
        });
    }
};