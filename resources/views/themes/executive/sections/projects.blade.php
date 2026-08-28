<section id="projects" class="py-24 px-6 relative bg-[#070A10] border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="mb-16 text-center space-y-3" data-aos="fade-up">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-400">Portfolio & Case Studies</h2>
            <h3 class="text-4xl lg:text-5xl font-heading font-bold text-white">Featured Initiatives</h3>
            <div class="w-16 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto rounded-full mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($projects ?? [] as $index => $project)
                <div class="group rounded-3xl bg-[#121826] border border-white/5 overflow-hidden hover:border-indigo-500/40 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/10"
                     data-aos="fade-up" 
                     data-aos-delay="{{ $index * 150 }}">
                    <div class="aspect-video w-full overflow-hidden bg-slate-900 relative">
                        <img src="{{ $project->image_url ?? 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?fit=crop&w=1200&q=80' }}" 
                             alt="{{ $project->title ?? 'Project' }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#121826] via-transparent to-transparent"></div>
                    </div>
                    <div class="p-8 space-y-4">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            {{ $project->category ?? 'Enterprise Platform' }}
                        </span>
                        <h4 class="text-2xl font-heading font-bold text-white group-hover:text-indigo-400 transition-colors">
                            {{ $project->title ?? 'Cadastral & Mining E-Governance System' }}
                        </h4>
                        <p class="text-slate-400 text-sm font-light leading-relaxed">
                            {{ $project->description ?? 'End-to-end digital automation solution supporting multi-department operations and secure real-time workflows.' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="group rounded-3xl bg-[#121826] border border-white/5 overflow-hidden hover:border-indigo-500/40 transition-all duration-500" data-aos="fade-up">
                    <div class="aspect-video w-full overflow-hidden bg-slate-900 relative">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?fit=crop&w=1200&q=80" alt="Showcase" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#121826] via-transparent to-transparent"></div>
                    </div>
                    <div class="p-8 space-y-4">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">Enterprise Platform</span>
                        <h4 class="text-2xl font-heading font-bold text-white">Digital Cadastral & Citizen Service Architecture</h4>
                        <p class="text-slate-400 text-sm font-light leading-relaxed">Real-time land and operations governance tracking platform with automated workflows.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
