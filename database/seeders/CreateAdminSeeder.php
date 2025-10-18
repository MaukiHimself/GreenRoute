<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $existingAdmin = User::where('email', 'admin@afiaorbit.com')->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin user already exists!');
            $this->command->info('Email: admin@afiaorbit.com');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@afiaorbit.com',
            'password' => Hash::make('Admin@2025!'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('');
        $this->command->info('Login Details:');
        $this->command->info('URL: https://afia-orbit.onrender.com/admin/login');
        $this->command->info('Email: admin@afiaorbit.com');
        $this->command->info('Password: Admin@2025!');
        $this->command->info('');
        $this->command->warn('⚠️  Please change the password after first login!');
    }
}
