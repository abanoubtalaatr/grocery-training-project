@php
    $isRtl = app()->getLocale() === 'ar';
    $pageTitle = View::yieldContent('page-title') ?: ($isRtl ? 'الرئيسية' : 'Dashboard');
@endphp

<header class="bg-white border-b border-gray-100 sticky top-0 z-40" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    <div class="flex items-center justify-between px-6 h-16">

        {{-- Page title + breadcrumb --}}
        <div class="flex flex-col justify-center">
            <h2 class="text-base font-semibold text-gray-900 leading-none">
                {{ $pageTitle }}
            </h2>
            <x-breadcrumb :page="$pageTitle" />
        </div>

        {{-- Right actions --}}
        <div class="flex items-center gap-2">

            {{-- Language Switcher --}}
            <x-language-switcher />

            {{-- Search --}}
            <button class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z" />
                </svg>
            </button>

            {{-- Notifications --}}
            <div class="relative">
                <button class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>
                <span class="absolute top-1.5 {{ $isRtl ? 'left-1.5' : 'right-1.5' }} w-2 h-2 bg-emerald-500 rounded-full ring-2 ring-white"></span>
            </div>

            {{-- Divider --}}
            <div class="w-px h-6 bg-gray-200 mx-1"></div>

            {{-- Avatar + name --}}
            <button class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors group">
                <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff"
                     class="w-7 h-7 rounded-full" alt="Admin">
                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Admin</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

        </div>

    </div>

</header>
