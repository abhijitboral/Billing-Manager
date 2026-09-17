<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\BillingPlanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'is_active'])]
class BillingPlan extends Model
{
    /** @use HasFactory<BillingPlanFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * The mapping rules that belong to this billing plan.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(BillingPlanRule::class)->orderBy('sort_order');
    }

    /**
     * Scope plans by active/inactive status.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}
