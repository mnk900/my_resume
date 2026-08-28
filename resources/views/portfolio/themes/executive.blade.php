<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Space Grotesk', 'Syne', 'sans-serif'],
                        sans: ['Inter', 'Plus Jakarta Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            gold: '#C5A880',
                            accent: '#6366F1',
                            dark: '#0B0F17',
                            surface: '#121826',
                            card: '#1B2436'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('themes/executive/css/executive.css') }}">
    @stack('styles')
</head>
<body class="bg-[#0B0F17] text-slate-100 font-sans antialiased overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    @yield('content')

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="{{ asset('themes/executive/js/executive.js') }}"></script>
    @include('partials.loader')
    @stack('scripts')
</body>
</html>
