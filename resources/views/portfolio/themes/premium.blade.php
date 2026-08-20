<!DOCTYPE html>
<html lang="en" style="overflow-x: hidden; width: 100%; margin: 0; padding: 0;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body style="background-color: #050505; color: #ffffff; margin: 0; padding: 0; box-sizing: border-box; overflow-x: hidden; width: 100%;">
    
    @yield('content')

    @include('partials.loader')
    @stack('scripts')
</body>
</html>
