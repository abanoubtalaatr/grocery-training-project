{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.login') }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 antialiased min-h-screen flex items-center justify-center p-8">

    <div class="w-full max-w-md">

        {{-- Brand --}}
        <div class="flex items-center gap-3 justify-center mb-8">
            <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.76-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </div>
            <span class="text-white text-lg font-semibold tracking-tight">{{ config('app.name') }}</span>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-8">

            {{-- Header + Language Switcher --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-white font-medium text-base">{{ __('app.welcome_back') }}</p>
                    <p class="text-gray-500 text-sm mt-0.5">{{ __('app.sign_in_to_account') }}</p>
                </div>

                {{-- Language Switcher --}}
                <div class="flex gap-1 bg-gray-800 rounded-lg p-1">
                    <a href="{{ route('lang.switch', 'ar') }}"
                        class="px-3 py-1 rounded-md text-xs font-medium transition-all
                              {{ app()->getLocale() === 'ar' ? 'bg-emerald-500 text-white' : 'text-gray-400 hover:text-white' }}">
                        AR
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="px-3 py-1 rounded-md text-xs font-medium transition-all
                              {{ app()->getLocale() === 'en' ? 'bg-emerald-500 text-white' : 'text-gray-400 hover:text-white' }}">
                        EN
                    </a>
                </div>
            </div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg px-4 py-3 mb-5">
                    <p class="text-red-400 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path stroke-linecap="round" d="M12 8v4m0 4h.01" />
                        </svg>
                        {{ $errors->first() }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-gray-400 text-xs mb-1.5">{{ __('app.email') }}</label>
                    <div class="relative">
                        <svg class="absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@store.com"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg
                                      {{ app()->getLocale() === 'ar' ? 'pr-9 pl-4' : 'pl-9 pr-4' }}
                                      py-2.5 text-gray-200 text-sm placeholder-gray-600
                                      focus:outline-none focus:border-emerald-500 transition-colors"
                            required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label class="block text-gray-400 text-xs mb-1.5">{{ __('app.password') }}</label>
                    <div class="relative">
                        <svg class="absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <input type="password" name="password" id="password_input" placeholder="••••••••"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg
                      {{ app()->getLocale() === 'ar' ? 'pr-9 pl-10' : 'pl-9 pr-10' }}
                      py-2.5 text-gray-200 text-sm placeholder-gray-600
                      focus:outline-none focus:border-emerald-500 transition-colors"
                            required>

                        {{-- Eye toggle --}}
                        <button type="button" id="toggle_password"
                            class="absolute {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            {{-- Eye open --}}
                            <svg id="icon_eye" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{-- Eye closed --}}
                            <svg id="icon_eye_slash" class="w-4 h-4 hidden" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-emerald-500 w-3.5 h-3.5">
                        <span class="text-gray-400 text-sm">{{ __('app.remember_me') }}</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium
                               text-sm rounded-lg py-2.5 transition-colors">
                    {{ __('app.login') }}
                </button>

            </form>
        </div>

        <p class="text-center text-gray-700 text-xs mt-6">
            {{ config('app.name') }} © {{ date('Y') }}
        </p>
    </div>

    <script>
    document.getElementById('toggle_password').addEventListener('click', function () {
        const input = document.getElementById('password_input');
        const eyeOpen = document.getElementById('icon_eye');
        const eyeSlash = document.getElementById('icon_eye_slash');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeSlash.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeSlash.classList.add('hidden');
        }
    });
</script>
</body>

</html>
