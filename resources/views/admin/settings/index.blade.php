@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-sliders me-2 text-primary"></i> System Settings & Announcements</h1>
        <p class="text-secondary small mb-0">Configure global platform settings, manage active portfolio themes, and dispatch system announcements.</p>
    </div>
</div>

<div class="row g-4">
    @if(session('ai_mock_message'))
        <div class="col-12">
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-0">
                <i class="fa-solid fa-circle-check text-success fa-xl me-3"></i>
                <div>
                    <h6 class="fw-bold mb-0">System Control Status Updated</h6>
                    <small>{{ session('ai_mock_message') }}</small>
                </div>
            </div>
        </div>
    @endif

    <!-- Global Module Controls: AI Mock Interview -->
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-white p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning-subtle text-warning p-3">
                        <i class="fa-solid fa-robot fa-2xl"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">AI Mock Interview Master Control</h5>
                        <p class="text-secondary small mb-0">
                            Single master control toggle. Disabling hides AI Mock Interview from all navigation bars, footers, candidate portfolio workspace tabs, job detail practice cards, and blocks direct route access across the entire platform.
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    @php $isAiMockEnabled = \App\Models\SystemSetting::isAiMockEnabled(); @endphp
                    <span class="badge {{ $isAiMockEnabled ? 'bg-success' : 'bg-danger' }} px-3 py-2 fs-6 rounded-pill">
                        <i class="fa-solid {{ $isAiMockEnabled ? 'fa-circle-check' : 'fa-eye-slash' }} me-1"></i>
                        {{ $isAiMockEnabled ? 'FEATURE ENABLED' : 'FEATURE HIDDEN & DISABLED' }}
                    </span>
                    <form action="{{ route('admin.settings.toggle-ai-mock') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $isAiMockEnabled ? 'btn-outline-danger' : 'btn-success' }} px-4 fw-bold rounded-pill shadow-sm" onclick="return confirm('Are you sure you want to {{ $isAiMockEnabled ? 'DISABLE and HIDE' : 'ENABLE and SHOW' }} AI Mock Interviews globally?');">
                            <i class="fa-solid {{ $isAiMockEnabled ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i>
                            {{ $isAiMockEnabled ? 'Disable & Hide AI Mock' : 'Enable & Show AI Mock' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Broadcast Announcement Dispatcher -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm bg-white p-3 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-bullhorn text-primary me-2"></i> System Broadcast Announcement Dispatcher</h6>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" value="broadcast">
                <div class="mb-3">
                    <label class="form-label">Announcement Target Audience</label>
                    <select name="audience" class="form-select">
                        <option value="all">All Platform Users (Professionals & Companies)</option>
                        <option value="professionals">All Registered Professionals</option>
                        <option value="companies">All Verified Companies</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Broadcast Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Important announcement title..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Announcement Content</label>
                    <textarea name="message" class="form-control" rows="4" placeholder="Enter full announcement text..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4" onclick="return confirm('Send this broadcast notification across the platform?');"><i class="fa-solid fa-paper-plane me-1"></i> Dispatch Broadcast</button>
            </form>
        </div>
    </div>

    <!-- Portfolio Themes Management -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm bg-white p-3 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-palette text-info me-2"></i> Portfolio Theme Engine Toggles</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Theme Name</th>
                            <th>Slug</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($themes as $theme)
                        <tr>
                            <td class="fw-semibold text-dark small">{{ $theme->name }}</td>
                            <td><span class="badge bg-secondary-subtle text-dark font-monospace">{{ $theme->slug }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('admin.themes.toggle', $theme->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $theme->is_active ? 'btn-success' : 'btn-outline-secondary' }} py-0 px-2 rounded-pill" style="font-size: 0.72rem;">
                                        {{ $theme->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No theme records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Add Theme Form -->
            <form action="{{ route('admin.themes.store') }}" method="POST" class="mt-3 border-top pt-3">
                @csrf
                <h6 class="fw-bold text-dark mb-2 small">+ Register New Theme</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Theme Name" required>
                    </div>
                    <div class="col-6">
                        <input type="text" name="slug" class="form-control form-control-sm" placeholder="Slug (e.g. classic)" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill mt-2 w-100">Add Theme</button>
            </form>
        </div>
    </div>
</div>
@endsection
