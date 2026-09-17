@php
    $editing = isset($billingPlan);
    $formRules = old('rules');

    if ($formRules === null) {
        $formRules = $editing
            ? $billingPlan->rules->map(fn ($rule) => [
                'source_product_id' => $rule->source_product_id,
                'target_product_id' => $rule->target_product_id,
                'source_campaign_id' => $rule->source_campaign_id,
                'target_campaign_id' => $rule->target_campaign_id,
                'days_to_next_billing' => $rule->days_to_next_billing,
                'target_mid' => $rule->target_mid,
            ])->values()->all()
            : [[
                'source_product_id' => '',
                'target_product_id' => '',
                'source_campaign_id' => '',
                'target_campaign_id' => '',
                'days_to_next_billing' => '',
                'target_mid' => '',
            ]];
    }
@endphp

<form
    method="POST"
    action="{{ $editing ? route('billing-plans.update', $billingPlan) : route('billing-plans.store') }}"
    x-data="billingPlanRepeater(@js($formRules))"
    class="space-y-8"
>
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-6">
        <div class="max-w-2xl">
            <x-input-label for="name" :value="__('Plan Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="block mt-2 w-full"
                :value="old('name', $billingPlan->name ?? '')"
                maxlength="255"
                required
                autofocus
                placeholder="e.g. Apex Vitality ME Setup"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
    </div>

    <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Plan Configuration') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Each row defines one source order mapping and its next rebill configuration.') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="addRule()"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    + {{ __('Add Rule') }}
                </button>
            </div>

            <div class="mt-6 overflow-x-auto">
                <div class="min-w-[1200px]">
                    <div class="grid grid-cols-[1.1fr_1.1fr_1.1fr_1.1fr_1fr_1.25fr_48px] gap-3 border-b border-gray-200 pb-3">
                        <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ __('Source Product ID') }} <span class="text-red-500">*</span></div>
                        <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ __('Target Product ID') }} <span class="text-red-500">*</span></div>
                        <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ __('Source Campaign ID') }} <span class="text-red-500">*</span></div>
                        <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ __('Target Campaign ID') }} <span class="text-red-500">*</span></div>
                        <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ __('Days to Next Billing') }} <span class="text-red-500">*</span></div>
                        <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ __('Target/Assigned MID') }} <span class="text-red-500">*</span></div>
                        <div></div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <template x-for="(rule, index) in rules" :key="rule._key">
                            <div class="grid grid-cols-[1.1fr_1.1fr_1.1fr_1.1fr_1fr_1.25fr_48px] gap-3 py-4 items-start">
                                <div>
                                    <input
                                        type="number"
                                        min="1"
                                        step="1"
                                        :name="`rules[${index}][source_product_id]`"
                                        x-model="rule.source_product_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <p x-show="hasError(index, 'source_product_id')" x-text="error(index, 'source_product_id')" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <input
                                        type="number"
                                        min="1"
                                        step="1"
                                        :name="`rules[${index}][target_product_id]`"
                                        x-model="rule.target_product_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <p x-show="hasError(index, 'target_product_id')" x-text="error(index, 'target_product_id')" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <input
                                        type="number"
                                        min="1"
                                        step="1"
                                        :name="`rules[${index}][source_campaign_id]`"
                                        x-model="rule.source_campaign_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <p x-show="hasError(index, 'source_campaign_id')" x-text="error(index, 'source_campaign_id')" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <input
                                        type="number"
                                        min="1"
                                        step="1"
                                        :name="`rules[${index}][target_campaign_id]`"
                                        x-model="rule.target_campaign_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <p x-show="hasError(index, 'target_campaign_id')" x-text="error(index, 'target_campaign_id')" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <input
                                        type="number"
                                        min="1"
                                        max="65535"
                                        step="1"
                                        :name="`rules[${index}][days_to_next_billing]`"
                                        x-model="rule.days_to_next_billing"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <p x-show="hasError(index, 'days_to_next_billing')" x-text="error(index, 'days_to_next_billing')" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <input
                                        type="text"
                                        maxlength="100"
                                        :name="`rules[${index}][target_mid]`"
                                        x-model="rule.target_mid"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="MID"
                                        required
                                    >
                                    <p x-show="hasError(index, 'target_mid')" x-text="error(index, 'target_mid')" class="mt-1 text-xs text-red-600"></p>
                                </div>

                                <div class="flex justify-center pt-2">
                                    <button
                                        type="button"
                                        @click="removeRule(index)"
                                        :disabled="rules.length === 1"
                                        :title="rules.length === 1 ? 'At least one rule is required' : 'Remove rule'"
                                        class="text-gray-400 hover:text-red-600 disabled:opacity-30 disabled:cursor-not-allowed"
                                    >
                                        <span class="sr-only">{{ __('Remove rule') }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            @if ($errors->has('rules'))
                <div class="mt-4 text-sm text-red-600">{{ $errors->first('rules') }}</div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('billing-plans.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900"
        >
            {{ __('Cancel') }}
        </a>

        <x-primary-button>
            {{ $editing ? __('Update Billing Plan') : __('Create Billing Plan') }}
        </x-primary-button>
    </div>
</form>

<script>
    function billingPlanRepeater(initialRules) {
        return {
            rules: initialRules.map((rule, index) => ({
                ...rule,
                _key: `${Date.now()}-${index}-${Math.random().toString(36).slice(2)}`
            })),
            errors: @js($errors->toArray()),

            addRule() {
                this.rules.push({
                    source_product_id: '',
                    target_product_id: '',
                    source_campaign_id: '',
                    target_campaign_id: '',
                    days_to_next_billing: '',
                    target_mid: '',
                    _key: `${Date.now()}-${Math.random().toString(36).slice(2)}`
                });
            },

            removeRule(index) {
                if (this.rules.length > 1) {
                    this.rules.splice(index, 1);
                }
            },

            error(index, field) {
                const messages = this.errors[`rules.${index}.${field}`] || [];
                return messages[0] || '';
            },

            hasError(index, field) {
                return this.error(index, field) !== '';
            }
        };
    }
</script>
