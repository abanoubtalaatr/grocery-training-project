<nav aria-label="Breadcrumb" class="mt-0.5">

    <ol class="flex items-center gap-1.5 text-xs text-gray-400">

        <li>
            <a href="{{ route('dashboard') }}"
               class="hover:text-gray-600 transition-colors">
                {{ __('app.home') }}
            </a>
        </li>

        @if(isset($page) && $page !== __('app.dashboard'))

            <li class="flex items-center">

                @if(app()->getLocale() === 'ar')

                    <svg class="w-3 h-3 text-gray-300"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2.5"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 4.5l-7.5 7.5 7.5 7.5" />
                    </svg>

                @else

                    <svg class="w-3 h-3 text-gray-300"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2.5"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>

                @endif

            </li>

            <li class="text-gray-500 font-medium">
                {{ $page }}
            </li>

        @endif

    </ol>

</nav>
