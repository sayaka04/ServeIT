<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - SB Admin</title>
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

                <div class="container py-5">
                    <h1>Edit User</h1>

                    <a href="{{ route('admins.index') }}" class="btn btn-secondary mb-3">← Back to User List</a>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admins.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required value="{{ old('first_name', $user->first_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $user->middle_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required value="{{ old('last_name', $user->last_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>

            </main>
            <!------------------------------>
            <!-----------Footer------------>
            <!------------------------------>
            @include('partials/footer')


        </div>
    </div>
</body>

</html>