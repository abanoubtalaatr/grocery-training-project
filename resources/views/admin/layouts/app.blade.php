<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('title') </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<main class="p-4"
    style="
        {{ app()->getLocale() === 'ar' }}
    ">

    @include('admin.partials.sidebar') <div
        style=" min-height:100vh; {{ app()->getLocale() === 'ar' ? 'margin-right:260px;' : 'margin-left:260px;' }} ">
        @include('admin.partials.navbar') <main class="p-4"> @yield('content') </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
