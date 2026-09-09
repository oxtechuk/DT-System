<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@workspace.local'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@workspace.local',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );

        $admin->assignRole('owner');

        // Reception test user
        $reception = User::firstOrCreate(
            ['email' => 'reception@workspace.local'],
            [
                'name'     => 'Reception',
                'email'    => 'reception@workspace.local',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );

        $reception->assignRole('reception');

        $this->command->info('Admin users seeded. Change passwords before production!');
    }
}
