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

                <h1>Report Details</h1>

                <div class="mb-3"><strong>Reporter:</strong> {{ $report->reporter->name ?? '—' }}</div>
                <div class="mb-3"><strong>Reported User:</strong> {{ $report->reportedUser->name ?? '—' }}</div>
                <div class="mb-3"><strong>Category:</strong> {{ $report->category->name ?? '—' }}</div>
                <div class="mb-3"><strong>Status:</strong> <span class="badge bg-info text-uppercase">{{ $report->status }}</span></div>
                <div class="mb-3"><strong>Description:</strong>
                    <p>{{ $report->description }}</p>
                </div>

                <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">Edit</a>


            </main>
            <!------------------------------>
            <!-----------Footer------------>
            <!------------------------------>
            @include('partials/footer')


        </div>
    </div>
</body>

</html>