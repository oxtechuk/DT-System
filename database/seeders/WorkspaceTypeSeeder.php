<?php

namespace Database\Seeders;

use App\Models\WorkspaceType;
use Illuminate\Database\Seeder;

class WorkspaceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Shared',  'code' => 'shared',  'pricing_mode' => 'duration', 'active' => true],
            ['name' => 'Private', 'code' => 'private', 'pricing_mode' => 'duration', 'active' => true],
        ];

        foreach ($types as $type) {
            WorkspaceType::firstOrCreate(['code' => $type['code']], $type);
        }

        $this->command->info('Workspace types seeded: Shared, Private');
    }
}
