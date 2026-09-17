<?php

namespace App\Services;

use App\Models\BillingPlan;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class BillingPlanService
{
    /**
     * Create a plan and all of its mapping rules atomically.
     */
    public function create(array $data): BillingPlan
    {
        return DB::transaction(function () use ($data): BillingPlan {
            $plan = BillingPlan::create([
                'name' => trim($data['name']),
                'is_active' => true,
            ]);

            $this->syncRules($plan, $data['rules']);

            return $plan->load('rules');
        });
    }

    /**
     * Update a plan and replace its complete rule set atomically.
     */
    public function update(BillingPlan $plan, array $data): BillingPlan
    {
        return DB::transaction(function () use ($plan, $data): BillingPlan {
            $plan->update([
                'name' => trim($data['name']),
            ]);

            // The repeater represents the complete configuration, so replacing
            // the existing rows prevents stale/deleted mappings from remaining.
            $plan->rules()->delete();
            $this->syncRules($plan, $data['rules']);

            return $plan->load('rules');
        });
    }

    /**
     * Delete a plan and its rules.
     *
     * The FK cascade also protects data integrity if a plan is deleted
     * outside this service.
     */
    public function delete(BillingPlan $plan): void
    {
        DB::transaction(function () use ($plan): void {
            $plan->delete();
        });
    }

    /**
     * Calculate the next billing date from the initial/previous billing date.
     */
    public function calculateNextBillingDate(
        CarbonInterface $billingDate,
        int $daysToNextBilling
    ): CarbonInterface {
        return $billingDate->copy()->addDays($daysToNextBilling);
    }

    private function syncRules(BillingPlan $plan, array $rules): void
    {
        foreach (array_values($rules) as $index => $rule) {
            $plan->rules()->create([
                'source_product_id' => (int) $rule['source_product_id'],
                'target_product_id' => (int) $rule['target_product_id'],
                'source_campaign_id' => (int) $rule['source_campaign_id'],
                'target_campaign_id' => (int) $rule['target_campaign_id'],
                'days_to_next_billing' => (int) $rule['days_to_next_billing'],
                'target_mid' => trim($rule['target_mid']),
                'sort_order' => $index,
            ]);
        }
    }
}
