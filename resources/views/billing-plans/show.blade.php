<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a
                    href="{{ route('billing-plans.index') }}"
                    title="{{ __('Back') }}"
                    class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                >
                    <span class="sr-only">{{ __('Back') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L6.414 9H15a1 1 0 110 2H6.414l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $billingPlan->name }}
                </h2>
            </div>

            <a
                href="{{ route('billing-plans.edit', $billingPlan) }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                {{ __('Edit Billing Plan') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Plan Name') }}</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $billingPlan->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Status') }}</p>
                        <div class="mt-1">
                            @if ($billingPlan->isActive())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('Active') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Created Date') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $billingPlan->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Updated Date') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $billingPlan->updated_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Billing Plan Rules') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Initial Order: Source Product + Source Campaign → Next Rebill: Target Product + Target Campaign + Assigned MID.') }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Source Product ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Target Product ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Source Campaign ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Target Campaign ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Days to Next Billing') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Target/Assigned MID') }}</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($billingPlan->rules as $index => $rule)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $rule->source_product_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $rule->target_product_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $rule->source_campaign_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $rule->target_campaign_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $rule->days_to_next_billing }} {{ Str::plural('day', $rule->days_to_next_billing) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $rule->target_mid }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-blue-900">{{ __('Billing Date Calculation') }}</h3>
                <p class="mt-1 text-sm text-blue-800">
                    {{ __('For each applicable rule: Next Billing Date = Initial/Previous Billing Date + Days to Next Billing.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
