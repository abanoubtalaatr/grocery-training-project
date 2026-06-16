<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

    @include('admin.partials.sidebar')

    <div style="margin-left:260px; min-height:100vh;">
        @include('admin.partials.navbar')

        <main class="p-4">
            @yield('content')
        </main>
    </div>

</body>
</html>