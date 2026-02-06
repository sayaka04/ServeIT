<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Technician Profile Dashboard" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Technician Profile - Dashboard</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Include CSS first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet" />
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />

    <!-- Include other JavaScript libraries later -->
    <script defer src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Include jQuery here, without 'defer' -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>
    <!-- Include Bootstrap JS -->
    @include('partials/bootstrap') <!-- Assuming this includes necessary Bootstrap JS -->
</head>


<body class="sb-nav-fixed">

    @include('partials/navigation-bar')

    <div id="layoutSidenav">
        @include('partials/sidebar')

        <div id="layoutSidenav_content">
            <main>


                <style>
                    body {
                        padding-top: 20px;
                    }

                    .img-fluid {
                        max-width: 100%;
                        height: auto;
                    }

                    .card {
                        margin-bottom: 30px;
                    }

                    .overflow-hidden {
                        overflow: hidden !important;
                    }

                    .p-0 {
                        padding: 0 !important;
                    }

                    .mt-n5 {
                        margin-top: -3rem !important;
                    }

                    .linear-gradient {
                        background-image: linear-gradient(#50b2fc, #f44c66);
                    }

                    .rounded-circle {
                        border-radius: 50% !important;
                    }

                    .align-items-center {
                        align-items: center !important;
                    }

                    .justify-content-center {
                        justify-content: center !important;
                    }

                    .d-flex {
                        display: flex !important;
                    }

                    .rounded-2 {
                        border-radius: 7px !important;
                    }

                    .bg-light-info {
                        --bs-bg-opacity: 1;
                        background-color: rgba(44, 43, 48, 1) !important;
                    }

                    .card {
                        margin-bottom: 30px;
                    }

                    .position-relative {
                        position: relative !important;
                    }

                    .shadow-none {
                        box-shadow: none !important;
                    }

                    .overflow-hidden {
                        overflow: hidden !important;
                    }

                    .border {
                        border: 1px solid #ebf1f6 !important;
                    }

                    .fs-6 {
                        font-size: 1.25rem !important;
                    }

                    .mb-2 {
                        margin-bottom: 0.5rem !important;
                    }

                    .d-block {
                        display: block !important;
                    }

                    a {
                        text-decoration: none;
                    }

                    .user-profile-tab .nav-item .nav-link.active {
                        color: #5d87ff;
                        border-bottom: 2px solid #5d87ff;
                    }

                    .mb-9 {
                        margin-bottom: 20px !important;
                    }

                    .fw-semibold {
                        font-weight: 600 !important;
                    }

                    .fs-4 {
                        font-size: 1rem !important;
                    }

                    .card,
                    .bg-light {
                        box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
                    }

                    .fs-2 {
                        font-size: .75rem !important;
                    }

                    .rounded-4 {
                        border-radius: 4px !important;
                    }

                    .ms-7 {
                        margin-left: 30px !important;
                    }
                </style>




                <div class="container">

                    <div class="card overflow-hidden">
                        <div class="card-body p-0">

                            <style>
                                /* New: Added to make the image fully fill its container */
                                .banner-container img {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: contain;
                                    /* This is the key change to eliminate black bars */
                                    object-position: center;
                                    /* Ensures the center of the image is always visible */
                                }

                                /* Your existing banner-container CSS */
                                .banner-container {
                                    width: auto;
                                    height: 300px;
                                    position: relative;
                                    /* The background color is no longer needed if object-fit: cover is used */
                                    /* background-color: #000; */
                                }

                                /* Your existing banner-overlay CSS */
                                .banner-overlay {
                                    position: absolute;
                                    top: 0;
                                    bottom: 0;
                                    left: 0;
                                    right: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    transition: .3s ease;
                                    background-color: rgba(0, 0, 0, 0.6);
                                    color: white;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    text-align: center;
                                    cursor: pointer;
                                }
                            </style>

                            @if($technician->technician_user_id == Auth::id())
                            <style>
                                .banner-container:hover .banner-overlay {
                                    opacity: 1;
                                }

                                .banner-container:hover img {
                                    opacity: 0.7;
                                }
                            </style>
                            @endif

                            <div class="banner-container border border-4 border-white">
                                @if($technician->banner_picture)
                                <img src="{{ route('getFile2', $technician->banner_picture) }}" alt="Technician Banner" class="img-fluid">
                                @else
                                <img src="https://www.bootdey.com/image/1352x300/00FFFF/000000" alt="Default Banner" class="img-fluid">
                                @endif

                                @if($technician->technician_user_id == Auth::id())
                                <div class="banner-overlay" data-bs-toggle="modal" data-bs-target="#bannerModal">
                                    <span>Change banner</span>
                                </div>
                                @endif

                            </div>
                            @if($technician->technician_user_id == Auth::id())
                            <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bannerModalLabel">Upload Banner</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('technicians.banner.change') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="bannerImageFile" class="form-label">Choose a new banner image</label>
                                                    <input class="form-control" type="file" id="bannerImageFile" name="banner_picture">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Upload Image</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row align-items-center">
                                <div class="col-lg-4 order-lg-1 order-2">
                                    <div class="d-flex align-items-center justify-content-around m-4">
                                        <div class="text-center">
                                            <i class="fa fa-file fs-6 d-block mb-2"></i>
                                            <h4 class="mb-0 fw-semibold lh-1">{{ $technician->weighted_score_rating }}</h4>
                                            <p class="mb-0 fs-4">Weighted Ratings</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fa fa-user fs-6 d-block mb-2"></i>
                                            <h4 class="mb-0 fw-semibold lh-1">{{ $technician->success_rate }}</h4>
                                            <p class="mb-0 fs-4">Success Rate</p>
                                        </div>
                                        <div class="text-center">
                                            <i class="fa fa-check fs-6 d-block mb-2"></i>
                                            <h4 class="mb-0 fw-semibold lh-1">{{ $technician->jobs_completed }}</h4>
                                            <p class="mb-0 fs-4">Completed Jobs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                                    <div class="mt-n5">

                                        <style>
                                            /* Position the overlay relative to its parent container */
                                            .profile-picture-container {
                                                position: relative;
                                            }

                                            /* This style makes the profile picture fit entirely inside its container */
                                            .profile-picture-container img {
                                                width: 100%;
                                                height: 100%;
                                                object-fit: contain;
                                            }

                                            /* The rest of the overlay CSS remains the same */
                                            .profile-picture-overlay {
                                                position: absolute;
                                                top: 0;
                                                left: 0;
                                                width: 100%;
                                                height: 100%;
                                                background-color: rgba(0, 0, 0, 0.6);
                                                color: white;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                text-align: center;
                                                opacity: 0;
                                                transition: opacity 0.3s ease;
                                                cursor: pointer;
                                            }
                                        </style>

                                        @if($technician->technician_user_id == Auth::id())
                                        <style>
                                            /* Show the overlay on hover */
                                            .profile-picture-container:hover .profile-picture-overlay {
                                                opacity: 1;
                                            }

                                            /* Add a subtle effect to the image on hover */
                                            .profile-picture-container:hover img {
                                                opacity: 0.7;
                                            }
                                        </style>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="profile-picture-container border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;">
                                                @if($technician->user->profile_picture)
                                                <img src="{{ route('getFile2', $technician->user->profile_picture ?? 'Default_Profile_Picture.png') }}" alt="" class="bg-primary w-100 h-100">
                                                @else
                                                <img src="{{ route('getFile2', 'Default_Profile_Picture.png') }}" alt="" class="bg-primary w-100 h-100">
                                                @endif
                                                @if($technician->technician_user_id == Auth::id())
                                                <div class="profile-picture-overlay" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                                                    <span>Change profile picture</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal fade" id="profilePictureModal" tabindex="-1" aria-labelledby="profilePictureModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="profilePictureModalLabel">Upload Profile Picture</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('users.picture.change') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="profileImageFile" class="form-label">Choose a new profile picture</label>
                                                                <input class="form-control" type="file" id="profileImageFile" name="profile_picture">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Upload Image</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- User Information -->
                                        <div class="text-center">
                                            <h5 class="fs-5 mb-0 fw-semibold">{{ $technician->user->first_name }} {{ $technician->user->middle_name }} {{ $technician->user->last_name }}</h5>
                                            <p class="mb-0 fs-4">Technician</p>
                                            @if ($technician && $technician->tesda_verified)
                                            <span class="badge bg-success">TESDA Verified</span>
                                            @elseif($technician)
                                            <span class="badge bg-danger">TESDA Unverified</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">Joined: {{ $technician->user->created_at }}</small>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 order-last">
                                    <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                                        <!-- <li class="position-relative">
                                            <a class="text-white d-flex align-items-center justify-content-center bg-primary p-2 fs-4 rounded-circle" href="javascript:void(0)" width="30" height="30">
                                                <i class="bi bi-facebook"></i> </a>
                                        </li>
                                        <li class="position-relative">
                                            <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle " href="javascript:void(0)">
                                                <i class="bi bi-twitter"></i> </a>
                                        </li>
                                        <li class="position-relative">
                                            <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle " href="javascript:void(0)">
                                                <i class="bi bi-linkedin"></i> </a>
                                        </li>
                                        <li class="position-relative">
                                            <a class="text-white bg-danger d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle " href="javascript:void(0)">
                                                <i class="bi bi-youtube"></i> </a>
                                        </li> -->


                                        @if(Auth::check() && Auth::id() !== $technician->technician_user_id)
                                        <!-- Report Button -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportUserModal">
                                            Report
                                        </button>

                                        <!-- Report Modal -->
                                        <div class="modal fade" id="reportUserModal" tabindex="-1" aria-labelledby="reportUserModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="reportUserModalLabel">Report Technician</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form id="reportForm" enctype="multipart/form-data">
                                                        <div class="modal-body">

                                                            <input type="hidden" name="reported_user_id" id="reported_user_id" value="{{ $technician->technician_user_id }}">

                                                            <div class="mb-3">
                                                                <label for="report-category" class="form-label">Select a Reason for Reporting:</label>
                                                                <select class="form-select" id="report-category" name="category_id" required>
                                                                    <option value="" disabled selected>Choose a category...</option>
                                                                    @foreach($reportCategories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="report-description" class="form-label">Detailed Description:</label>
                                                                <textarea class="form-control" id="report-description" name="description" rows="4" placeholder="Explain the issue..." required></textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="report-files" class="form-label">Attach image(s):</label>
                                                                <input
                                                                    type="file"
                                                                    class="form-control"
                                                                    id="report-files"
                                                                    name="files[]"
                                                                    multiple
                                                                    accept="image/*,application/pdf" />

                                                                <small class="text-muted">Max total size: 25MB. You may upload multiple images (JPG, PNG) OR a single PDF file. Please do not mix formats.</small>
                                                            </div>

                                                            @if(Auth::user()->is_admin)
                                                            <!-- Admin Take Action Checkbox -->
                                                            <div class="form-check mb-3">
                                                                <input class="form-check-input" type="checkbox" value="1" id="takeAdminAction" name="take_admin_action">
                                                                <label class="form-check-label" for="takeAdminAction">
                                                                    Take administrative action
                                                                </label>
                                                            </div>

                                                            <!-- Hidden Admin Action Inputs -->
                                                            <div id="adminActionFields" class="mb-3 d-none">
                                                                <div class="mb-3">
                                                                    <label for="action_taken" class="form-label">Action Taken</label>
                                                                    <select class="form-select" id="action_taken" name="action_taken">
                                                                        <option value="" disabled selected>Select an action...</option>
                                                                        <option value="warn">Warn</option>
                                                                        <option value="suspend">Suspend</option>
                                                                        <option value="ban">Ban</option>
                                                                        <option value="dismiss">Dismiss</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="admin_notes" class="form-label">Admin Notes</label>
                                                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Enter any notes or justification..."></textarea>
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

                                                            @endif


                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger" id="submitReportBtn">Submit Report</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>


                                        <script>
                                            $('#submitReportBtn').click(function(e) {
                                                e.preventDefault();

                                                const form = document.getElementById('reportForm');
                                                const formData = new FormData(form);
                                                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                                                $('#submitReportBtn').prop('disabled', true);

                                                $.ajax({
                                                    url: "{{ route('reports.store') }}",
                                                    method: 'POST',
                                                    data: formData,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function(response) {
                                                        alert(response.success);
                                                        $('#reportForm')[0].reset();
                                                        $('#reportUserModal').modal('hide');
                                                    },
                                                    error: function(xhr) {
                                                        let message = "Something went wrong.";
                                                        if (xhr.status === 422) {
                                                            const errors = xhr.responseJSON.errors;
                                                            message = "Validation Error:\n";
                                                            $.each(errors, function(key, val) {
                                                                message += `- ${val[0]}\n`;
                                                            });
                                                        } else if (xhr.responseJSON?.error) {
                                                            message = xhr.responseJSON.error;
                                                        }
                                                        alert(message);
                                                    },
                                                    complete: function() {
                                                        $('#submitReportBtn').prop('disabled', false);
                                                    }
                                                });
                                            });
                                        </script>


                                        @if(!Auth::user()->is_admin && !Auth::user()->is_admin_supervisor)
                                        <li>
                                            <form action="{{ route('conversations.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="technician_code" value="{{ $technician->technician_code }}">
                                                <button type="submit" class="btn btn-primary">
                                                    Message {{ $technician->user->first_name }} now!
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @endif
















                                    </ul>
                                </div>
                            </div>

                            <div class="row align-items-center pt-4">
                                @php
                                // Assuming $technician is the Technician model instance, and you need
                                // to check if the authenticated user's ID matches the technician's user_id
                                // to allow editing. You might use the simple check requested, but
                                // this ensures the technician can only edit *their own* map.
                                $isAuthorizedToEdit = Auth::check() && Auth::user()->is_technician && (Auth::user()->id === $technician->technician_user_id);
                                @endphp

                                <div class="col col-12 col-l-6 col-xl-6">
                                    <h3 class="text-secondary">Service Area Map</h3>

                                    {{-- Show update tools only if the authenticated user is the technician being viewed --}}
                                    @if($isAuthorizedToEdit)
                                    <div class="d-flex justify-content-start mb-3">
                                        <button id="getGpsBtn" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-location-arrow"></i> Get Current Location (GPS)
                                        </button>
                                        <button id="saveLocationBtn" class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-save"></i> Save New Location
                                        </button>
                                    </div>
                                    <p class="text-muted">
                                        <small>
                                            **Click the map** to set a new location or use the **GPS** button.
                                        </small>
                                    </p>

                                    {{-- Hidden fields to hold the new coordinates for submission --}}
                                    <input type="hidden" id="new_latitude" value="{{ $technician->latitude }}">
                                    <input type="hidden" id="new_longitude" value="{{ $technician->longitude }}">
                                    @endif

                                    {{-- Pass technician's current coordinates to JavaScript via data attributes --}}
                                    <div id="map" style="height: 400px;" class="rounded shadow-sm"
                                        data-latitude="{{ $technician->latitude }}"
                                        data-longitude="{{ $technician->longitude }}"></div>
                                </div>


                                <div class="col col-12 col-l-6 col-xl-6">
                                    <h3 class="text-secondary">Additional Information</h3>
                                    <div style="height: 400px;">
                                        <ul class="list-group shadow-sm p-2">
                                            <li class="list-group-item">
                                                <strong>Email:</strong> {{ $technician->user?->email ?? 'N/A' }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Home Service:</strong> {{ $technician->home_service ? 'Yes' : 'No' }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>TESDA Certificate #:</strong> {{ $technician->tesda_first_four }} ... {{ $technician->tesda_last_four }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Address:</strong> {{ $technician->address ?: 'Not Provided' }}
                                            </li>
                                            {{--
                                            <li class="list-group-item">
                                                <strong>Shop:</strong> {{ $technician->shop?->name ?? 'Independent Technician' }}
                                            </li>
                                            --}}
                                            <!-- NEW: Expertise Categories as Badges -->
                                            <li class="list-group-item">
                                                <strong>Expertise:</strong>
                                                @if(Auth::user()->is_technician && $technician->id === Auth::user()->technician->id)
                                                <a class="btn btn-sm btn-info"
                                                    href="{{route('technician.expertise.manage', $technician->technician_code)}}">
                                                    <i class="bi bi-pen-fill me-2"></i> Edit
                                                </a>
                                                @endif
                                                <div class="mt-2 d-flex flex-wrap gap-2">
                                                    @forelse ($technician->activeExpertiseCategories as $category)
                                                    <span class="badge bg-info shadow-sm">{{ $category->name }}</span>
                                                    @empty
                                                    <span class="text-muted fst-italic">No areas of expertise specified.</span>
                                                    @endforelse

                                                </div>
                                            </li>
                                            <!-- END NEW: Expertise Categories as Badges -->
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6 active" id="pills-ratings-tab" data-bs-toggle="pill" data-bs-target="#pills-ratings" type="button" role="tab" aria-controls="pills-ratings" aria-selected="true" tabindex="0">
                                        <i class="bi bi-hand-thumbs-up-fill"></i> <span class="d-none d-md-block"> Reviews and Rating</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-certificates-tab" data-bs-toggle="pill" data-bs-target="#pills-certificates" type="button" role="tab" aria-controls="pills-certificates" aria-selected="false" tabindex="-1">
                                        <i class="bi bi-patch-check-fill"></i> <span class="d-none d-md-block"> Certificates & Credentials</span>
                                    </button>
                                </li>
                                {{--
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button" role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1">
                                        <i class="bi bi-image-fill"></i> <span class="d-none d-md-block"> Gallery???</span>
                                    </button>
                                </li>
                                                                        --}}
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content" id="pills-tabContent">


                        @include('technician/profile/partials/ratings')

                        @include('technician/profile/partials/certificates')

                        <br><br>
                    </div>

                </div>




            </main>
            @include('partials/footer')
        </div>
    </div>




    <script>
        // Global variables for the map and marker, accessible to the second script block
        let map;
        let marker;

        document.addEventListener('DOMContentLoaded', function() {
            var mapElement = document.getElementById('map');

            if (!mapElement) return;

            var latitude = parseFloat(mapElement.dataset.latitude);
            var longitude = parseFloat(mapElement.dataset.longitude);
            const defaultLat = 7.0480140; // Default center (e.g., Davao City)
            const defaultLon = 125.5445480;
            let initialCoordsSet = false;

            // Determine initial map center and marker position
            let centerLat = defaultLat;
            let centerLon = defaultLon;
            let markerLat = defaultLat;
            let markerLon = defaultLon;

            if (!isNaN(latitude) && !isNaN(longitude) && (latitude !== 0 || longitude !== 0)) {
                centerLat = latitude;
                centerLon = longitude;
                markerLat = latitude;
                markerLon = longitude;
                initialCoordsSet = true;
            }

            // --- Initialize Map ---
            map = L.map('map').setView([centerLat, centerLon], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Create initial marker
            marker = L.marker([markerLat, markerLon]).addTo(map)
                .bindPopup(initialCoordsSet ? 'Technician Service Location' : 'Default Location (Davao City)').openPopup();
        });
    </script>

    @if(Auth::check() && Auth::user()->is_technician && Auth::user()->id === $technician->technician_user_id)
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Safety check: ensure the map was initialized
            if (typeof map === 'undefined') {
                console.error("Leaflet map object 'map' was not initialized.");
                return;
            }

            const saveLocationBtn = document.getElementById('saveLocationBtn');
            const newLatInput = document.getElementById('new_latitude');
            const newLonInput = document.getElementById('new_longitude');

            if (!saveLocationBtn || !newLatInput || !newLonInput) {
                return;
            }

            // Function to update the marker position and inputs
            function updateMarker(lat, lng) {
                marker.setLatLng([lat, lng]);
                newLatInput.value = lat;
                newLonInput.value = lng;
                saveLocationBtn.disabled = false;
                marker.bindPopup('New Location Set!').openPopup();
            }

            // --- 1. Map Click Functionality ---
            map.on('click', function(e) {
                updateMarker(e.latlng.lat, e.latlng.lng);
            });

            // --- 2. Get via GPS Button Functionality ---
            document.getElementById('getGpsBtn').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            updateMarker(lat, lng);
                            map.setView([lat, lng], 16);
                        },
                        function(error) {
                            alert('Error getting GPS location: ' + error.message);
                            console.error('Geolocation Error:', error);
                        }
                    );
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });

            // --- 3. Save Location (AJAX) ---
            saveLocationBtn.addEventListener('click', function() {
                const newLat = newLatInput.value;
                const newLon = newLonInput.value;

                saveLocationBtn.disabled = true;
                saveLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                // NOTE: Ensure your blade file has the CSRF meta tag: <meta name="csrf-token" content="{{ csrf_token() }}">
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/technician/update-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            latitude: newLat,
                            longitude: newLon
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        saveLocationBtn.innerHTML = '<i class="fas fa-check"></i> Location Saved!';
                        saveLocationBtn.classList.remove('btn-success');
                        saveLocationBtn.classList.add('btn-info');
                        marker.bindPopup('Technician Service Location (Saved)').openPopup();

                        setTimeout(() => {
                            saveLocationBtn.innerHTML = '<i class="fas fa-save"></i> Save New Location';
                            saveLocationBtn.classList.remove('btn-info');
                            saveLocationBtn.classList.add('btn-success');
                            saveLocationBtn.disabled = true;
                        }, 3000);
                    })
                    .catch(error => {
                        console.error('Update Error:', error);
                        saveLocationBtn.innerHTML = '<i class="fas fa-times"></i> Error Saving!';
                        saveLocationBtn.classList.remove('btn-success');
                        saveLocationBtn.classList.add('btn-danger');

                        setTimeout(() => {
                            saveLocationBtn.innerHTML = '<i class="fas fa-save"></i> Save New Location';
                            saveLocationBtn.classList.remove('btn-danger');
                            saveLocationBtn.classList.add('btn-success');
                            saveLocationBtn.disabled = false;
                        }, 3000);
                        alert('An error occurred while saving the location.');
                    });
            });
        });
    </script>
    @endif


</body>

</html>