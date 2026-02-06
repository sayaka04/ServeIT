<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

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
                <div class="container-fluid px-4">

                    <nav class="mb-3">
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">Back to Notifications</a>
                    </nav>

                    <h2>{{ $notification->subject }}</h2>
                    <p class="text-muted">Received {{ $notification->created_at->diffForHumans() }}</p>

                    <div class="card mb-3">
                        <div class="card-body">
                            <p>{{ $notification->description }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start align-items-center mb-3">
                        @php
                        // Dynamically build the route based on the notifiable_type and notifiable_id
                        $resourceRoute = '';
                        switch ($notification->notifiable_type) {
                        case 'App\\Models\\Repair':
                        $resourceRoute = route('repairs.show', $notification->notifiable_id);
                        break;
                        case 'App\\Models\\Message':
                        $resourceRoute = route('messages.show', $notification->notifiable_id);
                        break;
                        // Add more cases for other notifiable types as needed
                        }
                        @endphp

                        @if ($resourceRoute)
                        <a href="{{ $resourceRoute }}" class="btn btn-primary me-2">View Resource</a>
                        @endif

                        <!-- partials for show  -->
                    </div>

                </div>
            </main>

            @include('partials/footer')

        </div>
    </div>




</body>

</html>