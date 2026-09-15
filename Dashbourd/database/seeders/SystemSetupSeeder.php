<?php

namespace Database\Seeders;

use App\Models\WorkspaceType;
use App\Models\PricingRule;
use App\Models\Room;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemSetupSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedWorkspaceTypes();
        $this->seedPricingRules();
        $this->seedRooms();
        $this->seedSettings();
    }

    private function seedWorkspaceTypes(): void
    {
        $types = [
            ['name' => 'Shared',         'code' => 'shared',   'pricing_mode' => 'duration'],
            ['name' => 'Private Room',   'code' => 'private',  'pricing_mode' => 'duration'],
            ['name' => 'Meeting Room',   'code' => 'meeting',  'pricing_mode' => 'fixed'],
            ['name' => 'Open Desk',      'code' => 'open',     'pricing_mode' => 'duration'],
        ];

        foreach ($types as $type) {
            WorkspaceType::firstOrCreate(['code' => $type['code']], $type);
        }
    }

    private function seedPricingRules(): void
    {
        $shared  = WorkspaceType::where('code', 'shared')->first();
        $private = WorkspaceType::where('code', 'private')->first();

        if (!$shared || !$private) return;

        $from = Carbon::today()->startOfYear()->toDateString();

        // Shared pricing (EGP)
        $sharedPricing = [
            [30, 30],
            [60, 50],
            [90, 70],
            [120, 90],
            [180, 120],
            [240, 150],
            [300, 180],
            [360, 200],
            [480, 250],
        ];

        foreach ($sharedPricing as [$duration, $price]) {
            PricingRule::firstOrCreate(
                ['workspace_type_id' => $shared->id, 'duration_minutes' => $duration, 'effective_from' => $from],
                ['price' => $price, 'active' => true]
            );
        }

        // Private pricing (EGP) — slightly higher
        $privatePricing = [
            [30, 50],
            [60, 80],
            [90, 110],
            [120, 140],
            [180, 190],
            [240, 230],
            [300, 280],
            [360, 320],
            [480, 400],
        ];

        foreach ($privatePricing as [$duration, $price]) {
            PricingRule::firstOrCreate(
                ['workspace_type_id' => $private->id, 'duration_minutes' => $duration, 'effective_from' => $from],
                ['price' => $price, 'active' => true]
            );
        }
    }

    private function seedRooms(): void
    {
        $rooms = [
            ['name' => 'Room A',        'code' => 'A',   'capacity' => 4,  'color' => '#6366f1'],
            ['name' => 'Room B',        'code' => 'B',   'capacity' => 4,  'color' => '#8b5cf6'],
            ['name' => 'Room C',        'code' => 'C',   'capacity' => 6,  'color' => '#ec4899'],
            ['name' => 'Room D',        'code' => 'D',   'capacity' => 6,  'color' => '#14b8a6'],
            ['name' => 'Meeting Room',  'code' => 'MR',  'capacity' => 10, 'color' => '#f97316'],
            ['name' => 'Open Area',     'code' => 'OA',  'capacity' => 20, 'color' => '#22c55e'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['code' => $room['code']], array_merge($room, ['status' => 'active']));
        }
    }

    private function seedSettings(): void
    {
        $defaults = [
            ['key' => 'business_name', 'value' => 'DT WorkSpace', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency',      'value' => 'EGP',           'type' => 'string', 'group' => 'general'],
            ['key' => 'primary_color', 'value' => '#6366f1',       'type' => 'string', 'group' => 'branding'],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
