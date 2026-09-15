<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\PricingRule;
use Carbon\Carbon;

/**
 * DealPricingService
 *
 * Centralised pricing logic.
 * Rule from blueprint:
 *   - 1:05 is still charged as 1 hour.
 *   - At 1:10, the customer moves to the NEXT pricing level.
 * Grace threshold = 10 minutes (configurable).
 */
class DealPricingService
{
    /** Minutes of grace before stepping up to the next level */
    protected int $graceMinutes = 10;

    public function __construct(int $graceMinutes = 10)
    {
        $this->graceMinutes = $graceMinutes;
    }

    // ──────────────────────────────────────────────────
    // PUBLIC API
    // ──────────────────────────────────────────────────

    /**
     * Calculate the price for a deal being closed now (or at a given end time).
     *
     * @return array{
     *     actual_minutes: int,
     *     billable_minutes: int,
     *     pricing_rule_id: int|null,
     *     price: float,
     *     rule: PricingRule|null
     * }
     */
    public function calculate(Deal $deal, ?Carbon $endedAt = null): array
    {
        $endedAt ??= now();
        $startedAt = $deal->started_at;

        $actualMinutes = (int) $startedAt->diffInMinutes($endedAt);
        $billableMinutes = $this->applyGraceThreshold($actualMinutes);

        $rule = $this->findApplicableRule(
            workspaceTypeId: $deal->workspace_type_id,
            billableMinutes: $billableMinutes,
            on: $startedAt,
        );

        return [
            'actual_minutes'   => $actualMinutes,
            'billable_minutes' => $billableMinutes,
            'pricing_rule_id'  => $rule?->id,
            'price'            => (float) ($rule?->price ?? 0),
            'rule'             => $rule,
        ];
    }

    // ──────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────

    /**
     * Apply the 10-minute grace rule:
     *   actual ≤ (level + grace)  →  stay at that level.
     *   actual >  (level + grace) →  jump to the next level.
     */
    protected function applyGraceThreshold(int $actualMinutes): int
    {
        // Common duration levels (in minutes). Configurable in future.
        $levels = [30, 60, 90, 120, 180, 240, 300, 360, 480];

        foreach ($levels as $level) {
            if ($actualMinutes <= ($level + $this->graceMinutes)) {
                return $level;
            }
        }

        // Beyond all defined levels — return actual (charged at last rule)
        return $actualMinutes;
    }

    /**
     * Find the pricing rule that was active on the deal's start date
     * and matches the billable duration.
     */
    protected function findApplicableRule(
        int $workspaceTypeId,
        int $billableMinutes,
        Carbon $on
    ): ?PricingRule {
        return PricingRule::query()
            ->where('workspace_type_id', $workspaceTypeId)
            ->where('duration_minutes', '<=', $billableMinutes)
            ->where('effective_from', '<=', $on->toDateString())
            ->where(function ($q) use ($on) {
                $q->whereNull('effective_until')
                  ->orWhere('effective_until', '>=', $on->toDateString());
            })
            ->where('active', true)
            ->orderByDesc('duration_minutes') // pick the highest matching level
            ->first();
    }
}
