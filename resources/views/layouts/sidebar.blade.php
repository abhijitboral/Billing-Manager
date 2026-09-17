<aside class="hidden sm:block w-56 shrink-0 bg-white border-r border-gray-100 min-h-[calc(100vh-4rem)]">
    <nav class="py-6 px-4 space-y-1">
        <a
            href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
        >
            {{ __('Dashboard') }}
        </a>

        @if (Auth::user()->isSuperAdmin())
            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    {{ __('Super Admin') }}
                </p>

                <a
                    href="{{ route('users.index') }}"
                    class="mt-1 flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    {{ __('Users') }}
                </a>

                <a
                    href="{{ route('register') }}"
                    class="mt-1 flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('register') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    {{ __('Add User') }}
                </a>

                <a
                    href="{{ route('billing-plans.index') }}"
                    class="mt-1 flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('billing-plans.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    {{ __('Billing Plans') }}
                </a>
            </div>
        @endif
    </nav>
</aside>
