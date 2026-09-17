<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'source_product_id',
    'target_product_id',
    'source_campaign_id',
    'target_campaign_id',
    'days_to_next_billing',
    'target_mid',
    'sort_order',
])]
class BillingPlanRule extends Model
{
    public function billingPlan(): BelongsTo
    {
        return $this->belongsTo(BillingPlan::class);
    }

    protected function casts(): array
    {
        return [
            'source_product_id' => 'integer',
            'target_product_id' => 'integer',
            'source_campaign_id' => 'integer',
            'target_campaign_id' => 'integer',
            'days_to_next_billing' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
