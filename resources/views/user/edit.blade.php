<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Edit User Status - Dashboard</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />
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
                    <h1 class="mb-4">Edit User Status</h1>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" class="form-control" value="{{ $user->first_name }} {{ $user->last_name }}" disabled>
                        </div>

                        <div class="form-check mb-3">
                            <input type="hidden" name="is_banned" value="0" />
                            <input class="form-check-input" type="checkbox" name="is_banned" id="is_banned" value="1" {{ $user->is_banned ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_banned">Is Banned</label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="hidden" name="is_disabled" value="0" />
                            <input class="form-check-input" type="checkbox" name="is_disabled" id="is_disabled" value="1" {{ $user->is_disabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_disabled">Is Disabled</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </main>

            @include('partials/footer')
        </div>
    </div>
</body>

</html>