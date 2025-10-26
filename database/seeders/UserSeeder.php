<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Aziz Admin',
                'email' => 'aziz@aziz.com',
                'phone' => '+1234567890',
                'password' => bcrypt('19112002'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@tea.com',
                'phone' => '+1234567890',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Moderator User',
                'email' => 'mod@tea.com',
                'phone' => '+1234567891',
                'password' => bcrypt('password'),
                'role' => 'moderator',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '+1234567892',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Mike Chen',
                'email' => 'mike@example.com',
                'phone' => '+1234567893',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'phone' => '+1234567894',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Alex Rodriguez',
                'email' => 'alex@example.com',
                'phone' => '+1234567895',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Lisa Brown',
                'email' => 'lisa@example.com',
                'phone' => '+1234567896',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'David Kim',
                'email' => 'david@example.com',
                'phone' => '+1234567897',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Jessica Taylor',
                'email' => 'jessica@example.com',
                'phone' => '+1234567898',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null, // Use default avatar instead of external URL
            ],
            [
                'name' => 'Banned User',
                'email' => 'banned@example.com',
                'phone' => '+1234567899',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'banned',
                'avatar' => null, // Use default avatar instead of external URL
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
