<?php

namespace Database\Seeders;

use App\Models\PricingRule;
use App\Models\WorkspaceType;
use Illuminate\Database\Seeder;

class PricingRuleSeeder extends Seeder
{
    public function run(): void
    {
        $shared  = WorkspaceType::where('code', 'shared')->first();
        $private = WorkspaceType::where('code', 'private')->first();

        if (! $shared || ! $private) {
            $this->command->error('Run WorkspaceTypeSeeder first!');
            return;
        }

        $today = now()->toDateString();

        $rules = [
            // Shared pricing
            ['workspace_type_id' => $shared->id,  'duration_minutes' => 30,  'price' => 30.00],
            ['workspace_type_id' => $shared->id,  'duration_minutes' => 60,  'price' => 50.00],
            ['workspace_type_id' => $shared->id,  'duration_minutes' => 90,  'price' => 70.00],
            ['workspace_type_id' => $shared->id,  'duration_minutes' => 120, 'price' => 90.00],

            // Private pricing
            ['workspace_type_id' => $private->id, 'duration_minutes' => 30,  'price' => 80.00],
            ['workspace_type_id' => $private->id, 'duration_minutes' => 60,  'price' => 150.00],
            ['workspace_type_id' => $private->id, 'duration_minutes' => 90,  'price' => 200.00],
            ['workspace_type_id' => $private->id, 'duration_minutes' => 120, 'price' => 250.00],
        ];

        foreach ($rules as $rule) {
            PricingRule::firstOrCreate(
                [
                    'workspace_type_id' => $rule['workspace_type_id'],
                    'duration_minutes'  => $rule['duration_minutes'],
                    'effective_from'    => $today,
                ],
                array_merge($rule, ['effective_until' => null, 'active' => true])
            );
        }

        $this->command->info('Pricing rules seeded. (PLACEHOLDER — confirm real prices with business!)');
    }
}
