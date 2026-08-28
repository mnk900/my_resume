<section id="experience" class="py-24 px-6 relative bg-[#070A10] border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="mb-16 text-center space-y-3" data-aos="fade-up">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-400">Career Trajectory</h2>
            <h3 class="text-4xl lg:text-5xl font-heading font-bold text-white">Executive Experience</h3>
            <div class="w-16 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto rounded-full mt-4"></div>
        </div>

        <div class="space-y-8">
            @forelse($experiences ?? [] as $index => $exp)
                <div class="p-8 lg:p-10 rounded-3xl bg-[#121826]/70 border border-white/5 hover:border-indigo-500/40 transition-all duration-500 backdrop-blur-md group hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/10"
                     data-aos="fade-up" 
                     data-aos-delay="{{ $index * 120 }}">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        <div class="lg:col-span-4 space-y-2">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                {{ $exp->period ?? '2020 — Present' }}
                            </span>
                            <h4 class="text-2xl font-heading font-bold text-white group-hover:text-indigo-300 transition-colors">
                                {{ $exp->title ?? 'Lead Architect / Consultant' }}
                            </h4>
                            <p class="text-slate-400 font-medium">{{ $exp->company ?? 'Enterprise Organization' }}</p>
                        </div>

                        <div class="lg:col-span-8 text-slate-300 space-y-4 leading-relaxed font-light">
                            <p>{{ $exp->description ?? 'Spearheaded digital transformation, scalable database infrastructures, and enterprise application roadmaps.' }}</p>
                            @if(!empty($exp->achievements))
                                <ul class="space-y-2 text-sm text-slate-400">
                                    @foreach($exp->achievements as $item)
                                        <li class="flex items-start gap-3">
                                            <span class="text-indigo-400 font-bold mt-0.5">▹</span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 lg:p-10 rounded-3xl bg-[#121826]/70 border border-white/5" data-aos="fade-up">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        <div class="lg:col-span-4 space-y-2">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">2022 — Present</span>
                            <h4 class="text-2xl font-heading font-bold text-white">Chief Technology Lead</h4>
                            <p class="text-slate-400 font-medium">Digital Systems & Governance</p>
                        </div>
                        <div class="lg:col-span-8 text-slate-300 space-y-3 font-light">
                            <p>Directed system modernizations, security policies, and high-volume transaction engines.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
