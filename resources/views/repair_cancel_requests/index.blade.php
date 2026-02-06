<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Cancellation Requests</title>
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
                <div class="container-fluid">
                    <h1>Pending Cancellation Requests</h1>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    @if($cancelRequests->isEmpty())
                    <div class="alert alert-info">No pending cancellation requests.</div>
                    @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Repair ID</th>
                                <th>Device</th>
                                <th>Issue</th>
                                <th>Requestor</th>
                                <th>Reason</th>
                                <th>Requested At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cancelRequests as $request)
                            <tr>
                                <td>{{ $request->repair->id }}</td>
                                <td>{{ $request->repair->device }}</td>
                                <td>{{ $request->repair->issue }}</td>
                                <td>{{ $request->requestor->first_name }} {{ $request->requestor->middle_name }} {{ $request->requestor->last_name }}</td>
                                <td>{{ $request->reason }}</td>
                                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cancel_requests.accept', $request->id) }}" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accept cancellation request? This will mark the repair as cancelled.')">Accept</button>
                                    </form>

                                    <form method="POST" action="{{ route('cancel_requests.decline', $request->id) }}" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Decline cancellation request?')">Decline</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </main>

            @include('partials/footer')

        </div>
    </div>




</body>

</html>