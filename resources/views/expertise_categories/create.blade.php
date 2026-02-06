</html>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Create Category</title>
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
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h1 class="mb-0 text-primary">Create New Expertise Category</h1>
                                <a href="{{ route('expertise-categories.index') }}" class="btn btn-secondary">
                                    &larr; Back to List
                                </a>
                            </div>

                            <div class="card shadow-lg border-0">
                                <div class="card-body p-4">
                                    <form action="{{ route('expertise-categories.store') }}" method="POST">
                                        @csrf

                                        <div class="mb-4">
                                            <label for="name" class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="e.g., HVAC Repair, Plumbing, Electrical">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-bold">Description (Optional)</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                id="description" name="description" rows="4" maxlength="255" placeholder="A brief summary of this expertise area.">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                                <i class="bi bi-save me-1"></i> Save Category
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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