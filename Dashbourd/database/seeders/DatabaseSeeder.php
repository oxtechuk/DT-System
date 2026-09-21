<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with essential setup only.
     */
    public function run(): void
    {
        // 1. Essential Super Admin User
        User::updateOrCreate(
            ['email' => 'admin@ddt.com'],
            [
                'name'     => 'DDT Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Also ensure legacy admin email works if needed
        User::updateOrCreate(
            ['email' => 'admin@dt-system.com'],
            [
                'name'     => 'مدير النظام DDT',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Essential Workspace & Brand Settings
        $this->call(SystemSetupSeeder::class);

        // 3. Essential Cafe & Services Menu
        $this->call(ProductMenuSeeder::class);
    }
}
