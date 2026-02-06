<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Manage Expertise</title>
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
                    <h2 class="mb-4">Manage Expertise for {{ $technician->user->name ?? 'Technician' }}</h2>

                    <form action="{{ route('technician.expertise.update', $technician->technician_code) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Use PUT for updates, adjust if your route expects PATCH --}}

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="selectAll" {{ $allCategories->isEmpty() ? 'disabled' : '' }}>
                            <label class="form-check-label" for="selectAll">Select/Deselect All</label>
                        </div>

                        <div class="list-group">
                            @forelse($allCategories as $category)
                            @php
                            $hasCategory = $currentExpertise->has($category->id);
                            $isArchived = $hasCategory ? $currentExpertise[$category->id]->pivot->is_archived : false;
                            $isChecked = $hasCategory && !$isArchived;
                            @endphp

                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input expertise-checkbox" type="checkbox"
                                        name="expertise_categories[]" value="{{ $category->id }}"
                                        id="category_{{ $category->id }}" {{ $isChecked ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="category_{{ $category->id }}">
                                        <strong>{{ $category->name }}</strong>
                                        <div class="text-muted small">{{ $category->description }}</div>
                                    </label>
                                </div>
                                <div>
                                    @if($hasCategory && $isArchived)
                                    <span class="badge bg-danger">Archived</span>
                                    @elseif($hasCategory)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Unused</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-info">No expertise categories found.</div>
                            @endforelse
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    const selectAll = document.getElementById('selectAll');
                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            const checkboxes = document.querySelectorAll('.expertise-checkbox');
                            checkboxes.forEach(cb => cb.checked = this.checked);
                        });
                    }
                </script>

            </main>
            <!------------------------------>
            <!-----------Footer------------>
            <!------------------------------>
            @include('partials/footer')


        </div>
    </div>
</body>

</html>