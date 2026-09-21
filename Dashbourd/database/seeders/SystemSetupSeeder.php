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
            ['name' => 'Room 1 (Focus)',      'code' => 'R1',  'capacity' => 4,  'color' => '#4E8F35'],
            ['name' => 'Room 2 (Creative)',   'code' => 'R2',  'capacity' => 6,  'color' => '#79B84A'],
            ['name' => 'Room 3 (Quiet)',      'code' => 'R3',  'capacity' => 4,  'color' => '#303334'],
            ['name' => 'Meeting Room',        'code' => 'MR',  'capacity' => 12, 'color' => '#73777A'],
            ['name' => 'Open Area (المساحة العامة)', 'code' => 'OA', 'capacity' => 25, 'color' => '#4E8F35'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['code' => $room['code']], array_merge($room, ['status' => 'active']));
        }
    }

    private function seedSettings(): void
    {
        $defaults = [
            ['key' => 'workspace_name',       'value' => 'DDT WORKING SPACE',                      'type' => 'string', 'group' => 'general'],
            ['key' => 'workspace_slogan',     'value' => 'أكثر من مكان.. مجتمع بيكبر معاك',        'type' => 'string', 'group' => 'general'],
            ['key' => 'receipt_footer',       'value' => 'شكراً لزيارتكم DDT - نتمنى لكم يوماً منتجاً ومليئاً بالإنجازات!', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency',             'value' => 'EGP',                                    'type' => 'string', 'group' => 'general'],
            ['key' => 'primary_color',        'value' => '#4E8F35',                                'type' => 'string', 'group' => 'branding'],
            ['key' => 'loyalty_required_visits', 'value' => '5',                                   'type' => 'string', 'group' => 'general'],
            ['key' => 'loyalty_min_duration_minutes', 'value' => '180',                           'type' => 'string', 'group' => 'general'],
            ['key' => 'affiliate_discount_type',  'value' => 'percentage',                         'type' => 'string', 'group' => 'general'],
            ['key' => 'affiliate_discount_value', 'value' => '20',                                 'type' => 'string', 'group' => 'general'],
            ['key' => 'app_banner_title',     'value' => 'فعاليات وورش عمل DDT',                  'type' => 'string', 'group' => 'branding'],
            ['key' => 'app_banner_subtitle',  'value' => 'أكثر من مكان.. مجتمع بيكبر معاك • ورش عمل وجلسات تشبيك', 'type' => 'string', 'group' => 'branding'],
            ['key' => 'app_banner_button_text','value' => 'تصفح الفعاليات',                       'type' => 'string', 'group' => 'branding'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
