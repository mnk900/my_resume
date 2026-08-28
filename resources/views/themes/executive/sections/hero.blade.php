<section id="hero" class="min-h-screen flex items-center justify-center relative pt-28 pb-16 px-6 overflow-hidden">
    <div class="absolute top-1/4 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center w-full">
        <div class="lg:col-span-7 space-y-6" data-aos="fade-right" data-aos-duration="1000">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-400 text-xs font-semibold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                <span>{{ $user->title ?? 'Executive Professional & Technology Architect' }}</span>
            </div>

            <h1 class="text-5xl lg:text-7xl font-heading font-extrabold text-white leading-tight tracking-tight">
                Architecting <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-amber-300">Modern Digital</span> Ecosystems
            </h1>

            <p class="text-lg text-slate-300 max-w-xl font-light leading-relaxed">
                {{ $user->summary ?? 'Driving large-scale transformations, mission-critical digital infrastructures, and scalable technology roadmaps with strategic precision.' }}
            </p>

            <div class="flex flex-wrap gap-4 pt-4">
                <a href="#experience" class="px-8 py-4 rounded-xl bg-white text-slate-950 font-bold hover:bg-slate-200 transition-all shadow-xl shadow-white/5 flex items-center gap-2 group">
                    View Trajectory
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
                <a href="#projects" class="px-8 py-4 rounded-xl border border-white/20 hover:border-white/50 text-white font-semibold transition-all backdrop-blur-sm">
                    Featured Work
                </a>
            </div>
        </div>

        <div class="lg:col-span-5 relative" data-aos="zoom-in" data-aos-duration="1200">
            <div class="relative w-full max-w-md mx-auto aspect-[4/5] rounded-3xl overflow-hidden p-1 bg-gradient-to-b from-indigo-500/40 via-purple-500/20 to-transparent shadow-2xl">
                <div class="w-full h-full rounded-[22px] overflow-hidden bg-slate-900/90 relative group">
                    <img src="{{ $user->avatar_url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?fit=crop&w=800&q=80' }}" 
                         alt="{{ $user->name ?? 'Executive' }}" 
                         class="w-full h-full object-cover grayscale contrast-125 group-hover:scale-105 group-hover:grayscale-0 transition-all duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-transparent to-transparent opacity-90"></div>
                    <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10">
                        <p class="text-xs text-indigo-300 font-medium uppercase tracking-wider">Executive Focus</p>
                        <p class="text-sm font-semibold text-white mt-0.5">Enterprise Architecture • Strategy • Scaling</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
