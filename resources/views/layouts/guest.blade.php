<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <title>MyResume.cloud — Portfolio, Talent & Opportunity Network</title>

    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS 5.3.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Authentic Logo-Derived Design Tokens & Compact Sizing -->
    <style>
        :root {
            --brand-primary: #4c75a1;
            --brand-primary-hover: #375c85;
            --brand-secondary: #1e293b;
            --brand-light: #f8fafc;
            --font-headings: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            font-size: 0.875rem;
            background-color: var(--brand-light);
            color: #334155;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-headings);
            font-weight: 700;
            color: var(--brand-secondary);
        }

        .btn-primary {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
            color: #ffffff !important;
            min-height: 38px;
            font-size: 0.875rem;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--brand-primary-hover) !important;
            border-color: var(--brand-primary-hover) !important;
            color: #ffffff !important;
        }
        .text-primary, a {
            color: var(--brand-primary);
        }
        a:hover {
            color: var(--brand-primary-hover);
        }

        .form-control {
            min-height: 38px;
            font-size: 0.875rem;
            border-color: #cbd5e1;
            border-radius: 8px;
        }

        .auth-card {
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            background: #ffffff;
        }
    </style>
</head>
<body class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 text-center mb-4">
                <a href="{{ route('welcome') }}" class="text-decoration-none d-inline-block">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud" style="height: 52px; max-height: 52px; object-fit: contain;" class="rounded shadow-sm">
                </a>
            </div>
            <div class="w-100"></div>
            <div class="col-md-7 col-lg-5">
                <div class="auth-card">
                    <div class="p-4 p-sm-4">
                        {{ $slot }}
                    </div>
                </div>
                <div class="text-center mt-3">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} MyResume.cloud. Your professional identity connects you to opportunities.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.loader')
</body>
</html>
