<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                    <div>
                        <h1 class="h3 fw-bold text-dark mb-1">Account & Security Settings ⚙️</h1>
                        <p class="text-secondary small mb-0">Manage your login password, security credentials, and account details.</p>
                    </div>
                    <div>
                        <a href="{{ route('portfolio.edit') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Portfolio CMS
                        </a>
                    </div>
                </div>

                @include('profile.partials.update-password-form')
                @include('profile.partials.update-profile-information-form')
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
