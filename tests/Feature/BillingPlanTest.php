<?php

namespace Tests\Feature;

use App\Models\BillingPlan;
use App\Models\User;
use App\Services\BillingPlanService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingPlanTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'is_super_admin' => true,
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_create_a_billing_plan_with_multiple_rules(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('billing-plans.store'), [
            'name' => 'Apex Vitality ME Setup',
            'rules' => [
                [
                    'source_product_id' => 197,
                    'target_product_id' => 143,
                    'source_campaign_id' => 11,
                    'target_campaign_id' => 11,
                    'days_to_next_billing' => 14,
                    'target_mid' => 'MID-01',
                ],
                [
                    'source_product_id' => 143,
                    'target_product_id' => 149,
                    'source_campaign_id' => 11,
                    'target_campaign_id' => 11,
                    'days_to_next_billing' => 17,
                    'target_mid' => 'MID-02',
                ],
            ],
        ]);

        $plan = BillingPlan::query()->with('rules')->first();

        $response
            ->assertRedirect(route('billing-plans.index'))
            ->assertSessionHas('status', 'Billing plan created successfully.');

        $this->assertNotNull($plan);
        $this->assertSame('Apex Vitality ME Setup', $plan->name);
        $this->assertTrue($plan->is_active);
        $this->assertCount(2, $plan->rules);
        $this->assertSame(197, $plan->rules[0]->source_product_id);
        $this->assertSame(143, $plan->rules[0]->target_product_id);
        $this->assertSame('MID-01', $plan->rules[0]->target_mid);
    }

    public function test_duplicate_source_mapping_is_rejected(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('billing-plans.store'), [
            'name' => 'Duplicate Test',
            'rules' => [
                [
                    'source_product_id' => 197,
                    'target_product_id' => 143,
                    'source_campaign_id' => 11,
                    'target_campaign_id' => 11,
                    'days_to_next_billing' => 14,
                    'target_mid' => 'MID-01',
                ],
                [
                    'source_product_id' => 197,
                    'target_product_id' => 149,
                    'source_campaign_id' => 11,
                    'target_campaign_id' => 11,
                    'days_to_next_billing' => 17,
                    'target_mid' => 'MID-02',
                ],
            ],
        ]);

        $response
            ->assertSessionHasErrors('rules.1.source_product_id');

        $this->assertDatabaseCount('billing_plans', 0);
    }

    public function test_super_admin_can_update_rules_and_toggle_status(): void
    {
        $admin = $this->admin();

        $plan = BillingPlan::factory()->create([
            'name' => 'Original Plan',
            'is_active' => true,
        ]);

        $plan->rules()->create([
            'source_product_id' => 197,
            'target_product_id' => 143,
            'source_campaign_id' => 11,
            'target_campaign_id' => 11,
            'days_to_next_billing' => 14,
            'target_mid' => 'MID-01',
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($admin)->put(route('billing-plans.update', $plan), [
            'name' => 'Updated Plan',
            'rules' => [
                [
                    'source_product_id' => 143,
                    'target_product_id' => 149,
                    'source_campaign_id' => 11,
                    'target_campaign_id' => 11,
                    'days_to_next_billing' => 30,
                    'target_mid' => 'MID-03',
                ],
            ],
        ]);

        $response->assertRedirect(route('billing-plans.index'));

        $this->assertDatabaseHas('billing_plans', [
            'id' => $plan->id,
            'name' => 'Updated Plan',
        ]);

        $this->assertDatabaseHas('billing_plan_rules', [
            'billing_plan_id' => $plan->id,
            'source_product_id' => 143,
            'target_product_id' => 149,
            'days_to_next_billing' => 30,
            'target_mid' => 'MID-03',
        ]);

        $this->actingAs($admin)
            ->patch(route('billing-plans.toggle-active', $plan))
            ->assertRedirect(route('billing-plans.index'));

        $this->assertDatabaseHas('billing_plans', [
            'id' => $plan->id,
            'is_active' => false,
        ]);
    }

    public function test_non_super_admin_cannot_access_billing_plan_management(): void
    {
        $user = User::factory()->create([
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('billing-plans.index'))
            ->assertForbidden();
    }

    public function test_next_billing_date_is_calculated_from_previous_billing_date(): void
    {
        $service = app(BillingPlanService::class);
        $billingDate = Carbon::create(2026, 9, 17, 10, 30, 0);

        $next = $service->calculateNextBillingDate($billingDate, 30);

        $this->assertSame('2026-10-17 10:30:00', $next->format('Y-m-d H:i:s'));
    }
}
