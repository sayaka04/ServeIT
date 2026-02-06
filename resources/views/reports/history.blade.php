<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - SB Admin</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />


    <script defer src="{{ asset('js/scripts.js') }}"></script>


    @include('partials/bootstrap')


</head>

<body class="sb-nav-fixed" data-boundary-url="{{ route('haversine.boundary') }}" data-result-url="{{ route('technicians.search.result') }}">

    <!------------------------------>
    <!------------NavBar------------>
    <!------------------------------>
    @include('partials/navigation-bar')

    <div id="layoutSidenav">

        <!------------------------------>
        <!-----------SideBar------------>
        <!------------------------------>
        @include('partials/sidebar')

        <div id="layoutSidenav_content">

            <main>


                <h2>Report History for {{ $user->first_name }} {{ $user->last_name }}</h2>

                <a href="{{ route('reports.index') }}" class="btn btn-secondary mb-3">← Back to Reports</a>

                {{-- Section: Reports AGAINST this user --}}
                <h4 class="mt-4">🟥 Reports <strong>Against</strong> This User</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reported By</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportsAgainstUser as $report)
                        <tr>
                            <td>{{ $report->reporter->first_name }} {{ $report->reporter->last_name }}</td>
                            <td>{{ $report->category->name ?? '—' }}</td>
                            <td>{{ $report->description }}</td>
                            <td><span class="badge bg-secondary text-uppercase">{{ $report->status }}</span></td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No reports found against this user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Section: Reports BY this user --}}
                <h4 class="mt-5">🟦 Reports <strong>Submitted</strong> By This User</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reported User</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportsByUser as $report)
                        <tr>
                            <td>{{ $report->reportedUser->first_name }} {{ $report->reportedUser->last_name }}</td>
                            <td>{{ $report->category->name ?? '—' }}</td>
                            <td>{{ $report->description }}</td>
                            <td><span class="badge bg-secondary text-uppercase">{{ $report->status }}</span></td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No reports submitted by this user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </main>
            <!------------------------------>
            <!-----------Footer------------>
            <!------------------------------>
            @include('partials/footer')


        </div>
    </div>
</body>

</html>