@if(Auth::user()->is_admin)
<!-- 1st Row: Admin Dashboard Info Cards -->
<div class="row">
    <!-- Total Reports -->
    <div class="col-md-12 col-lg-4 mb-4">
        <div class="card text-dark h-100 shadow-sm border border-primary bg-primary-subtle">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Total Reports</h5>
                <p class="card-text display-4 fw-bold text-end">{{ $reports_count }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Reports -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card text-dark h-100 shadow-sm border border-warning bg-warning-subtle">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Pending</h5>
                <p class="card-text display-4 fw-bold text-end">{{ $reports_pending_count }}</p>
            </div>
        </div>
    </div>

    <!-- Under Review Reports -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card text-dark h-100 shadow-sm border border-info bg-info-subtle">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Under Review</h5>
                <p class="card-text display-4 fw-bold text-end">{{ $reports_review_count }}</p>
            </div>
        </div>
    </div>
</div>


<div class="row mb-4">

    <!-- Resolved Reports -->
    <div class="col-md-6 col-lg-6 mb-4">
        <div class="card text-dark h-100 shadow-sm border border-success bg-success-subtle">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Resolved</h5>
                <p class="card-text display-4 fw-bold text-end">{{ $reports_resolved_count }}</p>
            </div>
        </div>
    </div>

    <!-- Closed Reports -->
    <div class="col-md-6 col-lg-6 mb-4">
        <div class="card text-dark h-100 shadow-sm border border-secondary bg-secondary-subtle">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Closed</h5>
                <p class="card-text display-4 fw-bold text-end">{{ $reports_closed_count }}</p>
            </div>
        </div>
    </div>
</div>
@endif