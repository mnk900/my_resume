<footer class="bg-white border-top py-4 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="{{ route('welcome') }}" class="d-inline-flex align-items-center mb-2 text-decoration-none">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="MyResume.cloud" style="height: 38px;" class="rounded shadow-sm me-2">
                </a>
                <p class="text-secondary small mb-3 lh-normal">
                    Professional identity, opportunities, and talent discovery in one connected platform. Build your verified portfolio, discover matched opportunities, and connect with top organizations.
                </p>
                <div class="d-flex gap-3 text-muted">
                    <a href="https://linkedin.com" target="_blank" class="text-secondary hover-primary"><i class="fa-brands fa-linkedin fa-lg"></i></a>
                    <a href="https://twitter.com" target="_blank" class="text-secondary hover-primary"><i class="fa-brands fa-x-twitter fa-lg"></i></a>
                    <a href="https://facebook.com" target="_blank" class="text-secondary hover-primary"><i class="fa-brands fa-facebook fa-lg"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-dark mb-2 small">Platform</h6>
                <ul class="list-unstyled d-flex flex-column gap-1 small">
                    <li><a href="{{ route('talent.index') }}" class="text-secondary text-decoration-none hover-primary">Professionals</a></li>
                    <li><a href="{{ route('opportunities.index') }}" class="text-secondary text-decoration-none hover-primary">Opportunities</a></li>
                    <li><a href="{{ route('companies.index') }}" class="text-secondary text-decoration-none hover-primary">Companies</a></li>
                    <li><a href="{{ route('feed.index') }}" class="text-secondary text-decoration-none hover-primary">Network</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-dark mb-2 small">Professionals</h6>
                <ul class="list-unstyled d-flex flex-column gap-1 small">
                    <li><a href="{{ route('register') }}" class="text-secondary text-decoration-none hover-primary">Create Portfolio</a></li>
                    <li><a href="{{ route('applications.candidate.index') }}" class="text-secondary text-decoration-none hover-primary">My Applications</a></li>
                    <li><a href="{{ route('mock-interviews.index') }}" class="text-secondary text-decoration-none hover-primary">AI Mock Interviews</a></li>
                    <li><a href="{{ route('preferences.edit') }}" class="text-secondary text-decoration-none hover-primary">Career Preferences</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-dark mb-2 small">Companies</h6>
                <ul class="list-unstyled d-flex flex-column gap-1 small">
                    <li><a href="{{ route('companies.create') }}" class="text-secondary text-decoration-none hover-primary">Register Company</a></li>
                    <li><a href="{{ route('opportunities.create') }}" class="text-secondary text-decoration-none hover-primary">Post a Job</a></li>
                    <li><a href="{{ route('talent.index') }}" class="text-secondary text-decoration-none hover-primary">Find Talent</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-dark mb-2 small">Support</h6>
                <ul class="list-unstyled d-flex flex-column gap-1 small">
                    <li><a href="{{ route('welcome') }}#contact" class="text-secondary text-decoration-none hover-primary">Contact</a></li>
                    <li><a href="{{ route('restricted') }}" class="text-secondary text-decoration-none hover-primary">Privacy Policy</a></li>
                    <li><a href="{{ route('restricted') }}" class="text-secondary text-decoration-none hover-primary">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-3 mt-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small" style="font-size: 0.78rem;">
            <p class="mb-0">&copy; {{ date('Y') }} MyResume.cloud. All rights reserved.</p>
            <p class="mb-0">Designed for Professionals & Organizations Globally.</p>
        </div>
    </div>
</footer>
