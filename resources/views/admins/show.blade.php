<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Admin Details - Dashboard</title>

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
            <main class="container py-5">
                <h1>User Details</h1>

                <a href="{{ route('admins.index') }}" class="btn btn-secondary mb-3">← Back to User List</a>


                @if(Auth::user()->is_admin_supervisor))
                @include('admins.partials.disable-account')
                @endif

                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td>{{ $user->first_name }}</td>
                    </tr>
                    <tr>
                        <th>Middle Name</th>
                        <td>{{ $user->middle_name }}</td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td>{{ $user->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Admin</th>
                        <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Technician</th>
                        <td>{{ $user->is_technician ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Disabled</th>
                        <td>{{ $user->is_disabled ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Banned</th>
                        <td>{{ $user->is_banned ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $user->updated_at }}</td>
                    </tr>
                </table>

                <h2 class="mt-5">Actions Taken by This Admin</h2>

                @forelse ($adminActions as $action)
                <div class="alert alert-secondary mt-3">
                    <strong>Action:</strong> {{ ucfirst($action->action_taken) }} <br>

                    <strong>Performed by:</strong>
                    @if ($action->admin)
                    {{ $action->admin->first_name }} {{ $action->admin->last_name }} (ID: {{ $action->admin_id }})
                    @else
                    <em>Unknown admin (ID: {{ $action->admin_id }})</em>
                    @endif
                    <br>

                    <strong>On User:</strong>
                    @if ($action->target)
                    {{ $action->target->first_name }} {{ $action->target->last_name }} (ID: {{ $action->target_user_id }})
                    @else
                    <em>Deleted user (ID: {{ $action->target_user_id }})</em>
                    @endif
                    <br>

                    @if ($action->notes)
                    <strong>Notes:</strong> {{ $action->notes }} <br>
                    @endif

                    <strong>At:</strong> {{ $action->created_at->format('Y-m-d H:i:s') }}
                </div>
                @empty
                <div class="alert alert-light mt-3">No actions taken by this admin.</div>
                @endforelse

                {{-- Pagination --}}
                @if ($adminActions->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <p class="text-muted mb-0">
                        Showing {{ $adminActions->firstItem() }} to {{ $adminActions->lastItem() }} of {{ $adminActions->total() }} actions
                    </p>
                    <nav>
                        {{ $adminActions->withQueryString()->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
                @endif




            </main>

            @include('partials/footer')
        </div>
    </div>
</body>

</html>