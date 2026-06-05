<?php

namespace App\Console\Commands;

use App\Models\Contractor;
use Illuminate\Console\Command;

class SetupPaymentMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:setup-methods {--contractor-id=} {--reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup or update payment methods for contractors';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('reset')) {
            $this->resetAllPaymentMethods();
            return Command::SUCCESS;
        }

        if ($this->option('contractor-id')) {
            $this->setupForContractor($this->option('contractor-id'));
        } else {
            $this->setupInteractive();
        }

        return Command::SUCCESS;
    }

    /**
     * Setup payment methods interactively
     */
    private function setupInteractive()
    {
        $this->info('Payment Method Setup');
        $this->line('');

        $contractors = Contractor::all();

        if ($contractors->isEmpty()) {
            $this->error('No contractors found in the database.');
            return;
        }

        $this->info('Found ' . $contractors->count() . ' contractor(s)');

        foreach ($contractors as $contractor) {
            $this->line('');
            $this->info("Contractor: {$contractor->name} ({$contractor->registration_number})");

            $methods = [
                'vodacom_mpesa_lipa_no' => 'Vodacom M-Pesa',
                'airtel_money_lipa_no' => 'Airtel Money',
                'halopesa_lipa_no' => 'Halopesa',
                'mixx_by_yas_lipa_no' => 'Mixx by Yas (Tigo Pesa)',
                'crdb_bank_lipa_no' => 'CRDB Bank',
                'nmb_bank_lipa_no' => 'NMB Bank',
                'nbc_bank_lipa_no' => 'NBC Bank',
            ];

            foreach ($methods as $column => $name) {
                $current = $contractor->$column;
                $prompt = "Enter {$name} Lipa No";
                if ($current) {
                    $prompt .= " (current: {$current})";
                }
                $prompt .= ": ";

                $value = $this->ask($prompt);

                if (!empty($value)) {
                    $contractor->update([$column => $value]);
                    $this->line("  ✓ Updated {$name}");
                }
            }
        }

        $this->info('');
        $this->info('Payment methods setup completed!');
    }

    /**
     * Setup for a specific contractor
     */
    private function setupForContractor($contractorId)
    {
        $contractor = Contractor::find($contractorId);

        if (!$contractor) {
            $this->error("Contractor with ID {$contractorId} not found.");
            return;
        }

        $this->info("Setting up payment methods for: {$contractor->name}");

        $methods = [
            'vodacom_mpesa_lipa_no' => 'Vodacom M-Pesa',
            'airtel_money_lipa_no' => 'Airtel Money',
            'halopesa_lipa_no' => 'Halopesa',
            'mixx_by_yas_lipa_no' => 'Mixx by Yas (Tigo Pesa)',
            'crdb_bank_lipa_no' => 'CRDB Bank',
            'nmb_bank_lipa_no' => 'NMB Bank',
            'nbc_bank_lipa_no' => 'NBC Bank',
        ];

        foreach ($methods as $column => $name) {
            $value = $this->ask("Enter {$name} Lipa No (or leave blank to skip)");
            if (!empty($value)) {
                $contractor->update([$column => $value]);
                $this->line("  ✓ {$name}: {$value}");
            }
        }

        $this->info('Payment methods updated successfully!');
    }

    /**
     * Reset all payment methods to placeholder
     */
    private function resetAllPaymentMethods()
    {
        if (!$this->confirm('This will reset all payment methods to placeholder number. Continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $placeholder = '1234565';
        Contractor::all()->each(function ($contractor) use ($placeholder) {
            $contractor->update([
                'vodacom_mpesa_lipa_no' => $placeholder,
                'airtel_money_lipa_no' => $placeholder,
                'halopesa_lipa_no' => $placeholder,
                'mixx_by_yas_lipa_no' => $placeholder,
                'crdb_bank_lipa_no' => $placeholder,
                'nmb_bank_lipa_no' => $placeholder,
                'nbc_bank_lipa_no' => $placeholder,
            ]);
        });

        $this->info('All contractor payment methods reset to: ' . $placeholder);
    }
}
