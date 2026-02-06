<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Edit Repair Rating</title>
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
                    <h1 class="mt-4">Edit Repair Rating</h1>

                    <form method="POST" action="{{ route('repair_ratings.update', $repairRating->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select name="user_id" id="user_id" class="form-control">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $repairRating->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="technician_id">Technician</label>
                            <select name="technician_id" id="technician_id" class="form-control">
                                @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ $repairRating->technician_id == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="repair_id">Repair</label>
                            <input type="text" class="form-control" id="repair_id" value="{{ $repairRating->repair->id }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="user_weighted_score">User Score</label>
                            <input type="number" name="user_weighted_score" class="form-control" value="{{ $repairRating->user_weighted_score }}" step="0.01" max="100">
                        </div>

                        <div class="form-group">
                            <label for="technician_weighted_score">Technician Score</label>
                            <input type="number" name="technician_weighted_score" class="form-control" value="{{ $repairRating->technician_weighted_score }}" step="0.01" max="100">
                        </div>

                        <button type="submit" class="btn btn-warning mt-3">Update Rating</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>