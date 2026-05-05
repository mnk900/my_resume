@php
    $theme = $portfolio->theme ?? 'classic';
    
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
        'technical_skills' => $portfolio->skills->groupBy('category')->map(function($items) {
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
@endphp

@extends('portfolio.themes.' . $theme)

@section('content')
    @if($theme == 'premium')
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <nav>
        <div class="logo">
            @php
                $words = explode(' ', trim($user->name));
                $initials = '';
                foreach ($words as $w) $initials .= strtoupper($w[0][0]); // Bug fix: $w[0][0] or $w[0]?
                // Actually $w[0] is the first char of word.
                $initials = '';
                foreach ($words as $w) if($w) $initials .= strtoupper($w[0]);
            @endphp
            {{ $initials }}.
        </div>
        <ul>
            <li><a href="#hero">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#skills">Skills</a></li>
            <li><a href="#experience">Experience</a></li>
            <li><a href="#projects">Projects</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Academic <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                <div class="dropdown-content">
                    <a href="#skills-extra">Education</a>
                    <a href="#skills-extra">Achievements</a>
                    <a href="#contributions">Contributions</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Professional <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                <div class="dropdown-content">
                    <a href="#services">Services</a>
                    <a href="#trainings">Certifications</a>
                    <a href="#trainings">Trainings</a>
                    <a href="#testimonials">Testimonials</a>
                </div>
            </li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <section id="hero" class="hero">
        <div class="hero-content">
            <div class="hero-subtitle">{{ $profile['short_title'] }}</div>
            <h1>{{ $profile['name'] }}</h1>
            <p>{{ $profile['intro'] }}</p>
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
    </section>

    <section id="about" class="reveal">
        <h2 class="section-title">Profile</h2>
        <div style="max-width: 800px; color: var(--text-secondary); font-size: 1.1rem;">
            <p>{{ $profile['detailed_profile'] }}</p>
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="skills" class="reveal">
        <h2 class="section-title">Technical Expertise</h2>
        <div class="skills-grid">
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
                <p class="text-muted">No skills added yet.</p>
            @endforelse
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="services" class="reveal">
        <h2 class="section-title">Services Offered</h2>
        <div class="skills-grid">
            @forelse($portfolio->services as $service)
            <div class="skill-card">
                <h3>{{ $service->title }}</h3>
                <p style="color: var(--text-secondary); font-size: 0.95rem;">{{ $service->description }}</p>
            </div>
            @empty
                <p class="text-muted">No services listed yet.</p>
            @endforelse
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="experience" class="reveal">
        <h2 class="section-title">Work Experience</h2>
        <div class="timeline">
            @forelse($profile['experience'] as $exp)
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="exp-date">{{ $exp['date'] }}</div>
                <div class="exp-job">{{ $exp['title'] }}</div>
                <div class="exp-company">{{ $exp['company'] }}</div>
                <ul class="exp-details">
                    <li>{{ $exp['highlights'] }}</li>
                </ul>
            </div>
            @empty
                <p class="text-muted">No experience added yet.</p>
            @endforelse
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="skills-extra" class="reveal">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
            <div>
                <h3 style="margin-bottom: 2rem; color: var(--accent-color);">Soft Skills & Achievements</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    @foreach($profile['soft_skills'] as $skill)
                    <span class="tag" style="font-size: 0.9rem; padding: 10px 20px;">{{ $skill }}</span>
                    @endforeach
                    @if(empty($profile['soft_skills']))
                        <p class="text-muted">No achievements added.</p>
                    @endif
                </div>
            </div>
            <div>
                <h3 style="margin-bottom: 2rem; color: var(--accent-color);">Education</h3>
                @forelse($profile['education'] as $edu)
                <div class="skill-card" style="margin-bottom: 1rem;">
                    <div style="font-weight: 700; font-size: 1.1rem;">{{ $edu['degree'] }}</div>
                    <div style="color: var(--accent-color); font-size: 0.9rem; margin-bottom: 0.5rem;">{{ $edu['institution'] }}</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">{{ $edu['date'] }} | {{ $edu['result'] }}</div>
                </div>
                @empty
                    <p class="text-muted">No education added.</p>
                @endforelse
            </div>
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="trainings" class="reveal">
        <h2 class="section-title">Certifications & Trainings</h2>
        <div class="skills-grid">
            <div class="skill-card">
                <h3>Certifications</h3>
                <ul class="skill-list">
                    @forelse($profile['certifications'] as $cert)
                    <li><i class="fas fa-certificate"></i> {{ $cert }}</li>
                    @empty
                        <li>No certifications</li>
                    @endforelse
                </ul>
            </div>
            <div class="skill-card">
                <h3>Trainings</h3>
                <ul class="skill-list">
                    @forelse($profile['trainings'] as $training)
                    <li><i class="fas fa-chalkboard-teacher"></i> {{ $training }}</li>
                    @empty
                        <li>No trainings registered</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="projects" class="reveal">
        <h2 class="section-title">Flagship Projects</h2>
        <div class="projects-grid">
            @forelse($profile['projects'] as $project)
            <div class="project-card">
                <div class="project-img">
                    @if($project['image'])
                        <img src="{{ Storage::url($project['image']) }}" alt="{{ $project['name'] }}">
                    @else
                        <div style="height: 100%; width: 100%; background: #222; display: flex; align-items: center; justify-content: center; color: #444;">No Image</div>
                    @endif
                </div>
                <div class="project-content" style="padding: 30px;">
                    <div class="project-tags">
                        @foreach($project['tags'] as $tag)
                        <span class="tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <h3>{{ $project['name'] }}</h3>
                    <p>{{ $project['description'] }}</p>
                </div>
            </div>
            @empty
                <p class="text-muted col-12">No projects showcased yet.</p>
            @endforelse
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="contributions" class="reveal">
        <h2 class="section-title">Contributions</h2>
        <div class="skills-grid">
            @forelse($portfolio->contributions as $contrib)
            <div class="skill-card">
                <h3>{{ $contrib->title }}</h3>
                <p style="color: var(--text-secondary); font-size: 0.95rem;">{{ $contrib->description }}</p>
            </div>
            @empty
                <p class="text-muted">No contributions listed.</p>
            @endforelse
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="testimonials" class="reveal">
        <h2 class="section-title">Testimonials</h2>
        <div class="projects-grid">
            @forelse($portfolio->testimonials as $testi)
            <div class="skill-card">
                <p style="font-style: italic; color: var(--text-secondary); margin-bottom: 1.5rem;">"{{ $testi->content }}"</p>
                <div style="font-weight: 700; color: var(--accent-color);">— {{ $testi->client_name }}</div>
            </div>
            @empty
                <p class="text-muted text-center col-12">No testimonials yet.</p>
            @endforelse
        </div>
    </section>

    <div class="section-divider"><span></span></div>

    <section id="contact" class="reveal">
        <h2 class="section-title">Get In Touch</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Contact Details</h3>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Email</div>
                        <div style="font-weight: 600;">{{ $profile['email'] }}</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Contact</div>
                        <div style="font-weight: 600;">{{ $profile['phone'] }}</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-location-dot"></i></div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Location</div>
                        <div style="font-weight: 600;">{{ $profile['location'] }}</div>
                    </div>
                </div>
            </div>
            <div class="contact-form" style="background: var(--glass-bg); padding: 2rem; border-radius: 20px; border: 1px solid var(--glass-border);">
                <h4 style="margin-bottom: 2rem; color: #fff;">Send a Message</h4>
                @if(session('status') == 'message-sent')
                    <div style="background: rgba(0, 242, 255, 0.1); color: var(--accent-color); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; border: 1px solid var(--glass-border);">
                        Message sent successfully! I'll get back to you soon.
                    </div>
                @endif
                <form action="{{ url('/contact/submit/' . $portfolio->id . '/') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                    @csrf
                    <input type="text" name="name" placeholder="Your Name" required style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1rem; color: #fff; border-radius: 10px;">
                    <input type="email" name="email" placeholder="Your Email" required style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1rem; color: #fff; border-radius: 10px;">
                    <textarea name="message" placeholder="Your Message" rows="5" required style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1rem; color: #fff; border-radius: 10px;"></textarea>
                    <button type="submit" class="btn-primary" style="border: none; cursor: pointer;">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-nav">
            <a href="#hero">Home</a>
            <a href="#about">About</a>
            <a href="#skills">Skills</a>
            <a href="#experience">Experience</a>
            <a href="#projects">Projects</a>
            <a href="#contact">Contact</a>
        </div>
        <p class="mb-0">&copy; {{ now()->year }} {{ $profile['name'] }}. All rights reserved.</p>
    </footer>
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
        section { padding: 64px 10%; }
        .hero { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 0 10%; gap: 4rem; }
        .hero-content { flex: 1; }
        .hero-image { flex: 0 0 400px; height: 400px; border-radius: 30px; overflow: hidden; border: 4px solid var(--glass-border); box-shadow: var(--card-shadow); }
        .hero-image img { width: 100%; height: 100%; object-fit: cover; }
        .hero-subtitle { color: var(--accent-color); font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 2px; }
        .hero h1 { font-family: 'Outfit', sans-serif; font-size: clamp(2.5rem, 6vw, 4.5rem); line-height: 1.1; margin-bottom: 1.5rem; font-weight: 700; }
        .btn-primary { background: var(--accent-color); color: #000; padding: 1rem 2rem; border-radius: 5px; text-decoration: none; font-weight: 700; display: inline-block; transition: var(--transition); }
        .section-title { font-family: 'Outfit', sans-serif; font-size: 2.5rem; margin-bottom: 2rem; position: relative; display: inline-block; }
        .section-title::after { content: ''; position: absolute; bottom: -10px; left: 0; width: 60%; height: 4px; background: var(--accent-color); }
        .skills-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; }
        .skill-card { background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 2rem; border-radius: 15px; transition: var(--transition); }
        .skill-card h3 { color: var(--accent-color); margin-bottom: 1rem; }
        .timeline { position: relative; max-width: 800px; }
        .timeline-item { margin-bottom: 4rem; padding-left: 3rem; position: relative; }
        .timeline-item::before { content: ''; position: absolute; left: 0; top: 0; width: 2px; height: 100%; background: var(--glass-border); }
        .timeline-dot { position: absolute; left: -5px; top: 5px; width: 12px; height: 12px; background: var(--accent-color); border-radius: 50%; box-shadow: 0 0 10px var(--accent-color); }
        .exp-date { font-weight: 600; color: var(--accent-color); margin-bottom: 0.5rem; }
        .projects-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; }
        .project-card { background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; transition: var(--transition); }
        .project-img { width: 100%; height: 250px; background: #1a1a1a; overflow: hidden; }
        .project-img img { width: 100%; height: 100%; object-fit: cover; }
        .tag { background: rgba(0, 242, 255, 0.1); color: var(--accent-color); padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; margin-right: 5px; }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .bg-blob { position: fixed; width: 500px; height: 500px; background: radial-gradient(circle, rgba(0, 242, 255, 0.1) 0%, transparent 70%); filter: blur(80px); z-index: -1; border-radius: 50%; }
        .blob-1 { top: -100px; right: -100px; }
        .blob-2 { bottom: -100px; left: -100px; }
        nav { position: fixed; top: 0; width: 100%; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; z-index: 1000; background: rgba(5, 5, 5, 0.8); backdrop-filter: blur(10px); border-bottom: 1px solid var(--glass-border); box-sizing: border-box; }
        nav ul { display: flex; list-style: none; gap: 1.2rem; margin: 0; padding: 0; }
        nav ul li a { text-decoration: none; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; transition: var(--transition); }
        .logo { font-size: 1.5rem; font-weight: 700; color: var(--accent-color); }
        
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
            padding-top: 8px;  /* bridge the gap - no margin-top */
            overflow: visible;
        }
        /* invisible bridge so mouse doesn't exit hover zone */
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
        .dropdown-content a:first-child { border-radius: 10px 10px 0 0; }
        .dropdown-content a:last-child  { border-radius: 0 0 10px 10px; }
        .dropdown-content a:hover {
            background: rgba(0, 242, 255, 0.12);
            color: var(--accent-color);
        }
        .dropdown:hover .dropdown-content,
        .dropdown.open .dropdown-content { display: block; }
        .dropdown:hover .dropbtn,
        .dropdown.open .dropbtn { color: var(--accent-color); }

        .contact-container { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .contact-item { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem; }
        .contact-icon { width: 50px; height: 50px; background: var(--glass-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent-color); }
        footer { padding: 4rem 10%; text-align: center; border-top: 1px solid var(--glass-border); color: var(--text-secondary); }
        .footer-nav { display: flex; justify-content: center; flex-wrap: wrap; gap: 1.2rem; margin-bottom: 1rem; }
        .footer-nav a { text-decoration: none; color: var(--text-secondary); font-weight: 500; transition: var(--transition); }
        .footer-nav a:hover { color: var(--accent-color); }

        /* Section Divider */
        .section-divider {
            padding: 0 10%;
            display: flex;
            align-items: center;
            gap: 1.5rem;
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
            if (window.scrollY > 50) {
                nav.style.padding = '0.8rem 5%';
                nav.style.background = 'rgba(5, 5, 5, 0.95)';
            } else {
                nav.style.padding = '1rem 5%';
                nav.style.background = 'rgba(5, 5, 5, 0.8)';
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
    </script>
    @endif
@endpush
