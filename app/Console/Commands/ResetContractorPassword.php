<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetContractorPassword extends Command
{
    protected $signature = 'contractor:reset-password {email} {password}';

    protected $description = 'Reset a contractor account password (e.g. after a failed approval reset)';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $password = $this->argument('password');

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            $this->error("No user found with email: {$email}");

            return self::FAILURE;
        }

        if ($user->user_type !== 'contractor') {
            $this->error("User {$email} is not a contractor (type: {$user->user_type}).");

            return self::FAILURE;
        }

        $user->password = $password;
        $user->save();

        $this->info("Password updated for contractor {$user->name} ({$user->email}).");
        $this->line("Status: {$user->status}");

        return self::SUCCESS;
    }
}
