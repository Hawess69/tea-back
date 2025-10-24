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
                'avatar' => 'https://via.placeholder.com/150/0000FF/FFFFFF?text=A',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@tea.com',
                'phone' => '+1234567890',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/0000FF/FFFFFF?text=A',
            ],
            [
                'name' => 'Moderator User',
                'email' => 'mod@tea.com',
                'phone' => '+1234567891',
                'password' => bcrypt('password'),
                'role' => 'moderator',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/00FF00/FFFFFF?text=M',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '+1234567892',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/FF0000/FFFFFF?text=S',
            ],
            [
                'name' => 'Mike Chen',
                'email' => 'mike@example.com',
                'phone' => '+1234567893',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/FFFF00/000000?text=M',
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'phone' => '+1234567894',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/FF00FF/FFFFFF?text=E',
            ],
            [
                'name' => 'Alex Rodriguez',
                'email' => 'alex@example.com',
                'phone' => '+1234567895',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/00FFFF/000000?text=A',
            ],
            [
                'name' => 'Lisa Brown',
                'email' => 'lisa@example.com',
                'phone' => '+1234567896',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/800080/FFFFFF?text=L',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david@example.com',
                'phone' => '+1234567897',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/008000/FFFFFF?text=D',
            ],
            [
                'name' => 'Jessica Taylor',
                'email' => 'jessica@example.com',
                'phone' => '+1234567898',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150/FFA500/000000?text=J',
            ],
            [
                'name' => 'Banned User',
                'email' => 'banned@example.com',
                'phone' => '+1234567899',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'banned',
                'avatar' => 'https://via.placeholder.com/150/FF0000/FFFFFF?text=B',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
