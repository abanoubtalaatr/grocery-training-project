<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <title>@yield('title')</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center">

        @yield('content')

    </div>

</body>

</html>
