<x-app-layout>
    <x-slot name="header">
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
                {{ __('Create Billing Plan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8">
            @include('billing-plans._form')
        </div>
    </div>
</x-app-layout>
