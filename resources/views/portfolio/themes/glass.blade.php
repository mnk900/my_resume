<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portfolio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --primary-accent: #00f2fe;
            --secondary-accent: #4face6;
        }

        body {
            background: radial-gradient(circle at top right, #1a1a2e, #16213e, #0f3460);
            background-attachment: fixed;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .hero {
            padding: 150px 0 100px;
            background: transparent;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .glass-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-accent);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(to right, var(--primary-accent), var(--secondary-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 40px;
        }

        .progress {
            background: rgba(255, 255, 255, 0.1);
            height: 12px;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary-accent), var(--secondary-accent));
            box-shadow: 0 0 15px var(--primary-accent);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-accent), var(--secondary-accent));
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 242, 254, 0.3);
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 242, 254, 0.5);
        }

        .text-muted { color: #bdbdbd !important; }
        
        .timeline::before {
            background: var(--glass-border);
        }

        .portfolio-item img {
            transition: transform 0.6s ease;
        }

        .portfolio-item:hover img {
            transform: scale(1.1);
        }

        /* Float Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
