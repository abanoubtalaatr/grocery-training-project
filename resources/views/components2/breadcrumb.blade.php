@php $isRtl = app()->getLocale() === 'ar'; @endphp

<nav aria-label="Breadcrumb" class="mt-0.5" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    <ol class="flex items-center gap-1.5 text-xs text-gray-400">

        <li>
            <a href="{{ route('dashboard') }}" class="hover:text-gray-600 transition-colors">
                {{ $isRtl ? 'الرئيسية' : 'Home' }}
            </a>
        </li>

        @if(isset($page) && !in_array($page, ['Dashboard', 'الرئيسية']))

            <li class="flex items-center">
                {{-- Chevron flips automatically with RTL dir --}}
                <svg class="w-3 h-3 text-gray-300 {{ $isRtl ? 'rotate-180' : '' }}"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </li>

            <li class="text-gray-500 font-medium">
                {{ $page }}
            </li>

        @endif

    </ol>

</nav>
