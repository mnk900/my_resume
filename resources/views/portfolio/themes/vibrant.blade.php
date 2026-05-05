<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portfolio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; font-family: 'Poppins', sans-serif; }
        .hero { background: linear-gradient(135deg, #ff0080, #7928ca); color: white; padding: 120px 0; border-radius: 0 0 50px 50px; }
        .section-title { font-weight: 900; background: linear-gradient(135deg, #ff0080, #7928ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 3rem; margin-bottom: 50px; text-align: center; }
        .card { border-radius: 20px; border: none; background: #f8f9fa; }
        .btn-vibrant { background: linear-gradient(135deg, #ff0080, #7928ca); color: white; border: none; padding: 10px 25px; border-radius: 50px; text-decoration: none;}
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
