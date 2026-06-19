<div class="flex items-center bg-gray-100 rounded-lg p-0.5 text-xs font-semibold">

    <a href="{{ route('lang.switch', 'en') }}"
       class="px-2.5 py-1.5 rounded-md transition-all duration-150
              {{ app()->getLocale() === 'en'
                    ? 'bg-white text-gray-800 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700' }}">
        EN
    </a>

    <a href="{{ route('lang.switch', 'ar') }}"
       class="px-2.5 py-1.5 rounded-md transition-all duration-150
              {{ app()->getLocale() === 'ar'
                    ? 'bg-white text-gray-800 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700' }}">
        AR
    </a>

</div>
