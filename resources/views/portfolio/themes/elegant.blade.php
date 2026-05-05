<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portfolio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fdfdfd; color: #444; font-family: 'Garamond', serif; }
        .hero { background: #6610f2; color: white; padding: 150px 0; clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
        .section-title { font-family: sans-serif; text-transform: uppercase; letter-spacing: 2px; color: #6610f2; margin-bottom: 40px; text-align: center;}
        .card { border-radius: 0; border: 1px solid #eee; transition: all 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(102, 16, 242, 0.1); }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
