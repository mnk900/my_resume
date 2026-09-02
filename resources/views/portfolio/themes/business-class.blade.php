<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo')
    
    <!-- Google Fonts: Cinzel, Outfit, Playfair Display, Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
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
                        display: ['Outfit', 'Space Grotesk', 'sans-serif'],
                        serif: ['Cinzel', 'Playfair Display', 'serif'],
                        sans: ['Plus Jakarta Sans', 'Outfit', 'sans-serif'],
                        mono: ['Space Grotesk', 'monospace'],
                    },
                    colors: {
                        bc: {
                            gold: '#D4AF37',
                            goldLight: '#F3E5AB',
                            goldDark: '#AA771C',
                            bg: '#07090E',
                            card: '#0F131C',
                            border: 'rgba(212, 175, 55, 0.25)',
                        }
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-[#07090E] text-slate-100 font-sans antialiased overflow-x-hidden selection:bg-amber-500 selection:text-slate-950">

    @yield('content')

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 800, once: true });
            }
        });
    </script>
    @include('partials.loader')
    @stack('scripts')
</body>
</html>
