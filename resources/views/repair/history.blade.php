<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Repair History</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>
    @include('partials/bootstrap')
</head>

<body class="sb-nav-fixed">
    @include('partials/navigation-bar')

    <div id="layoutSidenav">
        @include('partials/sidebar')

        <div id="layoutSidenav_content">
            <main>
                <div class="container py-5">
                    <h1 class="mb-4 text-center">Repair History</h1>

                    <!-- Search -->
                    <form method="GET" action="{{ route('repairs.history') }}" class="row g-3 mb-4">
                        <div class="col-md-10">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control" placeholder="Search by issue, description, or device...">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-dark" type="submit">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>

                    <!-- Tabs with badges -->
                    @php
                    $active = request('status') ?? 'all';
                    $statusList = [
                    'all' => 'All',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    ];
                    @endphp

                    <ul class="nav nav-tabs mb-4">
                        @foreach($statusList as $key => $label)
                        @php
                        $badgeColor = match($key) {
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'light'
                        };
                        @endphp

                        <li class="nav-item">
                            <a href="{{ route('repairs.history', array_merge(request()->except('page'), ['status' => $key === 'all' ? null : $key])) }}"
                                class="nav-link {{ $active === $key ? 'active' : '' }}">
                                {{ $label }}
                                <span class="badge bg-{{ $badgeColor }} ms-1">
                                    {{ $statusCounts[$key] ?? 0 }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Issues</th>
                                    <th>Breakdown</th>
                                    <th>Device</th>
                                    <th>Device Type</th>
                                    <th>Status</th>
                                    <th>Completed On</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repair as $index => $r)
                                <tr>
                                    <td>{{ $repair->firstItem() + $index }}</td>
                                    <td>
                                        @php
                                        // 1. Decode the JSON string into a PHP associative array
                                        $issues = json_decode($r->issues, true);
                                        @endphp

                                        @if (!empty($issues))
                                        {{-- 2. Wrap the list in an unordered list (<ul>) with minimal styling --}}
                                        <ul style="padding-left: 15px; margin-bottom: 0;">
                                            @foreach ($issues as $issue_item)
                                            {{-- 3. Each issue becomes a list item (<li>) --}}
                                            <li><strong>{{ $issue_item['issue'] }}</strong></li>
                                            @endforeach
                                        </ul>
                                        @else
                                        No Issues Recorded
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        // 1. Decode the JSON string into a PHP associative array
                                        $breakdown_items = json_decode($r->breakdown, true);
                                        @endphp

                                        @if (!empty($breakdown_items))
                                        {{-- 2. Use <ul> with CSS to remove bullet icons and ensure clean alignment --}}
                                        <ul style="padding-left: 15px; margin-bottom: 0;">
                                            @foreach ($breakdown_items as $item)
                                            <li>
                                                {{-- 3. Use <strong> for proper HTML bolding --}}
                                                <strong>{{ $item['item'] }}</strong>: ₱{{ number_format($item['price'], 2) }}
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        No Breakdown Details
                                        @endif
                                    </td>
                                    <td>{{ $r->device }}</td>
                                    <td>{{ $r->device_type }}</td>
                                    <td>
                                        @switch($r->status)
                                        @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                        @case('declined')
                                        <span class="badge bg-secondary">Declined</span>
                                        @break
                                        @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                        @endswitch
                                    </td>
                                    <td>{{ optional($r->updated_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('repairs.show', $r->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No repair history found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($repair->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Showing {{ $repair->firstItem() }} to {{ $repair->lastItem() }} of {{ $repair->total() }} repairs
                        </small>
                        {{ $repair->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </main>
            @include('partials/footer')
        </div>
    </div>
</body>

</html>