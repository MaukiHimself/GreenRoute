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
            // Vodacom M-Pesa Name
            $table->string('vodacom_mpesa_lipa_name')->nullable()->after('vodacom_mpesa_lipa_no');

            // Airtel Money Name
            $table->string('airtel_money_lipa_name')->nullable()->after('airtel_money_lipa_no');

            // Halopesa Name
            $table->string('halopesa_lipa_name')->nullable()->after('halopesa_lipa_no');

            // Mixx by Yas Name
            $table->string('mixx_by_yas_lipa_name')->nullable()->after('mixx_by_yas_lipa_no');

            // CRDB Bank Name
            $table->string('crdb_bank_lipa_name')->nullable()->after('crdb_bank_lipa_no');

            // NMB Bank Name
            $table->string('nmb_bank_lipa_name')->nullable()->after('nmb_bank_lipa_no');

            // NBC Bank Name
            $table->string('nbc_bank_lipa_name')->nullable()->after('nbc_bank_lipa_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropColumn([
                'vodacom_mpesa_lipa_name',
                'airtel_money_lipa_name',
                'halopesa_lipa_name',
                'mixx_by_yas_lipa_name',
                'crdb_bank_lipa_name',
                'nmb_bank_lipa_name',
                'nbc_bank_lipa_name'
            ]);
        });
    }
};
