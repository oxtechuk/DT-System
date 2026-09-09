<?php

namespace App\Services\Deal;

use App\Models\Deal;
use App\Models\PricingRule;
use Carbon\Carbon;

/**
 * DealPricingService
 *
 * Centralised pricing engine for workspace sessions.
 *
 * Business Rules (from blueprint):
 *   - 1:05 → billed as 1 hour
 *   - At 1:10 → next pricing level
 *   - Pricing levels are configurable in pricing_rules table
 *   - Must use the pricing rule effective AT THE TIME the deal started
 *   - Never uses current price — always historical
 */
class DealPricingService
{
    /**
     * Calculate the billable duration (in minutes) applying the 10-minute threshold rule.
     *
     * Rule: If actual duration exceeds a level by ≤9 minutes → stay at current level.
     *       If actual duration exceeds a level by ≥10 minutes → move to next level.
     *
     * Example with levels [30, 60, 90, 120]:
     *   Actual 65 min → 60 min (65-60 = 5 < 10, stays at 60)
     *   Actual 70 min → 90 min (70-60 = 10, moves to 90)
     *   Actual 35 min → 30 min (35-30 = 5 < 10, stays at 30)
     *   Actual 40 min → 60 min (40-30 = 10, moves to 60)
     */
    public function billableDurationMinutes(int $actualMinutes, array $availableLevels): int
    {
        if (empty($availableLevels)) {
            return $actualMinutes;
        }

        // Sort levels ascending
        sort($availableLevels);

        $count = count($availableLevels);
        for ($i = 0; $i < $count; $i++) {
            $currentLevel = $availableLevels[$i];

            // If this is the last level, or actual is within grace period of current level (<= level + 9)
            if ($i === $count - 1 || $actualMinutes <= ($currentLevel + 9)) {
                return $currentLevel;
            }
        }

        return end($availableLevels);
    }

    /**
     * Calculate the session price for a deal.
     *
     * Returns an array with:
     *   actual_minutes   : raw duration
     *   billable_minutes : after applying threshold rule
     *   pricing_rule_id  : the historical rule used
     *   session_price    : the amount to charge
     */
    public function calculateForDeal(Deal $deal): array
    {
        $startedAt     = $deal->started_at;
        $endedAt       = $deal->ended_at ?? now();
        $actualMinutes = (int) ceil($startedAt->diffInSeconds($endedAt) / 60);

        // Get all available pricing levels for this workspace type at deal start time
        $rules = PricingRule::where('workspace_type_id', $deal->workspace_type_id)
            ->effectiveAt($startedAt)
            ->active()
            ->orderBy('duration_minutes')
            ->get();

        if ($rules->isEmpty()) {
            throw new \RuntimeException(
                "No pricing rules found for workspace_type_id={$deal->workspace_type_id} at {$startedAt}"
            );
        }

        $availableLevels = $rules->pluck('duration_minutes')->toArray();
        $billableMinutes = $this->billableDurationMinutes($actualMinutes, $availableLevels);

        // Find the matching rule
        $matchedRule = $rules->firstWhere('duration_minutes', $billableMinutes);

        // Fallback: use the highest available rule if billable exceeds all levels
        if (! $matchedRule) {
            $matchedRule = $rules->last();
            $billableMinutes = $matchedRule->duration_minutes;
        }

        return [
            'actual_minutes'   => $actualMinutes,
            'billable_minutes' => $billableMinutes,
            'pricing_rule_id'  => $matchedRule->id,
            'session_price'    => (float) $matchedRule->price,
        ];
    }
}
