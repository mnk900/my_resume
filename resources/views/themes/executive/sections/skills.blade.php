<section id="skills" class="py-24 px-6 relative bg-[#0B0F17] border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="mb-16 text-center space-y-3" data-aos="fade-up">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-400">Core Competencies</h2>
            <h3 class="text-4xl lg:text-5xl font-heading font-bold text-white">Technical & Strategic Matrix</h3>
            <div class="w-16 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto rounded-full mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($skills ?? [] as $index => $skill)
                <div class="p-6 rounded-2xl bg-[#121826]/60 border border-white/5 hover:border-indigo-500/30 transition-all group backdrop-blur-sm"
                     data-aos="fade-up" 
                     data-aos-delay="{{ $index * 100 }}">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-heading font-semibold text-white group-hover:text-indigo-400 transition-colors">{{ $skill->name ?? 'Enterprise Architecture' }}</span>
                        <span class="text-xs text-slate-400 font-mono">{{ $skill->level ?? '95' }}%</span>
                    </div>
                    <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $skill->level ?? '90' }}%"></div>
                    </div>
                </div>
            @empty
                <div class="p-6 rounded-2xl bg-[#121826]/60 border border-white/5 group hover:border-indigo-500/30 transition-all">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-heading font-semibold text-white">System Architecture & Design</span>
                        <span class="text-xs text-slate-400 font-mono">95%</span>
                    </div>
                    <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full" style="width: 95%"></div>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-[#121826]/60 border border-white/5 group hover:border-indigo-500/30 transition-all">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-heading font-semibold text-white">Full-Stack Cloud Engineering</span>
                        <span class="text-xs text-slate-400 font-mono">90%</span>
                    </div>
                    <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full" style="width: 90%"></div>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-[#121826]/60 border border-white/5 group hover:border-indigo-500/30 transition-all">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-heading font-semibold text-white">Digital Strategy & Governance</span>
                        <span class="text-xs text-slate-400 font-mono">92%</span>
                    </div>
                    <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full" style="width: 92%"></div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
