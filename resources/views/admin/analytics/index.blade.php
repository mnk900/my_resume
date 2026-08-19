@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-chart-pie me-2 text-primary"></i> Platform Analytics Hub</h1>
        <p class="text-secondary small mb-0">System metrics, user registration trends, location distribution, and skill demand insights.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- User Registration Trend -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100 p-3">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-plus me-2 text-primary"></i> Daily User Registrations (Past 14 Days)</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th class="text-end">New Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userGrowth as $growth)
                        <tr>
                            <td class="small text-dark">{{ $growth->date }}</td>
                            <td class="text-end fw-bold text-primary">{{ $growth->count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No registration data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Companies by Industry -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100 p-3">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-building me-2 text-info"></i> Top Companies by Industry</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Industry Sector</th>
                            <th class="text-end">Companies Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companiesByIndustry as $ind)
                        <tr>
                            <td class="small text-dark fw-semibold">{{ $ind->industry }}</td>
                            <td class="text-end fw-bold text-info">{{ $ind->count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No industry distribution data yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Opportunities by Location -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100 p-3">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-location-dot me-2 text-success"></i> Opportunities by City / Location</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>City / Location</th>
                            <th class="text-end">Jobs Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobsByLocation as $loc)
                        <tr>
                            <td class="small text-dark">{{ $loc->city }}</td>
                            <td class="text-end fw-bold text-success">{{ $loc->count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No location data yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Skills Demand -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100 p-3">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-code me-2 text-warning"></i> Most In-Demand Skills</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Skill Name</th>
                            <th class="text-end">Occurrences</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSkills as $skill)
                        <tr>
                            <td class="small text-dark fw-semibold">{{ $skill->name }}</td>
                            <td class="text-end fw-bold text-warning">{{ $skill->count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No skill statistics recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
