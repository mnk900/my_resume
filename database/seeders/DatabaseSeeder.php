<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ThemeSeeder::class);

        // Create Admin User
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@portfolio.com'],
            [
                'name' => 'System Admin',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create 5 Dummy Users (Portfolios are created automatically via Model Booted method)
        \App\Models\User::factory(5)->create();
    }
}
