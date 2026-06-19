@php $isRtl = app()->getLocale() === 'ar'; @endphp

<footer class="border-t border-gray-100 bg-white mt-auto" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    <div class="px-6 py-4 flex items-center justify-between">

        <p class="text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ $isRtl ? 'لوحة البقالة. جميع الحقوق محفوظة.' : 'Grocery Admin. All rights reserved.' }}
        </p>

        <div class="flex items-center gap-4 text-xs text-gray-400">
            @if($isRtl)
                <a href="#" class="hover:text-gray-600 transition-colors">الدعم</a>
                <a href="#" class="hover:text-gray-600 transition-colors">الشروط</a>
                <a href="#" class="hover:text-gray-600 transition-colors">الخصوصية</a>
            @else
                <a href="#" class="hover:text-gray-600 transition-colors">Privacy</a>
                <a href="#" class="hover:text-gray-600 transition-colors">Terms</a>
                <a href="#" class="hover:text-gray-600 transition-colors">Support</a>
            @endif
        </div>

    </div>

</footer>
