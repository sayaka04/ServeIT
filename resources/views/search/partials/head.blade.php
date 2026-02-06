<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Find Technicians</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />


    <script defer src="{{ asset('js/scripts.js') }}"></script>

    <script defer src="{{ asset('js/map/LocationHandler.js') }}"></script>
    {{-- <script defer src="{{ asset('js/map/LeafletHandler.js') }}"></script> --}}

    <script defer src="{{ asset('js/map/AjaxHandler.js') }}"></script>

    {{--<script defer src="{{ asset('js/map/Main.js') }}"></script>--}}
    <script>
        // Example static URL
        const theURL = "/fetch-contents";
    </script>
    <style>
        #map {
            height: calc(100vh * 0.7);
            width: calc(100vw * 0.9);
            border: 1px solid black;
        }

        .container {
            padding-top: 50px;
        }
    </style>
    @include('partials/bootstrap')

    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->

</head>