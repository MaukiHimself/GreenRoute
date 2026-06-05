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
            // Vodacom M-Pesa
            $table->string('vodacom_mpesa_lipa_no')->nullable()->after('registration_number');

            // Airtel Money
            $table->string('airtel_money_lipa_no')->nullable()->after('vodacom_mpesa_lipa_no');

            // Halopesa
            $table->string('halopesa_lipa_no')->nullable()->after('airtel_money_lipa_no');

            // Mixx by Yas (Tigo Pesa)
            $table->string('mixx_by_yas_lipa_no')->nullable()->after('halopesa_lipa_no');

            // CRDB Bank
            $table->string('crdb_bank_lipa_no')->nullable()->after('mixx_by_yas_lipa_no');

            // NMB Bank
            $table->string('nmb_bank_lipa_no')->nullable()->after('crdb_bank_lipa_no');

            // NBC Bank
            $table->string('nbc_bank_lipa_no')->nullable()->after('nmb_bank_lipa_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropColumn([
                'vodacom_mpesa_lipa_no',
                'airtel_money_lipa_no',
                'halopesa_lipa_no',
                'mixx_by_yas_lipa_no',
                'crdb_bank_lipa_no',
                'nmb_bank_lipa_no',
                'nbc_bank_lipa_no'
            ]);
        });
    }
};
