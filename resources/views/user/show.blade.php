<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Customer Profile Dashboard" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>User Profile</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

                    .mt-n5,
                    .mt-n3 {
                        margin-top: 0 !important;
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

                    .position-relative {
                        position: relative !important;
                    }

                    .shadow-none {
                        box-shadow: none !important;
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

                    .banner-container {
                        display: none;
                    }

                    .profile-picture-container img {
                        width: 100%;
                        height: 100%;
                        object-fit: contain;
                        object-position: center;
                    }
                </style>

                <div class="container">
                    @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-lg-4 order-lg-1 order-2 d-none d-lg-block">
                                </div>
                                <div class="col-lg-4 order-lg-2 order-1">
                                    <div>
                                        <style>
                                            .profile-picture-container {
                                                position: relative;
                                            }

                                            .profile-picture-container img {
                                                width: 100%;
                                                height: 100%;
                                                object-fit: contain;
                                            }

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
                                        @if(Auth::check() && $user->id == Auth::id())
                                        <style>
                                            .profile-picture-container:hover .profile-picture-overlay {
                                                opacity: 1;
                                            }

                                            .profile-picture-container:hover img {
                                                opacity: 0.7;
                                            }
                                        </style>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-center mb-2 mt-2">
                                            <div class="profile-picture-container border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;">
                                                @if($user->profile_picture)
                                                <img src="{{ route('getFile2', $user->profile_picture) }}" alt="" class="bg-primary w-100 h-100">
                                                @else
                                                <img src="{{ route('getFile2', 'Default_Profile_Picture.png') }}" alt="" class="bg-primary w-100 h-100">
                                                @endif
                                                @if(Auth::check() && $user->id == Auth::id())
                                                <div class="profile-picture-overlay" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                                                    <span>Change profile picture</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <h5 class="fs-5 mb-0 fw-semibold">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h5>
                                        </div>
                                    </div>
                                    @if(Auth::check() && $user->id == Auth::id())
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
                                    @endif
                                </div>
                                <div class="col-lg-4 order-last d-flex justify-content-end align-self-end">
                                    <div class="d-flex align-items-center justify-content-center justify-content-lg-end mb-4 gap-3">
                                        @if(Auth::check() && Auth::id() !== $user->id)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportUserModal">
                                            Report
                                        </button>
                                        <div class="modal fade" id="reportUserModal" tabindex="-1" aria-labelledby="reportUserModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="reportUserModalLabel">Report User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="reportForm" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="reported_user_id" id="reported_user_id" value="{{ $user->id }}">
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
                                                            <div class="form-check mb-3">
                                                                <input class="form-check-input" type="checkbox" value="1" id="takeAdminAction" name="take_admin_action">
                                                                <label class="form-check-label" for="takeAdminAction">
                                                                    Take administrative action
                                                                </label>
                                                            </div>
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
                                        @endif
                                        @if(Auth::check())
                                        @if($user->id == Auth::id())
                                        <a href="{{ route('conversations.index') }}" class="btn btn-primary d-flex align-items-center px-4">
                                            <i class="bi bi-chat-dots-fill me-2"></i>
                                            My Messages
                                        </a>
                                        <a href="{{ route('profile.edit') }}" class="btn btn-warning d-flex align-items-center px-4">
                                            <i class="bi bi-pencil-fill me-2"></i>
                                            Edit Profile
                                        </a>
                                        @elseif(Auth::user()->is_technician)
                                        @if($existingConversation)
                                        <a href="{{ route('conversations.show', $existingConversation->conversation_code) }}"
                                            class="btn btn-primary d-flex align-items-center px-4">
                                            <i class="bi bi-chat-dots-fill me-2"></i>
                                            Message Client
                                        </a>
                                        @endif
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-about-tab" data-bs-toggle="pill" data-bs-target="#pills-about" type="button" role="tab" aria-controls="pills-about" aria-selected="true">
                                        <i class="fa fa-user me-2"></i> About
                                    </button>
                                </li>
                                @if(Auth::user()->id == $user->id)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-repairs-tab" data-bs-toggle="pill" data-bs-target="#pills-repairs" type="button" role="tab" aria-controls="pills-repairs" aria-selected="false">
                                        <i class="fa fa-list me-2"></i> Repairs History
                                    </button>
                                </li>
                                @endif
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-ratings-tab" data-bs-toggle="pill" data-bs-target="#pills-ratings" type="button" role="tab" aria-controls="pills-ratings" aria-selected="false">
                                        <i class="fa fa-star me-2"></i> Ratings
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-about" role="tabpanel" aria-labelledby="pills-about-tab" tabindex="0">
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                    <div class="card shadow-none border">
                                        <div class="card-body">
                                            <h4 class="fw-semibold mb-3">Customer Information</h4>
                                            <p class="text-muted">This profile page summarizes your activity as a customer.</p>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="card shadow-none border">
                                        <div class="card-body">
                                            <h4 class="fw-semibold mb-3">Personal Information</h4>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1">Full Name</p>
                                                    <p class="text-muted mb-0">{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1">Email</p>
                                                    <p class="text-muted mb-0">{{$user->email}}</p>
                                                </div>
                                                @if(Auth::user() && Auth::user()->id == $user->id)
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1">Mobile</p>
                                                    <p class="text-muted mb-0">{{ $user->phone_number ?? '(Not Provided)' }}</p>
                                                </div>
                                                @endif
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-1">Date Joined</p>
                                                    <p class="text-muted mb-0">{{ $user->created_at->toFormattedDateString() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->id == $user->id)

                        <div class="tab-pane fade" id="pills-repairs" role="tabpanel" aria-labelledby="pills-repairs-tab" tabindex="0">
                            <div class="card shadow-none border">
                                <div class="card-body">
                                    <h4 class="fw-semibold mb-3">My Repair Requests ({{ $user->repairs->count() }})</h4>
                                    @forelse($user->repairs as $repair)
                                    <div class="border p-3 mb-3 rounded">
                                        <h5 class="fw-semibold">{{ $repair->title }}</h5>
                                        <p class="mb-1">Status: <span class="badge bg-primary">{{ $repair->status }}</span></p>
                                        <p class="mb-1">Technician: {{ $repair->technician->user->first_name ?? 'Unassigned' }} {{ $repair->technician->user->middle_name ?? '' }} {{ $repair->technician->user->last_name ?? '' }}</p>
                                        <p class="mb-0">Date: {{ $repair->created_at->toFormattedDateString() }}</p>
                                        <a href="{{ route('repairs.show', $repair) }}" class="btn btn-sm btn-outline-info mt-2">View Details</a>
                                    </div>
                                    @empty
                                    <p class="text-muted">You have not initiated any repair requests yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @endif

                        <div class="tab-pane fade" id="pills-ratings" role="tabpanel" aria-labelledby="pills-ratings-tab" tabindex="0">
                            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">
                                    Ratings <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">{{ $user->repairRatings->count() }}
                                    </span>
                                </h3>
                            </div>
                            <div class="row">
                                @forelse($user->repairRatings as $rating)
                                @php
                                $technician = $rating->technician->user ?? null;
                                $profilePicture = $technician && $technician->profile_picture
                                ? route('getFile2', $technician->profile_picture)
                                : route('getFile2', 'Default_Profile_Picture.png');

                                $score = $rating->user_weighted_score ?? 0;
                                $fullStars = floor($score / 20);
                                $halfStar = ($score % 20) >= 10;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                <div class="col-lg-6 col-md-12 review-item mb-4" data-review-id="{{ $rating->id }}">
                                    <div class="card review-card p-3 h-100 shadow-sm">
                                        <div class="d-flex align-items-center mb-3">
                                            <img
                                                src="{{ $profilePicture }}"
                                                alt="Technician Avatar"
                                                class="rounded-circle me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;"
                                                aria-label="Technician avatar">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">
                                                    {{ $technician->first_name ?? 'Unknown Technician' }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $rating->created_at->toFormattedDateString() }}
                                                </small>
                                            </div>
                                            <div class="rating-stars text-warning ms-2" aria-label="User rating">
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="bi bi-star-fill"></i>
                                                    @endfor

                                                    @if ($halfStar)
                                                    <i class="bi bi-star-half"></i>
                                                    @endif

                                                    @for ($i = 0; $i < $emptyStars; $i++)
                                                        <i class="bi bi-star"></i>
                                                        @endfor
                                            </div>
                                        </div>

                                        <p class="card-text text-muted small mb-2">
                                            "{{ $rating->technician_comment }}"
                                        </p>

                                        <div class="small">
                                            <strong>Weighted Rating:</strong> {{ $score }}
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-info" role="alert">
                                        No ratings.
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @include('partials/footer')
        </div>
    </div>
</body>

</html>