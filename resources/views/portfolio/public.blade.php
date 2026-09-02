@php
    $rawTheme = strtolower(trim($portfolio->theme ?? 'classic'));
    if (request()->has('theme')) {
        $rawTheme = strtolower(trim(request()->get('theme')));
    }
    if (str_contains($rawTheme, 'business')) {
        $theme = 'business-class';
    } elseif (str_contains($rawTheme, 'executive')) {
        $theme = 'executive';
    } elseif (str_contains($rawTheme, 'premium')) {
        $theme = 'premium';
    } elseif (str_contains($rawTheme, 'elegant')) {
        $theme = 'elegant';
    } else {
        $theme = 'classic';
    }
    
    // Prepare $profile array to match the requested code structure
    $profile = [
        'name' => $user->name,
        'title' => $portfolio->title,
        'short_title' => $portfolio->position ?? 'Professional',
        'intro' => $portfolio->description,
        'detailed_profile' => $portfolio->detailed_bio ?? $portfolio->description,
        'email' => $user->email,
        'phone' => $portfolio->contact_number,
        'linkedin' => $portfolio->linkedin_url,
        'location' => ($portfolio->city ?? 'Gilgit-Baltistan') . ', ' . ($portfolio->country ?? 'Pakistan'),
        'technical_skills' => $portfolio->skills->groupBy(function($s) {
            $cat = trim($s->category ?? '');
            return !empty($cat) ? $cat : 'Core Competencies';
        })->map(function($items) {
            return [
                'icon' => $items->first()->icon ?? 'code',
                'items' => $items->pluck('name')->toArray()
            ];
        })->toArray(),
        'soft_skills' => $portfolio->achievements->pluck('title')->toArray(),
        'experience' => $portfolio->experiences->map(function($exp) {
            return [
                'date' => $exp->start_date->format('M Y') . ' – ' . ($exp->end_date ? $exp->end_date->format('M Y') : 'Present'),
                'title' => $exp->position,
                'company' => $exp->company,
                'highlights' =>  $exp->description
            ];
        })->toArray(),
        'education' => $portfolio->education->map(function($edu) {
            return [
                'degree' => $edu->degree,
                'institution' => $edu->institution,
                'date' => $edu->start_date->format('Y') . ' – ' . $edu->end_date->format('Y'),
                'result' => 'Completed'
            ];
        })->toArray(),
        'certifications' => $portfolio->certifications->pluck('name')->toArray(),
        'trainings' => $portfolio->trainings->pluck('title')->toArray(),
        'projects' => $portfolio->projects->map(function($p) {
            return [
                'name' => $p->title,
                'description' => $p->description,
                'tags' => ['Portfolio'], // Can expand later
                'image' => $p->image_path
            ];
        })->toArray()
    ];

    $skills = $portfolio->skills;
    $projects = $portfolio->projects;
    $experiences = $portfolio->experiences;
    $education = $portfolio->education;
    $certifications = $portfolio->certifications;
    $trainings = $portfolio->trainings;
    $achievements = $portfolio->achievements;
    $contributions = $portfolio->contributions;
    $publications = $portfolio->publications;
    $testimonials = $portfolio->testimonials;
    $services = $portfolio->services;
    $media = $portfolio->media;
@endphp

@extends('portfolio.themes.' . $theme)

@section('content')
    @if($theme == 'executive')
    <!-- EXECUTIVE THEME (MASTER EDITORIAL 2-COLUMN COMPOSITION) -->
    <div class="relative bg-[#0B0F17] text-slate-100 font-sans antialiased overflow-x-hidden min-h-screen">
        <!-- Ambient Background Orbs -->
        <div class="fixed top-0 left-1/4 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="fixed bottom-1/4 right-10 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

        @php
            $allNavModules = collect([
                ['id' => 'about', 'label' => 'ABOUT', 'show' => $portfolio->show_about ?? true],
                ['id' => 'services', 'label' => 'SERVICES OFFERED', 'show' => $portfolio->show_services ?? true],
                ['id' => 'experience', 'label' => 'WORK EXPERIENCE', 'show' => $portfolio->show_experience ?? true],
                ['id' => 'skills', 'label' => 'SKILLS', 'show' => $portfolio->show_skills ?? true],
                ['id' => 'projects', 'label' => 'PROJECTS', 'show' => $portfolio->show_projects ?? true],
                ['id' => 'education', 'label' => 'EDUCATION', 'show' => $portfolio->show_education ?? true],
                ['id' => 'certifications', 'label' => 'CERTIFICATIONS', 'show' => $portfolio->show_certifications ?? true],
                ['id' => 'trainings', 'label' => 'TRAININGS', 'show' => $portfolio->show_trainings ?? true],
                ['id' => 'achievements', 'label' => 'ACHIEVEMENTS', 'show' => $portfolio->show_achievements ?? true],
                ['id' => 'contributions', 'label' => 'CONTRIBUTIONS', 'show' => $portfolio->show_contributions ?? true],
                ['id' => 'testimonials', 'label' => 'TESTIMONIALS', 'show' => $portfolio->show_testimonials ?? true],
                ['id' => 'publications', 'label' => 'PUBLICATIONS', 'show' => $portfolio->show_publications ?? true],
                ['id' => 'media', 'label' => 'MEDIA APPEARANCES', 'show' => $portfolio->show_media ?? true],
                ['id' => 'contact', 'label' => 'CONTACT', 'show' => true],
            ])->filter(function($m) {
                return $m['show'] !== false && $m['show'] !== 0 && $m['show'] !== '0';
            })->values()->all();
        @endphp

        <!-- MAIN EDITORIAL PORTFOLIO CONTAINER -->
        <main class="max-w-7xl mx-auto px-6 lg:px-12 pt-12 pb-20 relative z-10 space-y-6">
            
            <!-- ================================================== -->
            <!-- FIRST SECTION: HERO IDENTITY CONTENT & PROFILE IMAGE -->
            <!-- ================================================== -->
            <section id="hero" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center relative" data-aos="fade-up">
                
                <!-- LEFT: IDENTITY CONTENT (7 COLUMNS) -->
                <div class="lg:col-span-7 space-y-6" data-aos="fade-right" data-aos-duration="900">
                    <div>
                        <!-- 1. PROFESSIONAL POSITION FIRST -->
                        <span class="text-xs sm:text-sm font-mono font-bold uppercase tracking-widest text-indigo-400 block mb-2 break-words">
                            // {{ strtoupper($portfolio->position ?? 'Software Architect & Executive Lead') }}
                        </span>

                        <!-- 2. FULL NAME SECOND (RESPONSIVE FONT SIZE & BREAK WORDS) -->
                        <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-display font-extrabold text-white tracking-tighter uppercase leading-[1.08] text-left break-words mb-3">
                            {{ $user->name }}
                        </h1>
                    </div>

                    <!-- 3. SHORT PITCH HOOK / SUMMARY THIRD -->
                    <div class="space-y-3">
                        <p class="text-sm sm:text-base md:text-lg font-display font-light text-slate-300 tracking-wide leading-relaxed max-w-xl break-words">
                            {{ $portfolio->summary ?? $portfolio->description ?? $portfolio->detailed_bio ?? 'High-impact software architect and systems lead engineering scalable multi-tenant SaaS platforms, cloud infrastructure, and enterprise data solutions.' }}
                        </p>
                    </div>

                    <!-- 4. BUTTONS OF VIEW WORK AND CONTACT ME FOURTH (RESPONSIVE FLEX & SIZES) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-2">
                        <a href="#projects" data-target="projects" class="exec-nav-tab cursor-pointer px-6 py-3.5 w-full sm:w-auto min-w-0 sm:min-w-[180px] h-12 whitespace-nowrap rounded-full bg-white/10 border border-white/20 text-white font-bold text-xs uppercase tracking-widest hover:bg-white hover:text-slate-950 transition-all inline-flex items-center justify-center gap-2 shrink-0">
                            View Work <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                        <a href="#contact" data-target="contact" class="exec-nav-tab cursor-pointer px-6 py-3.5 w-full sm:w-auto min-w-0 sm:min-w-[180px] h-12 whitespace-nowrap rounded-full bg-white/10 border border-white/20 text-white font-bold text-xs uppercase tracking-widest hover:bg-white hover:text-slate-950 transition-all inline-flex items-center justify-center gap-2 shrink-0">
                            Contact Me
                        </a>
                    </div>

                    <!-- MONOCHROME SOCIAL LINKS BAR -->
                    <div class="flex items-center gap-5 text-slate-400 text-xl pt-2">
                        @if($portfolio->github_url)
                            <a href="{{ $portfolio->github_url }}" target="_blank" class="hover:text-indigo-400 hover:scale-110 transition-all duration-300" title="GitHub"><i class="fa-brands fa-github"></i></a>
                        @endif
                        @if($portfolio->twitter_url)
                            <a href="{{ $portfolio->twitter_url }}" target="_blank" class="hover:text-indigo-400 hover:scale-110 transition-all duration-300" title="X / Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        @endif
                    </div>
                </div>

                <!-- RIGHT: PROFILE IMAGE & POSITION OVERLAY WITH BUTTONS (5 COLUMNS) -->
                <div class="lg:col-span-5" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="hero-img-animated editorial-img-wrapper rounded-3xl aspect-[3/4] max-h-[500px] sm:max-h-[600px] w-full max-w-md mx-auto lg:max-w-none border border-white/10 shadow-2xl shadow-indigo-500/20 relative group overflow-hidden">
                        @if($portfolio->profile_image)
                            <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        @else
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?fit=crop&w=800&q=80" alt="{{ $user->name }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-transparent to-transparent opacity-90"></div>
                        
                        <!-- POSITION OVERLAY FOLLOWED BY EXPLORE PROJECTS & VIEW SKILLS BUTTONS -->
                        <div class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-6 p-4 sm:p-5 rounded-2xl bg-slate-950/75 backdrop-blur-md border border-white/15 space-y-2.5">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest block mb-0.5">Position / Role</span>
                                <h3 class="text-sm sm:text-base font-display font-extrabold text-white uppercase tracking-tight truncate">
                                    {{ $portfolio->position ?? $portfolio->organization ?? 'Executive Professional' }}
                                </h3>
                            </div>
                            
                            <div class="flex flex-col xs:flex-row items-stretch xs:items-center gap-2 pt-2 border-t border-white/10">
                                <a href="#projects" data-target="projects" class="exec-nav-tab cursor-pointer px-3.5 py-2.5 flex-1 w-full xs:w-auto h-9 sm:h-10 whitespace-nowrap rounded-xl bg-white/20 hover:bg-white hover:text-slate-950 border border-white/30 text-white font-bold text-[10px] sm:text-[11px] uppercase tracking-wider transition-all inline-flex items-center justify-center shrink-0">
                                    Explore Projects
                                </a>
                                <a href="#skills" data-target="skills" class="exec-nav-tab cursor-pointer px-3.5 py-2.5 flex-1 w-full xs:w-auto h-9 sm:h-10 whitespace-nowrap rounded-xl bg-white/10 hover:bg-white hover:text-slate-950 border border-white/20 text-white font-bold text-[10px] sm:text-[11px] uppercase tracking-wider transition-all inline-flex items-center justify-center shrink-0">
                                    View Skills
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- ================================================== -->
            <!-- STICKY HORIZONTAL DIRECTORY NAVIGATION BAR WITH SCROLL ARROWS -->
            <!-- ================================================== -->
            <div class="sticky top-0 z-40 bg-[#0B0F17]/95 backdrop-blur-xl py-3 my-6">
                <div class="flex items-center gap-3">
                    <!-- LEFT SCROLL ARROW BUTTON -->
                    <button type="button" id="execNavScrollLeft" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/5 border border-white/15 text-slate-300 hover:text-white hover:bg-white/15 hover:border-indigo-400/50 transition-all flex items-center justify-center shrink-0 shadow-lg cursor-pointer" title="Scroll Left">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>

                    <!-- HORIZONTAL SCROLLING MODULE TABS CONTAINER (HIDDEN NATIVE SCROLLBAR) -->
                    <div id="execNavTabsContainer" class="flex-grow overflow-x-auto flex items-center gap-2 sm:gap-3 font-display font-bold text-xs sm:text-sm tracking-tight text-slate-400 py-1" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($allNavModules as $index => $item)
                            <a href="#{{ $item['id'] }}" data-target="{{ $item['id'] }}" class="exec-nav-tab cursor-pointer whitespace-nowrap hover:text-white transition-all flex items-center gap-2 py-2 px-4 rounded-full border border-white/10 hover:border-indigo-400/50 hover:bg-white/5 group shrink-0">
                                <span class="text-[10px] font-mono text-indigo-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- RIGHT SCROLL ARROW BUTTON -->
                    <button type="button" id="execNavScrollRight" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/5 border border-white/15 text-slate-300 hover:text-white hover:bg-white/15 hover:border-indigo-400/50 transition-all flex items-center justify-center shrink-0 shadow-lg cursor-pointer" title="Scroll Right">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>

                    <!-- EXTRA CONTROLS -->
                    <div class="shrink-0 hidden lg:flex items-center gap-3 pl-2 border-l border-white/10">
                        <a href="/" class="hover:text-white transition-colors flex items-center gap-1.5 text-xs font-mono text-slate-400"><i class="fa-solid fa-arrow-left text-[10px]"></i> Main Site</a>
                        @if($portfolio->resume_file)
                            <a href="{{ Storage::url($portfolio->resume_file) }}" download class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold uppercase tracking-wider hover:bg-indigo-600 hover:text-white transition-all">
                                <i class="fa-solid fa-download text-xs"></i> Download Resume
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- FULL-WIDTH INTERACTIVE SECTION PANELS CONTAINER -->
            <div class="w-full space-y-12 z-20 pt-2" id="execRightColumn">
                    
                    <!-- 1. ABOUT SECTION (INITIALLY VISIBLE) -->
                    <section id="about" class="exec-section-panel block py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// ABOUT ME</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">
                                Strategic Vision & Expertise
                            </h2>
                            <p class="text-slate-200 text-base sm:text-lg font-light leading-relaxed">
                                {{ $portfolio->detailed_bio ?? $portfolio->description ?? 'Senior technologist and executive architect leading multi-department governance platforms, enterprise SaaS architectures, and high-scale systems.' }}
                            </p>

                            <!-- Information Metadata Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-white/10">
                                <div>
                                    <span class="text-xs font-mono text-slate-400 uppercase tracking-wider block mb-1">Location</span>
                                    <span class="text-xs font-bold text-white block">{{ trim(($portfolio->city ?? '') . ', ' . ($portfolio->country ?? 'Global')) ?: 'Global / Remote' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-mono text-slate-400 uppercase tracking-wider block mb-1">Profession</span>
                                    <span class="text-xs font-bold text-white block">{{ $portfolio->position ?? 'Software Architect' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-mono text-slate-400 uppercase tracking-wider block mb-1">Experience</span>
                                    <span class="text-xs font-bold text-white block">{{ !empty($experiences) && count($experiences) > 0 ? count($experiences) . '+ Roles' : '10+ Years' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-mono text-slate-400 uppercase tracking-wider block mb-1">Status</span>
                                    <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Available</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 2. SERVICES OFFERED SECTION -->
                    <section id="services" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// EXPERTISE & ADVISORY</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Services Offered</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                @if($portfolio->services && $portfolio->services->isNotEmpty())
                                    @foreach($portfolio->services as $service)
                                        <div class="p-6 rounded-3xl bg-[#121826]/70 border border-white/10 hover:border-indigo-400/50 transition-all backdrop-blur-md space-y-3 group">
                                            <i class="fa-solid {{ $service->icon ?? 'fa-compass-drafting' }} text-xl text-indigo-400 group-hover:scale-110 transition-transform"></i>
                                            <h3 class="text-lg font-display font-bold text-white group-hover:text-indigo-300 transition-colors">{{ $service->title }}</h3>
                                            <p class="text-slate-300 text-xs font-light leading-relaxed">{{ $service->description }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3 col-span-full">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-compass-drafting"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No services records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 3. WORK EXPERIENCE SECTION -->
                    <section id="experience" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// CAREER TRAJECTORY</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Work Experience</h2>

                            <div class="space-y-0 pt-2">
                                @if(!empty($experiences) && count($experiences) > 0)
                                    @foreach($experiences as $index => $exp)
                                        <div class="editorial-row py-6 px-4 border-b border-white/5" data-aos="fade-up" data-aos-delay="{{ ($index % 5) * 80 }}">
                                            <div class="space-y-3">
                                                <div class="flex flex-wrap justify-between items-center gap-2">
                                                    <span class="text-xs font-mono font-semibold text-indigo-400 uppercase tracking-wider">
                                                        {{ $exp->period ?? (($exp->start_date ? (is_string($exp->start_date) ? $exp->start_date : $exp->start_date->format('M Y')) : '') . ' — ' . ($exp->is_current ? 'PRESENT' : ($exp->end_date ? (is_string($exp->end_date) ? $exp->end_date : $exp->end_date->format('M Y')) : ''))) }}
                                                    </span>
                                                    <span class="text-xs font-mono text-slate-400">{{ $exp->company ?? $portfolio->organization ?? 'Enterprise Organization' }}</span>
                                                </div>
                                                <h3 class="text-xl font-display font-bold text-white">
                                                    {{ $exp->position ?? $exp->title ?? 'Executive Consultant' }}
                                                </h3>
                                                <p class="text-slate-300 text-sm font-light leading-relaxed">
                                                    {{ $exp->description ?? 'Directed digital transformation, scalable database infrastructures, and enterprise application roadmaps.' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-briefcase"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No work experience records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 4. SKILLS SECTION -->
                    <section id="skills" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// TECHNICAL MATRIX</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Skills & Competencies</h2>

                            <div class="space-y-8 pt-2">
                                @if(!empty($skills) && count($skills) > 0)
                                    @php
                                        $skillsGrouped = $skills->groupBy(function($s) {
                                            return !empty($s->category) ? $s->category : 'Core Competencies';
                                        });
                                    @endphp
                                    @foreach($skillsGrouped as $categoryName => $catSkills)
                                        <div>
                                            <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-3">
                                                <span>// {{ $categoryName }}</span>
                                                <span class="flex-grow h-px bg-white/10"></span>
                                            </h3>
                                            <div class="flex flex-wrap gap-2.5">
                                                @foreach($catSkills as $skill)
                                                    <div class="px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-indigo-400/50 hover:bg-white/10 transition-all flex items-center gap-2.5 group">
                                                        <span class="font-display font-semibold text-xs text-white group-hover:text-indigo-300 transition-colors">{{ $skill->name }}</span>
                                                        @if(!empty($skill->level))
                                                            <span class="text-[10px] font-mono text-slate-400 group-hover:text-indigo-400 transition-colors">{{ $skill->level }}%</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-code"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No skill records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 5. PROJECTS SECTION (4 COLUMNS GRID DISPLAY) -->
                    <section id="projects" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                <div>
                                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block mb-1">// CASE STUDIES</span>
                                    <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Projects & Initiatives</h2>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">
                                @if(!empty($projects) && count($projects) > 0)
                                    @foreach($projects as $index => $project)
                                        <div class="group relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl bg-[#121826] aspect-[4/5] flex flex-col justify-end transition-all duration-500 hover:border-indigo-400/50 hover:shadow-indigo-500/20" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 100 }}">
                                            <!-- PROJECT IMAGE BACKGROUND -->
                                            @if($project->image_path)
                                                <img src="{{ Storage::url($project->image_path) }}" alt="{{ $project->title }}" class="absolute inset-0 w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                                            @else
                                                <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex flex-col items-center justify-center p-6 text-center">
                                                    <i class="fa-solid fa-layer-group text-4xl text-indigo-400/40 mb-2 group-hover:scale-110 transition-transform"></i>
                                                </div>
                                            @endif

                                            <!-- GRADIENT OVERLAY FOR READABILITY -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-[#0B0F17]/70 to-transparent opacity-90 group-hover:opacity-95 transition-opacity"></div>

                                            <!-- OVERLAY CONTENT ON PROJECT IMAGE -->
                                            <div class="relative z-10 p-5 space-y-3">
                                                <!-- SINGLE LINE TITLE WITH ELLIPSIS IF LONG -->
                                                <h3 class="text-base font-display font-extrabold text-white truncate tracking-tight" title="{{ $project->title }}">
                                                    {{ $project->title }}
                                                </h3>

                                                <!-- READ MORE BUTTON ON PROJECT IMAGE (TRIGGERS MODAL POPUP) -->
                                                <button type="button" class="exec-project-modal-btn inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-indigo-600 hover:text-white border border-white/20 hover:border-indigo-400 text-white font-bold text-xs uppercase tracking-wider transition-all duration-300 backdrop-blur-md cursor-pointer shrink-0"
                                                    data-title="{{ $project->title }}"
                                                    data-description="{{ $project->description }}"
                                                    data-image="{{ $project->image_path ? Storage::url($project->image_path) : '' }}"
                                                    data-url="{{ $project->project_url ?? '' }}">
                                                    Read More <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3 col-span-full">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-layer-group"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No project records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 6. EDUCATION SECTION -->
                    <section id="education" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// ACADEMIC HISTORY</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Education</h2>

                            <div class="space-y-4 pt-2">
                                @if(!empty($education) && count($education) > 0)
                                    @foreach($education as $index => $edu)
                                        @php
                                            $sDate = $edu->start_date ? (is_string($edu->start_date) ? \Carbon\Carbon::parse($edu->start_date) : $edu->start_date) : null;
                                            $eDate = $edu->end_date ? (is_string($edu->end_date) ? \Carbon\Carbon::parse($edu->end_date) : $edu->end_date) : null;
                                            
                                            $startFormatted = $sDate ? $sDate->format('M Y') : ($edu->start_year ?? '2014');
                                            $endFormatted = $eDate ? $eDate->format('M Y') : ($edu->end_year ?? 'PRESENT');
                                            
                                            $sYear = $sDate ? (int)$sDate->format('Y') : (int)($edu->start_year ?? 2014);
                                            $eYear = $eDate ? (int)$eDate->format('Y') : ($endFormatted === 'PRESENT' ? (int)date('Y') : (int)($edu->end_year ?? 2018));
                                            
                                            $diffYears = ($eYear >= $sYear) ? ($eYear - $sYear) : 0;
                                            if ($diffYears <= 1) {
                                                $countYearsText = '1 Year Duration';
                                            } else {
                                                $countYearsText = $diffYears . ' Years Duration';
                                            }
                                        @endphp
                                        <div class="editorial-row py-5 px-5 rounded-2xl bg-[#121826]/60 border border-white/10 hover:border-indigo-400/50 transition-all space-y-2" data-aos="fade-up">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="text-xs font-mono font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fa-regular fa-calendar-check text-[11px]"></i>
                                                    {{ $startFormatted }} — {{ $endFormatted }}
                                                </span>
                                                <span class="text-[11px] font-mono font-semibold text-emerald-400 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                                                    {{ $countYearsText }}
                                                </span>
                                            </div>
                                            <h3 class="text-lg font-display font-bold text-white">{{ $edu->degree }}</h3>
                                            <p class="text-slate-300 text-xs font-light">{{ $edu->institution }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No education records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 7. CERTIFICATIONS SECTION -->
                    <section id="certifications" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// CREDENTIALS</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Certifications</h2>

                            <div class="space-y-4 pt-2">
                                @if(!empty($certifications) && count($certifications) > 0)
                                    @foreach($certifications as $cert)
                                        <div class="p-5 rounded-2xl bg-[#121826]/70 border border-white/10 backdrop-blur-md flex justify-between items-center">
                                            <div>
                                                <h4 class="text-base font-display font-bold text-white">{{ $cert->name }}</h4>
                                                <span class="text-xs text-slate-400 block">{{ $cert->issuing_organization }}</span>
                                            </div>
                                            @if(!empty($cert->credential_url))
                                                <a href="{{ $cert->credential_url }}" target="_blank" class="text-xs font-mono text-indigo-400 hover:text-white">Verify →</a>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-certificate"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No certification records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 8. TRAININGS SECTION -->
                    <section id="trainings" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// SPECIALIZED COURSES</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Trainings & Workshops</h2>

                            <div class="space-y-4 pt-2">
                                @if(!empty($trainings) && count($trainings) > 0)
                                    @foreach($trainings as $tr)
                                        <div class="p-5 rounded-2xl bg-[#121826]/70 border border-white/10 backdrop-blur-md">
                                            <h4 class="text-base font-display font-bold text-white">{{ $tr->title }}</h4>
                                            <span class="text-xs text-slate-400 block">{{ $tr->institution }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No training records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 9. ACHIEVEMENTS SECTION -->
                    <section id="achievements" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// RECOGNITION & HONORS</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Key Achievements</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                @if($portfolio->achievements && $portfolio->achievements->isNotEmpty())
                                    @foreach($portfolio->achievements as $ach)
                                        <div class="p-6 rounded-3xl bg-[#121826]/70 border border-white/10 backdrop-blur-md space-y-2">
                                            <span class="text-[10px] font-mono text-indigo-400 uppercase tracking-wider block">{{ $ach->date ?? 'HONOR' }}</span>
                                            <h3 class="text-base font-display font-bold text-white">{{ $ach->title }}</h3>
                                            <p class="text-slate-300 text-xs font-light leading-relaxed">{{ $ach->description }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3 col-span-full">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-trophy"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No achievement records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 10. CONTRIBUTIONS SECTION -->
                    <section id="contributions" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block">// COMMUNITY & OPEN CODE</span>
                            <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Technical Contributions</h2>

                            <div class="space-y-4 pt-2">
                                @if($portfolio->contributions && $portfolio->contributions->isNotEmpty())
                                    @foreach($portfolio->contributions as $cb)
                                        <div class="p-6 rounded-3xl bg-[#121826]/70 border border-white/10 backdrop-blur-md space-y-3">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-base font-display font-bold text-white">{{ $cb->title }}</h4>
                                                @if(!empty($cb->url))
                                                    <a href="{{ $cb->url }}" target="_blank" class="text-xs font-mono text-indigo-400 hover:text-white">Repository →</a>
                                                @endif
                                            </div>
                                            <p class="text-slate-300 text-xs font-light leading-relaxed">{{ $cb->description ?? '' }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-code-commit"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No contribution records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 11. TESTIMONIALS SECTION -->
                    <section id="testimonials" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                <div>
                                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block mb-1">// CLIENT & LEADER ENDORSEMENTS</span>
                                    <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Testimonials & Endorsements</h2>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                @if(!empty($testimonials) && count($testimonials) > 0)
                                    @foreach($testimonials as $index => $tst)
                                        @php
                                            $name = $tst->client_name ?? $tst->author_name ?? 'Client';
                                            $designation = $tst->designation ?? $tst->author_title ?? '';
                                            if (!empty($tst->author_company)) {
                                                $designation .= ($designation ? ' — ' : '') . $tst->author_company;
                                            }
                                            $content = $tst->content ?? $tst->quote ?? '';
                                            $initials = '';
                                            $parts = explode(' ', trim($name));
                                            if (count($parts) >= 2) {
                                                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
                                            } else {
                                                $initials = strtoupper(substr($name, 0, 2));
                                            }
                                        @endphp
                                        <div class="relative p-7 sm:p-8 rounded-3xl bg-[#121826]/70 border border-white/10 hover:border-indigo-400/50 transition-all duration-500 backdrop-blur-md space-y-6 flex flex-col justify-between group hover:shadow-2xl hover:shadow-indigo-500/10 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ ($index % 2) * 100 }}">
                                            <!-- Ambient Quote Icon in Background -->
                                            <i class="fa-solid fa-quote-right absolute top-6 right-6 text-4xl text-indigo-500/10 group-hover:text-indigo-400/20 transition-all pointer-events-none"></i>

                                            <div class="space-y-4 relative z-10">
                                                <!-- Star Rating Bar -->
                                                <div class="flex items-center gap-1 text-amber-400 text-xs">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>

                                                <!-- Quote Content -->
                                                <p class="text-slate-200 text-sm sm:text-base font-light italic leading-relaxed">
                                                    "{{ $content }}"
                                                </p>
                                            </div>

                                            <!-- Client Info Footer -->
                                            <div class="flex items-center gap-3.5 pt-4 border-t border-white/10 relative z-10">
                                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 border border-indigo-400/30 text-indigo-300 font-display font-extrabold text-sm flex items-center justify-center shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                                                    {{ $initials }}
                                                </div>
                                                <div class="space-y-0.5">
                                                    <h4 class="text-sm font-display font-bold text-white group-hover:text-indigo-300 transition-colors">{{ $name }}</h4>
                                                    @if(!empty($designation))
                                                        <span class="text-xs font-mono text-slate-400 block">{{ $designation }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3 col-span-full">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-quote-left"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No testimonial records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 12. PUBLICATIONS SECTION -->
                    <section id="publications" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                <div>
                                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block mb-1">// SCHOLARLY & RESEARCH PAPERS</span>
                                    <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Publications & Reports</h2>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                @if(!empty($publications) && count($publications) > 0)
                                    @foreach($publications as $index => $pub)
                                        <div class="p-6 sm:p-7 rounded-3xl bg-[#121826]/70 border border-white/10 hover:border-indigo-400/50 transition-all backdrop-blur-md space-y-4 flex flex-col justify-between group" data-aos="fade-up" data-aos-delay="{{ ($index % 2) * 100 }}">
                                            <div class="space-y-3">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <span class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 font-mono text-[10px] font-bold uppercase tracking-wider">
                                                        {{ $pub->type ?? 'JOURNAL PAPER' }}
                                                    </span>
                                                    @if(!empty($pub->year))
                                                        <span class="text-xs font-mono text-slate-400 font-semibold flex items-center gap-1.5">
                                                            <i class="fa-regular fa-calendar text-[10px] text-indigo-400"></i> {{ $pub->year }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h3 class="text-lg font-display font-extrabold text-white group-hover:text-indigo-300 transition-colors leading-snug">
                                                    {{ $pub->title }}
                                                </h3>

                                                <div class="space-y-1.5 text-xs text-slate-300 font-light border-t border-white/5 pt-3">
                                                    @if(!empty($pub->authors))
                                                        <p class="flex items-center gap-2">
                                                            <strong class="font-mono text-[11px] text-indigo-400 uppercase tracking-wider">Authors:</strong>
                                                            <span class="text-slate-200">{{ $pub->authors }}</span>
                                                        </p>
                                                    @endif
                                                    @if(!empty($pub->publisher))
                                                        <p class="flex items-center gap-2">
                                                            <strong class="font-mono text-[11px] text-indigo-400 uppercase tracking-wider">Publisher:</strong>
                                                            <span class="text-slate-200">{{ $pub->publisher }}</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- ACTION BUTTONS -->
                                            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-white/10">
                                                @if(!empty($pub->link))
                                                    <a href="{{ $pub->link }}" target="_blank" class="px-4 py-2 rounded-xl bg-indigo-600/80 hover:bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider transition-all inline-flex items-center gap-2">
                                                        Online Link <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                    </a>
                                                @endif
                                                @if(!empty($pub->report_path))
                                                    <a href="{{ Storage::url($pub->report_path) }}" target="_blank" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white hover:text-slate-950 border border-white/20 text-white font-bold text-xs uppercase tracking-wider transition-all inline-flex items-center gap-2">
                                                        Download Report <i class="fa-solid fa-download text-[10px]"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3 col-span-full">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-book-open"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No publication records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 13. MEDIA APPEARANCES SECTION -->
                    <section id="media" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                <div>
                                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-purple-400 block mb-1">// PRESS, KEYNOTES & BROADCASTS</span>
                                    <h2 class="section-title-clamp font-display font-extrabold text-white uppercase tracking-tight">Media Appearances</h2>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                @if(!empty($media) && count($media) > 0)
                                    @foreach($media as $index => $med)
                                        <div class="p-6 sm:p-7 rounded-3xl bg-[#121826]/70 border border-white/10 hover:border-purple-400/50 transition-all backdrop-blur-md space-y-4 flex flex-col justify-between group" data-aos="fade-up" data-aos-delay="{{ ($index % 2) * 100 }}">
                                            <div class="space-y-3">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <span class="px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 font-mono text-[10px] font-bold uppercase tracking-wider">
                                                        {{ $med->type ?? 'MEDIA APPEARANCE' }}
                                                    </span>
                                                    @if(!empty($med->date))
                                                        <span class="text-xs font-mono text-slate-400 font-semibold flex items-center gap-1.5">
                                                            <i class="fa-regular fa-calendar-check text-[10px] text-purple-400"></i> {{ is_string($med->date) ? $med->date : $med->date->format('M Y') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h3 class="text-lg font-display font-extrabold text-white group-hover:text-purple-300 transition-colors leading-snug">
                                                    {{ $med->title }}
                                                </h3>

                                                <div class="space-y-1.5 text-xs text-slate-300 font-light border-t border-white/5 pt-3">
                                                    @if(!empty($med->channel_platform))
                                                        <p class="flex items-center gap-2">
                                                            <strong class="font-mono text-[11px] text-purple-400 uppercase tracking-wider">Channel / Platform:</strong>
                                                            <span class="text-slate-200">{{ $med->channel_platform }}</span>
                                                        </p>
                                                    @endif
                                                    @if(!empty($med->newspaper_name))
                                                        <p class="flex items-center gap-2">
                                                            <strong class="font-mono text-[11px] text-purple-400 uppercase tracking-wider">Press / Outlet:</strong>
                                                            <span class="text-slate-200">{{ $med->newspaper_name }}</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- ACTION BUTTON -->
                                            @if(!empty($med->link))
                                                <div class="pt-4 border-t border-white/10">
                                                    <a href="{{ $med->link }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-purple-600/80 hover:bg-purple-600 text-white font-bold text-xs uppercase tracking-wider transition-all inline-flex items-center justify-center gap-2 w-full sm:w-auto">
                                                        Watch / Read Coverage <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 rounded-3xl bg-[#121826]/50 border border-white/10 text-center space-y-3 col-span-full">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                                            <i class="fa-solid fa-video"></i>
                                        </div>
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider">No Records Found</h3>
                                        <p class="text-slate-400 text-xs max-w-sm mx-auto">No media appearance records have been added yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    <!-- 14. CONTACT SECTION -->
                    <section id="contact" class="exec-section-panel hidden py-6 relative z-10" data-aos="fade-up">
                        <div class="space-y-8">
                            <div>
                                <span class="text-xs font-mono font-bold uppercase tracking-widest text-indigo-400 block mb-2">// INITIATE DIALOGUE & INQUIRIES</span>
                                <h2 class="hero-title-clamp font-display font-extrabold text-white uppercase tracking-tighter leading-none mb-3">
                                    LET’S WORK TOGETHER.
                                </h2>
                                <p class="text-slate-300 text-sm font-light leading-relaxed max-w-xl">
                                    Available for executive advisory, system architecture, and strategic technology consulting. Reach out directly or send a message below.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-2">
                                <!-- LEFT COLUMN: DIRECT CONTACT DETAILS -->
                                <div class="lg:col-span-5 space-y-4">
                                    <div class="p-7 rounded-3xl bg-[#121826]/70 border border-white/10 backdrop-blur-md space-y-6">
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider border-b border-white/10 pb-3">Contact Details</h3>

                                        <div class="space-y-4">
                                            @if($portfolio->show_email && !empty($profile['email']))
                                                <div class="flex items-start gap-4 group">
                                                    <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                        <i class="fa-solid fa-envelope text-sm"></i>
                                                    </div>
                                                    <div class="space-y-0.5 overflow-hidden">
                                                        <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 block">Direct Email</span>
                                                        <a href="mailto:{{ $profile['email'] }}" class="text-xs sm:text-sm font-display font-semibold text-white hover:text-indigo-300 transition-colors truncate block">
                                                            {{ $profile['email'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($portfolio->show_phone && !empty($profile['phone']))
                                                <div class="flex items-start gap-4 group">
                                                    <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                        <i class="fa-solid fa-phone text-sm"></i>
                                                    </div>
                                                    <div class="space-y-0.5 overflow-hidden">
                                                        <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 block">Phone / Mobile</span>
                                                        <a href="tel:{{ $profile['phone'] }}" class="text-xs sm:text-sm font-display font-semibold text-white hover:text-indigo-300 transition-colors truncate block">
                                                            {{ $profile['phone'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(!empty($profile['location']))
                                                <div class="flex items-start gap-4 group">
                                                    <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                        <i class="fa-solid fa-location-dot text-sm"></i>
                                                    </div>
                                                    <div class="space-y-0.5 overflow-hidden">
                                                        <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 block">Location</span>
                                                        <span class="text-xs sm:text-sm font-display font-semibold text-white truncate block">
                                                            {{ $profile['location'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($portfolio->show_linkedin && !empty($profile['linkedin']))
                                                <div class="flex items-start gap-4 group">
                                                    <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                                                    </div>
                                                    <div class="space-y-0.5 overflow-hidden">
                                                        <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 block">LinkedIn Profile</span>
                                                        <a href="{{ $profile['linkedin'] }}" target="_blank" class="text-xs sm:text-sm font-display font-semibold text-indigo-400 hover:text-white transition-colors truncate block">
                                                            Connect on LinkedIn →
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN: CONTACT FORM -->
                                <div class="lg:col-span-7">
                                    <div class="p-7 sm:p-8 rounded-3xl bg-[#121826]/70 border border-white/10 shadow-2xl backdrop-blur-md space-y-5">
                                        <h3 class="text-base font-display font-bold text-white uppercase tracking-wider border-b border-white/10 pb-3">Send Message</h3>

                                        @if(session('status') == 'message-sent')
                                            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-mono flex items-center gap-3">
                                                <i class="fa-solid fa-circle-check text-base"></i>
                                                <span>Message sent successfully! {{ $user->name }} will respond shortly.</span>
                                            </div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs font-mono space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                                                        <span>{{ $error }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <form action="{{ route('portfolio.contact.store', $portfolio->id) }}" method="POST" class="space-y-5">
                                            @csrf
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                                <div>
                                                    <label class="text-xs font-mono uppercase tracking-wider text-slate-400 block mb-2">Your Name <span class="text-rose-400">*</span></label>
                                                    <input type="text" name="name" placeholder="Full Name" required class="w-full px-5 py-3.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 transition-colors text-sm">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-mono uppercase tracking-wider text-slate-400 block mb-2">Your Email <span class="text-rose-400">*</span></label>
                                                    <input type="email" name="email" placeholder="name@domain.com" required class="w-full px-5 py-3.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 transition-colors text-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-mono uppercase tracking-wider text-slate-400 block mb-2">Your Inquiry / Message <span class="text-rose-400">*</span></label>
                                                <textarea name="message" rows="4" placeholder="Brief details regarding your requirement..." required class="w-full px-5 py-3.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 transition-colors text-sm"></textarea>
                                            </div>
                                            <button type="submit" class="w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-widest transition-all duration-300 shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2 cursor-pointer">
                                                Send Message <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

        </main>

        <script>
        (function() {
            function initExecNav() {
                const panels = document.querySelectorAll('.exec-section-panel');
                const tabs = document.querySelectorAll('.exec-nav-tab');

                if (!panels.length) return;

                function showPanel(targetId, userClicked = false) {
                    if (!targetId) return;

                    // 1. Hide all section panels
                    panels.forEach(p => {
                        p.style.setProperty('display', 'none', 'important');
                        p.classList.add('hidden');
                        p.classList.remove('block');
                    });

                    // 2. Display target section panel
                    const target = document.getElementById(targetId);
                    if (target) {
                        target.style.setProperty('display', 'block', 'important');
                        target.classList.remove('hidden');
                        target.classList.add('block');

                        if (typeof AOS !== 'undefined') {
                            AOS.refresh();
                        }

                        // Smoothly scroll window to the VERY START of the selected section panel
                        if (userClicked) {
                            requestAnimationFrame(function() {
                                const stickyNav = document.querySelector('.sticky.top-0');
                                const stickyOffset = stickyNav ? stickyNav.offsetHeight : 65;
                                const rect = target.getBoundingClientRect();
                                const targetScrollY = window.pageYOffset + rect.top - stickyOffset - 15;

                                window.scrollTo({
                                    top: Math.max(0, targetScrollY),
                                    behavior: 'smooth'
                                });
                            });
                        }
                    }

                    // 3. Highlight pill tabs across horizontal directory bar with PURE WHITE text when active/clicked
                    const tabsNavBox = document.getElementById('execNavTabsContainer');
                    tabs.forEach(t => {
                        const tTarget = t.getAttribute('data-target') || (t.getAttribute('href') ? t.getAttribute('href').replace('#', '') : '');

                        if (tTarget === targetId) {
                            t.classList.add('bg-indigo-600', 'text-white', 'border-indigo-400', 'font-extrabold', 'shadow-lg', 'shadow-indigo-500/30');
                            t.classList.remove('bg-white', 'text-slate-950', 'text-slate-400', 'border-white/10');
                            const numSpan = t.querySelector('span:first-child');
                            if (numSpan) numSpan.classList.add('text-indigo-200');

                            // Center active tab pill horizontally within directory bar only (preventing vertical window shift)
                            if (tabsNavBox && t.closest('#execNavTabsContainer')) {
                                try {
                                    const tRect = t.getBoundingClientRect();
                                    const cRect = tabsNavBox.getBoundingClientRect();
                                    const offsetLeft = (tRect.left - cRect.left) + tabsNavBox.scrollLeft - (cRect.width / 2) + (tRect.width / 2);
                                    tabsNavBox.scrollTo({ left: offsetLeft, behavior: 'smooth' });
                                } catch(e) {}
                            }
                        } else {
                            t.classList.remove('bg-indigo-600', 'bg-white', 'text-slate-950', 'text-white', 'border-indigo-400', 'border-white', 'font-extrabold', 'shadow-lg', 'shadow-indigo-500/30');
                            t.classList.add('text-slate-400', 'border-white/10');
                            const numSpan = t.querySelector('span:first-child');
                            if (numSpan) {
                                numSpan.classList.remove('text-indigo-200');
                                numSpan.classList.add('text-indigo-400');
                            }
                        }
                    });
                }

                // Smooth Scroll Controls for Left & Right Arrow Buttons
                const leftArrowBtn = document.getElementById('execNavScrollLeft');
                const rightArrowBtn = document.getElementById('execNavScrollRight');
                const tabsContainer = document.getElementById('execNavTabsContainer');

                if (leftArrowBtn && tabsContainer) {
                    leftArrowBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        tabsContainer.scrollBy({ left: -280, behavior: 'smooth' });
                    });
                }

                if (rightArrowBtn && tabsContainer) {
                    rightArrowBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        tabsContainer.scrollBy({ left: 280, behavior: 'smooth' });
                    });
                }

                // Initialize: show About by default (no auto scroll on page load)
                showPanel('about', false);

                // Project Details Modal Popup Logic
                const projectModal = document.getElementById('execProjectModal');
                const closeModalBtn = document.getElementById('closeExecProjectModal');
                const closeModalActionBtn = document.getElementById('closeExecProjectModalBtn');
                const modalTitle = document.getElementById('execModalProjectTitle');
                const modalDesc = document.getElementById('execModalProjectDesc');
                const modalImg = document.getElementById('execModalProjectImg');
                const modalImgWrapper = document.getElementById('execModalImgWrapper');
                const modalUrl = document.getElementById('execModalProjectUrl');

                function openProjectModal(data) {
                    if (!projectModal) return;
                    if (modalTitle) modalTitle.textContent = data.title || 'Project Details';
                    if (modalDesc) modalDesc.textContent = data.description || 'Full project architecture specifications and implementation details.';
                    
                    if (data.image && data.image.trim() !== '') {
                        if (modalImg) modalImg.src = data.image;
                        if (modalImgWrapper) modalImgWrapper.style.display = 'block';
                    } else if (modalImgWrapper) {
                        if (modalImg) modalImg.src = 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?fit=crop&w=1200&q=80';
                        if (modalImgWrapper) modalImgWrapper.style.display = 'block';
                    }

                    if (modalUrl) {
                        if (data.url && data.url.trim() !== '' && data.url !== '#contact') {
                            modalUrl.href = data.url;
                            modalUrl.style.display = 'inline-flex';
                        } else {
                            modalUrl.style.display = 'none';
                        }
                    }

                    projectModal.classList.remove('hidden');
                    setTimeout(() => {
                        projectModal.classList.remove('opacity-0');
                    }, 10);
                    document.body.style.overflow = 'hidden';
                }

                function closeProjectModal() {
                    if (!projectModal) return;
                    projectModal.classList.add('opacity-0');
                    setTimeout(() => {
                        projectModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }, 250);
                }

                // Global event listener for Read More buttons
                document.addEventListener('click', function(e) {
                    const modalBtn = e.target.closest('.exec-project-modal-btn');
                    if (modalBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        openProjectModal({
                            title: modalBtn.getAttribute('data-title'),
                            description: modalBtn.getAttribute('data-description'),
                            image: modalBtn.getAttribute('data-image'),
                            url: modalBtn.getAttribute('data-url')
                        });
                        return;
                    }

                    const tab = e.target.closest('.exec-nav-tab');
                    if (tab) {
                        e.preventDefault();
                        e.stopPropagation();
                        const targetId = tab.getAttribute('data-target') || (tab.getAttribute('href') ? tab.getAttribute('href').replace('#', '') : '');
                        if (targetId) {
                            showPanel(targetId, true);
                        }
                    }
                }, true);

                if (closeModalBtn) closeModalBtn.addEventListener('click', closeProjectModal);
                if (closeModalActionBtn) closeModalActionBtn.addEventListener('click', closeProjectModal);
                if (projectModal) {
                    projectModal.addEventListener('click', function(e) {
                        if (e.target === projectModal) closeProjectModal();
                    });
                }
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeProjectModal();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initExecNav);
            } else {
                initExecNav();
            }
        })();
        </script>

        <!-- PROJECT DETAILS POPUP MODAL -->
        <div id="execProjectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/85 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
            <div class="relative w-full max-w-2xl bg-[#121826] border border-white/15 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] text-slate-100 transform transition-all duration-300">
                <!-- MODAL CLOSE BUTTON -->
                <button type="button" id="closeExecProjectModal" class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-slate-900/80 hover:bg-white hover:text-slate-950 border border-white/20 text-slate-300 font-bold flex items-center justify-center transition-all cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>

                <!-- MODAL BANNER IMAGE -->
                <div id="execModalImgWrapper" class="relative w-full h-48 sm:h-60 bg-slate-900 overflow-hidden shrink-0">
                    <img id="execModalProjectImg" src="" alt="Project Image" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#121826] via-transparent to-transparent"></div>
                </div>

                <!-- MODAL CONTENT BODY -->
                <div class="p-6 sm:p-8 overflow-y-auto space-y-4">
                    <div>
                        <span class="text-xs font-mono font-bold text-indigo-400 uppercase tracking-widest block mb-1">// PROJECT DETAILS</span>
                        <h3 id="execModalProjectTitle" class="text-xl sm:text-2xl font-display font-extrabold text-white uppercase tracking-tight"></h3>
                    </div>

                    <div class="border-t border-white/10 pt-4">
                        <p id="execModalProjectDesc" class="text-slate-300 text-sm font-light leading-relaxed whitespace-pre-line"></p>
                    </div>

                    <!-- MODAL ACTION BUTTONS -->
                    <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-white/10">
                        <a id="execModalProjectUrl" href="#" target="_blank" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider transition-all inline-flex items-center gap-2">
                            Visit Live Site <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                        <button type="button" id="closeExecProjectModalBtn" class="px-6 py-3 rounded-xl bg-white/10 hover:bg-white hover:text-slate-950 border border-white/20 text-white font-bold text-xs uppercase tracking-wider transition-all cursor-pointer">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- EXECUTIVE THEME FOOTER -->
        <footer class="border-t border-white/10 py-12 bg-[#070A10] text-slate-400 text-xs relative z-10">
            <div class="max-w-7xl mx-auto px-6 space-y-8">
                <!-- TOP ROW: CIRCULAR PROFILE IMAGE & FULL NAME ON LEFT + HORIZONTAL WHOLE NAVIGATION -->
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6 pb-8 border-b border-white/5">
                    <!-- LEFT: CIRCULAR IMAGE + FULL NAME -->
                    <div class="flex items-center gap-3 shrink-0">
                        @if($portfolio->profile_image)
                            <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-white/20 shadow-md">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=1E293B&color=fff" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-white/20 shadow-md">
                        @endif
                        <span class="font-display font-extrabold text-white text-sm tracking-tight">{{ strtoupper($user->name) }}</span>
                    </div>

                    <!-- RIGHT: HORIZONTAL WHOLE NAVIGATION (CENTER ALIGNED WHEN WRAPPED) -->
                    <nav class="flex flex-wrap items-center justify-center text-center gap-x-5 gap-y-2.5 font-mono font-bold text-[11px] uppercase tracking-wider text-slate-400">
                        @foreach($allNavModules as $footMod)
                            <a href="#{{ $footMod['id'] }}" data-target="{{ $footMod['id'] }}" class="exec-nav-tab cursor-pointer hover:text-white transition-colors whitespace-nowrap">
                                {{ $footMod['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- CENTER BOTTOM ROW: COPYRIGHT & POWERED BY MESSAGES -->
                <div class="text-center space-y-2">
                    <p class="text-slate-400 text-xs">
                        &copy; {{ now()->year }} <strong class="text-white font-semibold">{{ $user->name }}</strong>. All rights reserved.
                    </p>
                    <p class="text-slate-500 text-[11px]">
                        Powered by <a href="https://itechgb.com/" target="_blank" class="text-indigo-400 hover:text-white font-medium transition-colors">Innovative Technologies GB</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    @elseif($theme == 'business-class')
    <!-- ================================================== -->
    <!-- LUXURY EXECUTIVE THEME: "BUSINESS CLASS" -->
    <!-- ================================================== -->
    <style>
        /* Business Class Animation Signatures */
        @keyframes bcBlurFadeIn {
            0% { filter: blur(20px); opacity: 0; transform: scale(0.96); }
            100% { filter: blur(0px); opacity: 1; transform: scale(1); }
        }
        .bc-anim-about { animation: bcBlurFadeIn 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bc3DCardFlip {
            0% { transform: perspective(1200px) rotateY(-90deg); opacity: 0; }
            100% { transform: perspective(1200px) rotateY(0deg); opacity: 1; }
        }
        .bc-anim-services { animation: bc3DCardFlip 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcTimelineSlideUp {
            0% { transform: translateY(80px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .bc-anim-experience { animation: bcTimelineSlideUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcLineDraw {
            0% { height: 0%; }
            100% { height: 100%; }
        }
        .bc-gold-timeline-line { animation: bcLineDraw 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcSpringScaleUp {
            0% { transform: scale(0.7); opacity: 0; }
            70% { transform: scale(1.04); }
            100% { transform: scale(1); opacity: 1; }
        }
        .bc-anim-skills { animation: bcSpringScaleUp 0.65s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

        @keyframes bcCinematicZoom {
            0% { transform: scale(1.18); filter: contrast(135%) brightness(120%); opacity: 0; }
            100% { transform: scale(1); filter: contrast(100%) brightness(100%); opacity: 1; }
        }
        .bc-anim-projects { animation: bcCinematicZoom 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcLateralSlide {
            0% { transform: translateX(-100px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        .bc-anim-education { animation: bcLateralSlide 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcGoldShimmerSweep {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .bc-gold-shimmer-card {
            background: linear-gradient(110deg, #0F131C 30%, rgba(212, 175, 55, 0.25) 50%, #0F131C 70%);
            background-size: 200% 100%;
            animation: bcGoldShimmerSweep 3s infinite linear;
        }
        .bc-anim-certifications { animation: bcSpringScaleUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcCascadingDrop {
            0% { transform: translateY(-90px); opacity: 0; }
            70% { transform: translateY(12px); opacity: 1; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .bc-anim-trainings { animation: bcCascadingDrop 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcRadialPulse {
            0% { transform: scale(0.2); opacity: 0; filter: blur(10px); }
            100% { transform: scale(1); opacity: 1; filter: blur(0); }
        }
        .bc-anim-contributions { animation: bcRadialPulse 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcQuoteScale {
            0% { transform: scale(0.5) rotate(-15deg); opacity: 0; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .bc-anim-testimonials { animation: bcQuoteScale 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcBookUnfold {
            0% { transform: perspective(1000px) rotateY(-95deg); transform-origin: left center; opacity: 0; }
            100% { transform: perspective(1000px) rotateY(0deg); opacity: 1; }
        }
        .bc-anim-publications { animation: bcBookUnfold 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcIrisExpand {
            0% { clip-path: circle(0% at 50% 50%); opacity: 0; }
            100% { clip-path: circle(150% at 50% 50%); opacity: 1; }
        }
        .bc-anim-media { animation: bcIrisExpand 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes bcGlassRise {
            0% { transform: translateY(80px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .bc-anim-contact { animation: bcGlassRise 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="relative bg-[#07090E] text-slate-100 font-sans antialiased overflow-x-hidden min-h-screen selection:bg-amber-500 selection:text-slate-950">
        <!-- Ambient Gold & Deep Slate Background Glow Orbs -->
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-gradient-to-tr from-amber-500/10 via-yellow-600/5 to-indigo-600/10 rounded-full blur-[160px] pointer-events-none z-0"></div>
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-amber-600/5 rounded-full blur-[140px] pointer-events-none z-0"></div>

        <!-- MAIN CENTRAL ORBIT CONTAINER -->
        <main class="relative z-10 min-h-screen flex flex-col items-center justify-start pt-6 sm:pt-10 pb-16 px-4 sm:px-6">

            <!-- STACKED HEADER ABOVE AVATAR (NAME, POSITION, AND SHORT SUMMARY) -->
            <div class="text-center space-y-2.5 max-w-3xl mx-auto mb-6 sm:mb-8">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 font-mono text-[11px] uppercase tracking-widest shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    // {{ strtoupper($portfolio->position ?? 'Executive Leader & Systems Architect') }}
                </span>
                @php
                    $nameLen = mb_strlen($user->name ?? '');
                    $nameFontSize = $nameLen > 30 
                        ? 'text-xs sm:text-base md:text-xl lg:text-2xl' 
                        : ($nameLen > 20 
                            ? 'text-sm sm:text-xl md:text-2xl lg:text-3xl' 
                            : 'text-lg sm:text-2xl md:text-3xl lg:text-4xl');
                @endphp
                <div class="w-full overflow-hidden px-1 flex justify-center items-center">
                    <h1 class="whitespace-nowrap font-serif font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-100 to-white uppercase tracking-tight leading-none text-center inline-block max-w-full {{ $nameFontSize }}">
                        {{ $user->name }}
                    </h1>
                </div>
                <p class="text-slate-300 text-xs sm:text-sm md:text-base font-light leading-relaxed max-w-xl mx-auto">
                    {{ $portfolio->summary ?? $portfolio->description ?? $portfolio->detailed_bio ?? 'Strategic technologist and executive architect leading enterprise SaaS platforms and high-scale systems.' }}
                </p>
            </div>

            <!-- CENTRAL ORBIT CONTAINER (DESKTOP ORBITAL RING & RESPONSIVE GRID DOCKING) -->
            <div class="relative w-full max-w-[560px] lg:max-w-[640px] aspect-square max-h-[580px] flex items-center justify-center mx-auto my-2 sm:my-4">

                <!-- CENTERPIECE AVATAR WITH CONCENTRIC GOLD RINGS -->
                <div class="relative z-20 group cursor-pointer" id="bcCenterAvatar">
                    <!-- Concentric Glowing Ring Pulses -->
                    <div class="absolute -inset-4 rounded-full bg-gradient-to-r from-amber-500/30 via-yellow-500/20 to-amber-600/30 blur-xl opacity-75 group-hover:opacity-100 transition-opacity animate-pulse"></div>
                    <div class="absolute -inset-2 rounded-full border border-amber-400/40 animate-ping opacity-25"></div>
                    
                    <!-- Avatar Circle -->
                    <div class="w-32 h-32 sm:w-40 sm:h-40 md:w-44 md:h-44 rounded-full p-1.5 bg-gradient-to-b from-amber-400 via-amber-600/60 to-slate-900 shadow-[0_0_60px_rgba(212,175,55,0.3)] transition-transform duration-500 group-hover:scale-105 relative z-10 overflow-hidden">
                        @if($portfolio->profile_image)
                            <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        @else
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?fit=crop&w=800&q=80" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        @endif
                    </div>

                    <!-- Gold Ring Overlay Badge -->
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full bg-[#07090E]/90 border border-amber-400/40 text-[10px] font-mono font-bold text-amber-300 uppercase tracking-widest shadow-xl whitespace-nowrap z-30">
                        EXECUTIVE HUB
                    </div>
                </div>

                <!-- ORBITAL NAVIGATION NODES CONTAINER (14 NODES DISTRIBUTED SYMMETRICALLY) -->
                <div class="absolute inset-0 z-30 pointer-events-none md:pointer-events-auto flex items-center justify-center">
                    @php
                        $bcNavItems = [
                            ['id' => 'about', 'label' => 'About', 'icon' => 'fa-user-tie', 'show' => $portfolio->show_about ?? true],
                            ['id' => 'services', 'label' => 'Services Offered', 'icon' => 'fa-briefcase', 'show' => $portfolio->show_services ?? true],
                            ['id' => 'experience', 'label' => 'Work Experience', 'icon' => 'fa-building-columns', 'show' => $portfolio->show_experience ?? true],
                            ['id' => 'skills', 'label' => 'Skills', 'icon' => 'fa-layer-group', 'show' => $portfolio->show_skills ?? true],
                            ['id' => 'projects', 'label' => 'Projects', 'icon' => 'fa-diagram-project', 'show' => $portfolio->show_projects ?? true],
                            ['id' => 'education', 'label' => 'Education', 'icon' => 'fa-graduation-cap', 'show' => $portfolio->show_education ?? true],
                            ['id' => 'certifications', 'label' => 'Certifications', 'icon' => 'fa-award', 'show' => $portfolio->show_certifications ?? true],
                            ['id' => 'trainings', 'label' => 'Trainings', 'icon' => 'fa-chalkboard-user', 'show' => $portfolio->show_trainings ?? true],
                            ['id' => 'contributions', 'label' => 'Contributions', 'icon' => 'fa-handshake-angle', 'show' => $portfolio->show_contributions ?? true],
                            ['id' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fa-quote-left', 'show' => $portfolio->show_testimonials ?? true],
                            ['id' => 'publications', 'label' => 'Publications', 'icon' => 'fa-book-bookmark', 'show' => $portfolio->show_publications ?? true],
                            ['id' => 'media', 'label' => 'Media Appearances', 'icon' => 'fa-tv', 'show' => $portfolio->show_media ?? true],
                            ['id' => 'contact', 'label' => 'Contact', 'icon' => 'fa-paper-plane', 'show' => true],
                            ['id' => 'main-site', 'label' => 'Main Site', 'icon' => 'fa-compass', 'show' => true, 'external' => '/'],
                        ];
                    @endphp

                    <!-- DESKTOP ORBIT NODES (MD & ABOVE) -->
                    <div class="hidden md:block absolute inset-0">
                        @foreach($bcNavItems as $index => $item)
                            @php
                                $angleDeg = ($index * (360 / count($bcNavItems))) - 90;
                                $angleRad = deg2rad($angleDeg);
                                $radiusPercent = 42; 
                                $leftPercent = 50 + ($radiusPercent * cos($angleRad));
                                $topPercent = 50 + ($radiusPercent * sin($angleRad));
                            @endphp
                            <button type="button" 
                                    data-bc-target="{{ $item['id'] }}" 
                                    data-external="{{ $item['external'] ?? '' }}"
                                    style="left: {{ number_format($leftPercent, 2) }}%; top: {{ number_format($topPercent, 2) }}%;"
                                    class="bc-orbit-node absolute -translate-x-1/2 -translate-y-1/2 group pointer-events-auto cursor-pointer focus:outline-none z-30 transition-all duration-300 hover:scale-115"
                                    title="{{ $item['label'] }}">
                                <div class="w-11 h-11 lg:w-13 lg:h-13 rounded-2xl bg-[#0F131C]/90 border border-amber-400/30 hover:border-amber-400 text-amber-300 hover:bg-amber-500 hover:text-slate-950 shadow-xl shadow-amber-500/10 backdrop-blur-md flex items-center justify-center transition-all duration-300">
                                    <i class="fa-solid {{ $item['icon'] }} text-sm lg:text-base"></i>
                                </div>
                                <span class="absolute top-full left-1/2 -translate-x-1/2 mt-1 px-2.5 py-0.5 rounded-full bg-[#0F131C]/95 border border-amber-400/30 text-[10px] font-display font-semibold text-amber-200/90 whitespace-nowrap pointer-events-none shadow-md group-hover:border-amber-400 group-hover:text-amber-300 group-hover:bg-[#0F131C] transition-all">
                                    {{ $item['label'] }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                </div>
            </div>

            <!-- MOBILE / TABLET DOCKED RESPONSIVE GRID (SM & BELOW) -->
            <div class="md:hidden w-full max-w-lg mx-auto grid grid-cols-2 xs:grid-cols-3 gap-3 pt-4 z-30">
                @foreach($bcNavItems as $item)
                    <button type="button" 
                            data-bc-target="{{ $item['id'] }}" 
                            data-external="{{ $item['external'] ?? '' }}"
                            class="bc-orbit-node p-3 rounded-2xl bg-[#0F131C]/90 border border-amber-400/30 hover:border-amber-400 text-slate-200 hover:text-white flex items-center gap-2.5 shadow-lg backdrop-blur-md cursor-pointer transition-all active:scale-95">
                        <div class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                        </div>
                        <span class="text-xs font-display font-semibold truncate text-left">{{ $item['label'] }}</span>
                    </button>
                @endforeach
            </div>

        </main>

        <!-- FLOATING RETURN TO HUB CONTROL -->
        <div id="bcHubCloseControl" class="fixed top-6 right-6 z-50 hidden">
            <button type="button" id="bcCloseHubBtn" class="px-5 py-2.5 rounded-full bg-[#0F131C]/90 border border-amber-400/50 hover:border-amber-400 text-amber-300 hover:bg-amber-500 hover:text-slate-950 font-mono text-xs uppercase tracking-widest font-bold shadow-2xl backdrop-blur-xl flex items-center gap-2.5 transition-all duration-300 cursor-pointer hover:scale-105">
                <span>Return to Hub</span>
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- 1. ABOUT OVERLAY -->
        <div id="bc-overlay-about" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-4xl mx-auto pt-16 pb-20 space-y-8 bc-anim-about">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// EXECUTIVE SUMMARY</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Strategic Vision & About</h2>
                </div>
                <div class="p-8 sm:p-10 rounded-3xl bg-[#0F131C]/80 border border-amber-400/20 space-y-6 text-slate-200 text-base sm:text-lg font-light leading-relaxed shadow-2xl">
                    <p>{{ $portfolio->detailed_bio ?? $portfolio->description ?? 'Executive architect and technology strategist.' }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-white/10 text-xs font-mono">
                        <div>
                            <span class="text-slate-400 block mb-1 uppercase tracking-wider">Location</span>
                            <strong class="text-amber-300 font-semibold">{{ trim(($portfolio->city ?? '') . ', ' . ($portfolio->country ?? 'Global')) ?: 'Global / Remote' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1 uppercase tracking-wider">Position</span>
                            <strong class="text-amber-300 font-semibold">{{ $portfolio->position ?? 'Executive Architect' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1 uppercase tracking-wider">Experience</span>
                            <strong class="text-amber-300 font-semibold">{{ !empty($experiences) && count($experiences) > 0 ? count($experiences) . '+ Roles' : '10+ Years' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1 uppercase tracking-wider">Status</span>
                            <strong class="text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Available</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. SERVICES OFFERED OVERLAY -->
        <div id="bc-overlay-services" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-services">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// CORE CAPABILITIES</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Services Offered</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($services) && count($services) > 0)
                        @foreach($services as $srv)
                            <div class="p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 hover:border-amber-400/60 transition-all space-y-4 shadow-2xl group">
                                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl">
                                    <i class="fa-solid {{ $srv->icon ?? 'fa-cube' }}"></i>
                                </div>
                                <h3 class="text-xl font-display font-bold text-white group-hover:text-amber-300 transition-colors">{{ $srv->title }}</h3>
                                <p class="text-slate-300 text-sm font-light leading-relaxed">{!! $srv->description !!}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No service records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. WORK EXPERIENCE OVERLAY -->
        <div id="bc-overlay-experience" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-4xl mx-auto pt-16 pb-20 space-y-8 bc-anim-experience">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// CAREER TRACK</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Work Experience</h2>
                </div>
                <div class="relative pl-6 sm:pl-10 space-y-8">
                    <div class="bc-gold-timeline-line absolute top-0 bottom-0 left-2.5 w-0.5 bg-gradient-to-b from-amber-400 via-amber-600/50 to-transparent"></div>
                    @if(!empty($experiences) && count($experiences) > 0)
                        @foreach($experiences as $exp)
                            <div class="relative space-y-3 p-7 rounded-3xl bg-[#0F131C]/80 border border-amber-400/20 shadow-xl">
                                <div class="absolute -left-6 sm:-left-10 top-8 w-5 h-5 rounded-full bg-[#07090E] border-2 border-amber-400 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                </div>
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h3 class="text-lg font-display font-bold text-white">{{ $exp->position }}</h3>
                                    <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 font-mono text-xs">
                                        {{ $exp->start_date->format('M Y') }} – {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-mono uppercase tracking-wider text-slate-400">{{ $exp->company }}</h4>
                                <p class="text-slate-300 text-sm font-light leading-relaxed">{!! $exp->description !!}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No work experience records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. SKILLS OVERLAY -->
        <div id="bc-overlay-skills" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-skills">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// TECHNICAL PROFICIENCY</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Skills & Competencies</h2>
                </div>
                <div class="space-y-8">
                    @if(!empty($skills) && count($skills) > 0)
                        @php
                            $bcSkillsGrouped = collect($skills)->groupBy(function($s) {
                                $cat = is_array($s) ? ($s['category'] ?? '') : ($s->category ?? '');
                                return !empty(trim($cat)) ? trim($cat) : 'Core Competencies';
                            });
                        @endphp
                        @foreach($bcSkillsGrouped as $parentCategory => $catSkills)
                            <div>
                                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 mb-4 flex items-center gap-3">
                                    <span>// {{ $parentCategory }}</span>
                                    <span class="flex-grow h-px bg-amber-400/20"></span>
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($catSkills as $sk)
                                        @php
                                            $skName = is_array($sk) ? ($sk['name'] ?? '') : ($sk->name ?? '');
                                            $skProf = is_array($sk) ? ($sk['proficiency'] ?? $sk['percentage'] ?? 90) : ($sk->proficiency ?? $sk->percentage ?? 90);
                                        @endphp
                                        <div class="p-4 rounded-2xl bg-[#0F131C]/90 border border-amber-400/20 hover:border-amber-400/50 space-y-2.5 shadow-xl transition-all">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-display font-bold text-white">{{ $skName }}</span>
                                                <span class="text-[10px] font-mono text-amber-400 font-bold">{{ $skProf }}%</span>
                                            </div>
                                            <div class="w-full h-1.5 rounded-full bg-white/10 overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-amber-500 to-yellow-300 rounded-full" style="width: {{ $skProf }}%;"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No skill records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 5. PROJECTS OVERLAY -->
        <div id="bc-overlay-projects" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-6xl mx-auto pt-16 pb-20 space-y-8 bc-anim-projects">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// FEATURED PORTFOLIO</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Projects & Case Studies</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @if(!empty($projects) && count($projects) > 0)
                        @foreach($projects as $proj)
                            <div class="group h-full rounded-3xl bg-[#0F131C] border border-amber-400/20 hover:border-amber-400/60 overflow-hidden shadow-2xl flex flex-col justify-between transition-all duration-300">
                                <!-- TOP IMAGE CONTAINER -->
                                <div class="relative w-full h-44 sm:h-48 overflow-hidden shrink-0 bg-[#07090E]">
                                    @if(!empty($proj->image_path))
                                        <img src="{{ Storage::url($proj->image_path) }}" alt="{{ $proj->title }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#0F131C] via-slate-900 to-[#07090E] flex flex-col items-center justify-center p-4 text-center">
                                            <i class="fa-solid fa-layer-group text-3xl text-amber-400/40 mb-1 group-hover:scale-110 transition-transform"></i>
                                            <span class="text-[10px] font-mono text-slate-500 uppercase tracking-widest">Case Study</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0F131C] via-transparent to-transparent opacity-80"></div>
                                </div>
                                
                                <!-- CARD CONTENT BODY -->
                                <div class="p-5 flex flex-col flex-grow justify-between space-y-3 bg-[#0F131C]">
                                    <div class="space-y-1.5">
                                        <span class="text-[10px] font-mono font-bold text-amber-400 uppercase tracking-widest block">// FEATURED PROJECT</span>
                                        <h3 class="text-sm sm:text-base font-display font-extrabold text-white line-clamp-1 leading-snug" title="{{ $proj->title }}">
                                            {{ $proj->title }}
                                        </h3>
                                        <p class="text-slate-300 text-xs font-light leading-relaxed line-clamp-3">
                                            {{ strip_tags($proj->description) }}
                                        </p>
                                    </div>

                                    <button type="button" 
                                            data-bc-modal-title="{{ $proj->title }}" 
                                            data-bc-modal-desc="{{ $proj->description }}" 
                                            data-bc-modal-img="{{ !empty($proj->image_path) ? Storage::url($proj->image_path) : 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?fit=crop&w=1200&q=80' }}"
                                            data-bc-modal-url="{{ $proj->project_url ?? '#contact' }}" 
                                            class="bc-read-more-btn w-full py-2.5 px-4 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-300 hover:text-slate-950 border border-amber-400/30 hover:border-amber-400 font-mono font-bold text-xs uppercase tracking-wider transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 mt-2">
                                        Read More <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No project records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 6. EDUCATION OVERLAY -->
        <div id="bc-overlay-education" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-4xl mx-auto pt-16 pb-20 space-y-8 bc-anim-education">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// ACADEMIC QUALIFICATIONS</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Education</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($education) && count($education) > 0)
                        @foreach($education as $edu)
                            <div class="p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-3 shadow-xl relative overflow-hidden">
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 font-mono text-xs">
                                    {{ $edu->start_date->format('Y') }} – {{ $edu->end_date->format('Y') }}
                                </span>
                                <h3 class="text-xl font-display font-bold text-white pt-2">{{ $edu->degree }}</h3>
                                <h4 class="text-xs font-mono uppercase tracking-wider text-slate-400">{{ $edu->institution }}</h4>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No education records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 7. CERTIFICATIONS OVERLAY -->
        <div id="bc-overlay-certifications" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-certifications">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// VERIFIED CREDENTIALS</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Certifications</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(!empty($certifications) && count($certifications) > 0)
                        @foreach($certifications as $cert)
                            <div class="bc-gold-shimmer-card p-7 rounded-3xl bg-[#0F131C]/90 border border-amber-400/30 space-y-3 shadow-xl relative overflow-hidden">
                                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-award"></i>
                                </div>
                                <h3 class="text-base font-display font-bold text-white">{{ $cert->name }}</h3>
                                <p class="text-xs font-mono text-slate-400">{{ $cert->issuing_organization ?? 'Professional Board' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No certification records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 8. TRAININGS OVERLAY -->
        <div id="bc-overlay-trainings" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-trainings">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// PROFESSIONAL DEVELOPMENT</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Trainings & Workshops</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($trainings) && count($trainings) > 0)
                        @foreach($trainings as $trn)
                            <div class="p-7 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-3 shadow-xl">
                                <h3 class="text-lg font-display font-bold text-white">{{ $trn->title }}</h3>
                                <p class="text-xs font-mono text-amber-400">{{ $trn->institution ?? 'Executive Institute' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No training records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 9. CONTRIBUTIONS OVERLAY -->
        <div id="bc-overlay-contributions" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-contributions">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// IMPACT & INITIATIVES</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Contributions</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($contributions) && count($contributions) > 0)
                        @foreach($contributions as $cnt)
                            <div class="p-7 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-3 shadow-xl">
                                <h3 class="text-lg font-display font-bold text-white">{{ $cnt->title }}</h3>
                                <div class="text-slate-300 text-sm font-light leading-relaxed">{!! $cnt->description !!}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No contribution records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 10. TESTIMONIALS OVERLAY -->
        <div id="bc-overlay-testimonials" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-testimonials">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// CLIENT ENDORSEMENTS</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Testimonials</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($testimonials) && count($testimonials) > 0)
                        @foreach($testimonials as $tst)
                            @php
                                $tName = $tst->client_name ?? $tst->author_name ?? 'Client';
                                $tDesig = $tst->designation ?? $tst->author_title ?? '';
                                $tContent = $tst->content ?? $tst->quote ?? '';
                            @endphp
                            <div class="p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-5 shadow-2xl relative overflow-hidden">
                                <i class="fa-solid fa-quote-right absolute top-6 right-6 text-4xl text-amber-500/10"></i>
                                <p class="text-slate-200 text-sm sm:text-base font-light italic leading-relaxed">"{{ $tContent }}"</p>
                                <div class="border-t border-white/10 pt-4">
                                    <h4 class="text-sm font-bold text-white">{{ $tName }}</h4>
                                    <span class="text-xs font-mono text-amber-400 block">{{ $tDesig }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No testimonial records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 11. PUBLICATIONS OVERLAY -->
        <div id="bc-overlay-publications" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-publications">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// RESEARCH & PAPERS</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Publications & Reports</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($publications) && count($publications) > 0)
                        @foreach($publications as $pub)
                            <div class="p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-4 shadow-2xl">
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 font-mono text-xs">
                                    {{ $pub->type ?? 'JOURNAL PAPER' }} ({{ $pub->year }})
                                </span>
                                <h3 class="text-lg font-display font-bold text-white">{{ $pub->title }}</h3>
                                <p class="text-xs font-mono text-slate-400">Authors: {{ $pub->authors }}</p>
                                @if(!empty($pub->link))
                                    <a href="{{ $pub->link }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all">
                                        Online Link <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No publication records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 12. MEDIA APPEARANCES OVERLAY -->
        <div id="bc-overlay-media" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-5xl mx-auto pt-16 pb-20 space-y-8 bc-anim-media">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// PRESS & BROADCASTS</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Media Appearances</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($media) && count($media) > 0)
                        @foreach($media as $med)
                            <div class="p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-4 shadow-2xl">
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 font-mono text-xs">
                                    {{ $med->type == 'tv' ? 'TV / Video Interview' : 'Newspaper / Op-Ed Article' }}
                                </span>
                                <h3 class="text-lg font-display font-bold text-white">{{ $med->title }}</h3>
                                <p class="text-xs font-mono text-slate-400">
                                    {{ $med->channel_platform ?? $med->newspaper_name }}
                                </p>
                                @if(!empty($med->link))
                                    <a href="{{ $med->link }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all">
                                        Watch / Read <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 rounded-3xl bg-[#0F131C]/50 border border-white/10 text-center space-y-3 col-span-full">
                            <h3 class="text-base font-bold text-white uppercase">No Records Found</h3>
                            <p class="text-slate-400 text-xs">No media appearance records have been added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 13. CONTACT OVERLAY -->
        <div id="bc-overlay-contact" class="bc-overlay-modal fixed inset-0 z-40 bg-[#07090E]/95 backdrop-blur-2xl p-6 sm:p-12 overflow-y-auto hidden">
            <div class="max-w-4xl mx-auto pt-16 pb-20 space-y-8 bc-anim-contact">
                <div class="border-b border-amber-400/20 pb-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-widest text-amber-400 block mb-1">// INITIATE DIALOGUE</span>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-serif font-extrabold text-white uppercase tracking-tight">Contact</h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-5 p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-6">
                        <h3 class="text-lg font-bold text-white">Direct Details</h3>
                        <div class="space-y-4 text-xs font-mono">
                            @if($portfolio->show_email && !empty($profile['email']))
                                <div>
                                    <span class="text-slate-400 block mb-1">EMAIL</span>
                                    <a href="mailto:{{ $profile['email'] }}" class="text-amber-300 font-bold hover:underline">{{ $profile['email'] }}</a>
                                </div>
                            @endif
                            @if($portfolio->show_phone && !empty($profile['phone']))
                                <div>
                                    <span class="text-slate-400 block mb-1">PHONE</span>
                                    <a href="tel:{{ $profile['phone'] }}" class="text-amber-300 font-bold hover:underline">{{ $profile['phone'] }}</a>
                                </div>
                            @endif
                            @if(!empty($profile['location']))
                                <div>
                                    <span class="text-slate-400 block mb-1">LOCATION</span>
                                    <span class="text-white font-bold">{{ $profile['location'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-7 p-8 rounded-3xl bg-[#0F131C]/90 border border-amber-400/20 space-y-5">
                        <h3 class="text-lg font-bold text-white">Send Inquiry</h3>
                        <form action="{{ route('portfolio.contact.store', $portfolio->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="text" name="name" placeholder="Your Name" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-xs font-mono">
                            <input type="email" name="email" placeholder="Your Email" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-xs font-mono">
                            <textarea name="message" rows="4" placeholder="Your Message..." required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-xs font-mono"></textarea>
                            <button type="submit" class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs uppercase font-mono tracking-widest transition-all">
                                Send Message →
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROJECT DETAILS MODAL POPUP FOR BUSINESS CLASS -->
        <div id="bcProjectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/90 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
            <div class="relative w-full max-w-2xl bg-[#0F131C] border border-amber-400/30 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] text-slate-100">
                <button type="button" id="closeBcProjectModal" class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-slate-900/80 hover:bg-amber-500 hover:text-slate-950 border border-white/20 text-slate-300 font-bold flex items-center justify-center transition-all cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
                <div id="bcModalImgWrapper" class="relative w-full h-48 sm:h-60 bg-slate-900 overflow-hidden shrink-0">
                    <img id="bcModalProjectImg" src="" alt="Project Image" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0F131C] via-transparent to-transparent"></div>
                </div>
                <div class="p-6 sm:p-8 overflow-y-auto space-y-4">
                    <h3 id="bcModalProjectTitle" class="text-xl sm:text-2xl font-serif font-extrabold text-amber-300 uppercase tracking-tight"></h3>
                    <p id="bcModalProjectDesc" class="text-slate-300 text-sm font-light leading-relaxed whitespace-pre-line border-t border-white/10 pt-4"></p>
                    <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                        <a id="bcModalProjectUrl" href="#" target="_blank" class="px-6 py-2.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs uppercase tracking-wider">
                            Visit Live Site <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                        <button type="button" id="closeBcProjectModalBtn" class="px-6 py-2.5 rounded-xl bg-white/10 hover:bg-white hover:text-slate-950 border border-white/20 text-white font-bold text-xs uppercase tracking-wider cursor-pointer">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUSINESS CLASS JAVASCRIPT STATE ENGINE & ANIMATION SIGNATURES -->
        <script>
        (function() {
            function initBusinessClassEngine() {
                const nodes = document.querySelectorAll('.bc-orbit-node');
                const overlays = document.querySelectorAll('.bc-overlay-modal');
                const hubCloseControl = document.getElementById('bcHubCloseControl');
                const closeHubBtn = document.getElementById('bcCloseHubBtn');

                function openSectionOverlay(targetId) {
                    if (!targetId || targetId === 'main-site') return;

                    overlays.forEach(o => o.classList.add('hidden'));

                    const activeOverlay = document.getElementById('bc-overlay-' + targetId);
                    if (activeOverlay) {
                        activeOverlay.classList.remove('hidden');
                        if (hubCloseControl) hubCloseControl.classList.remove('hidden');
                        
                        if (window.location.hash !== '#' + targetId) {
                            history.pushState(null, null, '#' + targetId);
                        }
                    }
                }

                function returnToHub() {
                    overlays.forEach(o => o.classList.add('hidden'));
                    if (hubCloseControl) hubCloseControl.classList.add('hidden');
                    
                    if (window.location.hash) {
                        history.pushState(null, null, window.location.pathname + window.location.search);
                    }
                }

                nodes.forEach(node => {
                    node.addEventListener('click', function(e) {
                        const ext = this.getAttribute('data-external');
                        if (ext && ext.trim() !== '') {
                            window.location.href = ext;
                            return;
                        }
                        const targetId = this.getAttribute('data-bc-target');
                        if (targetId) {
                            openSectionOverlay(targetId);
                        }
                    });
                });

                if (closeHubBtn) {
                    closeHubBtn.addEventListener('click', returnToHub);
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        returnToHub();
                        closeBcProjectModal();
                    }
                });

                const initialHash = window.location.hash.replace('#', '');
                if (initialHash) {
                    openSectionOverlay(initialHash);
                }

                // Project Details Modal Popup
                const bcProjectModal = document.getElementById('bcProjectModal');
                const closeBcModalBtn = document.getElementById('closeBcProjectModal');
                const closeBcModalActionBtn = document.getElementById('closeBcProjectModalBtn');
                const bcModalTitle = document.getElementById('bcModalProjectTitle');
                const bcModalDesc = document.getElementById('bcModalProjectDesc');
                const bcModalImg = document.getElementById('bcModalProjectImg');
                const bcModalImgWrapper = document.getElementById('bcModalImgWrapper');
                const bcModalUrl = document.getElementById('bcModalProjectUrl');

                function openBcProjectModal(data) {
                    if (!bcProjectModal) return;
                    if (bcModalTitle) bcModalTitle.textContent = data.title || 'Project Details';
                    if (bcModalDesc) bcModalDesc.textContent = data.description || 'Project details.';
                    if (data.image && data.image.trim() !== '') {
                        if (bcModalImg) bcModalImg.src = data.image;
                        if (bcModalImgWrapper) bcModalImgWrapper.style.display = 'block';
                    }
                    if (bcModalUrl) {
                        if (data.url && data.url.trim() !== '' && data.url !== '#contact') {
                            bcModalUrl.href = data.url;
                            bcModalUrl.style.display = 'inline-flex';
                        } else {
                            bcModalUrl.style.display = 'none';
                        }
                    }
                    bcProjectModal.classList.remove('hidden');
                    setTimeout(() => bcProjectModal.classList.remove('opacity-0'), 10);
                }

                function closeBcProjectModal() {
                    if (!bcProjectModal) return;
                    bcProjectModal.classList.add('opacity-0');
                    setTimeout(() => bcProjectModal.classList.add('hidden'), 250);
                }

                document.addEventListener('click', function(e) {
                    const readMore = e.target.closest('.bc-read-more-btn');
                    if (readMore) {
                        e.preventDefault();
                        openBcProjectModal({
                            title: readMore.getAttribute('data-bc-modal-title'),
                            description: readMore.getAttribute('data-bc-modal-desc'),
                            image: readMore.getAttribute('data-bc-modal-img'),
                            url: readMore.getAttribute('data-bc-modal-url')
                        });
                    }
                });

                if (closeBcModalBtn) closeBcModalBtn.addEventListener('click', closeBcProjectModal);
                if (closeBcModalActionBtn) closeBcModalActionBtn.addEventListener('click', closeBcProjectModal);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initBusinessClassEngine);
            } else {
                initBusinessClassEngine();
            }
        })();
        </script>
    </div>
    @elseif($theme == 'premium')
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <nav>
        <div class="premium-nav-container">
            <div class="logo">
                <a href="#hero" style="display: flex; align-items: center; text-decoration: none;">
                    @if($portfolio->profile_image)
                        <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="logo-avatar">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100&background=0D8ABC&color=fff" alt="{{ $user->name }}" class="logo-avatar">
                    @endif
                </a>
            </div>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <ul>
                <li><a href="/"><i class="fas fa-arrow-left me-1"></i> Main Site</a></li>
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">About</a></li>
                @if($portfolio->show_skills)<li><a href="#skills">Skills</a></li>@endif
                @if($portfolio->show_experience)<li><a href="#experience">Experience</a></li>@endif
                @if($portfolio->show_projects)<li><a href="#projects">Projects</a></li>@endif
                
                @if($portfolio->show_education || $portfolio->show_achievements || $portfolio->show_contributions || ($portfolio->show_publications && $portfolio->publications->isNotEmpty()))
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">Academic <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                    <div class="dropdown-content">
                        @if($portfolio->show_education)<a href="#skills-extra">Education</a>@endif
                        @if($portfolio->show_achievements)<a href="#skills-extra">Achievements</a>@endif
                        @if($portfolio->show_contributions)<a href="#contributions">Contributions</a>@endif
                        @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())<a href="#publications">Publications</a>@endif
                    </div>
                </li>
                @endif
                
                @if($portfolio->show_services || $portfolio->show_certifications || $portfolio->show_trainings || $portfolio->show_testimonials || ($portfolio->show_media && $portfolio->media->isNotEmpty()))
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">Professional <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                    <div class="dropdown-content">
                        @if($portfolio->show_services)<a href="#services">Services</a>@endif
                        @if($portfolio->show_certifications)<a href="#trainings">Certifications</a>@endif
                        @if($portfolio->show_trainings)<a href="#trainings">Trainings</a>@endif
                        @if($portfolio->show_testimonials)<a href="#testimonials">Testimonials</a>@endif
                        @if($portfolio->show_media && $portfolio->media->isNotEmpty())<a href="#media">Media</a>@endif
                    </div>
                </li>
                @endif
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section id="hero" class="hero">
        <div class="premium-container">
            <div class="premium-hero-row">
                <div class="hero-content">
                    <div class="hero-subtitle">{{ $profile['short_title'] }}</div>
                    <h1>{{ $profile['name'] }}</h1>
                    <div class="hero-intro-text" style="margin-bottom: 1.5rem; color: var(--text-secondary); line-height: 1.8;">{!! $profile['intro'] !!}</div>
                    <div class="hero-btns">
                        <a href="#projects" class="btn-primary">View Projects</a>
                        <a href="#contact" class="btn-primary" style="background: transparent; border: 2px solid var(--accent-color); color: var(--accent-color); margin-left: 1rem;">Contact Me</a>
                    </div>
                </div>
                <div class="hero-image">
                    @if($portfolio->profile_image)
                        <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $profile['name'] }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=400&background=0D8ABC&color=fff" alt="{{ $profile['name'] }}">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="about" class="reveal">
        <div class="premium-container">
            <div class="premium-row">
                <div class="premium-col-left">
                    <h2 class="section-title">Profile</h2>
                    
                    <!-- Quick Facts Sidebar Card -->
                    <div class="premium-facts-card">
                        <h4 class="facts-title">Quick Info</h4>
                        <div class="facts-list">
                            <div class="fact-item">
                                <div class="fact-icon"><i class="fas fa-location-dot"></i></div>
                                <div>
                                    <span class="fact-label">Location</span>
                                    <span class="fact-value">{{ $profile['location'] }}</span>
                                </div>
                            </div>
                            @if($portfolio->show_email && $profile['email'])
                            <div class="fact-item">
                                <div class="fact-icon"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <span class="fact-label">Email</span>
                                    <span class="fact-value" style="word-break: break-all;">{{ $profile['email'] }}</span>
                                </div>
                            </div>
                            @endif
                            @if($portfolio->show_phone && $profile['phone'])
                            <div class="fact-item">
                                <div class="fact-icon"><i class="fas fa-phone"></i></div>
                                <div>
                                    <span class="fact-label">Phone</span>
                                    <span class="fact-value">{{ $profile['phone'] }}</span>
                                </div>
                            </div>
                            @endif
                            @if($portfolio->show_linkedin && $profile['linkedin'])
                            <div class="fact-item">
                                <div class="fact-icon"><i class="fab fa-linkedin-in"></i></div>
                                <div>
                                    <span class="fact-label">LinkedIn</span>
                                    <span class="fact-value"><a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener noreferrer">View Profile</a></span>
                                </div>
                            </div>
                            @endif
                            <div class="fact-item">
                                <div class="fact-icon"><i class="fas fa-briefcase"></i></div>
                                <div>
                                    <span class="fact-label">Status</span>
                                    <span class="fact-value text-accent">Open to Opportunities</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="premium-col-right">
                    <div class="premium-bio-text">
                        <p>{!! $profile['detailed_profile'] !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($portfolio->show_skills)
    <div class="section-divider"><span></span></div>

    <section id="skills" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Technical Expertise</h2>
            <div class="skills-grid" data-limit="9">
                @forelse($profile['technical_skills'] as $category => $skills)
                <div class="skill-card">
                    <h3><i class="fas fa-{{ $skills['icon'] }}"></i> {{ $category }}</h3>
                    <ul class="skill-list">
                        @foreach($skills['items'] as $skill)
                        <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
                @empty
                    <p class="text-muted col-12">No skills added yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_services)
    <div class="section-divider"><span></span></div>

    <section id="services" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Services Offered</h2>
            <div class="skills-grid" data-limit="9">
                @forelse($portfolio->services as $service)
                <div class="skill-card">
                    <h3>{{ $service->title }}</h3>
                    <div style="color: var(--text-secondary); font-size: 0.95rem;">{!! $service->description !!}</div>
                </div>
                @empty
                    <p class="text-muted col-12">No services listed yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_experience)
    <div class="section-divider"><span></span></div>

    <section id="experience" class="reveal">
        <div class="premium-container">
            <div class="premium-row">
                <div class="premium-col-left">
                    <h2 class="section-title">Work Experience</h2>
                    <p class="premium-section-desc">A detailed overview of my professional timeline, highlighting positions, key achievements, and the technical value delivered in each role.</p>
                </div>
                <div class="premium-col-right">
                    <div class="timeline" data-limit="9">
                        @forelse($profile['experience'] as $exp)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="exp-date">{{ $exp['date'] }}</div>
                            <div class="exp-job">{{ $exp['title'] }}</div>
                            <div class="exp-company">{{ $exp['company'] }}</div>
                            <ul class="exp-details">
                                <li>{!! $exp['highlights'] !!}</li>
                            </ul>
                        </div>
                        @empty
                            <p class="text-muted col-12">No experience added yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_achievements || $portfolio->show_education)
    <div class="section-divider"><span></span></div>

    <section id="skills-extra" class="reveal">
        <div class="premium-container">
            <div class="premium-skills-extra-grid" style="{{ !$portfolio->show_achievements || !$portfolio->show_education ? 'grid-template-columns: 1fr;' : '' }}">
                @if($portfolio->show_achievements)
                <div>
                    <h3 class="premium-extra-title">Soft Skills & Achievements</h3>
                    <div class="premium-tags-flex">
                        @foreach($profile['soft_skills'] as $skill)
                        <span class="tag" style="font-size: 0.9rem; padding: 10px 20px;">{{ $skill }}</span>
                        @endforeach
                        @if(empty($profile['soft_skills']))
                            <p class="text-muted">No achievements added.</p>
                        @endif
                    </div>
                </div>
                @endif
                @if($portfolio->show_education)
                <div>
                    <h3 class="premium-extra-title">Education</h3>
                    <div class="premium-edu-list" data-limit="9">
                        @forelse($profile['education'] as $edu)
                        <div class="skill-card">
                            <div class="premium-edu-degree">{{ $edu['degree'] }}</div>
                            <div class="premium-edu-institution">{{ $edu['institution'] }}</div>
                            <div class="premium-edu-meta">{{ $edu['date'] }} | {{ $edu['result'] }}</div>
                        </div>
                        @empty
                            <p class="text-muted col-12">No education added.</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_certifications || $portfolio->show_trainings)
    <div class="section-divider"><span></span></div>

    <section id="trainings" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Certifications & Trainings</h2>
            <div class="premium-grid-2col" style="{{ !$portfolio->show_certifications || !$portfolio->show_trainings ? 'grid-template-columns: 1fr;' : '' }}">
                @if($portfolio->show_certifications)
                <div class="skill-card">
                    <h3>Certifications</h3>
                    <ul class="skill-list" data-limit="9">
                        @forelse($profile['certifications'] as $cert)
                        <li><i class="fas fa-certificate"></i> {{ $cert }}</li>
                        @empty
                            <li>No certifications</li>
                        @endforelse
                    </ul>
                </div>
                @endif
                @if($portfolio->show_trainings)
                <div class="skill-card">
                    <h3>Trainings</h3>
                    <ul class="skill-list" data-limit="9">
                        @forelse($profile['trainings'] as $training)
                        <li><i class="fas fa-chalkboard-teacher"></i> {{ $training }}</li>
                        @empty
                            <li>No trainings registered</li>
                        @endforelse
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_projects)
    <div class="section-divider"><span></span></div>

    <section id="projects" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Flagship Projects</h2>
            <div class="projects-grid" data-limit="9">
                @forelse($profile['projects'] as $project)
                @php
                    $plainDesc = strip_tags($project['description']);
                    $isLongDesc = strlen($plainDesc) > 120;
                    $isLongTitle = strlen($project['name']) > 30;
                    
                    $displayTitle = $isLongTitle ? (substr($project['name'], 0, 30) . '...') : $project['name'];
                    $displayDesc = $isLongDesc ? (substr($plainDesc, 0, 120) . '...') : $plainDesc;
                @endphp
                <div class="project-card">
                    <div class="project-img">
                        @if($project['image'])
                            <img src="{{ Storage::url($project['image']) }}" alt="{{ $project['name'] }}">
                        @else
                            <div style="height: 100%; width: 100%; background: #222; display: flex; align-items: center; justify-content: center; color: #444;">No Image</div>
                        @endif
                    </div>
                    <div class="project-content" style="padding: 30px; display: flex; flex-direction: column; height: calc(100% - 250px); box-sizing: border-box;">
                        <div class="project-tags" style="margin-bottom: 10px;">
                            @foreach($project['tags'] as $tag)
                            <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <h3 title="{{ $project['name'] }}" style="margin-top: 0; margin-bottom: 10px; font-size: 1.4rem;">{{ $displayTitle }}</h3>
                        <p style="margin-bottom: 20px; flex-grow: 1;">{{ $displayDesc }}</p>
                        <div style="margin-top: auto;">
                            <button class="btn-primary" 
                                    onclick="openPremiumProjectModal(this)"
                                    data-title="{{ $project['name'] }}"
                                    data-desc="{{ $project['description'] }}"
                                    data-image="{{ $project['image'] ? Storage::url($project['image']) : '' }}"
                                    data-tags="{{ implode(',', $project['tags']) }}"
                                    style="padding: 0.6rem 1.2rem; font-size: 0.85rem; border-radius: 30px; cursor: pointer; border: none; background: var(--accent-color); color: #000; font-weight: 700; transition: var(--transition);">
                                View Details <i class="fas fa-arrow-right ms-1" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-muted col-12 text-center py-4">No projects showcased yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_contributions)
    <div class="section-divider"><span></span></div>

    <section id="contributions" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Contributions</h2>
            <div class="skills-grid" data-limit="9">
                @forelse($portfolio->contributions as $contrib)
                <div class="skill-card">
                    <h3>{{ $contrib->title }}</h3>
                    <div style="color: var(--text-secondary); font-size: 0.95rem;">{!! $contrib->description !!}</div>
                </div>
                @empty
                    <p class="text-muted col-12">No contributions listed.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_testimonials)
    <div class="section-divider"><span></span></div>

    <section id="testimonials" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Testimonials</h2>
            @php $testiCount = count($portfolio->testimonials); @endphp
            <div class="@if($testiCount == 1) premium-testimonials-1 @elseif($testiCount == 2) premium-grid-2col @else projects-grid @endif" data-limit="9">
                @forelse($portfolio->testimonials as $testi)
                <div class="skill-card">
                    <div style="font-style: italic; color: var(--text-secondary); margin-bottom: 1.5rem;">{!! $testi->content !!}</div>
                    <div style="font-weight: 700; color: var(--accent-color);">— {{ $testi->client_name }}</div>
                </div>
                @empty
                    <p class="text-muted text-center col-12">No testimonials yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_media && $portfolio->media->isNotEmpty())
    <div class="section-divider"><span></span></div>

    <section id="media" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Media Appearances</h2>
            <div class="premium-grid-2col">
                <!-- TV & Talk Show Appearances -->
                <div class="skill-card">
                    <h3><i class="fas fa-tv"></i> TV & Talk Show Appearances</h3>
                    <ul class="skill-list" data-limit="9" style="list-style: none; padding-left: 0; margin-top: 1.5rem;">
                        @forelse($portfolio->media->where('type', 'tv') as $tv)
                            <li style="margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 1rem;">
                                <div class="fw-bold" style="font-size: 1.1rem; color: var(--text-primary);">{{ $tv->title }}</div>
                                <div style="color: var(--accent-color); font-size: 0.9rem; margin-top: 0.2rem;">
                                    <i class="fas fa-broadcast-tower me-1"></i> {{ $tv->channel_platform }} &bull; {{ \Carbon\Carbon::parse($tv->date)->format('M d, Y') }}
                                </div>
                                <div style="margin-top: 0.5rem;">
                                    <a href="{{ $tv->link }}" target="_blank" class="tag" style="background: rgba(255,255,255,0.05); text-decoration: none; color: var(--text-primary); font-size: 0.8rem; display: inline-block;"><i class="fas fa-play-circle me-1"></i> Watch Appearance</a>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted">No TV appearances listed yet.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Newspaper Op-eds -->
                <div class="skill-card">
                    <h3><i class="fas fa-newspaper"></i> Newspaper Op-eds</h3>
                    <ul class="skill-list" data-limit="9" style="list-style: none; padding-left: 0; margin-top: 1.5rem;">
                        @forelse($portfolio->media->where('type', 'oped') as $oped)
                            <li style="margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 1rem;">
                                <div class="fw-bold" style="font-size: 1.1rem; color: var(--text-primary);">{{ $oped->title }}</div>
                                <div style="color: var(--accent-color); font-size: 0.9rem; margin-top: 0.2rem;">
                                    <i class="fas fa-pen-nib me-1"></i> {{ $oped->newspaper_name }} &bull; {{ \Carbon\Carbon::parse($oped->date)->format('M d, Y') }}
                                </div>
                                <div style="margin-top: 0.5rem;">
                                    <a href="{{ $oped->link }}" target="_blank" class="tag" style="background: rgba(255,255,255,0.05); text-decoration: none; color: var(--text-primary); font-size: 0.8rem; display: inline-block;"><i class="fas fa-book-open me-1"></i> Read Article</a>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted">No newspaper op-eds listed yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())
    <div class="section-divider"><span></span></div>

    <section id="publications" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Publications</h2>
            <div class="skills-grid" data-limit="9">
                @foreach($portfolio->publications as $pub)
                <div class="skill-card">
                    <span class="tag" style="background: var(--accent-color); color: #000; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.8rem; display: inline-block;">{{ $pub->type }}</span>
                    <h3 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 1.3rem;">{{ $pub->title }}</h3>
                    <p style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 1rem;">
                        <strong>Authors:</strong> {{ $pub->authors }} <br>
                        <strong>Publisher:</strong> {{ $pub->publisher }} ({{ $pub->year }})
                    </p>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @if($pub->link)
                            <a href="{{ $pub->link }}" target="_blank" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 20px; text-decoration: none; display: inline-block; font-weight: 600;">Online Link</a>
                        @endif
                        @if($pub->report_path)
                            <a href="{{ Storage::url($pub->report_path) }}" target="_blank" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 20px; text-decoration: none; display: inline-block; font-weight: 600; background: #28a745; color: #fff;">Download Report</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="section-divider"><span></span></div>

    <section id="contact" class="reveal">
        <div class="premium-container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Contact Details</h3>
                    @if($portfolio->show_email && $profile['email'])
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Email</div>
                                <div style="font-weight: 600;">{{ $profile['email'] }}</div>
                            </div>
                        </div>
                    @endif

                    @if($portfolio->show_phone && $profile['phone'])
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Contact</div>
                                <div style="font-weight: 600;">{{ $profile['phone'] }}</div>
                            </div>
                        </div>
                    @endif

                    @if($portfolio->show_linkedin && $profile['linkedin'])
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fab fa-linkedin"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">LinkedIn</div>
                                <div style="font-weight: 600;"><a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">{{ $profile['linkedin'] }}</a></div>
                            </div>
                        </div>
                    @endif

                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-location-dot"></i></div>
                        <div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Location</div>
                            <div style="font-weight: 600;">{{ $profile['location'] }}</div>
                        </div>
                    </div>

                    @if(!$portfolio->show_email && !$portfolio->show_phone && !$portfolio->show_linkedin)
                        <p class="text-muted mt-3">Contact details are hidden by the portfolio owner.</p>
                    @endif
                </div>
                <div class="contact-form" style="background: var(--glass-bg); padding: 2rem; border-radius: 20px; border: 1px solid var(--glass-border);">
                    <h4 style="margin-bottom: 2rem; color: #fff;">Send a Message</h4>
                    @if(session('status') == 'message-sent')
                        <div style="background: rgba(0, 242, 255, 0.1); color: var(--accent-color); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; border: 1px solid var(--glass-border);">
                            Message sent successfully! I'll get back to you soon.
                        </div>
                    @endif
                    @if ($errors->any())
                        <div style="background: rgba(255, 0, 0, 0.1); color: #ff6b6b; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; border: 1px solid rgba(255, 0, 0, 0.2); font-size: 0.9rem;">
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('portfolio.contact.store', $portfolio->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                        @csrf
                        <input type="text" name="name" placeholder="Your Name" required style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1rem; color: #fff; border-radius: 10px;">
                        <input type="email" name="email" placeholder="Your Email" required style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1rem; color: #fff; border-radius: 10px;">
                        <textarea name="message" placeholder="Your Message" rows="5" required style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1rem; color: #fff; border-radius: 10px;"></textarea>
                        <button type="submit" class="btn-primary" style="border: none; cursor: pointer;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer style="background: rgba(10, 15, 29, 0.96); border-top: 1px solid rgba(255, 255, 255, 0.08); padding: 4.5rem 0 2.5rem; position: relative; z-index: 10;">
        <div class="premium-container">
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; max-width: 850px; margin: 0 auto;">
                <!-- Profile Avatar Image -->
                <div style="margin-bottom: 1.25rem;">
                    @if($portfolio->profile_image)
                        <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" style="width: 84px; height: 84px; object-fit: cover; border-radius: 50%; border: 3px solid var(--accent-color); box-shadow: 0 0 25px rgba(56, 189, 248, 0.35);">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=160&background=0D8ABC&color=fff" alt="{{ $user->name }}" style="width: 84px; height: 84px; object-fit: cover; border-radius: 50%; border: 3px solid var(--accent-color); box-shadow: 0 0 25px rgba(56, 189, 248, 0.35);">
                    @endif
                </div>

                <!-- Profile User Name -->
                <h3 style="color: #ffffff; font-weight: 700; margin-bottom: 0.25rem; font-size: 1.6rem; letter-spacing: -0.02em;">{{ $user->name }}</h3>
                
                <!-- Tagline / Title -->
                <p style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 2rem; font-weight: 500;">
                    {{ $portfolio->title ?? $profile['short_title'] }}
                </p>

                <!-- Complete Header-Style Portfolio Navigation Links -->
                <div class="footer-nav" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem 1.75rem; margin-bottom: 2.25rem;">
                    <a href="#hero" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Home</a>
                    <a href="#about" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">About</a>
                    @if($portfolio->show_skills)<a href="#skills" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Skills</a>@endif
                    @if($portfolio->show_experience)<a href="#experience" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Experience</a>@endif
                    @if($portfolio->show_projects)<a href="#projects" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Projects</a>@endif
                    @if($portfolio->show_education)<a href="#skills-extra" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Education</a>@endif
                    @if($portfolio->show_achievements)<a href="#skills-extra" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Achievements</a>@endif
                    @if($portfolio->show_contributions)<a href="#contributions" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Contributions</a>@endif
                    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())<a href="#publications" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Publications</a>@endif
                    @if($portfolio->show_services)<a href="#services" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Services</a>@endif
                    @if($portfolio->show_certifications)<a href="#trainings" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Certifications</a>@endif
                    @if($portfolio->show_trainings)<a href="#trainings" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Trainings</a>@endif
                    @if($portfolio->show_testimonials)<a href="#testimonials" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Testimonials</a>@endif
                    @if($portfolio->show_media && $portfolio->media->isNotEmpty())<a href="#media" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Media</a>@endif
                    <a href="#contact" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.3s ease;">Contact</a>
                </div>

                <div style="width: 100%; height: 1px; background: rgba(255,255,255,0.08); margin-bottom: 1.75rem;"></div>

                <!-- Professional Copyright & Branding -->
                <p style="color: #94a3b8; font-size: 0.88rem; margin-bottom: 0; line-height: 1.6;">
                    &copy; {{ now()->year }} <strong style="color: #ffffff;">{{ $user->name }}</strong>. All rights reserved <span style="margin: 0 6px; opacity: 0.5;">&bull;</span> Powered by <a href="https://itechgb.com/" target="_blank" style="color: #e2e8f0; text-decoration: underline; text-underline-offset: 3px; font-weight: 500; transition: color 0.2s ease;">Innovative Technologies GB</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Reusable Custom Premium Modal for Project Details -->
    <div id="premiumProjectModal" class="premium-modal">
        <div class="premium-modal-backdrop"></div>
        <div class="premium-modal-content">
            <button type="button" class="premium-modal-close">&times;</button>
            <div class="premium-modal-body">
                <div class="premium-modal-img-col" id="premiumModalImgCol">
                    <img id="premiumModalImg" src="" alt="Project Image">
                </div>
                <div class="premium-modal-text-col" id="premiumModalTextCol">
                    <div class="premium-modal-tags" id="premiumModalTags"></div>
                    <h3 id="premiumModalTitle"></h3>
                    <p id="premiumModalDesc"></p>
                </div>
            </div>
        </div>
    </div>
    @elseif($theme == 'classic')
    <!-- Classic Clean Theme Layout -->
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-white border-bottom py-3 custom-classic-nav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#hero">
                @if($portfolio->profile_image)
                    <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="logo-avatar-classic">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100&background=1e293b&color=fff" alt="{{ $user->name }}" class="logo-avatar-classic">
                @endif
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#classicNavbar" aria-controls="classicNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="classicNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="/"><i class="fas fa-arrow-left me-1"></i> Main Site</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#hero">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#about">About</a></li>
                    @if($portfolio->show_skills)<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#skills">Skills</a></li>@endif
                    @if($portfolio->show_services)<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#services">Services</a></li>@endif
                    @if($portfolio->show_experience)<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#experience">Experience</a></li>@endif
                    @if($portfolio->show_education)<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#education">Education</a></li>@endif
                    @if($portfolio->show_projects)<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#projects">Projects</a></li>@endif
                    @if($portfolio->show_testimonials)<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#testimonials">Testimonials</a></li>@endif
                    @if($portfolio->show_media && $portfolio->media->isNotEmpty())<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#media">Media</a></li>@endif
                    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())<li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#publications">Publications</a></li>@endif
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="hero" class="py-5 bg-white border-bottom">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-center text-lg-start order-2 order-lg-1">
                    <h1 class="display-3 fw-bold mb-3 serif-heading text-dark">{{ $profile['name'] }}</h1>
                    <div class="mb-4">
                        <span class="badge text-uppercase tracking-wider px-3 py-2 bg-secondary-subtle text-secondary fw-bold fs-7">{{ $profile['short_title'] }}</span>
                    </div>
                    <div class="lead text-secondary mb-5 fs-5 lh-base">{!! $profile['intro'] !!}</div>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                        <a href="#projects" class="btn btn-dark btn-lg px-4 py-3 rounded-pill shadow-sm">View Work</a>
                        <a href="#contact" class="btn btn-outline-dark btn-lg px-4 py-3 rounded-pill">Contact Me</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center order-1 order-lg-2">
                    <div class="position-relative d-inline-block">
                        <div class="absolute-border-decor"></div>
                        @if($portfolio->profile_image)
                            <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $profile['name'] }}" class="img-fluid rounded-4 shadow-lg border object-fit-cover" style="max-width: 100%; width: 380px; height: auto; aspect-ratio: 380/420; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=400&background=1e293b&color=fff" alt="{{ $profile['name'] }}" class="img-fluid rounded-4 shadow-lg border" style="max-width: 100%; width: 380px; height: auto; aspect-ratio: 380/420; object-fit: cover;">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    

    <!-- Profile Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container py-5">
            <div class="max-w-column mx-auto">
                <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">01 / Profile</h6>
                <h2 class="serif-heading mb-4 text-dark fs-1">About Me</h2>
                <div class="text-secondary fs-5 lh-lg lead-text-classic">
                    {!! $profile['detailed_profile'] !!}
                </div>
            </div>
        </div>
    </section>

    @if($portfolio->show_skills)
    <section id="skills" class="py-5 bg-white border-top border-bottom">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">02 / Expertise</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Technical Skills</h2>
            <div class="row g-4" data-limit="9">
                @forelse($profile['technical_skills'] as $category => $skills)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border rounded-3 bg-light shadow-none hover-shadow-classic transition">
                        <h4 class="fw-bold mb-4 d-flex align-items-center text-dark fs-5">
                            <span class="icon-circle-bg me-3"><i class="fas fa-{{ $skills['icon'] }}"></i></span>
                            {{ $category }}
                        </h4>
                        <ul class="list-unstyled mb-0">
                            @foreach($skills['items'] as $skill)
                            <li class="mb-3">
                                <div class="d-flex justify-content-between mb-1 small fw-semibold text-secondary">
                                    <span>{{ $skill }}</span>
                                    <span>90%</span>
                                </div>
                                <div class="progress progress-classic" style="height: 6px;">
                                    <div class="progress-bar bg-dark" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @empty
                    <p class="text-muted col-12">No skills registered yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_services)
    <!-- Services Offered -->
    <section id="services" class="py-5 bg-light">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">03 / Offerings</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Services Offered</h2>
            <div class="row g-4" data-limit="9">
                @forelse($portfolio->services as $service)
                <div class="col-md-6">
                    <div class="card h-100 p-4 border-0 rounded-3 bg-white shadow-sm hover-shadow-classic transition">
                        <div class="mb-3 text-secondary"><i class="fas fa-cubes fs-3 text-dark"></i></div>
                        <h4 class="fw-bold text-dark fs-5 mb-3">{{ $service->title }}</h4>
                        <div class="text-secondary small mb-0 lh-base">{!! $service->description !!}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted col-12 text-center py-4">No services listed.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_experience)
    <!-- Work Experience Timeline -->
    <section id="experience" class="py-5 bg-white border-top border-bottom">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px; z-index: 10;">
                        <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">04 / History</h6>
                        <h2 class="serif-heading mb-4 text-dark fs-1">Work Experience</h2>
                        <p class="text-secondary lead fs-6 lh-base">
                            A chronicle of my professional career, highlighting achievements, key responsibilities, and technologies deployed in industry settings.
                        </p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="classic-timeline position-relative" data-limit="9">
                        @forelse($profile['experience'] as $exp)
                        <div class="classic-timeline-item mb-5 pb-2 position-relative">
                            <div class="timeline-dot-classic"></div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                <h4 class="fw-bold text-dark mb-0 fs-5">{{ $exp['title'] }}</h4>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 font-monospace small mt-2 mt-sm-0">{{ $exp['date'] }}</span>
                            </div>
                            <h5 class="text-primary-accent fw-semibold mb-3 fs-6">{{ $exp['company'] }}</h5>
                            <div class="text-secondary mb-0 lh-base">{!! $exp['highlights'] !!}</div>
                        </div>
                        @empty
                            <p class="text-muted text-center py-4">No work experience loaded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_education || $portfolio->show_achievements)
    <!-- Education, Soft Skills & Achievements -->
    <section id="education" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Education Column -->
                @if($portfolio->show_education)
                <div class="col-lg-6">
                    <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">05 / Education</h6>
                    <h2 class="serif-heading mb-5 text-dark fs-2">Education Credentials</h2>
                    <div class="d-flex flex-column gap-4" data-limit="9">
                        @forelse($profile['education'] as $edu)
                        <div class="card p-4 border rounded-3 bg-white shadow-sm hover-shadow-classic transition">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h4 class="fw-bold text-dark fs-6 mb-0">{{ $edu['degree'] }}</h4>
                                <span class="badge bg-light text-secondary border px-2 py-1 small font-monospace">{{ $edu['date'] }}</span>
                            </div>
                            <div class="text-secondary small fw-semibold">{{ $edu['institution'] }}</div>
                        </div>
                        @empty
                            <p class="text-muted">No education records registered.</p>
                        @endforelse
                    </div>
                </div>
                @endif
                
                <!-- Achievements Column -->
                @if($portfolio->show_achievements)
                <div class="col-lg-6">
                    <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">06 / Soft Skills</h6>
                    <h2 class="serif-heading mb-5 text-dark fs-2">Achievements</h2>
                    <div class="d-flex flex-wrap gap-2" data-limit="9">
                        @forelse($profile['soft_skills'] as $skill)
                        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill fs-7 shadow-sm hover-shadow-classic transition"><i class="fas fa-star text-warning me-2"></i>{{ $skill }}</span>
                        @empty
                            <p class="text-muted">No achievements listed.</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Certifications & Trainings -->
    @if($portfolio->show_certifications || $portfolio->show_trainings)
    <section id="trainings" class="py-5 bg-white border-top border-bottom">
        <div class="container py-5">
            <div class="row g-5">
                @if($portfolio->show_certifications)
                <div class="{{ $portfolio->show_trainings ? 'col-lg-6' : 'col-lg-12' }}">
                    <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">07 / Verification</h6>
                    <h2 class="serif-heading mb-5 text-dark fs-2">Certifications</h2>
                    <ul class="list-group list-group-flush" data-limit="9">
                        @forelse($portfolio->certifications as $cert)
                        <li class="list-group-item bg-transparent py-3 border-bottom d-flex align-items-center gap-3">
                            <span class="icon-circle-bg-sm"><i class="fas fa-certificate text-dark"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark fs-6 mb-1">{{ $cert->name }}</h5>
                                <small class="text-secondary fw-semibold">{{ $cert->issuer }}</small>
                            </div>
                        </li>
                        @empty
                            <p class="text-muted">No certifications loaded.</p>
                        @endforelse
                    </ul>
                </div>
                @endif
                @if($portfolio->show_trainings)
                <div class="{{ $portfolio->show_certifications ? 'col-lg-6' : 'col-lg-12' }}">
                    <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">08 / Training</h6>
                    <h2 class="serif-heading mb-5 text-dark fs-2">Registrations & Trainings</h2>
                    <ul class="list-group list-group-flush" data-limit="9">
                        @forelse($portfolio->trainings as $training)
                        <li class="list-group-item bg-transparent py-3 border-bottom d-flex align-items-center gap-3">
                            <span class="icon-circle-bg-sm"><i class="fas fa-chalkboard-teacher text-dark"></i></span>
                            <div>
                                <h5 class="fw-bold text-dark fs-6 mb-1">{{ $training->title }}</h5>
                                <small class="text-secondary fw-semibold">{{ $training->institution }}</small>
                            </div>
                        </li>
                        @empty
                            <p class="text-muted">No training events logged.</p>
                        @endforelse
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_projects)
    <!-- Flagship Projects -->
    <section id="projects" class="py-5 bg-light">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">09 / Portfolio</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Flagship Projects</h2>
            <div class="row g-4" data-limit="9">
                @forelse($profile['projects'] as $project)
                @php
                    $plainDesc = strip_tags($project['description']);
                    $isLongDesc = strlen($plainDesc) > 120;
                    $isLongTitle = strlen($project['name']) > 30;
                    
                    $displayTitle = $isLongTitle ? (substr($project['name'], 0, 30) . '...') : $project['name'];
                    $displayDesc = $isLongDesc ? (substr($plainDesc, 0, 120) . '...') : $plainDesc;
                @endphp
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 border rounded-3 bg-white shadow-sm overflow-hidden hover-shadow-classic transition">
                        <div class="project-img-wrapper position-relative">
                            @if($project['image'])
                                <img src="{{ Storage::url($project['image']) }}" alt="{{ $project['name'] }}" class="img-fluid object-fit-cover w-100" style="height: 220px;">
                            @else
                                <div class="bg-secondary-subtle d-flex align-items-center justify-content-center text-muted" style="height: 220px;">No Image</div>
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex gap-1 mb-2">
                                @foreach($project['tags'] as $tag)
                                <span class="badge bg-light text-secondary border small">{{ $tag }}</span>
                                @endforeach
                            </div>
                            <h4 class="fw-bold text-dark fs-5 mb-2" title="{{ $project['name'] }}">{{ $displayTitle }}</h4>
                            <p class="text-secondary small mb-3 lh-base flex-grow-1">{{ $displayDesc }}</p>
                            <div class="mt-auto">
                                <button class="btn btn-link text-decoration-none p-0 fw-bold text-primary-accent hover-underline-classic" 
                                        onclick="openProjectDetailsModal(this)"
                                        data-title="{{ $project['name'] }}"
                                        data-desc="{{ $project['description'] }}"
                                        data-image="{{ $project['image'] ? Storage::url($project['image']) : '' }}"
                                        data-tags="{{ implode(',', $project['tags']) }}">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-muted col-12 text-center py-4">No flagship projects configured.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_contributions)
    <section id="contributions" class="py-5 bg-white border-top border-bottom">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">10 / Contributions</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Platform Contributions</h2>
            <div class="row g-4" data-limit="9">
                @forelse($portfolio->contributions as $contrib)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border rounded-3 bg-light shadow-none hover-shadow-classic transition">
                        <h4 class="fw-bold text-dark fs-6 mb-3"><i class="fas fa-hands-helping text-dark me-2"></i>{{ $contrib->title }}</h4>
                        <div class="text-secondary small mb-0 lh-base">{!! $contrib->description !!}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted col-12 text-center py-4">No contributions registered.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_testimonials)
    <section id="testimonials" class="py-5 bg-light">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">11 / Testimonials</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Client Testimonials</h2>
            <div class="row g-4" data-limit="9">
                @forelse($portfolio->testimonials as $testi)
                <div class="col-md-6">
                    <div class="card h-100 p-4 border-0 rounded-3 bg-white shadow-sm hover-shadow-classic transition">
                        <span class="serif-quote text-secondary-subtle display-4 lh-1 mb-2 d-block">“</span>
                        <div class="text-secondary small italic mb-3 lh-lg">{!! $testi->content !!}</div>
                        <h5 class="fw-bold text-dark fs-6 mb-0">— {{ $testi->client_name }}</h5>
                    </div>
                </div>
                @empty
                    <p class="text-muted col-12 text-center py-4">No client reviews registered.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    <!-- Media Appearances (Classic) -->
    @if($portfolio->show_media && $portfolio->media->isNotEmpty())
    <section id="media" class="py-5 bg-white border-top">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">12 / Media</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Media Appearances</h2>
            <div class="row g-4" data-limit="9">
                <!-- TV appearances -->
                <div class="col-md-6">
                    <div class="card h-100 p-4 border rounded-3 bg-light shadow-none hover-shadow-classic transition">
                        <h4 class="fw-bold text-dark fs-5 mb-4"><i class="fas fa-tv me-2 text-dark"></i>TV & Talk Shows</h4>
                        <div class="list-group list-group-flush bg-transparent">
                            @forelse($portfolio->media->where('type', 'tv') as $tv)
                                <div class="list-group-item bg-transparent px-0 border-0 mb-3">
                                    <h5 class="fw-bold fs-6 mb-1 text-dark">{{ $tv->title }}</h5>
                                    <div class="text-muted small mb-2">
                                        <i class="fas fa-broadcast-tower me-1"></i>{{ $tv->channel_platform }} &bull; {{ \Carbon\Carbon::parse($tv->date)->format('M d, Y') }}
                                    </div>
                                    <a href="{{ $tv->link }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill"><i class="fas fa-play-circle me-1"></i>Watch</a>
                                </div>
                            @empty
                                <p class="text-muted small">No TV appearances listed yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Newspaper opeds -->
                <div class="col-md-6">
                    <div class="card h-100 p-4 border rounded-3 bg-light shadow-none hover-shadow-classic transition">
                        <h4 class="fw-bold text-dark fs-5 mb-4"><i class="fas fa-newspaper me-2 text-dark"></i>Newspaper Op-eds</h4>
                        <div class="list-group list-group-flush bg-transparent">
                            @forelse($portfolio->media->where('type', 'oped') as $oped)
                                <div class="list-group-item bg-transparent px-0 border-0 mb-3">
                                    <h5 class="fw-bold fs-6 mb-1 text-dark">{{ $oped->title }}</h5>
                                    <div class="text-muted small mb-2">
                                        <i class="fas fa-pen-nib me-1"></i>{{ $oped->newspaper_name }} &bull; {{ \Carbon\Carbon::parse($oped->date)->format('M d, Y') }}
                                    </div>
                                    <a href="{{ $oped->link }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill"><i class="fas fa-book-open me-1"></i>Read Article</a>
                                </div>
                            @empty
                                <p class="text-muted small">No newspaper op-eds listed yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Publications (Classic) -->
    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())
    <section id="publications" class="py-5 bg-light border-top border-bottom">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">13 / Publications</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Publications & Reports</h2>
            <div class="row g-4" data-limit="9">
                @foreach($portfolio->publications as $pub)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-3 bg-white shadow-sm hover-shadow-classic transition">
                        <span class="badge bg-secondary align-self-start mb-3">{{ $pub->type }}</span>
                        <h4 class="fw-bold text-dark fs-5 mb-2">{{ $pub->title }}</h4>
                        <p class="text-secondary small mb-3">
                            <strong>Authors:</strong> {{ $pub->authors }}<br>
                            <strong>Publisher:</strong> {{ $pub->publisher }} ({{ $pub->year }})
                        </p>
                        <div class="mt-auto d-flex gap-2">
                            @if($pub->link)
                                <a href="{{ $pub->link }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fas fa-external-link-alt me-1"></i>Online Link</a>
                            @endif
                            @if($pub->report_path)
                                <a href="{{ Storage::url($pub->report_path) }}" target="_blank" class="btn btn-sm btn-success rounded-pill text-white"><i class="fas fa-download me-1"></i>Report</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Form -->
    <section id="contact" class="py-5 bg-white border-top">
        <div class="container py-5">
            <h6 class="text-uppercase tracking-widest text-primary-accent fw-bold mb-3">12 / Contact</h6>
            <h2 class="serif-heading mb-5 text-dark fs-1">Get In Touch</h2>
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-4">
                        @if($portfolio->show_email && $profile['email'])
                        <div class="d-flex align-items-center gap-3">
                            <span class="icon-circle-bg"><i class="fas fa-envelope text-dark"></i></span>
                            <div>
                                <small class="text-secondary text-uppercase d-block font-monospace">Email</small>
                                <a href="mailto:{{ $profile['email'] }}" class="text-dark fw-bold text-decoration-none">{{ $profile['email'] }}</a>
                            </div>
                        </div>
                        @endif

                        @if($portfolio->show_phone && $profile['phone'])
                        <div class="d-flex align-items-center gap-3">
                            <span class="icon-circle-bg"><i class="fas fa-phone text-dark"></i></span>
                            <div>
                                <small class="text-secondary text-uppercase d-block font-monospace">Phone</small>
                                <a href="tel:{{ $profile['phone'] }}" class="text-dark fw-bold text-decoration-none">{{ $profile['phone'] }}</a>
                            </div>
                        </div>
                        @endif

                        @if($portfolio->show_linkedin && $profile['linkedin'])
                        <div class="d-flex align-items-center gap-3">
                            <span class="icon-circle-bg"><i class="fab fa-linkedin text-dark"></i></span>
                            <div>
                                <small class="text-secondary text-uppercase d-block font-monospace">LinkedIn</small>
                                <a href="{{ $profile['linkedin'] }}" target="_blank" class="text-dark fw-bold text-decoration-none">LinkedIn Profile</a>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex align-items-center gap-3">
                            <span class="icon-circle-bg"><i class="fas fa-location-dot text-dark"></i></span>
                            <div>
                                <small class="text-secondary text-uppercase d-block font-monospace">Location</small>
                                <span class="text-dark fw-bold">{{ $profile['location'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card p-4 p-md-5 border rounded-3 bg-light shadow-none">
                        <h4 class="fw-bold text-dark mb-4 fs-5">Send Message</h4>
                        @if(session('status') == 'message-sent')
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                Message sent successfully! I will respond shortly.
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm mb-4">
                                <ul class="mb-0 small" style="list-style: none; padding-left: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('portfolio.contact.store', $portfolio->id) }}" method="POST" class="d-flex flex-column gap-3">
                            @csrf
                            <div>
                                <input type="text" name="name" placeholder="Name" required class="form-control form-control-lg border-2 bg-white small">
                            </div>
                            <div>
                                <input type="email" name="email" placeholder="Email" required class="form-control form-control-lg border-2 bg-white small">
                            </div>
                            <div>
                                <textarea name="message" placeholder="Message" rows="5" required class="form-control form-control-lg border-2 bg-white small"></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark btn-lg py-3 rounded-pill fw-bold shadow-sm">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-white border-top">
        <div class="container">
            <div class="d-flex flex-column align-items-center text-center" style="max-width: 850px; margin: 0 auto;">
                <!-- Profile Avatar Image -->
                <div class="mb-3 position-relative">
                    @if($portfolio->profile_image)
                        <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="rounded-circle border border-2 border-primary p-1 shadow-sm" style="width: 84px; height: 84px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=160&background=0D8ABC&color=fff" alt="{{ $user->name }}" class="rounded-circle border border-2 border-primary p-1 shadow-sm" style="width: 84px; height: 84px; object-fit: cover;">
                    @endif
                </div>

                <!-- Profile User Name -->
                <h3 class="fw-bold text-dark mb-1 fs-4">{{ $user->name }}</h3>

                <!-- Tagline / Title -->
                <p class="text-secondary fw-medium small mb-4">{{ $portfolio->title ?? $profile['short_title'] }}</p>

                <!-- Complete Header-Style Portfolio Navigation Links -->
                <div class="d-flex flex-wrap justify-content-center gap-2 gap-md-3 mb-4">
                    <a href="#hero" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Home</a>
                    <a href="#about" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">About</a>
                    @if($portfolio->show_skills)<a href="#skills" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Skills</a>@endif
                    @if($portfolio->show_experience)<a href="#experience" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Experience</a>@endif
                    @if($portfolio->show_projects)<a href="#projects" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Projects</a>@endif
                    @if($portfolio->show_education)<a href="#skills-extra" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Education</a>@endif
                    @if($portfolio->show_achievements)<a href="#skills-extra" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Achievements</a>@endif
                    @if($portfolio->show_contributions)<a href="#contributions" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Contributions</a>@endif
                    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())<a href="#publications" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Publications</a>@endif
                    @if($portfolio->show_services)<a href="#services" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Services</a>@endif
                    @if($portfolio->show_certifications)<a href="#trainings" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Certifications</a>@endif
                    @if($portfolio->show_trainings)<a href="#trainings" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Trainings</a>@endif
                    @if($portfolio->show_testimonials)<a href="#testimonials" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Testimonials</a>@endif
                    @if($portfolio->show_media && $portfolio->media->isNotEmpty())<a href="#media" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Media</a>@endif
                    <a href="#contact" class="text-secondary text-decoration-none hover-dark fw-medium small px-2">Contact</a>
                </div>

                <hr class="w-100 text-muted my-3 opacity-25">

                <!-- Professional Copyright & Branding -->
                <p class="text-secondary mb-0 small">&copy; {{ now()->year }} <strong class="text-dark">{{ $user->name }}</strong>. All rights reserved <span class="mx-1 text-muted">&bull;</span> Powered by <a href="https://itechgb.com/" target="_blank" class="text-dark text-decoration-underline fw-semibold">Innovative Technologies GB</a></p>
            </div>
        </div>
    </footer>

    <!-- Reusable Bootstrap Modal for Project Details -->
    <div class="modal fade" id="classicProjectModal" tabindex="-1" aria-labelledby="classicProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg position-relative">
                <!-- Close Button positioned absolutely on the top right with a background for readability -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3 bg-white p-2 rounded-circle shadow-sm" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8; width: 1.25rem; height: 1.25rem; box-sizing: content-box;"></button>
                
                <div class="row g-0 align-items-stretch" style="min-height: 400px;">
                    <!-- Left Column: Image (Desktop) -->
                    <div class="col-md-5 d-none d-md-block bg-light border-end" id="projectModalImgCol">
                        <div class="h-100 d-flex align-items-center justify-content-center p-3">
                            <img id="projectModalImg" src="" alt="Project Image" class="img-fluid rounded-3 shadow-sm" style="max-height: 380px; object-fit: contain;">
                        </div>
                    </div>
                    <!-- Right Column: Details (Tags, Title, Description) -->
                    <div class="col-md-7 d-flex flex-column justify-content-center" id="projectModalContentCol">
                        <div class="modal-body p-4 p-md-5">
                            <!-- Mobile Image (visible only on mobile if image exists) -->
                            <div class="mb-4 d-md-none text-center bg-light p-3 rounded-3 border" id="projectModalMobileImgWrapper">
                                <img id="projectModalMobileImg" src="" alt="Project Image" class="img-fluid rounded-3 shadow-sm" style="max-height: 240px; object-fit: contain;">
                            </div>
                            
                            <div class="d-flex flex-wrap gap-1 mb-3" id="projectModalTags">
                                <!-- Tags dynamically loaded here -->
                            </div>
                            <h3 class="fw-bold text-dark mb-3 serif-heading fs-2" id="projectModalTitle">Project Title</h3>
                            <p class="text-secondary lh-lg fs-6 mb-0" id="projectModalDesc" style="white-space: pre-line;">Project Description</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($theme == 'elegant')
    @php
        $elegantDivider = '
        <div class="elegant-section-divider">
            <div class="elegant-divider-line"></div>
            <div class="elegant-divider-diamond"><i class="fas fa-circle" style="font-size: 0.35rem; color: var(--elegant-indigo); margin-right: 6px;"></i><i class="fas fa-gem"></i><i class="fas fa-circle" style="font-size: 0.35rem; color: var(--elegant-indigo); margin-left: 6px;"></i></div>
            <div class="elegant-divider-line"></div>
        </div>';
    @endphp
    <!-- Elegant Indigo Theme Layout -->
    
    <!-- Navbar -->
    <nav class="elegant-nav">
        <div class="elegant-nav-container">
            <a class="elegant-logo" href="#hero">
                @if($portfolio->profile_image)
                    <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" class="logo-avatar-elegant">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100&background=4f46e5&color=fff" alt="{{ $user->name }}" class="logo-avatar-elegant">
                @endif
            </a>
            <div class="elegant-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <ul>
                <li><a href="/"><i class="fas fa-arrow-left me-1"></i> Main Site</a></li>
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">About</a></li>
                @if($portfolio->show_skills)<li><a href="#skills">Skills</a></li>@endif
                @if($portfolio->show_experience)<li><a href="#experience">Experience</a></li>@endif
                @if($portfolio->show_projects)<li><a href="#projects">Projects</a></li>@endif
                
                @if($portfolio->show_education || $portfolio->show_achievements || $portfolio->show_contributions || ($portfolio->show_publications && $portfolio->publications->isNotEmpty()))
                <li class="elegant-dropdown">
                    <a href="javascript:void(0)" class="elegant-dropbtn">Academic <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                    <div class="elegant-dropdown-content">
                        @if($portfolio->show_education)<a href="#education">Education</a>@endif
                        @if($portfolio->show_achievements)<a href="#skills-extra">Achievements</a>@endif
                        @if($portfolio->show_contributions)<a href="#contributions">Contributions</a>@endif
                        @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())<a href="#publications">Publications</a>@endif
                    </div>
                </li>
                @endif
                
                @if($portfolio->show_services || $portfolio->show_certifications || $portfolio->show_trainings || $portfolio->show_testimonials || ($portfolio->show_media && $portfolio->media->isNotEmpty()))
                <li class="elegant-dropdown">
                    <a href="javascript:void(0)" class="elegant-dropbtn">Professional <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                    <div class="elegant-dropdown-content">
                        @if($portfolio->show_services)<a href="#services">Services</a>@endif
                        @if($portfolio->show_certifications)<a href="#trainings">Certifications</a>@endif
                        @if($portfolio->show_trainings)<a href="#trainings">Trainings</a>@endif
                        @if($portfolio->show_testimonials)<a href="#testimonials">Testimonials</a>@endif
                        @if($portfolio->show_media && $portfolio->media->isNotEmpty())<a href="#media">Media</a>@endif
                    </div>
                </li>
                @endif
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="elegant-hero">
        <div class="elegant-container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-start order-2 order-lg-1">
                    <div class="elegant-hero-subtitle">Executive Portfolio</div>
                    <h1 class="elegant-hero-title">{{ $user->name }}</h1>
                    <p class="elegant-hero-position">{{ $profile['short_title'] }}</p>
                    <div class="elegant-hero-desc">{!! $profile['intro'] !!}</div>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="#projects" class="elegant-btn-solid">Explore Portfolio</a>
                        <a href="#contact" class="elegant-btn-outline">Get In Touch</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center order-1 order-lg-2">
                    <div class="elegant-hero-image-wrapper">
                        <div class="elegant-hero-image-offset"></div>
                        <div class="elegant-hero-image">
                            @if($portfolio->profile_image)
                                <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}">
                            @else
                                <div class="elegant-image-placeholder">
                                    <i class="fas fa-user-tie fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {!! $elegantDivider !!}

    <!-- Profile Section -->
    <section id="about" class="elegant-section">
        <div class="elegant-container">
            
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">01 / Profile</h6>
                <h2 class="elegant-section-title">Biography & Information</h2>
            </div>
            
            <div class="elegant-profile-highlight text-center mb-5">
                {!! $portfolio->description !!}
            </div>
            
            <div class="row g-5 mt-2">
                <div class="col-lg-4">
                    <div class="elegant-meta-card">
                        <h4 class="elegant-meta-title">Identity & Contacts</h4>
                        <ul class="elegant-meta-list">
                            <li>
                                <span class="meta-label">Location</span>
                                <span class="meta-val">{{ $profile['location'] }}</span>
                            </li>
                            @if($portfolio->show_email && $profile['email'])
                            <li>
                                <span class="meta-label">Email</span>
                                <span class="meta-val"><a href="mailto:{{ $profile['email'] }}">{{ $profile['email'] }}</a></span>
                            </li>
                            @endif
                            @if($portfolio->show_phone && $profile['phone'])
                            <li>
                                <span class="meta-label">Phone</span>
                                <span class="meta-val">{{ $profile['phone'] }}</span>
                            </li>
                            @endif
                            @if($portfolio->show_linkedin && $profile['linkedin'])
                            <li>
                                <span class="meta-label">LinkedIn</span>
                                <span class="meta-val"><a href="{{ $profile['linkedin'] }}" target="_blank">View Profile</a></span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="elegant-bio-text">
                        <h4 class="elegant-meta-title">Executive Biography</h4>
                        <p>{!! $profile['detailed_profile'] !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($portfolio->show_skills)
    {!! $elegantDivider !!}
    <!-- Skills Section -->
    <section id="skills" class="elegant-section elegant-bg-alt">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">02 / Expertise</h6>
                <h2 class="elegant-section-title">Technical Capabilities</h2>
            </div>
            
            <div class="elegant-skills-grid" data-limit="9">
                @forelse($profile['technical_skills'] as $category => $skills)
                <div class="elegant-skill-card">
                    <div class="elegant-skill-card-top-bar"></div>
                    <h3 class="elegant-skill-title"><i class="fas fa-{{ $skills['icon'] }} me-2 text-indigo"></i> {{ $category }}</h3>
                    <ul class="elegant-skill-list">
                        @foreach($skills['items'] as $skill)
                        <li><span class="elegant-bullet"></span> {{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No skill categories defined yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_services)
    {!! $elegantDivider !!}
    <!-- Services Offered -->
    <section id="services" class="elegant-section">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">03 / Solutions</h6>
                <h2 class="elegant-section-title">Services Offered</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                @forelse($portfolio->services as $service)
                <div class="col-md-6 col-lg-4">
                    <div class="elegant-service-card">
                        <div class="elegant-service-icon"><i class="fas fa-gem"></i></div>
                        <h4 class="elegant-service-title">{{ $service->title }}</h4>
                        <div class="elegant-card-divider"></div>
                        <div class="elegant-service-desc">{!! $service->description !!}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No professional services listed yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_experience)
    {!! $elegantDivider !!}
    <!-- Work Experience Timeline -->
    <section id="experience" class="elegant-section elegant-bg-alt">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">04 / History</h6>
                <h2 class="elegant-section-title">Professional Timeline</h2>
            </div>
            
            <div class="elegant-timeline" data-limit="9">
                @forelse($profile['experience'] as $exp)
                <div class="elegant-timeline-item">
                    <div class="elegant-timeline-badge"><i class="fas fa-briefcase"></i></div>
                    <div class="elegant-timeline-content">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h4 class="elegant-timeline-job">{{ $exp['title'] }}</h4>
                            <span class="elegant-timeline-date">{{ $exp['date'] }}</span>
                        </div>
                        <div class="elegant-timeline-company"><i class="fas fa-building me-1"></i> {{ $exp['company'] }}</div>
                        <div class="elegant-timeline-highlights">{!! $exp['highlights'] !!}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No history records loaded yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_education)
    {!! $elegantDivider !!}
    <!-- Academic & Credentials -->
    <section id="education" class="elegant-section">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">05 / Education</h6>
                <h2 class="elegant-section-title">Academic Credentials</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                @forelse($profile['education'] as $edu)
                <div class="col-md-6">
                    <div class="elegant-edu-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="elegant-edu-date"><i class="far fa-calendar-alt me-1"></i> {{ $edu['date'] }}</div>
                            <span class="elegant-edu-result-badge">{{ $edu['result'] }}</span>
                        </div>
                        <h4 class="elegant-edu-degree">{{ $edu['degree'] }}</h4>
                        <div class="elegant-edu-institution">{{ $edu['institution'] }}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No education details recorded.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_achievements)
    {!! $elegantDivider !!}
    <!-- Achievements & Soft Skills -->
    <section id="skills-extra" class="elegant-section elegant-bg-alt">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">06 / Traits</h6>
                <h2 class="elegant-section-title">Achievements & Soft Skills</h2>
            </div>
            
            <div class="elegant-tags-container" data-limit="9">
                @forelse($profile['soft_skills'] as $skill)
                <span class="elegant-tag-badge"><i class="fas fa-award text-gold me-2"></i> {{ $skill }}</span>
                @empty
                    <p class="text-muted text-center col-12">No extra skills or achievements registered.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_certifications || $portfolio->show_trainings)
    {!! $elegantDivider !!}
    <!-- Certifications & Trainings -->
    <section id="trainings" class="elegant-section">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">07 / Validation</h6>
                <h2 class="elegant-section-title">Certifications & Trainings</h2>
            </div>
            
            <div class="row g-5">
                <!-- Certifications Column -->
                @if($portfolio->show_certifications)
                <div class="{{ $portfolio->show_trainings ? 'col-lg-6' : 'col-lg-12' }}">
                    <h4 class="elegant-column-title"><i class="fas fa-certificate text-indigo me-2"></i> Certifications</h4>
                    <div class="elegant-card-divider mb-4"></div>
                    <ul class="elegant-list-group" data-limit="9">
                        @forelse($profile['certifications'] as $cert)
                        <li class="elegant-list-item">
                            <span class="elegant-list-icon"><i class="fas fa-check"></i></span>
                            <span class="elegant-list-text">{{ $cert }}</span>
                        </li>
                        @empty
                            <li class="elegant-list-item text-muted">No certifications added.</li>
                        @endforelse
                    </ul>
                </div>
                @endif
                
                <!-- Trainings Column -->
                @if($portfolio->show_trainings)
                <div class="{{ $portfolio->show_certifications ? 'col-lg-6' : 'col-lg-12' }}">
                    <h4 class="elegant-column-title"><i class="fas fa-chalkboard-teacher text-indigo me-2"></i> Professional Trainings</h4>
                    <div class="elegant-card-divider mb-4"></div>
                    <ul class="elegant-list-group" data-limit="9">
                        @forelse($profile['trainings'] as $training)
                        <li class="elegant-list-item">
                            <span class="elegant-list-icon"><i class="fas fa-check"></i></span>
                            <span class="elegant-list-text">{{ $training }}</span>
                        </li>
                        @empty
                            <li class="elegant-list-item text-muted">No trainings registered.</li>
                        @endforelse
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_projects)
    {!! $elegantDivider !!}
    <!-- Flagship Projects -->
    <section id="projects" class="elegant-section elegant-bg-alt">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">08 / Portfolio</h6>
                <h2 class="elegant-section-title">Flagship Projects</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                @forelse($profile['projects'] as $project)
                @php
                    $plainDesc = strip_tags($project['description']);
                    $isLongDesc = strlen($plainDesc) > 120;
                    $isLongTitle = strlen($project['name']) > 30;
                    
                    $displayTitle = $isLongTitle ? (substr($project['name'], 0, 30) . '...') : $project['name'];
                    $displayDesc = $isLongDesc ? (substr($plainDesc, 0, 120) . '...') : $plainDesc;
                @endphp
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="elegant-project-card">
                        <div class="elegant-project-img-wrapper">
                            @if($project['image'])
                                <img src="{{ Storage::url($project['image']) }}" alt="{{ $project['name'] }}">
                            @else
                                <div class="elegant-project-placeholder">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="elegant-project-body">
                            <div class="elegant-project-tags">
                                @foreach($project['tags'] as $tag)
                                <span class="elegant-proj-tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                            <h4 class="elegant-project-title" title="{{ $project['name'] }}">{{ $displayTitle }}</h4>
                            <p class="elegant-project-desc">{{ $displayDesc }}</p>
                            <div class="mt-auto pt-3">
                                <button class="elegant-project-btn" 
                                        onclick="openElegantProjectModal(this)"
                                        data-title="{{ $project['name'] }}"
                                        data-desc="{{ $project['description'] }}"
                                        data-image="{{ $project['image'] ? Storage::url($project['image']) : '' }}"
                                        data-tags="{{ implode(',', $project['tags']) }}">
                                    View Details <i class="fas fa-chevron-right ms-1" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No flagship projects showcased.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_contributions)
    {!! $elegantDivider !!}
    <!-- Contributions -->
    <section id="contributions" class="elegant-section">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">09 / Platform</h6>
                <h2 class="elegant-section-title">Contributions</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                @forelse($portfolio->contributions as $contrib)
                <div class="col-md-6 col-lg-4">
                    <div class="elegant-contrib-card">
                        <h4 class="elegant-contrib-title"><i class="fas fa-code-fork me-2 text-indigo"></i> {{ $contrib->title }}</h4>
                        <div class="elegant-card-divider"></div>
                        <div class="elegant-contrib-desc">{!! $contrib->description !!}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No contributions listed.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if($portfolio->show_testimonials)
    {!! $elegantDivider !!}
    <!-- Testimonials -->
    <section id="testimonials" class="elegant-section elegant-bg-alt">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">10 / Endorsements</h6>
                <h2 class="elegant-section-title">Client Testimonials</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                @forelse($portfolio->testimonials as $testi)
                <div class="col-md-6">
                    <div class="elegant-testimonial-card">
                        <span class="elegant-quote-mark">“</span>
                        <div class="elegant-testimonial-text">{!! $testi->content !!}</div>
                        <div class="elegant-testimonial-client">— {{ $testi->client_name }}</div>
                    </div>
                </div>
                @empty
                    <p class="text-muted text-center col-12 py-4">No testimonials uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    <!-- Media Appearances (Elegant) -->
    @if($portfolio->show_media && $portfolio->media->isNotEmpty())
    {!! $elegantDivider !!}

    <section id="media" class="elegant-section">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">11 / Media</h6>
                <h2 class="elegant-section-title">Media Appearances</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                <!-- TV appearances -->
                <div class="col-md-6">
                    <div class="elegant-contrib-card h-100">
                        <h4 class="elegant-contrib-title"><i class="fas fa-tv me-2 text-indigo"></i> TV & Talk Shows</h4>
                        <div class="elegant-card-divider"></div>
                        <div style="margin-top: 1.5rem;">
                            @forelse($portfolio->media->where('type', 'tv') as $tv)
                                <div style="margin-bottom: 1.5rem;">
                                    <h5 class="fw-bold fs-6 mb-1 text-dark">{{ $tv->title }}</h5>
                                    <div class="text-muted small mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-broadcast-tower me-1"></i>{{ $tv->channel_platform }} &bull; {{ \Carbon\Carbon::parse($tv->date)->format('M d, Y') }}
                                    </div>
                                    <a href="{{ $tv->link }}" target="_blank" style="color: var(--elegant-indigo); text-decoration: none; font-size: 0.9rem; font-weight: 600;"><i class="fas fa-play-circle me-1"></i> Watch Appearance</a>
                                </div>
                            @empty
                                <p class="text-muted small">No TV appearances listed.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Newspaper opeds -->
                <div class="col-md-6">
                    <div class="elegant-contrib-card h-100">
                        <h4 class="elegant-contrib-title"><i class="fas fa-newspaper me-2 text-indigo"></i> Newspaper Op-eds</h4>
                        <div class="elegant-card-divider"></div>
                        <div style="margin-top: 1.5rem;">
                            @forelse($portfolio->media->where('type', 'oped') as $oped)
                                <div style="margin-bottom: 1.5rem;">
                                    <h5 class="fw-bold fs-6 mb-1 text-dark">{{ $oped->title }}</h5>
                                    <div class="text-muted small mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-pen-nib me-1"></i>{{ $oped->newspaper_name }} &bull; {{ \Carbon\Carbon::parse($oped->date)->format('M d, Y') }}
                                    </div>
                                    <a href="{{ $oped->link }}" target="_blank" style="color: var(--elegant-indigo); text-decoration: none; font-size: 0.9rem; font-weight: 600;"><i class="fas fa-book-open me-1"></i> Read Article</a>
                                </div>
                            @empty
                                <p class="text-muted small">No newspaper op-eds listed.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Publications (Elegant) -->
    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())
    {!! $elegantDivider !!}

    <section id="publications" class="elegant-section elegant-bg-alt">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">12 / Publications</h6>
                <h2 class="elegant-section-title">Publications & Reports</h2>
            </div>
            
            <div class="row g-4" data-limit="9">
                @foreach($portfolio->publications as $pub)
                <div class="col-md-6 col-lg-4">
                    <div class="elegant-contrib-card d-flex flex-column h-100">
                        <div class="mb-2">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; background: var(--elegant-indigo-light); color: var(--elegant-indigo); padding: 4px 10px; border-radius: 20px;">{{ $pub->type }}</span>
                        </div>
                        <h4 class="elegant-contrib-title" style="margin-top: 0.5rem; line-height: 1.4;">{{ $pub->title }}</h4>
                        <div class="elegant-card-divider"></div>
                        <p class="elegant-contrib-desc" style="font-size: 0.9rem; color: var(--elegant-dark-muted);">
                            <strong>Authors:</strong> {{ $pub->authors }} <br>
                            <strong>Publisher:</strong> {{ $pub->publisher }} ({{ $pub->year }})
                        </p>
                        <div class="mt-auto pt-3 d-flex gap-2">
                            @if($pub->link)
                                <a href="{{ $pub->link }}" target="_blank" style="color: var(--elegant-indigo); text-decoration: none; font-size: 0.85rem; font-weight: 600;"><i class="fas fa-external-link-alt me-1"></i> Online Link</a>
                            @endif
                            @if($pub->report_path)
                                <a href="{{ Storage::url($pub->report_path) }}" target="_blank" style="color: #28a745; text-decoration: none; font-size: 0.85rem; font-weight: 600; margin-left: auto;"><i class="fas fa-download me-1"></i> Report</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {!! $elegantDivider !!}

    <!-- Contact Section -->
    <section id="contact" class="elegant-section">
        <div class="elegant-container">
            <div class="text-center mb-5">
                <h6 class="elegant-section-tag">11 / Connect</h6>
                <h2 class="elegant-section-title">Get In Touch</h2>
            </div>
            
            <div class="elegant-contact-card">
                <div class="row g-0">
                    <div class="col-lg-5 elegant-contact-sidebar">
                        <h3 class="elegant-contact-title">Let's create something together</h3>
                        <p class="elegant-contact-subtitle">Reach out for collaborations, consultation, or interviews.</p>
                        
                        <div class="elegant-contact-details mt-5">
                            @if($portfolio->show_email && $profile['email'])
                            <div class="d-flex align-items-center mb-4">
                                <span class="elegant-contact-icon"><i class="fas fa-envelope"></i></span>
                                <div>
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value">{{ $profile['email'] }}</div>
                                </div>
                            </div>
                            @endif
                            @if($portfolio->show_phone && $profile['phone'])
                            <div class="d-flex align-items-center mb-4">
                                <span class="elegant-contact-icon"><i class="fas fa-phone"></i></span>
                                <div>
                                    <div class="contact-label">Phone</div>
                                    <div class="contact-value">{{ $profile['phone'] }}</div>
                                </div>
                            </div>
                            @endif
                            @if($portfolio->show_linkedin && $profile['linkedin'])
                            <div class="d-flex align-items-center">
                                <span class="elegant-contact-icon"><i class="fab fa-linkedin-in"></i></span>
                                <div>
                                    <div class="contact-label">LinkedIn</div>
                                    <div class="contact-value"><a href="{{ $profile['linkedin'] }}" target="_blank" style="color: #fff; text-decoration: underline;">Connect on LinkedIn</a></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-7 elegant-contact-form-col">
                        @if(session('status') === 'message-sent')
                            <div class="alert alert-success border-0 rounded-0 shadow-sm p-4 mb-4">
                                <h5 class="fw-bold mb-2"><i class="fas fa-check-circle me-2"></i> Thank you!</h5>
                                <p class="mb-0 small text-secondary">Your message has been sent successfully. I will get back to you shortly.</p>
                            </div>
                        @endif
                        
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-0 shadow-sm p-4 mb-4">
                                <ul class="mb-0 small" style="list-style: none; padding-left: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ url('/contact/submit/' . $portfolio->id . '/') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="elegant-form-group">
                                        <input type="text" name="name" required placeholder=" ">
                                        <label>Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="elegant-form-group">
                                        <input type="email" name="email" required placeholder=" ">
                                        <label>Your Email</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="elegant-form-group">
                                        <input type="text" name="subject" placeholder=" ">
                                        <label>Subject (Optional)</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="elegant-form-group">
                                        <textarea name="message" rows="5" required placeholder=" "></textarea>
                                        <label>Your Message</label>
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="elegant-btn-solid border-0 w-100 py-3 text-center">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="elegant-footer" style="background: #0f172a; color: #f8fafc; padding: 4.5rem 0 2.5rem; border-top: 1px solid rgba(99, 102, 241, 0.2);">
        <div class="elegant-container">
            <div class="d-flex flex-column align-items-center text-center" style="max-width: 850px; margin: 0 auto;">
                <!-- Profile Avatar Image -->
                <div style="margin-bottom: 1.25rem;">
                    @if($portfolio->profile_image)
                        <img src="{{ Storage::url($portfolio->profile_image) }}" alt="{{ $user->name }}" style="width: 84px; height: 84px; object-fit: cover; border-radius: 50%; border: 2px solid #8b5cf6; padding: 3px; box-shadow: 0 4px 20px rgba(139, 92, 246, 0.25);">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=160&background=6366f1&color=fff" alt="{{ $user->name }}" style="width: 84px; height: 84px; object-fit: cover; border-radius: 50%; border: 2px solid #8b5cf6; padding: 3px; box-shadow: 0 4px 20px rgba(139, 92, 246, 0.25);">
                    @endif
                </div>

                <!-- Profile User Name -->
                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.85rem; font-weight: 700; color: #ffffff; margin-bottom: 0.25rem;">{{ $user->name }}</h3>

                <!-- Tagline / Title -->
                <p style="color: #94a3b8; font-size: 0.95rem; font-weight: 500; margin-bottom: 2rem; letter-spacing: 0.5px;">{{ $portfolio->title ?? $profile['short_title'] }}</p>

                <!-- Complete Header-Style Portfolio Navigation Links -->
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem 1.75rem; margin-bottom: 2.25rem;">
                    <a href="#hero" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Home</a>
                    <a href="#about" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">About</a>
                    @if($portfolio->show_skills)<a href="#skills" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Skills</a>@endif
                    @if($portfolio->show_experience)<a href="#experience" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Experience</a>@endif
                    @if($portfolio->show_projects)<a href="#projects" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Projects</a>@endif
                    @if($portfolio->show_education)<a href="#skills-extra" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Education</a>@endif
                    @if($portfolio->show_achievements)<a href="#skills-extra" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Achievements</a>@endif
                    @if($portfolio->show_contributions)<a href="#contributions" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Contributions</a>@endif
                    @if($portfolio->show_publications && $portfolio->publications->isNotEmpty())<a href="#publications" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Publications</a>@endif
                    @if($portfolio->show_services)<a href="#services" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Services</a>@endif
                    @if($portfolio->show_certifications)<a href="#trainings" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Certifications</a>@endif
                    @if($portfolio->show_trainings)<a href="#trainings" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Trainings</a>@endif
                    @if($portfolio->show_testimonials)<a href="#testimonials" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Testimonials</a>@endif
                    @if($portfolio->show_media && $portfolio->media->isNotEmpty())<a href="#media" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Media</a>@endif
                    <a href="#contact" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">Contact</a>
                </div>

                <div style="width: 100%; height: 1px; background: rgba(255,255,255,0.08); margin-bottom: 1.75rem;"></div>

                <!-- Professional Copyright & Branding -->
                <p style="color: #94a3b8; font-size: 0.88rem; margin-bottom: 0; line-height: 1.6;">
                    &copy; {{ now()->year }} <strong style="color: #ffffff;">{{ $user->name }}</strong>. All rights reserved <span style="margin: 0 6px; opacity: 0.5;">&bull;</span> Powered by <a href="https://itechgb.com/" target="_blank" style="color: #e2e8f0; text-decoration: underline; text-underline-offset: 3px; font-weight: 500;">Innovative Technologies GB</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Reusable Custom Elegant Project Details Modal -->
    <div id="elegantProjectModal" class="elegant-modal">
        <div class="elegant-modal-backdrop"></div>
        <div class="elegant-modal-content">
            <button type="button" class="elegant-modal-close">&times;</button>
            <div class="elegant-modal-body">
                <div class="elegant-modal-img-col" id="elegantModalImgCol">
                    <img id="elegantModalImg" src="" alt="Project Image">
                </div>
                <div class="elegant-modal-text-col" id="elegantModalTextCol">
                    <div class="elegant-modal-tags" id="elegantModalTags"></div>
                    <h3 id="elegantModalTitle"></h3>
                    <p id="elegantModalDesc"></p>
                </div>
            </div>
        </div>
    </div>
    @else
        <!-- Generic Theme Layout -->
        <header class="py-5 text-center mb-5">
             <div class="container">
                <h1 class="display-4 fw-bold">{{ $portfolio->title }}</h1>
                <p class="lead">{{ $portfolio->description }}</p>
             </div>
        </header>
        <main class="container">
            <!-- Simplified sections for other themes -->
            <p class="text-center">Theme content for {{ $theme }}</p>
        </main>
    @endif
@endsection

@push('styles')
    @if($theme == 'premium')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700&display=swap');
        :root {
            --bg-primary: #050505;
            --bg-secondary: #0f1115;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --accent-color: #00f2ff;
            --accent-hover: #00d2df;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --card-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .d-none {
            display: none !important;
        }
        section {
            padding: 80px 0;
            box-sizing: border-box;
        }
        .premium-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            width: 100%;
            box-sizing: border-box;
        }
        .menu-toggle{
            display: none;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 0 60px 0;
            box-sizing: border-box;
        }
        .premium-hero-row {
            display: flex;
            align-items: center;
            gap: 4rem;
            width: 100%;
        }
        h1, h2, h3, h4, h5, h6 {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }
        .hero-content { flex: 1.2; width: 100%; }
        .hero-image { flex: 0 0 380px; max-width: 100%; height: auto; aspect-ratio: 1 / 1; border-radius: 30px; overflow: hidden; border: 4px solid var(--glass-border); box-shadow: var(--card-shadow); }
        .hero-image img { width: 100%; height: 100%; object-fit: cover; }
        .hero-subtitle { color: var(--accent-color); font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 2px; word-break: break-word; overflow-wrap: anywhere; white-space: normal; }
        .hero h1 { font-family: 'Outfit', sans-serif; font-size: clamp(2rem, 5vw, 4.5rem); line-height: 1.1; margin-bottom: 1.5rem; font-weight: 700; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
        .btn-primary { background: var(--accent-color); color: #000; padding: 1rem 2rem; border-radius: 5px; text-decoration: none; font-weight: 700; display: inline-block; transition: var(--transition); }
        .section-title { font-family: 'Outfit', sans-serif; font-size: clamp(1.8rem, 4vw, 2.5rem); margin-top: 0; margin-bottom: 2rem; position: relative; display: inline-block; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
        .section-title::after { content: ''; position: absolute; bottom: -10px; left: 0; width: 60%; height: 4px; background: var(--accent-color); }
        
        /* Premium Row/Column Split layouts */
        .premium-row {
            display: flex;
            gap: 4rem;
            width: 100%;
        }
        .premium-col-left {
            flex: 1;
        }
        .premium-col-right {
            flex: 1.5;
        }
        
        @media (min-width: 992px) {
            .premium-col-left {
                position: sticky;
                top: 100px;
                height: fit-content;
            }
        }
        
        /* Profile Quick Facts */
        .premium-facts-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            padding: 2rem;
            border-radius: 20px;
            margin-top: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .facts-title {
            font-family: 'Outfit', sans-serif;
            color: var(--accent-color);
            font-size: 1.3rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 0.75rem;
        }
        .facts-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .fact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .fact-icon {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            font-size: 1rem;
            flex-shrink: 0;
            transition: var(--transition);
        }
        .fact-item:hover .fact-icon {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            transform: translateY(-2px);
        }
        .fact-label {
            display: block;
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .fact-value {
            display: block;
            font-size: 0.95rem;
            color: var(--text-primary);
            font-weight: 500;
        }
        .fact-value a {
            color: var(--accent-color);
            text-decoration: none;
            transition: var(--transition);
        }
        .fact-value a:hover {
            color: var(--text-primary);
            text-decoration: underline;
        }
        .text-accent {
            color: var(--accent-color) !important;
        }
        .premium-bio-text {
            color: var(--text-secondary);
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .premium-bio-text p {
            margin-bottom: 1.5rem;
        }
        .premium-section-desc {
            color: var(--text-secondary);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-top: 1.5rem;
        }
        
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            width: 100%;
        }
        
        /* 2 Column Equal Split Grid for Certifications/Trainings/Testimonials */
        .premium-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            width: 100%;
        }
        @media (max-width: 768px) {
            .premium-grid-2col {
                grid-template-columns: 1fr;
            }
        }
        
        /* Dynamic Testimonials layout adjustments */
        .premium-testimonials-1 {
            display: block;
            width: 100%;
        }
        .premium-testimonials-1 .skill-card {
            width: 100%;
            box-sizing: border-box;
        }
        
        .skill-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            padding: 2rem;
            border-radius: 15px;
            transition: var(--transition);
        }
        .skill-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        .skill-card h3 { color: var(--accent-color); margin-bottom: 1rem; font-family: 'Outfit', sans-serif; }
        .skill-list { list-style: none; padding: 0; margin: 0; }
        .skill-list li { padding: 0.5rem 0; border-bottom: 1px solid var(--glass-border); color: var(--text-secondary); }
        
        /* Timeline */
        .timeline { position: relative; width: 100%; }
        .timeline-item { margin-bottom: 3.5rem; padding-left: 2.5rem; position: relative; }
        .timeline-item:last-child { margin-bottom: 0; }
        .timeline-item::before { content: ''; position: absolute; left: 0; top: 5px; width: 2px; height: calc(100% + 3.5rem); background: var(--glass-border); }
        .timeline-item:last-child::before { display: none; }
        .timeline-dot { position: absolute; left: -5px; top: 5px; width: 12px; height: 12px; background: var(--accent-color); border-radius: 50%; box-shadow: 0 0 10px var(--accent-color); }
        .exp-date { font-weight: 600; color: var(--accent-color); margin-bottom: 0.5rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; }
        .exp-job { font-size: 1.3rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.2rem; font-family: 'Outfit', sans-serif; }
        .exp-company { color: var(--text-secondary); font-weight: 500; margin-bottom: 1rem; font-size: 1rem; }
        .exp-details { list-style: none; padding: 0; margin: 0; color: var(--text-secondary); font-size: 1rem; line-height: 1.6; }
        .exp-details li { position: relative; padding-left: 1.2rem; }
        .exp-details li::before { content: '→'; position: absolute; left: 0; color: var(--accent-color); }

        /* Skills Extra */
        .premium-skills-extra-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            width: 100%;
        }
        .premium-extra-title {
            margin-top: 0;
            margin-bottom: 2rem;
            color: var(--accent-color);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
        }
        .premium-tags-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }
        .premium-edu-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .premium-edu-degree {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
        }
        .premium-edu-institution {
            color: var(--accent-color);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .premium-edu-meta {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.8rem; width: 100%; }
        .project-card { background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; transition: var(--transition); display: flex; flex-direction: column; }
        .project-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        .project-img { width: 100%; height: 250px; background: #1a1a1a; overflow: hidden; }
        .project-img img { width: 100%; height: 100%; object-fit: cover; }
        .tag { background: rgba(0, 242, 255, 0.1); color: var(--accent-color); padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; margin-right: 5px; word-break: break-word; overflow-wrap: anywhere; white-space: normal; max-width: 100%; }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .bg-blob { position: fixed; width: 500px; height: 500px; background: radial-gradient(circle, rgba(0, 242, 255, 0.1) 0%, transparent 70%); filter: blur(80px); z-index: -1; border-radius: 50%; }
        .blob-1 { top: -100px; right: -100px; }
        .blob-2 { bottom: -100px; left: -100px; }
        
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            background: rgba(5, 5, 5, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            box-sizing: border-box;
        }
        .premium-nav-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            box-sizing: border-box;
        }
        nav ul { display: flex; list-style: none; gap: 1.5rem; margin: 0; padding: 0; align-items: center; }
        nav ul li a { text-decoration: none; color: var(--text-secondary); font-size: 0.9rem; font-weight: 500; transition: var(--transition); }
        nav ul li a:hover { color: var(--accent-color); }
        .logo { display: flex; align-items: center; font-family: 'Outfit', sans-serif; }
        .logo-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent-color); transition: var(--transition); }
        .logo-avatar:hover { transform: scale(1.05); box-shadow: 0 0 15px rgba(0, 242, 255, 0.4); }
        
        /* Dropdown Styles */
        nav ul li { list-style: none; position: relative; }
        .dropdown { position: relative; }
        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(10, 12, 18, 0.98);
            min-width: 190px;
            box-shadow: 0px 12px 30px rgba(0,0,0,0.6);
            z-index: 9999;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            padding-top: 8px;
            overflow: visible;
        }
        .dropdown-content::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 0;
            width: 100%;
            height: 8px;
        }
        .dropdown-content a {
            color: var(--text-secondary);
            padding: 11px 18px;
            text-decoration: none;
            display: block;
            font-size: 0.85rem;
            transition: var(--transition);
            white-space: nowrap;
        }
        .dropdown-content a:hover {
            background: rgba(0, 242, 255, 0.12);
            color: var(--accent-color);
        }
        .dropdown:hover .dropdown-content,
        .dropdown.open .dropdown-content { display: block; }
        .dropdown:hover .dropbtn,
        .dropdown.open .dropbtn { color: var(--accent-color); }
 
        .contact-container { display: grid; grid-template-columns: 1fr 1.2fr; gap: 4rem; width: 100%; }
        .contact-item { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem; }
        .contact-icon { width: 50px; height: 50px; background: var(--glass-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent-color); }
        footer { padding: 4rem 0; text-align: center; border-top: 1px solid var(--glass-border); color: var(--text-secondary); }
        .footer-nav { display: flex; justify-content: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem; }
        .footer-nav a { text-decoration: none; color: var(--text-secondary); font-weight: 500; transition: var(--transition); }
        .footer-nav a:hover { color: var(--accent-color); }
 
        /* Section Divider */
        .section-divider {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-sizing: border-box;
        }
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 242, 255, 0.3), transparent);
        }
        .section-divider span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: var(--accent-color);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--accent-color), 0 0 30px rgba(0, 242, 255, 0.4);
            animation: pulse-dot 2.5s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.7; }
        }
 
        /* Custom Premium Modal */
        .premium-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            box-sizing: border-box;
        }
        .premium-modal.active {
            display: flex;
        }
        .premium-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
        }
        .premium-modal-content {
            position: relative;
            background: rgba(15, 17, 21, 0.95);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            z-index: 2001;
            animation: modalFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .premium-modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            color: #fff;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            z-index: 2005;
        }
        .premium-modal-close:hover {
            background: rgba(255,255,255,0.15);
            color: var(--accent-color);
        }
        .premium-modal-body {
            display: flex;
            min-height: 400px;
        }
        .premium-modal-img-col {
            flex: 1;
            background: #000;
            position: relative;
            border-top-left-radius: 23px;
            border-bottom-left-radius: 23px;
            overflow: hidden;
        }
        .premium-modal-img-col img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .premium-modal-text-col {
            flex: 1.2;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-sizing: border-box;
        }
        .premium-modal-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .premium-modal-text-col h3 {
            font-family: 'Outfit', sans-serif;
            color: #fff;
            font-size: 2.2rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 1.5rem;
        }
        .premium-modal-text-col p {
            color: var(--text-secondary);
            font-size: 1.05rem;
            line-height: 1.7;
            margin: 0;
            white-space: pre-line;
        }
 
        /* =========================
   MOBILE FIRST RESPONSIVE
 ========================= */
 
/* Tablet */
@media (max-width: 992px) {
    .premium-container {
        padding: 0 1.5rem;
    }
    .premium-row {
        flex-direction: column;
        gap: 2.5rem;
    }
    .premium-skills-extra-grid {
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
    .premium-hero-row {
        flex-direction: column-reverse;
        text-align: center;
        gap: 2.5rem;
    }
    .hero {
        padding: 120px 0 60px 0;
    }
    .hero-image {
        width: 300px;
        max-width: 100%;
        height: auto;
        aspect-ratio: 1/1;
        flex: 0 0 auto;
        margin: 0 auto;
    }
    .contact-container {
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
}
 
/* Mobile */
@media (max-width: 768px) {
    /* NAVBAR */
    .logo-avatar {
        width: 40px;
        height: 40px;
    }
    nav {
        padding: 1rem 0;
    }
    nav ul {
        position: fixed;
        top: 70px;
        right: -100%;
        width: 260px;
        height: calc(100vh - 70px);
        background: rgba(10, 12, 18, 0.98);
        flex-direction: column;
        align-items: flex-start;
        padding: 2rem;
        transition: 0.3s ease;
        border-left: 1px solid var(--glass-border);
    }
    nav ul.active {
        right: 0;
    }
    nav ul li {
        width: 100%;
    }
    nav ul li a {
        display: block;
        padding: 10px 0;
        font-size: 1rem;
    }
    /* Hamburger */
    .menu-toggle {
        display: block;
        cursor: pointer;
        font-size: 1.5rem;
        color: var(--text-primary);
    }
 
    /* HERO */
    .hero h1 {
        font-size: clamp(1.8rem, 6vw, 2.5rem);
        margin-top: 3rem;
    }
    .hero-subtitle {
        font-size: 0.9rem;
        word-break: break-word;
        overflow-wrap: anywhere;
        white-space: normal;
        max-width: 100%;
    }
 
    /* GRIDS */
    .skills-grid,
    .projects-grid {
        grid-template-columns: 1fr;
    }
 
    /* TIMELINE */
    .timeline-item {
        padding-left: 2rem;
    }
 
    /* IMAGE FIX */
    .project-img {
        height: 200px;
    }
 
    /* CONTACT */
    .contact-item {
        gap: 1rem;
    }
    .contact-icon {
        width: 40px;
        height: 40px;
    }
    /* Prevent form from overflowing viewport on mobile */
    .contact-form {
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    .contact-form input,
    .contact-form textarea,
    .contact-form button {
        width: 100% !important;
        box-sizing: border-box !important;
        max-width: 100% !important;
    }

    /* Modal Mobile */
    .premium-modal-body {
        flex-direction: column;
    }
    .premium-modal-img-col {
        border-top-left-radius: 23px;
        border-top-right-radius: 23px;
        border-bottom-left-radius: 0;
        height: 250px;
    }
    .premium-modal-text-col {
        padding: 2rem;
    }
}
 
/* Small Mobile */
@media (max-width: 480px) {
    .hero-image {
        width: 240px;
        max-width: 100%;
        height: auto;
        aspect-ratio: 1/1;
        flex: 0 0 auto;
    }
    .hero h1 {
        font-size: 1.8rem;
        margin-top: 3rem;
    }
    .btn-primary {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
    }
    .section-title {
        font-size: 1.8rem;
    }
}

/* Show More / Show Less Button Styling */
.premium-show-more-btn {
    background: transparent;
    border: 2px solid var(--accent-color);
    color: var(--accent-color);
    padding: 0.8rem 2rem;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    outline: none;
    display: inline-block;
    margin-top: 2rem;
}
.premium-show-more-btn:hover {
    background: var(--accent-color);
    color: #000;
    box-shadow: 0 0 15px rgba(0, 242, 255, 0.4);
    transform: translateY(-2px);
}
.premium-show-more-btn.expanded {
    background: var(--accent-color);
    color: #000;
}
.premium-show-more-btn.expanded:hover {
    background: var(--accent-hover);
    box-shadow: 0 0 15px rgba(0, 242, 255, 0.6);
    transform: translateY(-2px);
}
@media (max-width: 640px) {
    .hero-content h1 { font-size: 1.65rem !important; line-height: 1.25 !important; }
    .hero-subtitle { font-size: 0.75rem !important; }
    .section-title { font-size: 1.35rem !important; line-height: 1.25 !important; }
    .hero-intro-text, p { font-size: 0.88rem !important; line-height: 1.5 !important; }
}
    </style>
    @endif
    @if($theme == 'classic')
    <style>
        /* Classic Clean Custom Style overrides */
        h1, h2, h3, h4, h5, h6, .serif-heading {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }
        .serif-heading {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
        }
        .max-w-column {
            max-width: 800px;
        }
        .text-primary-accent {
            color: #0f766e;
        }
        .bg-light {
            background-color: #f8fafc !important;
        }
        .hover-shadow-classic:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-2px);
        }
        .transition {
            transition: all 0.3s ease;
        }
        .custom-classic-nav {
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.9) !important;
        }
        .custom-classic-nav .nav-link {
            transition: color 0.2s ease;
        }
        .custom-classic-nav .nav-link:hover, .custom-classic-nav .nav-link.active {
            color: #0f766e !important;
        }
        .progress-classic {
            border-radius: 10px;
            background-color: #e2e8f0;
        }
        .progress-classic .progress-bar {
            background-color: #0f766e !important;
            border-radius: 10px;
        }
        .icon-circle-bg {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #0f766e;
            font-size: 1.2rem;
        }
        .icon-circle-bg-sm {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #0f766e;
            font-size: 0.9rem;
        }
        .classic-timeline::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 8px;
            width: 2px;
            height: calc(100% - 20px);
            background-color: #cbd5e1;
        }
        .classic-timeline-item {
            padding-left: 35px;
        }
        .timeline-dot-classic {
            position: absolute;
            top: 6px;
            left: 3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #0f766e;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #0f766e;
        }
        .serif-quote {
            font-family: 'Playfair Display', Georgia, serif;
            color: #cbd5e1;
        }
        .absolute-border-decor {
            position: absolute;
            top: 20px;
            left: -20px;
            width: 100%;
            height: 100%;
            border: 4px solid #0f766e;
            border-radius: 1rem;
            z-index: -1;
        }
        .lead-text-classic p {
            margin-bottom: 1.5rem;
        }
        .logo-avatar-classic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #0f766e;
            transition: all 0.3s ease;
        }
        .logo-avatar-classic:hover {
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .logo-avatar-classic {
                width: 40px;
                height: 40px;
            }
        }
        @media (max-width: 576px) {
            .display-3 {
                font-size: 2.0rem !important;
            }
            .absolute-border-decor {
                left: -10px;
                top: 10px;
            }
            #hero {
                padding: 1.5rem 0 !important;
            }
            #hero img {
                max-width: 260px !important;
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 380 / 420 !important;
                border-radius: 1rem !important; /* rounded-4 equivalent */
                object-fit: cover !important;
            }
            #hero .container {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            #hero .lead {
                margin-bottom: 1.5rem !important;
                font-size: 0.95rem !important;
            }
        }
        /* Mobile: allow badge text to wrap (Bootstrap sets white-space: nowrap by default) */
        @media (max-width: 768px) {
            .badge {
                white-space: normal;
                word-break: break-word;
                overflow-wrap: anywhere;
                max-width: 100%;
                display: inline-block;
            }
            /* Achievement pills: flex so icon stays top-left and text wraps below itself */
            .d-flex.flex-wrap.gap-2 .badge {
                display: inline-flex;
                align-items: flex-start;
                max-width: calc(100vw - 3rem);
            }
            .d-flex.flex-wrap.gap-2 .badge i {
                flex-shrink: 0;
                margin-top: 2px; /* align icon with first line of text */
            }
        }
        @media (max-width: 640px) {
            .display-1, .display-2, .display-3, .display-4, .display-5, .display-6 { font-size: 1.5rem !important; line-height: 1.25 !important; }
            h1, .h1 { font-size: 1.4rem !important; }
            h2, .h2, .serif-heading { font-size: 1.25rem !important; }
            h3, .h3 { font-size: 1.1rem !important; }
            h4, .h4 { font-size: 0.95rem !important; }
            .lead, p { font-size: 0.88rem !important; line-height: 1.5 !important; }
        }
    </style>
    @endif
    @if($theme == 'elegant')
    <style>
        :root {
            --elegant-indigo: #4f46e5;
            --elegant-indigo-hover: #3730a3;
            --elegant-indigo-light: #f4f3f8;
            --elegant-navy: #1e1b4b;
            --elegant-border: #e2e8f0;
            --elegant-gold: #d97706;
            --elegant-gold-light: #fef3c7;
            --elegant-bg-alt: #f8f9fc;
            --elegant-font-serif: 'Cormorant Garamond', Georgia, serif;
            --elegant-font-sans: 'DM Sans', sans-serif;
            --elegant-shadow: 0 10px 30px -15px rgba(79, 70, 229, 0.08);
            --elegant-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        h1, h2, h3, h4, h5, h6, .elegant-hero-title, .elegant-section-title {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }
        .text-indigo { color: var(--elegant-indigo) !important; }
        .text-gold { color: var(--elegant-gold) !important; }
        .elegant-bg-alt { background-color: var(--elegant-bg-alt) !important; }
        .elegant-container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; width: 100%; box-sizing: border-box; }
        
        .elegant-section { padding: 100px 0; position: relative; box-sizing: border-box; }
        .elegant-section-tag { font-family: var(--elegant-font-sans); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 3px; font-weight: 700; color: var(--elegant-indigo); margin-bottom: 0.5rem; }
        .elegant-section-title { font-family: var(--elegant-font-serif); font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 600; color: var(--elegant-navy); margin-top: 0; margin-bottom: 3rem; }
        .elegant-section-divider { height: 1px; background: linear-gradient(90deg, transparent, var(--elegant-border), transparent); margin-bottom: 4rem; }

        /* Navigation */
        .elegant-nav { position: fixed; top: 0; left: 0; right: 0; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-bottom: 1px solid rgba(79, 70, 229, 0.08); z-index: 1000; transition: var(--elegant-transition); }
        .elegant-nav-container { display: flex; justify-content: space-between; align-items: center; height: 80px; max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .elegant-logo { display: flex; align-items: center; cursor: pointer; text-decoration: none; }
        .logo-avatar-elegant {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--elegant-indigo);
            transition: var(--elegant-transition);
        }
        .logo-avatar-elegant:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.2);
        }
        @media (max-width: 768px) {
            .logo-avatar-elegant {
                width: 40px;
                height: 40px;
            }
        }
        .elegant-nav ul { display: flex; list-style: none; margin: 0; padding: 0; align-items: center; gap: 2rem; }
        .elegant-nav ul li { margin: 0; }
        .elegant-nav ul li a { font-family: var(--elegant-font-sans); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--elegant-navy); text-decoration: none; position: relative; padding: 5px 0; transition: var(--elegant-transition); }
        .elegant-nav ul li a::after { content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 2px; background: var(--elegant-indigo); transition: var(--elegant-transition); }
        .elegant-nav ul li a:hover { color: var(--elegant-indigo); }
        .elegant-nav ul li a:hover::after, .elegant-nav ul li a.active::after { width: 100%; }
        .elegant-menu-toggle { display: none; font-size: 1.5rem; color: var(--elegant-navy); cursor: pointer; }

        @media (max-width: 991px) {
            .elegant-menu-toggle { display: block; }
            .elegant-nav ul {
                display: none;
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                background: #ffffff;
                border-bottom: 1px solid var(--elegant-border);
                flex-direction: column;
                padding: 2rem 0;
                gap: 1.5rem;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            }
            .elegant-nav ul.active { display: flex; }
        }

        /* Dropdowns */
        .elegant-dropdown { position: relative; }
        .elegant-dropdown-content { display: none; position: absolute; top: 100%; left: 0; background: #ffffff; min-width: 200px; box-shadow: var(--elegant-shadow); border: 1px solid var(--elegant-border); padding: 10px 0; z-index: 100; margin-top: 10px; }
        .elegant-dropdown-content a { display: block; padding: 10px 20px !important; text-transform: none !important; letter-spacing: 0.5px !important; font-weight: 500 !important; font-size: 0.9rem !important; color: var(--elegant-navy) !important; text-decoration: none; }
        .elegant-dropdown-content a:hover { background: var(--elegant-indigo-light); color: var(--elegant-indigo) !important; }
        .elegant-dropdown-content a::after { display: none !important; }
        .elegant-dropdown:hover .elegant-dropdown-content, .elegant-dropdown.open .elegant-dropdown-content { display: block; }

        /* Hero */
        .elegant-hero { min-height: 100vh; display: flex; align-items: center; padding-top: 120px; padding-bottom: 80px; background: radial-gradient(circle at 80% 20%, rgba(79, 70, 229, 0.04), transparent 50%); position: relative; box-sizing: border-box; }
        .elegant-hero-subtitle { font-family: var(--elegant-font-sans); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 4px; font-weight: 700; color: var(--elegant-indigo); margin-bottom: 1rem; }
        .elegant-hero-title { font-family: var(--elegant-font-serif); font-size: clamp(2rem, 6vw, 4.5rem); font-weight: 700; line-height: 1.1; color: var(--elegant-navy); margin-bottom: 1.5rem; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
        .elegant-hero-position { font-family: var(--elegant-font-serif); font-size: 2rem; font-style: italic; color: var(--elegant-gold); margin-bottom: 1.5rem; font-weight: 400; word-break: break-word; overflow-wrap: anywhere; white-space: normal; }
        .elegant-hero-desc { font-size: 1.1rem; line-height: 1.8; color: var(--elegant-navy); opacity: 0.85; max-width: 600px; margin-bottom: 2rem; }

        .elegant-hero-image-wrapper { position: relative; display: inline-block; margin-top: 2rem; max-width: 100%; }
        .elegant-hero-image-offset { position: absolute; top: 15px; left: 15px; width: 100%; height: 100%; border: 1.5px solid var(--elegant-indigo); z-index: 1; transition: var(--elegant-transition); box-sizing: border-box; }
        .elegant-hero-image { position: relative; z-index: 2; width: 300px; max-width: 100%; height: auto; aspect-ratio: 300 / 370; overflow: hidden; border: 8px solid #ffffff; box-shadow: var(--elegant-shadow); background: #ffffff; box-sizing: border-box; }
        .elegant-hero-image img { width: 100%; height: 100%; object-fit: cover; transition: var(--elegant-transition); }
        .elegant-hero-image-wrapper:hover .elegant-hero-image-offset { transform: translate(-10px, -10px); }
        .elegant-hero-image-wrapper:hover .elegant-hero-image img { transform: scale(1.05); }
        .elegant-image-placeholder { height: 100%; width: 100%; display: flex; align-items: center; justify-content: center; background: var(--elegant-bg-alt); }

        /* Profile Details */
        .elegant-profile-highlight { font-family: var(--elegant-font-serif); font-size: 1.8rem; font-style: italic; line-height: 1.6; color: var(--elegant-indigo); max-width: 900px; margin: 0 auto; }
        .elegant-meta-card { background: #ffffff; padding: 2.5rem; border: 1px solid var(--elegant-border); border-top: 4px solid var(--elegant-indigo); box-shadow: var(--elegant-shadow); text-align: left; }
        .elegant-meta-title { font-family: var(--elegant-font-serif); font-size: 1.6rem; color: var(--elegant-navy); margin-top: 0; margin-bottom: 1.5rem; font-weight: 600; text-align: left; }
        .elegant-meta-list { list-style: none; padding: 0; margin: 0; }
        .elegant-meta-list li { display: flex; flex-direction: column; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .elegant-meta-list li:last-child { border-bottom: none; }
        .elegant-meta-list li .meta-label { font-family: var(--elegant-font-sans); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--elegant-indigo); font-weight: 700; margin-bottom: 4px; }
        .elegant-meta-list li .meta-val { font-size: 0.95rem; font-weight: 500; color: var(--elegant-navy); overflow-wrap: break-word; }
        .elegant-meta-list li .meta-val a { color: var(--elegant-navy); text-decoration: none; transition: var(--elegant-transition); }
        .elegant-meta-list li .meta-val a:hover { color: var(--elegant-indigo); text-decoration: underline; }
        .elegant-bio-text { text-align: left; }
        .elegant-bio-text p { font-size: 1.05rem; line-height: 1.8; color: var(--elegant-navy); opacity: 0.85; margin-bottom: 1.5rem; }

        /* Skills Grid */
        .elegant-skills-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
        .elegant-skill-card { background: #ffffff; border: 1px solid var(--elegant-border); padding: 2.5rem; transition: var(--elegant-transition); position: relative; box-shadow: var(--elegant-shadow); text-align: left; }
        .elegant-skill-card-top-bar { position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--elegant-indigo); width: 0; transition: var(--elegant-transition); }
        .elegant-skill-card:hover .elegant-skill-card-top-bar { width: 100%; }
        .elegant-skill-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.15); }
        .elegant-skill-title { font-family: var(--elegant-font-serif); font-size: 1.5rem; color: var(--elegant-navy); margin-top: 0; margin-bottom: 1.5rem; font-weight: 600; }
        .elegant-skill-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
        .elegant-skill-list li { font-size: 0.95rem; font-weight: 500; display: flex; align-items: center; color: var(--elegant-navy); opacity: 0.9; }
        .elegant-bullet { height: 6px; width: 6px; border-radius: 50%; background: var(--elegant-indigo); display: inline-block; margin-right: 12px; }

        /* Services */
        .elegant-service-card { background: #ffffff; border: 1px solid var(--elegant-border); padding: 2.5rem; transition: var(--elegant-transition); box-shadow: var(--elegant-shadow); height: 100%; display: flex; flex-direction: column; text-align: left; }
        .elegant-service-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.15); border-color: rgba(79, 70, 229, 0.2); }
        .elegant-service-icon { height: 50px; width: 50px; border-radius: 4px; background: var(--elegant-indigo-light); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--elegant-indigo); margin-bottom: 1.5rem; }
        .elegant-service-title { font-family: var(--elegant-font-serif); font-size: 1.5rem; color: var(--elegant-navy); margin-top: 0; margin-bottom: 1rem; font-weight: 600; }
        .elegant-card-divider { height: 1px; background: var(--elegant-border); width: 40px; margin-bottom: 1rem; transition: var(--elegant-transition); }
        .elegant-service-card:hover .elegant-card-divider { width: 100%; background: var(--elegant-indigo); }
        .elegant-service-desc { font-size: 0.95rem; line-height: 1.6; color: var(--elegant-navy); opacity: 0.8; margin: 0; }

        /* Experience Timeline */
        .elegant-timeline { position: relative; max-width: 850px; margin: 0 auto; padding-left: 45px; text-align: left; }
        .elegant-timeline::before { content: ''; position: absolute; left: 15px; top: 5px; bottom: 5px; width: 2px; background: var(--elegant-border); }
        .elegant-timeline-item { position: relative; margin-bottom: 4rem; }
        .elegant-timeline-item:last-child { margin-bottom: 0; }
        .elegant-timeline-badge { position: absolute; left: -45px; top: 2px; height: 32px; width: 32px; border-radius: 50%; background: #ffffff; border: 2px solid var(--elegant-indigo); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--elegant-indigo); z-index: 2; transition: var(--elegant-transition); }
        .elegant-timeline-item:hover .elegant-timeline-badge { background: var(--elegant-indigo); color: #ffffff; transform: scale(1.1); }
        .elegant-timeline-content { background: #ffffff; border: 1px solid var(--elegant-border); padding: 2.5rem; box-shadow: var(--elegant-shadow); transition: var(--elegant-transition); }
        .elegant-timeline-content:hover { box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.12); }
        .elegant-timeline-job { font-family: var(--elegant-font-serif); font-size: 1.5rem; color: var(--elegant-navy); margin: 0; font-weight: 600; }
        .elegant-timeline-date { font-family: var(--elegant-font-sans); font-size: 0.8rem; font-weight: 700; color: var(--elegant-indigo); background: var(--elegant-indigo-light); padding: 6px 16px; border-radius: 20px; letter-spacing: 0.5px; }
        .elegant-timeline-company { font-family: var(--elegant-font-sans); font-size: 0.95rem; font-weight: 600; color: var(--elegant-gold); margin-top: 0.25rem; margin-bottom: 1.25rem; }
        .elegant-timeline-highlights { font-size: 0.95rem; line-height: 1.7; color: var(--elegant-navy); opacity: 0.85; margin: 0; }

        /* Education */
        .elegant-edu-card { background: #ffffff; border: 1px solid var(--elegant-border); padding: 2.5rem; box-shadow: var(--elegant-shadow); transition: var(--elegant-transition); height: 100%; border-left: 4px solid var(--elegant-indigo); text-align: left; }
        .elegant-edu-card:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.12); }
        .elegant-edu-date { font-size: 0.85rem; color: var(--elegant-indigo); font-weight: 600; }
        .elegant-edu-result-badge { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; background: var(--elegant-gold-light); color: var(--elegant-gold); padding: 4px 12px; border-radius: 4px; }
        .elegant-edu-degree { font-family: var(--elegant-font-serif); font-size: 1.4rem; color: var(--elegant-navy); margin-top: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; }
        .elegant-edu-institution { font-size: 0.95rem; font-weight: 500; color: #475569; }

        /* Achievements */
        .elegant-tags-container { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; max-width: 900px; margin: 0 auto; }
        .elegant-tag-badge { background: #ffffff; border: 1.5px solid var(--elegant-indigo); color: var(--elegant-navy); font-weight: 600; font-size: 0.95rem; padding: 12px 24px; border-radius: 4px; transition: var(--elegant-transition); display: flex; align-items: center; box-shadow: var(--elegant-shadow); word-break: break-word; overflow-wrap: anywhere; white-space: normal; min-width: 0; }
        .elegant-tag-badge:hover { transform: translateY(-2px); background: var(--elegant-indigo-light); border-color: var(--elegant-indigo); }

        /* Certifications & Trainings */
        .elegant-list-group { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem; }
        .elegant-list-item { background: #ffffff; border: 1px solid var(--elegant-border); padding: 1.25rem 1.75rem; border-radius: 4px; display: flex; align-items: center; gap: 15px; box-shadow: var(--elegant-shadow); transition: var(--elegant-transition); text-align: left; }
        .elegant-list-item:hover { border-color: rgba(79, 70, 229, 0.2); transform: translateY(-2px); }
        .elegant-list-icon { height: 28px; width: 28px; border-radius: 50%; background: var(--elegant-indigo-light); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--elegant-indigo); }
        .elegant-list-text { font-size: 0.95rem; font-weight: 600; color: var(--elegant-navy); }

        /* Projects */
        .elegant-project-card { background: #ffffff; border: 1px solid var(--elegant-border); box-shadow: var(--elegant-shadow); transition: var(--elegant-transition); height: 100%; display: flex; flex-direction: column; text-align: left; }
        .elegant-project-card:hover { transform: translateY(-5px); box-shadow: 0 20px 35px -10px rgba(79, 70, 229, 0.15); border-color: rgba(79, 70, 229, 0.15); }
        .elegant-project-img-wrapper { height: 240px; overflow: hidden; position: relative; background: var(--elegant-bg-alt); border-bottom: 1px solid var(--elegant-border); }
        .elegant-project-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: var(--elegant-transition); }
        .elegant-project-card:hover .elegant-project-img-wrapper img { transform: scale(1.04); }
        .elegant-project-placeholder { height: 100%; width: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; }
        .elegant-project-body { padding: 2.25rem; display: flex; flex-direction: column; flex-grow: 1; }
        .elegant-project-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1rem; }
        .elegant-proj-tag { background: var(--elegant-indigo-light); color: var(--elegant-indigo); font-family: var(--elegant-font-sans); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 4px 10px; border-radius: 4px; }
        .elegant-project-title { font-family: var(--elegant-font-serif); font-size: 1.5rem; color: var(--elegant-navy); margin-top: 0; margin-bottom: 0.75rem; font-weight: 600; }
        .elegant-project-desc { font-size: 0.95rem; line-height: 1.6; color: var(--elegant-navy); opacity: 0.8; margin-bottom: 1.5rem; flex-grow: 1; }
        .elegant-project-btn { background: transparent; border: none; padding: 0; color: var(--elegant-indigo); font-family: var(--elegant-font-sans); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; transition: var(--elegant-transition); display: inline-flex; align-items: center; }
        .elegant-project-btn:hover { color: var(--elegant-indigo-hover); }

        /* Contributions */
        .elegant-contrib-card { background: #ffffff; border: 1px solid var(--elegant-border); padding: 2.25rem; box-shadow: var(--elegant-shadow); transition: var(--elegant-transition); height: 100%; text-align: left; }
        .elegant-contrib-card:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.12); }
        .elegant-contrib-title { font-family: var(--elegant-font-serif); font-size: 1.4rem; color: var(--elegant-navy); margin-top: 0; margin-bottom: 1rem; font-weight: 600; }
        .elegant-contrib-desc { font-size: 0.95rem; line-height: 1.6; color: var(--elegant-navy); opacity: 0.8; margin: 0; }

        /* Testimonials */
        .elegant-testimonial-card { background: #ffffff; border: 1px solid var(--elegant-border); padding: 3rem; box-shadow: var(--elegant-shadow); position: relative; height: 100%; display: flex; flex-direction: column; text-align: left; }
        .elegant-quote-mark { font-family: var(--elegant-font-serif); font-size: 5rem; line-height: 1; color: var(--elegant-indigo-light); position: absolute; top: 1.5rem; left: 2.5rem; font-weight: 900; z-index: 1; }
        .elegant-testimonial-text { font-family: var(--elegant-font-serif); font-size: 1.35rem; font-style: italic; line-height: 1.6; color: var(--elegant-navy); position: relative; z-index: 2; margin-bottom: 2rem; flex-grow: 1; }
        .elegant-testimonial-client { font-family: var(--elegant-font-sans); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--elegant-indigo); position: relative; z-index: 2; }

        /* Contact Details */
        .elegant-contact-card { background: #ffffff; border: 1px solid var(--elegant-border); box-shadow: var(--elegant-shadow); text-align: left; }
        .elegant-contact-sidebar { background: var(--elegant-navy); color: #ffffff; padding: 4rem 3rem; display: flex; flex-direction: column; justify-content: center; }
        .elegant-contact-title { font-family: var(--elegant-font-serif); font-size: 2.2rem; line-height: 1.2; font-weight: 600; margin-bottom: 1rem; color: #ffffff; }
        .elegant-contact-subtitle { font-size: 1rem; line-height: 1.6; opacity: 0.75; margin: 0; }
        .elegant-contact-icon { height: 40px; width: 40px; border-radius: 4px; background: rgba(255, 255, 255, 0.08); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: var(--elegant-indigo); margin-right: 15px; }
        .contact-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.5; font-weight: 600; }
        .contact-value { font-size: 0.95rem; font-weight: 500; }
        .contact-value a { color: #ffffff; text-decoration: none; }
        .elegant-contact-form-col { padding: 4rem 3rem; background: #ffffff; }

        .elegant-form-group { position: relative; }
        .elegant-form-group input, .elegant-form-group textarea {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-bottom: 1.5px solid var(--elegant-border);
            background: transparent;
            outline: none;
            font-size: 0.95rem;
            color: var(--elegant-navy);
            transition: var(--elegant-transition);
            box-sizing: border-box;
        }
        .elegant-form-group textarea { resize: none; }
        .elegant-form-group label {
            position: absolute;
            left: 0;
            top: 12px;
            font-size: 0.95rem;
            color: #94a3b8;
            pointer-events: none;
            transition: var(--elegant-transition);
        }
        .elegant-form-group input:focus ~ label,
        .elegant-form-group input:not(:placeholder-shown) ~ label,
        .elegant-form-group textarea:focus ~ label,
        .elegant-form-group textarea:not(:placeholder-shown) ~ label {
            top: -12px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--elegant-indigo);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .elegant-form-group input:focus, .elegant-form-group textarea:focus {
            border-bottom-color: var(--elegant-indigo);
        }

        /* Footer */
        .elegant-footer { padding: 40px 0; background: #ffffff; border-top: 1px solid var(--elegant-border); }

        /* Show More Button styling */
        .elegant-show-more-btn {
            background: transparent;
            border: 1.5px solid var(--elegant-indigo);
            color: var(--elegant-indigo);
            padding: 0.8rem 2.2rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.85rem;
            font-family: var(--elegant-font-sans);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: var(--elegant-transition);
            outline: none;
            display: inline-block;
            margin-top: 2.5rem;
        }
        .elegant-show-more-btn:hover {
            background: var(--elegant-indigo);
            color: #ffffff;
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.25);
            transform: translateY(-2px);
        }
        .elegant-show-more-btn.expanded {
            background: var(--elegant-indigo);
            color: #ffffff;
        }
        .elegant-show-more-btn.expanded:hover {
            background: var(--elegant-indigo-hover);
        }

        /* Elegant Modal Custom Styling */
        .elegant-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .elegant-modal.active {
            opacity: 1;
            pointer-events: auto;
        }
        .elegant-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 27, 75, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .elegant-modal-content {
            position: relative;
            z-index: 2;
            width: 90%;
            max-width: 960px;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(30, 27, 75, 0.25);
            border-radius: 4px;
            border: 1px solid var(--elegant-border);
            overflow: hidden;
            transform: translateY(30px) scale(0.95);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .elegant-modal.active .elegant-modal-content {
            transform: translateY(0) scale(1);
        }
        .elegant-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            height: 36px;
            width: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid var(--elegant-border);
            font-size: 1.5rem;
            color: var(--elegant-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: var(--elegant-transition);
        }
        .elegant-modal-close:hover {
            background: var(--elegant-indigo-light);
            color: var(--elegant-indigo);
            transform: rotate(90deg);
        }
        .elegant-modal-body {
            display: flex;
            flex-direction: row;
            min-height: 420px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .elegant-modal-img-col {
            flex: 1.1;
            background: var(--elegant-bg-alt);
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid var(--elegant-border);
            position: relative;
            padding: 2rem;
        }
        .elegant-modal-img-col img {
            max-width: 100%;
            max-height: 380px;
            object-fit: contain;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-radius: 2px;
        }
        .elegant-modal-text-col {
            flex: 1.2;
            padding: 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            text-align: left;
        }
        .elegant-modal-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 1.25rem;
        }
        #elegantModalTitle {
            font-family: var(--elegant-font-serif);
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--elegant-navy);
            margin-top: 0;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        #elegantModalDesc {
            font-size: 1.05rem;
            line-height: 1.8;
            color: var(--elegant-navy);
            opacity: 0.85;
            margin: 0;
        }

        @media (max-width: 768px) {
            .elegant-modal-body {
                flex-direction: column;
            }
            .elegant-modal-img-col {
                border-right: none;
                border-bottom: 1px solid var(--elegant-border);
                padding: 3rem 1.5rem 1.5rem 1.5rem;
            }
            .elegant-modal-img-col img {
                max-height: 240px;
            }
            .elegant-modal-text-col {
                padding: 2.5rem 1.5rem;
            }
            #elegantModalTitle {
                font-size: 1.8rem;
            }
            .elegant-skills-grid {
                grid-template-columns: 1fr;
            }
            .elegant-hero-image-offset {
                top: 10px;
                left: 10px;
            }
        }
        @media (max-width: 640px) {
            .elegant-hero-title { font-size: 1.6rem !important; line-height: 1.25 !important; }
            .elegant-section-title { font-size: 1.3rem !important; line-height: 1.25 !important; margin-bottom: 1.5rem !important; }
            h1, .h1, .display-1, .display-2, .display-3, .display-4 { font-size: 1.5rem !important; line-height: 1.25 !important; }
            h2, .h2 { font-size: 1.25rem !important; }
            h3, .h3 { font-size: 1.05rem !important; }
            .lead, p { font-size: 0.88rem !important; line-height: 1.5 !important; }
        }
        @media (max-width: 576px) {
            .elegant-contact-sidebar, .elegant-contact-form-col {
                padding: 2.5rem 1.5rem !important;
            }
            .elegant-hero {
                padding-top: 80px !important;
                padding-bottom: 2rem !important;
                min-height: auto !important;
            }
            .elegant-hero-image {
                width: 180px !important;
                height: 180px !important;
                border-radius: 50% !important;
                border: 4px solid #ffffff !important;
            }
            .elegant-hero-image-offset {
                display: none !important;
            }
            .elegant-hero-title {
                font-size: 2.0rem !important;
                margin-bottom: 0.75rem !important;
            }
            .elegant-hero-position {
                font-size: 1.25rem !important;
                margin-bottom: 0.75rem !important;
            }
            .elegant-hero-desc {
                font-size: 0.95rem !important;
                margin-bottom: 1.25rem !important;
            }
        }
    </style>
    @endif
@endpush

@push('scripts')
    @if($theme == 'premium')
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('active');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

        // Dropdown click toggle (fallback for touch / fast mouse)
        document.querySelectorAll('.dropdown .dropbtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const parent = this.closest('.dropdown');
                const isOpen = parent.classList.contains('open');
                document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
                if (!isOpen) parent.classList.add('open');
            });
        });
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
        });

        // Smooth Scroll for Navigation
        document.querySelectorAll('nav ul li a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Navbar change on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (nav) {
                if (window.scrollY > 50) {
                    nav.style.padding = '0.8rem 0';
                    nav.style.background = 'rgba(5, 5, 5, 0.95)';
                } else {
                    nav.style.padding = '1rem 0';
                    nav.style.background = 'rgba(5, 5, 5, 0.8)';
                }
            }
        });

        // Parallax effect for blobs
        window.addEventListener('mousemove', (e) => {
            const blobs = document.querySelectorAll('.bg-blob');
            const mouseX = e.clientX;
            const mouseY = e.clientY;
            blobs.forEach((blob, index) => {
                const speed = (index + 1) * 0.02;
                const x = (window.innerWidth - mouseX * speed) / 100;
                const y = (window.innerHeight - mouseY * speed) / 100;
                blob.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
        const toggle = document.querySelector('.menu-toggle');
        const navMenu = document.querySelector('nav ul');

        if (toggle && navMenu) {
            toggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });
        }

        // Open Premium Project Details Modal
        function openPremiumProjectModal(btn) {
            const title = btn.getAttribute('data-title');
            const desc = btn.getAttribute('data-desc');
            const image = btn.getAttribute('data-image');
            const tags = btn.getAttribute('data-tags').split(',').filter(t => t);

            document.getElementById('premiumModalTitle').textContent = title;
            document.getElementById('premiumModalDesc').innerHTML = desc;

            const tagsContainer = document.getElementById('premiumModalTags');
            tagsContainer.innerHTML = '';
            tags.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'tag';
                span.textContent = tag;
                tagsContainer.appendChild(span);
            });

            const imgCol = document.getElementById('premiumModalImgCol');
            const img = document.getElementById('premiumModalImg');

            if (image) {
                imgCol.style.display = 'block';
                img.src = image;
            } else {
                imgCol.style.display = 'none';
                img.src = '';
            }

            const modal = document.getElementById('premiumProjectModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Custom modal close and pagination collapse logic
        function initializePremiumPagination() {
            // Modal Close Events
            const modal = document.getElementById('premiumProjectModal');
            if (modal) {
                const closeBtn = modal.querySelector('.premium-modal-close');
                const backdrop = modal.querySelector('.premium-modal-backdrop');
                
                const closeModal = () => {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                };
                
                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (backdrop) backdrop.addEventListener('click', closeModal);
            }

            // Dynamic "Show More" pagination collapse behavior for premium theme
            document.querySelectorAll('[data-limit]').forEach(container => {
                const limit = parseInt(container.getAttribute('data-limit'));
                const children = Array.from(container.children);
                if (children.length > limit) {
                    // Hide extra items
                    for (let i = limit; i < children.length; i++) {
                        children[i].classList.add('d-none');
                    }
                    
                    // Create Show More button styled premium
                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = 'text-center mt-4 w-100';
                    
                    const btn = document.createElement('button');
                    btn.className = 'premium-show-more-btn';
                    btn.textContent = 'Show More';
                    
                    btn.addEventListener('click', () => {
                        const isCollapsed = btn.textContent === 'Show More';
                        if (isCollapsed) {
                            // Expand
                            for (let i = limit; i < children.length; i++) {
                                children[i].classList.remove('d-none');
                            }
                            btn.textContent = 'Show Less';
                            btn.classList.add('expanded');
                        } else {
                            // Collapse
                            for (let i = limit; i < children.length; i++) {
                                children[i].classList.add('d-none');
                            }
                            btn.textContent = 'Show More';
                            btn.classList.remove('expanded');
                            // Scroll back to container top smoothly
                            window.scrollTo({
                                top: container.getBoundingClientRect().top + window.scrollY - 120,
                                behavior: 'smooth'
                            });
                        }
                    });
                    
                    btnWrapper.appendChild(btn);
                    container.parentNode.insertBefore(btnWrapper, container.nextSibling);
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializePremiumPagination);
        } else {
            initializePremiumPagination();
        }
    </script>
    @endif
    @if($theme == 'classic')
    <script>
        // Smooth scrolling active link highlight
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section, header');
            const navLinks = document.querySelectorAll('.custom-classic-nav .nav-link');
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= sectionTop - 120) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Smooth Scroll for Navigation
        document.querySelectorAll('.custom-classic-nav .nav-link, .btn-outline-dark, .btn-dark').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 85,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Dynamic "Show More" pagination collapse behavior
        function initializeClassicPagination() {
            document.querySelectorAll('[data-limit]').forEach(container => {
                const limit = parseInt(container.getAttribute('data-limit'));
                const children = Array.from(container.children);
                if (children.length > limit) {
                    // Hide extra items
                    for (let i = limit; i < children.length; i++) {
                        children[i].classList.add('d-none');
                    }
                    
                    // Create Show More button
                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = 'text-center mt-4 w-100';
                    
                    const btn = document.createElement('button');
                    btn.className = 'btn btn-outline-dark px-4 py-2 rounded-pill fw-bold';
                    btn.textContent = 'Show More';
                    btn.style.transition = 'all 0.3s ease';
                    
                    btn.addEventListener('click', () => {
                        const isCollapsed = btn.textContent === 'Show More';
                        if (isCollapsed) {
                            // Expand
                            for (let i = limit; i < children.length; i++) {
                                children[i].classList.remove('d-none');
                            }
                            btn.textContent = 'Show Less';
                            btn.classList.remove('btn-outline-dark');
                            btn.classList.add('btn-dark');
                        } else {
                            // Collapse
                            for (let i = limit; i < children.length; i++) {
                                children[i].classList.add('d-none');
                            }
                            btn.textContent = 'Show More';
                            btn.classList.remove('btn-dark');
                            btn.classList.add('btn-outline-dark');
                            // Scroll back to container top smoothly
                            window.scrollTo({
                                top: container.getBoundingClientRect().top + window.scrollY - 120,
                                behavior: 'smooth'
                            });
                        }
                    });
                    
                    btnWrapper.appendChild(btn);
                    container.parentNode.insertBefore(btnWrapper, container.nextSibling);
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeClassicPagination);
        } else {
            initializeClassicPagination();
        }

        // Open Project Details Modal dynamically
        function openProjectDetailsModal(btn) {
            const title = btn.getAttribute('data-title');
            const desc = btn.getAttribute('data-desc');
            const image = btn.getAttribute('data-image');
            const tags = btn.getAttribute('data-tags').split(',').filter(t => t);

            // Populate Modal fields
            document.getElementById('projectModalTitle').textContent = title;
            document.getElementById('projectModalDesc').innerHTML = desc;

            // Handle image display dynamically
            const imgCol = document.getElementById('projectModalImgCol');
            const contentCol = document.getElementById('projectModalContentCol');
            const desktopImg = document.getElementById('projectModalImg');
            const mobileImgWrapper = document.getElementById('projectModalMobileImgWrapper');
            const mobileImg = document.getElementById('projectModalMobileImg');

            if (image) {
                // Show image columns and reset content column sizing
                if (imgCol) {
                    imgCol.classList.add('d-none');
                    imgCol.classList.add('d-md-block');
                }
                
                if (contentCol) {
                    contentCol.classList.remove('col-12');
                    contentCol.classList.add('col-md-7');
                }
                
                if (desktopImg) {
                    desktopImg.src = image;
                }
                
                if (mobileImgWrapper && mobileImg) {
                    mobileImgWrapper.classList.remove('d-none');
                    mobileImg.src = image;
                }
            } else {
                // Hide image columns and set content to full width
                if (imgCol) {
                    imgCol.classList.add('d-none');
                    imgCol.classList.remove('d-md-block');
                }
                
                if (contentCol) {
                    contentCol.classList.remove('col-md-7');
                    contentCol.classList.add('col-12');
                }
                
                if (desktopImg) {
                    desktopImg.src = '';
                }
                
                if (mobileImgWrapper && mobileImg) {
                    mobileImgWrapper.classList.add('d-none');
                    mobileImg.src = '';
                }
            }

            // Populate tags
            const tagsContainer = document.getElementById('projectModalTags');
            tagsContainer.innerHTML = '';
            tags.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'badge bg-light text-secondary border small';
                span.textContent = tag;
                tagsContainer.appendChild(span);
            });

            // Show Modal using Bootstrap modal library
            const projectModal = new bootstrap.Modal(document.getElementById('classicProjectModal'));
            projectModal.show();
        }
    </script>
    @endif
    @if($theme == 'elegant')
    <script>
        // Dropdown Click Toggle for Mobile Devices
        document.querySelectorAll('.elegant-dropdown .elegant-dropbtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const parent = this.closest('.elegant-dropdown');
                const isOpen = parent.classList.contains('open');
                document.querySelectorAll('.elegant-dropdown.open').forEach(d => d.classList.remove('open'));
                if (!isOpen) parent.classList.add('open');
            });
        });
        document.addEventListener('click', () => {
            document.querySelectorAll('.elegant-dropdown.open').forEach(d => d.classList.remove('open'));
        });

        // Mobile Menu Toggle
        const elegantMenuToggle = document.querySelector('.elegant-menu-toggle');
        const elegantNavMenu = document.querySelector('.elegant-nav ul');
        if (elegantMenuToggle && elegantNavMenu) {
            elegantMenuToggle.addEventListener('click', () => {
                elegantNavMenu.classList.toggle('active');
            });
        }

        // Open Elegant Project Details Modal
        function openElegantProjectModal(btn) {
            const title = btn.getAttribute('data-title');
            const desc = btn.getAttribute('data-desc');
            const image = btn.getAttribute('data-image');
            const tags = btn.getAttribute('data-tags').split(',').filter(t => t);

            document.getElementById('elegantModalTitle').textContent = title;
            document.getElementById('elegantModalDesc').innerHTML = desc;

            const tagsContainer = document.getElementById('elegantModalTags');
            tagsContainer.innerHTML = '';
            tags.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'elegant-proj-tag';
                span.textContent = tag;
                tagsContainer.appendChild(span);
            });

            const imgCol = document.getElementById('elegantModalImgCol');
            const contentCol = document.getElementById('elegantModalTextCol');
            const img = document.getElementById('elegantModalImg');

            if (image) {
                imgCol.style.display = 'flex';
                img.src = image;
                contentCol.style.flex = '1.2';
            } else {
                imgCol.style.display = 'none';
                img.src = '';
                contentCol.style.flex = '1';
            }

            const modal = document.getElementById('elegantProjectModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Custom Modal Close Events and Initialization
        function initializeElegantAll() {
            // Modal Close Events
            const modal = document.getElementById('elegantProjectModal');
            if (modal) {
                const closeBtn = modal.querySelector('.elegant-modal-close');
                const backdrop = modal.querySelector('.elegant-modal-backdrop');
                
                const closeModal = () => {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                };
                
                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (backdrop) backdrop.addEventListener('click', closeModal);
            }

            // Dynamic "Show More" pagination collapse behavior
            document.querySelectorAll('[data-limit]').forEach(container => {
                const limit = parseInt(container.getAttribute('data-limit'));
                const children = Array.from(container.children);
                if (children.length > limit) {
                    // Hide extra items
                    for (let i = limit; i < children.length; i++) {
                        children[i].classList.add('d-none');
                    }
                    
                    // Create Show More button
                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = 'text-center mt-2 w-100';
                    
                    const btn = document.createElement('button');
                    btn.className = 'elegant-show-more-btn';
                    btn.textContent = 'Show More';
                    
                    btn.addEventListener('click', () => {
                        const isCollapsed = btn.textContent === 'Show More';
                        if (isCollapsed) {
                            // Expand
                            for (let i = limit; i < children.length; i++) {
                                children[i].classList.remove('d-none');
                            }
                            btn.textContent = 'Show Less';
                            btn.classList.add('expanded');
                        } else {
                            // Collapse
                            for (let i = limit; i < children.length; i++) {
                                children[i].classList.add('d-none');
                            }
                            btn.textContent = 'Show More';
                            btn.classList.remove('expanded');
                            // Scroll back to container top smoothly
                            window.scrollTo({
                                top: container.getBoundingClientRect().top + window.scrollY - 120,
                                behavior: 'smooth'
                            });
                        }
                    });
                    
                    btnWrapper.appendChild(btn);
                    container.parentNode.insertBefore(btnWrapper, container.nextSibling);
                }
            });

            // Smooth scrolling active link highlight (ScrollSpy)
            window.addEventListener('scroll', () => {
                const sections = document.querySelectorAll('section, header');
                const navLinks = document.querySelectorAll('.elegant-nav ul li a');
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (window.scrollY >= sectionTop - 120) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });

            // Smooth Scroll navigation click
            document.querySelectorAll('.elegant-nav ul li a, .elegant-btn-solid, .elegant-btn-outline').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href.startsWith('#')) {
                        e.preventDefault();
                        const targetId = href.substring(1);
                        const targetElement = document.getElementById(targetId);
                        if (targetElement) {
                            // Close mobile menu if open
                            if (elegantNavMenu) elegantNavMenu.classList.remove('active');
                            
                            window.scrollTo({
                                top: targetElement.offsetTop - 85,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeElegantAll);
        } else {
            initializeElegantAll();
        }
    </script>
    @endif
@endpush
