<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', config('app.name'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

<div class="admin-wrapper">

    @if (app()->getLocale() == 'en')

        @include('admin.layouts.sidebar')
    
    @endif


    <div class="main-wrapper">

        @include('admin.layouts.header')

        <main class="content-area">
            @yield('content')
        </main>

    </div>


    @if (app()->getLocale() == 'ar')

    @include('admin.layouts.sidebar')
    
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>