<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Create Repair Rating</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">


    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/shortened-sidebar.css') }}" rel="stylesheet" />
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
                    <h1 class="mt-4">Create Repair Rating</h1>

                    <!-- Form Start -->
                    <form method="POST" action="{{ route('repair_ratings.store') }}">
                        @csrf

                        <!-- User Selection -->
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select name="user_id" id="user_id" class="form-control">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Technician Selection -->
                        <div class="form-group">
                            <label for="technician_id">Technician</label>
                            <select name="technician_id" id="technician_id" class="form-control">
                                @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->user->first_name }} {{ $technician->user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Repair Selection -->
                        <div class="form-group">
                            <label for="repair_id">Repair</label>
                            <select name="repair_id" id="repair_id" class="form-control">
                                @foreach($repairs as $repair)
                                <option value="{{ $repair->id }}">Repair #{{ $repair->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- User Score -->
                        <div class="form-group">
                            <label for="user_weighted_score">User Score</label>
                            <input type="number" name="user_weighted_score" class="form-control" step="0.01" min="0" max="100" value="{{ old('user_weighted_score') }}">
                            @error('user_weighted_score')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Technician Score -->
                        <div class="form-group">
                            <label for="technician_weighted_score">Technician Score</label>
                            <input type="number" name="technician_weighted_score" class="form-control" step="0.01" min="0" max="100" value="{{ old('technician_weighted_score') }}">
                            @error('technician_weighted_score')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- User Comment -->
                        <div class="form-group">
                            <label for="user_comment">User Comment</label>
                            <textarea name="user_comment" class="form-control" rows="4">{{ old('user_comment') }}</textarea>
                            @error('user_comment')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Technician Comment -->
                        <div class="form-group">
                            <label for="technician_comment">Technician Comment</label>
                            <textarea name="technician_comment" class="form-control" rows="4">{{ old('technician_comment') }}</textarea>
                            @error('technician_comment')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Create Rating</button>
                    </form>
                    <!-- Form End -->

                </div>
            </main>
        </div>
    </div>
</body>

</html>