<?php

namespace Database\Seeders;

use App\Models\Contractor;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all contractors
        $contractors = Contractor::all();

        // Placeholder Lipa No for testing
        $placeholderNumber = '1234565';

        foreach ($contractors as $contractor) {
            $contractor->update([
                'vodacom_mpesa_lipa_no' => $placeholderNumber,
                'airtel_money_lipa_no' => $placeholderNumber,
                'halopesa_lipa_no' => $placeholderNumber,
                'mixx_by_yas_lipa_no' => $placeholderNumber,
                'crdb_bank_lipa_no' => $placeholderNumber,
                'nmb_bank_lipa_no' => $placeholderNumber,
                'nbc_bank_lipa_no' => $placeholderNumber,
            ]);
        }

        $this->command->info('Seeded ' . $contractors->count() . ' contractors with payment methods.');
    }
}
