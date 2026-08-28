<section id="about" class="py-20 px-6 border-t border-white/5 bg-[#0B0F17]">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-4" data-aos="fade-up">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-400 mb-2">Executive Summary</h2>
            <h3 class="text-3xl lg:text-4xl font-heading font-bold text-white leading-tight">Vision & Strategy</h3>
        </div>
        <div class="lg:col-span-8 text-slate-300 text-lg font-light leading-relaxed space-y-4" data-aos="fade-up" data-aos-delay="100">
            <p>
                {{ $user->bio ?? 'Senior technologist and architect with deep experience leading mission-critical initiatives, high-availability platforms, and cross-functional teams to deliver impactful digital systems.' }}
            </p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 pt-6">
                <div class="border-l-2 border-indigo-500 pl-4">
                    <div class="text-2xl font-bold font-heading text-white">10+ Years</div>
                    <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">Experience</div>
                </div>
                <div class="border-l-2 border-purple-500 pl-4">
                    <div class="text-2xl font-bold font-heading text-white">High Scale</div>
                    <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">System Architectures</div>
                </div>
                <div class="border-l-2 border-emerald-500 pl-4">
                    <div class="text-2xl font-bold font-heading text-white">Public & Private</div>
                    <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">Sector Impact</div>
                </div>
            </div>
        </div>
    </div>
</section>
