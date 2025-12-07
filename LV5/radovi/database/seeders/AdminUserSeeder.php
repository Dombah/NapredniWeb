<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create sample nastavnik if not exists
        User::firstOrCreate(
            ['email' => 'nastavnik@example.com'],
            [
                'name' => 'Nastavnik Test',
                'password' => Hash::make('password'),
                'role' => 'nastavnik',
            ]
        );

        // Create sample student if not exists
        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student Test',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // Create second sample student if not exists
        User::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'name' => 'Student Test 2',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );
    }
}
