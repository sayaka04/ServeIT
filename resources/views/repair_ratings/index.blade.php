<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Repair Ratings Index" />
    <meta name="author" content="" />

    <title>Repair Ratings</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/shortened-sidebar.css') }}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>

    @include('partials/bootstrap')
</head>

<body class="sb-nav-fixed">

    @include('partials/navigation-bar')

    <div id="layoutSidenav">
        @include('partials/sidebar')

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    <h1 class="mb-4">Repair Ratings</h1>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('repair_ratings.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create New Rating
                        </a>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Ratings Table
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Technician</th>
                                            <th>Repair ID</th>
                                            <th>User Score</th>
                                            <th>Technician Score</th>
                                            <th>User Comment</th>
                                            <th>Technician Comment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ratings as $repairRating)
                                        <tr>
                                            <td>{{ $repairRating->id }}</td>
                                            <td>
                                                {{ $repairRating->user->first_name }}
                                                {{ $repairRating->user->last_name }}
                                            </td>
                                            <td>
                                                {{ optional($repairRating->technician->user)->first_name }}
                                                {{ optional($repairRating->technician->user)->last_name }}
                                            </td>
                                            <td>{{ $repairRating->repair->id }}</td>
                                            <td>{{ $repairRating->user_weighted_score ?? 'N/A' }}</td>
                                            <td>{{ $repairRating->technician_weighted_score ?? 'N/A' }}</td>
                                            <td>{{ $repairRating->user_comment ?? 'N/A' }}</td>
                                            <td>{{ $repairRating->technician_comment ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('repair_ratings.show', $repairRating->id) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('repair_ratings.edit', $repairRating->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No ratings available.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>