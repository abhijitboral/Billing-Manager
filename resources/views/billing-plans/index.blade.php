<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Billing Plans') }}
            </h2>

            <a
                href="{{ route('billing-plans.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
                + {{ __('Create Billing Plan') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200 text-sm font-medium text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700">
                    {{ __('The requested action could not be completed.') }}
                </div>
            @endif

            <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-4 mb-5">
                <form method="GET" action="{{ route('billing-plans.index') }}" class="grid grid-cols-1 md:grid-cols-[1fr_180px_auto_auto] gap-3">
                    <div>
                        <x-text-input
                            name="search"
                            type="search"
                            class="block w-full"
                            :value="$search"
                            placeholder="Search plan, product, campaign or MID..."
                            aria-label="Search billing plans"
                        />
                    </div>

                    <div>
                        <label for="status" class="sr-only">{{ __('Status') }}</label>
                        <select
                            id="status"
                            name="status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="all" @selected($status === 'all')>{{ __('All Statuses') }}</option>
                            <option value="active" @selected($status === 'active')>{{ __('Active') }}</option>
                            <option value="inactive" @selected($status === 'inactive')>{{ __('Inactive') }}</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                    >
                        {{ __('Search') }}
                    </button>

                    @if ($search !== '' || $status !== 'all')
                        <a
                            href="{{ route('billing-plans.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50"
                        >
                            {{ __('Clear') }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Plan Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Rules') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Created') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Updated') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($plans as $plan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('billing-plans.show', $plan) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $plan->name }}
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $plan->rules_count }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($plan->isActive())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ __('Inactive') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $plan->created_at->format('M d, Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $plan->updated_at->format('M d, Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-4">
                                            <a
                                                href="{{ route('billing-plans.show', $plan) }}"
                                                title="{{ __('View') }}"
                                                class="text-gray-600 hover:text-gray-900"
                                            >
                                                <span class="sr-only">{{ __('View') }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 3C5.455 3 1.73 6.134 1 10c.73 3.866 4.455 7 9 7s8.27-3.134 9-7c-.73-3.866-4.455-7-9-7zm0 11a4 4 0 110-8 4 4 0 010 8zm0-2a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </a>

                                            <a
                                                href="{{ route('billing-plans.edit', $plan) }}"
                                                title="{{ __('Edit') }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                <span class="sr-only">{{ __('Edit') }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a1 1 0 00-1.414 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a1 1 0 012 2H5v9h9v-4a1 1 0 112 0v4a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 01-2-2h4a1 1 0 012-1h5a1 1 0 011 1v1h-1V6H7v1h6V5a2 2 0 00-2-2H8a2 2 0 01-2 2H4a2 2 0 01-2 2z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('billing-plans.toggle-active', $plan) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    title="{{ $plan->isActive() ? __('Deactivate') : __('Activate') }}"
                                                    class="{{ $plan->isActive() ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}"
                                                >
                                                    <span class="sr-only">{{ $plan->isActive() ? __('Deactivate') : __('Activate') }}</span>
                                                    @if ($plan->isActive())
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM6.28 6.22a.75.75 0 01-1.06 1.06L8.94 11l-3.72 3.72a.75.75 0 101.06 1.06L10 12.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 11l3.72-3.72a.75.75 0 00-1.06-1.06L10 9.94 6.28 6.22z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a1 1 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <form
                                                method="POST"
                                                action="{{ route('billing-plans.destroy', $plan) }}"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this billing plan? All mapping rules will also be deleted.') }}');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="{{ __('Delete') }}" class="text-red-600 hover:text-red-900">
                                                    <span class="sr-only">{{ __('Delete') }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                        {{ $search !== '' || $status !== 'all' ? __('No billing plans match your filters.') : __('No billing plans found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($plans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $plans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
