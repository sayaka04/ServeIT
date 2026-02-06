@isset($technicians)
{{-- Results --}}
@if (isset($message))
{{-- Message for invalid params or initial state --}}
<div class="alert alert-info text-center shadow-sm" role="alert">
    <i class="fas fa-info-circle me-2"></i> {{ $message }}
</div>
@else
{{-- Results Header --}}
<h4 class="mb-4 text-primary">
    Found <span class="fw-bold">{{ $technicians->total() }}</span> Technicians
    <small class="text-secondary fw-normal">(Page {{ $technicians->currentPage() }} of {{ $technicians->lastPage() }})</small>
</h4>

<div class="d-grid gap-3" id="search-result">

    @forelse ($technicians as $technician)
    <div class="card technician-card shadow-sm border-start border-4 border-primary p-4 hover:shadow-lg transition">

        {{-- Row 1: Name, Code, Address, and Final Score --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-dark me-3">
                    {{ $technician->technician_full_name ?? 'N/A' }}
                    {{-- <small class="text-muted fw-normal">({{ $technician->technician_code ?? 'N/A' }})</small>
                    --}}
                </h5>
                @if ($technician->tesda_verified)
                <span class="badge bg-success me-2"><i class="fas fa-check-circle"></i> Verified</span>
                @endif
            </div>
            {{--
                        <div class="final-score text-end">
                            <small class="text-uppercase text-secondary d-block lh-1">Final Rank Score</small>
                            <span class="fs-4 text-primary fw-bold lh-1">{{ number_format($technician->final_weighted_score ?? 0, 5) }}</span>
        </div>
        --}}
    </div>

    {{-- NEW: Expertise List --}}
    <div class="mb-3">
        @if(isset($technician->expertises) && count($technician->expertises) > 0)
        <div class="d-flex flex-wrap gap-1 align-items-center">
            <small class="text-secondary me-2 fw-semibold" style="font-size: 0.85rem;">Expertise:</small>
            @foreach($technician->expertises as $expertise)
            <span class="badge rounded-pill bg-light text-dark border border-secondary-subtle fw-normal">
                {{ $expertise->name }}
            </span>
            @endforeach
        </div>
        @else
        <small class="text-muted fst-italic">No specific expertise listed</small>
        @endif
    </div>
    {{-- END NEW --}}

    <hr class="mt-0 mb-3">

    {{-- Row 2: Core Metrics (Rating, Distance, Jobs, Success Rate) --}}
    <div class="d-flex flex-wrap gap-3 mb-3">

        {{-- Rating --}}
        <span class="badge bg-warning text-dark p-2">
            <i class="fas fa-star me-1"></i>
            Rating: <span class="fw-bold">{{ number_format($technician->weighted_score_rating ?? 0, 2) }}</span>
        </span>

        {{-- Distance --}}
        <span class="badge bg-secondary p-2">
            <i class="fas fa-map-marker-alt me-1"></i>
            @if (isset($technician->distance_km))
            <span class="fw-bold">{{ number_format($technician->distance_km, 2) }} km</span> away
            @else
            Distance N/A
            @endif
        </span>

        {{-- Jobs Completed --}}
        <span class="badge bg-info text-dark p-2">
            <i class="fas fa-briefcase me-1"></i>
            Jobs Completed: <span class="fw-bold">{{ number_format($technician->jobs_completed ?? 0, 0) }}</span>
        </span>

        {{-- Success Rate --}}
        <span class="badge bg-success p-2">
            <i class="fas fa-percent me-1"></i>
            Success Rate: <span class="fw-bold">{{ number_format($technician->success_rate ?? 0, 2) }}%</span>
        </span>

        {{-- Home Service --}}
        @if ($technician->home_service)
        <span class="badge bg-primary p-2">
            <i class="fas fa-home me-1"></i> Home Service
        </span>
        @endif
    </div>

    {{-- Row 3: Normalized Scores Breakdown (Detailed Metrics)
                    <div class="score-breakdown bg-light p-3 rounded mb-3">
                        <p class="mb-2 fw-bold text-dark"><i class="fas fa-balance-scale-left me-1"></i> Normalized Scores Breakdown:</p>
                        <div class="row row-cols-md-3 g-2 small text-muted">
                            <div class="col">
                                <i class="fas fa-trophy text-warning"></i> Rating (x0.21):
                                <span class="fw-semibold">{{ number_format($technician->normalized_overall_rating_score ?? 0, 4) }}</span>
</div>
<div class="col">
    <i class="fas fa-route text-info"></i> Proximity (x0.17):
    <span class="fw-semibold">{{ number_format($technician->normalized_proximity_score ?? 0, 4) }}</span>
</div>
<div class="col">
    <i class="fas fa-certificate text-success"></i> TESDA (x0.21):
    <span class="fw-semibold">{{ number_format($technician->normalized_tesda_certification_score ?? 0, 4) }}</span>
</div>
<div class="col">
    <i class="fas fa-clock text-primary"></i> Availability (x0.12):
    <span class="fw-semibold">{{ number_format($technician->normalized_availability_score ?? 0, 4) }}</span>
</div>
<div class="col">
    <i class="fas fa-thumbs-up text-success"></i> Success Rate (x0.18):
    <span class="fw-semibold">{{ number_format($technician->normalized_success_rate_score ?? 0, 4) }}</span>
</div>
<div class="col">
    <i class="fas fa-check text-secondary"></i> Jobs (x0.11):
    <span class="fw-semibold">{{ number_format($technician->normalized_jobs_completed_score ?? 0, 4) }}</span>
</div>
</div>
</div>
--}}

{{-- Row 4: Footer - Address and Profile Button --}}
<div class="d-flex justify-content-between align-items-center">
    <small class="text-muted"><i class="fas fa-map-pin me-1"></i> Address: {{ $technician->address ?? 'N/A' }}</small>
    <a href="{{ route('technicians.show', $technician->technician_code ?? '#') }}" class="btn btn-sm btn-primary">
        View Profile <i class="fas fa-arrow-circle-right ms-1"></i>
    </a>
</div>
</div>

@empty
{{-- No results found --}}
<div class="alert alert-warning text-center shadow-sm" role="alert">
    <i class="fas fa-search-minus me-2"></i> No technicians found matching the current criteria.
</div>
@endforelse
</div>

<br><br><br>
<div id="paginationContainer" class="col-12 mt-3">
    @if ($technicians->lastPage() > 1)
    {!! $technicians->links('pagination::bootstrap-5') !!}
    @endif
</div>

@endif
@endisset