<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>User List (Admin)</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/shortened-sidebar.css') }}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>

    @include('partials/bootstrap')
</head>

<body class="sb-nav-fixed" data-boundary-url="{{ route('haversine.boundary') }}" data-result-url="{{ route('technicians.search.result') }}">
    @include('partials/navigation-bar')

    <div id="layoutSidenav">
        @include('partials/sidebar')

        <div id="layoutSidenav_content">
            <main>
                <div class="container py-5">
                    <h1 class="mb-4 text-center">Users</h1>

                    <!-- Filter + Search -->
                    <form method="GET" action="{{ route('users.index') }}" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="role" class="form-label">Filter by Role</label>
                            <select name="role" id="role" class="form-select" onchange="this.form.submit()">
                                <option value="">-- All --</option>
                                <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Client</option>
                                <option value="technician" {{ request('role') === 'technician' ? 'selected' : '' }}>Technician</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="search" class="form-label">Search by Name</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Enter name...">
                        </div>

                        <div class="col-md-2 d-grid align-items-end">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>

                    @if(request('search') || request('role'))
                    <div class="mb-3">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Clear Filters
                        </a>
                    </div>
                    @endif

                    <!-- User Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_technician)
                                        <span class="badge bg-primary">Technician</span>
                                        @else
                                        <span class="badge bg-secondary">Client</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_banned)
                                        <span class="badge bg-danger me-1">
                                            <i class="bi bi-ban"></i> Banned
                                        </span>
                                        @endif

                                        @if($user->is_disabled)
                                        <span class="badge bg-warning text-dark me-1">
                                            <i class="bi bi-slash-circle"></i> Disabled
                                        </span>
                                        @endif

                                        @if(!$user->is_banned && !$user->is_disabled)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Active
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($user->is_technician && $user->technician)
                                        <a href="{{ route('technicians.show', $user->technician->technician_code) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @else
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </small>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </main>

            @include('partials/footer')
        </div>
    </div>
</body>

</html>