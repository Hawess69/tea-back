<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder for creating admin users
 * 
 * Creates default admin and moderator accounts for testing and initial setup.
 * 
 * @package Database\Seeders
 */
final class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@tea.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@tea.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create moderator user
        User::firstOrCreate(
            ['email' => 'moderator@tea.com'],
            [
                'name' => 'Moderator User',
                'email' => 'moderator@tea.com',
                'password' => Hash::make('password123'),
                'role' => 'moderator',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@tea.com / password123');
        $this->command->info('Moderator: moderator@tea.com / password123');
    }
}


