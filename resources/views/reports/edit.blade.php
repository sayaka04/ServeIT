<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - SB Admin</title>
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


                <div class="container">

                    <h1>Edit Report</h1>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="container">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Report Details #{{ $report->id }}</h5>
                                <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'resolved' ? 'success' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </div>

                            <div class="card-body p-0">
                                <div class="row g-0">

                                    <div class="col-lg-4 border-end">
                                        <div class="p-3">
                                            <div class="mb-3">
                                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Involved Parties</small>
                                                <div class="d-flex justify-content-between mt-2">
                                                    <div>
                                                        <span class="d-block text-muted" style="font-size: 0.85rem;">Reporter</span>
                                                        <strong>{{ $report->reporter->first_name }} {{ $report->reporter->last_name }}</strong>
                                                        @if($report->is_admin_report) <span class="badge bg-info text-dark" style="font-size: 0.65rem;">Admin</span> @endif
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="d-block text-muted" style="font-size: 0.85rem;">Reported User</span>
                                                        <strong class="text-danger">{{ $report->reportedUser->first_name }} {{ $report->reportedUser->last_name }}</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-3">

                                            <div class="mb-3">
                                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Details</small>
                                                <ul class="list-unstyled mt-2 mb-0">
                                                    <li class="mb-2">
                                                        <span class="text-muted">Category:</span>
                                                        <span class="badge bg-secondary">{{ $report->category->name }}</span>
                                                    </li>
                                                    <li class="mb-2">
                                                        <span class="text-muted">Date:</span>
                                                        <span>{{ $report->created_at->format('M d, Y h:i A') }}</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <hr class="my-3">

                                            <div>
                                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Description</small>
                                                <div class="bg-light p-3 rounded mt-2 border" style="max-height: 300px; overflow-y: auto;">
                                                    <p class="mb-0" style="white-space: pre-wrap; font-size: 0.9rem;">{{ $report->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 bg-secondary bg-opacity-10 d-flex flex-column justify-content-center align-items-center" style="min-height: 600px;">
                                        @if($report->file_path)
                                        <div class="w-100 h-100 d-flex flex-column">
                                            <iframe
                                                src="{{ route('getFile2', $report->file_path) }}"
                                                width="100%"
                                                height="600px"
                                                style="border: none;"
                                                title="Report Evidence">
                                            </iframe>

                                            <div class="bg-white border-top p-2 text-end">
                                                <small class="me-2 text-muted">Having trouble viewing?</small>
                                                <a href="{{ route('getFile2', $report->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    Open in New Tab <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center text-muted p-5">
                                            <div style="font-size: 3rem;">📂</div>
                                            <h6 class="mt-2">No Evidence Attached</h6>
                                            <p class="small">This report was submitted without a PDF or images.</p>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <form action="{{ route('reports.update', $report) }}" method="POST">
                        @csrf
                        @method('PUT')


                        <div class="container">
                            <div class="row mt-2">

                                <div class="col col-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select" required>
                                            @foreach(['pending', 'under_review', 'resolved', 'closed'] as $status)
                                            <option value="{{ $status }}" {{ $report->status === $status ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="admin_notes" class="form-label">Admin Note</label>
                                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Notes... (Not visible to users)">{{ $report->admin_notes }}</textarea>
                                    </div>
                                    <!-- Admin Take Action Checkbox -->
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="takeAdminAction" name="take_admin_action">
                                        <label class="form-check-label" for="takeAdminAction">
                                            Take administrative action
                                        </label>
                                    </div>
                                </div>

                                <div class="col col-12 col-lg-6">
                                    <!-- Hidden Admin Action Inputs -->
                                    <div id="adminActionFields" class="mb-3 d-none">
                                        <div class="mb-3">
                                            <label for="action_taken" class="form-label">Action Taken</label>
                                            <select class="form-select" id="action_taken" name="action_taken">
                                                <option value="" disabled selected>Select an action...</option>
                                                <option value="warn">Warn</option>
                                                <option value="suspend">Suspend</option>
                                                <option value="ban">Ban</option>
                                                <option value="unban">Unban</option>
                                                <option value="dismiss">Dismiss</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Admin message to user</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any notes or justification..."></textarea>
                                        </div>
                                    </div>
                                    <!-- JavaScript For Hidden Admin Action Inputs -->
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const checkbox = document.getElementById('takeAdminAction');
                                            const adminFields = document.getElementById('adminActionFields');

                                            if (checkbox) {
                                                checkbox.addEventListener('change', function() {
                                                    if (this.checked) {
                                                        adminFields.classList.remove('d-none');
                                                    } else {
                                                        adminFields.classList.add('d-none');
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                </div>

                            </div>
                            <div class="d-flex justify-content-center pt-2">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update Report</button>
                                </div>
                            </div>
                    </form>


                    <table class="table mt-2 pt-2">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center fw-bold fs-5">
                                    History of Admin Actions Taken
                                </th>
                            </tr>
                            <tr>
                                <th>By Admin</th>
                                <th>Action</th>
                                <th>Notes</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report->adminActions as $action)
                            <tr>
                                <td>{{ $action->admin->first_name }}　{{ $action->admin->middle_name }}　{{ $action->admin->last_name }}</td>
                                <td>{{ ucfirst($action->action_taken) }}</td>
                                <td class="w-50">{{ $action->notes ?? '-' }}</td>
                                <td>{{ $action->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No admin actions recorded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>


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