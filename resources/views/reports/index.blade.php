<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reports - Admin</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />

    <script defer src="{{ asset('js/scripts.js') }}"></script>

    @include('partials/bootstrap')
</head>

<body class="sb-nav-fixed" data-boundary-url="{{ route('haversine.boundary') }}" data-result-url="{{ route('technicians.search.result') }}">

    @include('partials/navigation-bar')

    <div id="layoutSidenav">
        @include('partials/sidebar')

        <div id="layoutSidenav_content">
            <main>
                <div class="container py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Reports</h1>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Status Summary -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="card border-warning shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-warning mb-1 text-uppercase">Pending</h6>
                                    <div class="fs-5 fw-bold">{{ $pendingCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card border-info shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-info mb-1 text-uppercase">Under Review</h6>
                                    <div class="fs-5 fw-bold">{{ $reviewCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card border-success shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-success mb-1 text-uppercase">Resolved</h6>
                                    <div class="fs-5 fw-bold">{{ $resolvedCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card border-secondary shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-1 text-uppercase">Closed</h6>
                                    <div class="fs-5 fw-bold">{{ $closedCount }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter/Search -->
                    <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end mb-4">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">-- All Statuses --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search by Name</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Reporter or Reported User">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>

                    @if(request('status') || request('search'))
                    <div class="mb-3">
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle"></i> Clear Filters
                        </a>
                    </div>
                    @endif

                    <!-- Reports Table -->
                    <div class="table-responsive shadow-sm">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Reporter</th>
                                    <th>Reported User</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th style="width: 250px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td>
                                        {{ $report->reporter->first_name }} {{ $report->reporter->middle_name }} {{ $report->reporter->last_name }}
                                        <a href="{{ route('reports.userHistory', $report->reporter) }}" class="btn btn-sm btn-info text-white" title="Reporter History">
                                            <i class="bi bi-clock-history"></i> </a>
                                    </td>
                                    <td>
                                        {{ $report->reportedUser->first_name }} {{ $report->reportedUser->middle_name }} {{ $report->reportedUser->last_name }}
                                        <a href="{{ route('reports.userHistory', $report->reportedUser) }}" class="btn btn-sm btn-info text-white" title="Reported User History">
                                            <i class="bi bi-clock-history"></i> </a>
                                    </td>
                                    <td>{{ $report->category->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase">{{ $report->status }}</span>
                                    </td>
                                    <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-info text-white" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            {{--
                                            <!-- this is now replaced by edit, having the title "View" Now  -->
                                            <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info text-white" title="View">
                                            </a>

                                            <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this report?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            --}}

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No reports found.</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($reports->count())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-muted mb-0">
                            Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} reports
                        </p>
                        <div>
                            {{ $reports->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif

                </div>
            </main>

            @include('partials/footer')
        </div>
    </div>
</body>

</html>