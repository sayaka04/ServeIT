<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Manage Expertise Categories</title>
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
                <div class="container py-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0 text-primary">Expertise Categories</h1>
                        <a href="{{ route('expertise-categories.create') }}" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> + Add New Category
                        </a>
                    </div>

                    {{-- Success/Error Messages --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- Tab Navigation for Active/Archived --}}
                    @php
                    $isArchivedView = request('archived') === 'true';
                    @endphp
                    <ul class="nav nav-tabs mb-3" id="categoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ !$isArchivedView ? 'active bg-white shadow-sm' : '' }}"
                                href="{{ route('expertise-categories.index') }}"
                                role="tab">
                                Active Categories
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $isArchivedView ? 'active bg-white shadow-sm' : '' }}"
                                href="{{ route('expertise-categories.index', ['archived' => 'true']) }}"
                                role="tab">
                                Archived Categories
                            </a>
                        </li>
                    </ul>

                    <div class="card shadow-lg border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="py-3 px-4" style="width: 5%;">ID</th>
                                            <th class="py-3 px-4" style="width: 25%;">Name</th>
                                            <th class="py-3 px-4" style="width: 50%;">Description</th>
                                            <th class="py-3 px-4 text-center" style="width: 20%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category)
                                        <tr class="{{ $category->is_archived ? '' : '' }}">
                                            <td class="py-3 px-4">{{ $category->id }}</td>
                                            <td class="py-3 px-4 fw-bold">{{ $category->name }}</td>
                                            <td class="py-3 px-4 text-muted">{{ $category->description ?? 'No description provided.' }}</td>
                                            <td class="py-3 px-4 text-center">
                                                {{-- Edit is always available --}}
                                                <a href="{{ route('expertise-categories.edit', $category) }}" class="btn btn-sm btn-outline-warning me-2">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>

                                                @if ($category->is_archived)
                                                {{-- Restore Button --}}
                                                <form action="{{ route('expertise-categories.restore', $category) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore \'{{ $category->name }}\'?')">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </button>
                                                </form>
                                                @else
                                                {{-- Archive Button (Soft Delete) --}}
                                                <form action="{{ route('expertise-categories.archive', $category) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Are you sure you want to archive \'{{ $category->name }}\'? Archiving will hide it from new assignments.')">
                                                        <i class="bi bi-archive"></i> Archive
                                                    </button>
                                                </form>
                                                @endif

                                                {{-- Permanent Hard Delete Button (Only visible in archived view for clarity) --}}
                                                @if ($category->is_archived)
                                                <form action="{{ route('expertise-categories.destroy', $category) }}" method="POST" style="display:inline-block;" class="ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('WARNING: Are you absolutely sure you want to PERMANENTLY DELETE \'{{ $category->name }}\'? This cannot be undone and may cause data integrity issues.')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No expertise categories found in the {{ $isArchivedView ? 'archived' : 'active' }} list.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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