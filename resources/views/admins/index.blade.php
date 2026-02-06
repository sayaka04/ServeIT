<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>User Management (Admin)</title>

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
                <div class="container py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Admin Users</h1>
                        <a href="{{ route('admins.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create Admin
                        </a>
                    </div>

                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Search / Filter -->
                    <form method="GET" action="{{ route('admins.index') }}" class="row g-3 align-items-end mb-4">
                        <div class="col-md-8">
                            <label for="search" class="form-label">Search admin by name or email</label>
                            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Search...">
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>

                    @if (request('search'))
                    <div class="mb-3">
                        <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Clear Search
                        </a>
                    </div>
                    @endif

                    <div class="table-responsive shadow-sm">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Admin Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->is_admin)
                                        <span class="badge bg-success">Yes</span>
                                        @else
                                        <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admins.show', $user->id) }}" class="btn btn-info text-white" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            {{--
                                                <a href="{{ route('admins.edit', $user->id) }}" class="btn btn-warning text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admins.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this admin?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                            --}}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No admins found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-muted mb-0">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} admins
                        </p>
                        <nav>
                            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif



            </main>

            @include('partials/footer')
        </div>
    </div>
</body>

</html>