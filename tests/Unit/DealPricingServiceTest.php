<?php

namespace Tests\Unit;

use App\Services\Deal\DealPricingService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DealPricingServiceTest extends TestCase
{
    private DealPricingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DealPricingService();
    }

    // ─── Blueprint Rule: 1:05 billed as 1hr, 1:10 moves to next level ────────

    #[Test]
    public function bills_at_30_when_actual_is_exactly_30(): void
    {
        $this->assertEquals(30, $this->service->billableDurationMinutes(30, [30, 60, 90, 120]));
    }

    #[Test]
    public function bills_at_30_when_actual_is_35_within_grace(): void
    {
        // 35 - 30 = 5 < 10 → stays at 30
        $this->assertEquals(30, $this->service->billableDurationMinutes(35, [30, 60, 90, 120]));
    }

    #[Test]
    public function bills_at_30_when_actual_is_39_max_grace(): void
    {
        // 39 - 30 = 9 < 10 → stays at 30
        $this->assertEquals(30, $this->service->billableDurationMinutes(39, [30, 60, 90, 120]));
    }

    #[Test]
    public function bills_at_60_when_actual_is_40_crossing_threshold(): void
    {
        // 40 - 30 = 10 ≥ 10 → moves to 60
        $this->assertEquals(60, $this->service->billableDurationMinutes(40, [30, 60, 90, 120]));
    }

    #[Test]
    public function bills_at_60_when_actual_is_exactly_60(): void
    {
        $this->assertEquals(60, $this->service->billableDurationMinutes(60, [30, 60, 90, 120]));
    }

    #[Test]
    public function blueprint_example_105_billed_as_1hr(): void
    {
        // Blueprint: "1:05 is charged as 1 hour" → 65 - 60 = 5 < 10 → stays at 60
        $this->assertEquals(60, $this->service->billableDurationMinutes(65, [30, 60, 90, 120]));
    }

    #[Test]
    public function blueprint_example_110_moves_to_next_level(): void
    {
        // Blueprint: "At 1:10, the customer moves to the next pricing level" → 70 - 60 = 10 ≥ 10 → moves to 90
        $this->assertEquals(90, $this->service->billableDurationMinutes(70, [30, 60, 90, 120]));
    }

    #[Test]
    public function bills_at_60_when_actual_is_69_max_grace(): void
    {
        // 69 - 60 = 9 < 10 → stays at 60
        $this->assertEquals(60, $this->service->billableDurationMinutes(69, [30, 60, 90, 120]));
    }

    #[Test]
    public function bills_at_90_when_actual_is_70(): void
    {
        $this->assertEquals(90, $this->service->billableDurationMinutes(70, [30, 60, 90, 120]));
    }

    #[Test]
    public function stays_at_last_level_when_no_higher_level_exists(): void
    {
        // 130 - 120 = 10 → would move to next, but no next → stays at 120
        $this->assertEquals(120, $this->service->billableDurationMinutes(130, [30, 60, 90, 120]));
    }

    #[Test]
    public function works_with_two_levels_crossing(): void
    {
        // 45 - 30 = 15 ≥ 10 → moves to 60
        $this->assertEquals(60, $this->service->billableDurationMinutes(45, [30, 60]));
    }

    #[Test]
    public function works_with_two_levels_within_grace(): void
    {
        // 38 - 30 = 8 < 10 → stays at 30
        $this->assertEquals(30, $this->service->billableDurationMinutes(38, [30, 60]));
    }

    #[Test]
    public function handles_unsorted_levels_input(): void
    {
        // Levels passed in wrong order should still work
        $this->assertEquals(90, $this->service->billableDurationMinutes(70, [90, 30, 120, 60]));
    }
}
