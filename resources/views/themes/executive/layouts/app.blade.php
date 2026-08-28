<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name ?? 'Executive Portfolio' }} | Executive Theme</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Syne', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
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
</head>
<body class="bg-[#0B0F17] text-slate-100 font-sans antialiased overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Header Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-[#0B0F17]/80 border-b border-white/10 transition-all duration-300" id="mainNav">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#hero" class="font-heading font-extrabold text-2xl tracking-wider text-white hover:text-indigo-400 transition-colors">
                {{ strtoupper(explode(' ', $user->name ?? 'EXECUTIVE')[0]) }}<span class="text-indigo-500">.</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
                <a href="#about" class="text-slate-300 hover:text-white transition-colors">About</a>
                <a href="#experience" class="text-slate-300 hover:text-white transition-colors">Experience</a>
                <a href="#skills" class="text-slate-300 hover:text-white transition-colors">Expertise</a>
                <a href="#projects" class="text-slate-300 hover:text-white transition-colors">Showcase</a>
                <a href="#contact" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold shadow-lg shadow-indigo-500/25 hover:opacity-95 transition-all">Get in Touch</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/10 py-12 bg-[#070A10] text-center text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p>© {{ date('Y') }} {{ $user->name ?? 'Executive Portfolio' }}. All Rights Reserved &bull; Powered by <a href="https://itechgb.com/" target="_blank" class="text-slate-200 underline font-medium">Innovative Technologies GB</a></p>
            <div class="flex space-x-6 text-xs text-slate-400">
                <a href="#hero" class="hover:text-white transition-colors">Back to Top ↑</a>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="{{ asset('themes/executive/js/executive.js') }}"></script>
</body>
</html>
