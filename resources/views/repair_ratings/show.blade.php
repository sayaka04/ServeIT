<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Repair Rating Details</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>

    @include('partials.bootstrap')
</head>

<body class="sb-nav-fixed">
    @include('partials.navigation-bar')

    <div id="layoutSidenav">
        @include('partials.sidebar-client')

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Repair Rating Details</h1>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Repair Rating #{{ $repairRating->id }}</h5>
                            <p><strong>User:</strong> {{ $repairRating->user->name }}</p>
                            <p><strong>Technician:</strong> {{ $repairRating->technician->name }}</p>
                            <p><strong>Repair ID:</strong> #{{ $repairRating->repair->id }}</p>
                            <p><strong>User Score:</strong> {{ $repairRating->user_weighted_score }}</p>
                            <p><strong>Technician Score:</strong> {{ $repairRating->technician_weighted_score }}</p>

                            <a href="{{ route('repair_ratings.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>