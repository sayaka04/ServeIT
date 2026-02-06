<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conversation Lists</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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
                <div class="container-fluid list-group">


                    <h1 class="p-2">Messages</h1>
                    <div class="container-fluid py-4">
                        @foreach($conversations as $conversation)
                        <a href="{{ url('/conversations/' . $conversation->conversation_code) }}"
                            class="btn btn-white list-group-item list-group-item-action mb-4 p-4 shadow-sm rounded-4 border border-light-subtle bg-white text-decoration-none hover-shadow transition-all">

                            <div class="d-flex flex-column flex-md-row align-items-start justify-content-between">

                                <!-- Left: Avatar and Info -->
                                <div class="d-flex align-items-center mb-3 mb-md-0">
                                    <div class="avatar me-3">
                                        @if(Auth::user()->is_technician)
                                        <img src="{{ route('getFile2', $conversation->user->profile_picture ?? 'Default_Profile_Picture.png') }}"
                                            alt="User Avatar" class="rounded-circle border border-2" width="50" height="50">
                                        @else
                                        <img src="{{ route('getFile2', $conversation->technician->user->profile_picture ?? 'Default_Profile_Picture.png') }}"
                                            alt="User Avatar" class="rounded-circle border border-2" width="50" height="50">
                                        @endif
                                    </div>

                                    <div>
                                        <h5 class="mb-1 fw-semibold text-dark">
                                            @if($is_technician)
                                            {{ $conversation->user->first_name }} {{ $conversation->user->middle_name }} {{ $conversation->user->last_name }}
                                            @else
                                            {{ $conversation->technician->user->first_name }} {{ $conversation->technician->user->middle_name }} {{ $conversation->technician->user->last_name }}
                                            @endif
                                        </h5>

                                        <p class="mb-0 text-muted fst-italic">
                                            @if($is_technician)
                                            {{ $conversation->user->email }}
                                            @else
                                            {{ $conversation->technician->user->email }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Right: Status + Last Updated -->
                                <div class="text-end text-md-start">
                                    <div class="mb-1 small text-muted">

                                        @if($is_technician)
                                        User ID:
                                        {{ $conversation->technician_user_id }}
                                        @else
                                        Technician ID:
                                        {{ $conversation->user_id }}
                                        @endif
                                    </div>
                                    <div class="mb-2 small text-muted">
                                        Last Updated: {{ \Carbon\Carbon::parse($conversation->updated_at)->diffForHumans() }}
                                    </div>

                                    @if(isset($onlineStatuses[$conversation->id]) && $onlineStatuses[$conversation->id])
                                    <span class="badge bg-success text-white px-3 py-1">Online</span>
                                    @else
                                    <span class="badge bg-secondary text-white px-3 py-1">Offline</span>
                                    @endif
                                </div>
                            </div>

                        </a>
                        @endforeach
                    </div>

                </div>

            </main>

            @include('partials/footer')

        </div>
    </div>
</body>
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>

</html>