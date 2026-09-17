<?php

namespace Database\Factories;

use App\Models\BillingPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BillingPlan>
 */
class BillingPlanFactory extends Factory
{
    protected $model = BillingPlan::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'is_active' => true,
        ];
    }
}
