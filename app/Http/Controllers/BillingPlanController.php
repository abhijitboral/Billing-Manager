<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingPlanRequest;
use App\Http\Requests\UpdateBillingPlanRequest;
use App\Models\BillingPlan;
use App\Services\BillingPlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BillingPlanController extends Controller
{
    public function __construct(
        private readonly BillingPlanService $billingPlanService
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = (string) $request->input('status', 'all');

        $plans = BillingPlan::query()
            ->withCount('rules')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('rules', function ($ruleQuery) use ($search): void {
                            $ruleQuery->where('source_product_id', 'like', '%'.$search.'%')
                                ->orWhere('target_product_id', 'like', '%'.$search.'%')
                                ->orWhere('source_campaign_id', 'like', '%'.$search.'%')
                                ->orWhere('target_campaign_id', 'like', '%'.$search.'%')
                                ->orWhere('target_mid', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($status === 'active', fn ($query) => $query->active())
            ->when($status === 'inactive', fn ($query) => $query->inactive())
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('billing-plans.index', compact('plans', 'search', 'status'));
    }

    public function create(): View
    {
        return view('billing-plans.create');
    }

    public function store(StoreBillingPlanRequest $request): RedirectResponse
    {
        $this->billingPlanService->create($request->validated());

        return redirect()
            ->route('billing-plans.index')
            ->with('status', 'Billing plan created successfully.');
    }

    public function show(BillingPlan $billingPlan): View
    {
        $billingPlan->load('rules');

        return view('billing-plans.show', compact('billingPlan'));
    }

    public function edit(BillingPlan $billingPlan): View
    {
        $billingPlan->load('rules');

        return view('billing-plans.edit', compact('billingPlan'));
    }

    public function update(
        UpdateBillingPlanRequest $request,
        BillingPlan $billingPlan
    ): RedirectResponse {
        $this->billingPlanService->update($billingPlan, $request->validated());

        return redirect()
            ->route('billing-plans.index')
            ->with('status', 'Billing plan updated successfully.');
    }

    public function destroy(BillingPlan $billingPlan): RedirectResponse
    {
        $this->billingPlanService->delete($billingPlan);

        return redirect()
            ->route('billing-plans.index')
            ->with('status', 'Billing plan deleted successfully.');
    }

    public function toggleActive(BillingPlan $billingPlan): RedirectResponse
    {
        $billingPlan->update([
            'is_active' => ! $billingPlan->is_active,
        ]);

        return redirect()
            ->route('billing-plans.index')
            ->with(
                'status',
                'Billing plan '.($billingPlan->is_active ? 'activated' : 'deactivated').' successfully.'
            );
    }
}
