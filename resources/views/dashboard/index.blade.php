<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Dashboard (
        @if (Auth::user()->is_admin)
        Admin
        @elseif (Auth::user()->is_technician)
        Technician
        @else
        Client
        @endif
        )</title>

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Chart.js for charts (JavaScript library, not CSS) -->
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js"></script>

    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>


    @include('partials/bootstrap')

    <style>
        /* FIX for FullCalendar date numbers missing.
    The .fc-daygrid-day-number element is being hidden by another CSS rule.
*/
        .fc-daygrid-day-number {
            /* This is the most critical fix. It forces the text to be visible
        and readable, overriding any transparent color or hidden visibility. 
    */
            color: #333 !important;
            /* Sets the date number color to dark gray */
            opacity: 1 !important;
            /* Ensures it's fully opaque */
            visibility: visible !important;
            /* Ensures it is not hidden */

            /* Optional: Styling adjustments for better appearance */
            font-size: 0.85em;
            /* Adjust size if necessary */
            padding: 3px 5px;
            /* Gives it some padding */
            font-weight: bold;
            /* Makes the date number stand out */
        }

        /* Optional but recommended: Ensure the date number is layered correctly
    in case another element is covering it.
*/
        .fc-daygrid-day-top {
            z-index: 10;
        }

        .fc-daygrid-dot-event {
            display: none !important;
        }
    </style>

</head>

<body class="sb-nav-fixed">


    <!------------------------------>
    <!-----------Partials----------->
    <!------------NavBar------------>
    <!------------------------------>
    @include('partials/navigation-bar')

    <div id="layoutSidenav">

        <!------------------------------>
        <!-----------Partials----------->
        <!-----------SideBar------------>
        <!------------------------------>
        @include('partials/sidebar')

        <div id="layoutSidenav_content">

            <main>

                <div class="container-fluid ">

                    @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success text-center mb-4" role="alert">
                        A new verification link has been sent to your email address.
                    </div>
                    @endif

                    <h1 class="mt-1">Dashboard</h1>

                    @if(!Auth::user()->is_admin)
                    @if (Auth::user()->is_technician)
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="GET" action="{{ route('dashboard.index') }}" class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label for="year" class="form-label">Select year</label>
                                    <input
                                        type="date"
                                        id="year"
                                        name="year"
                                        class="form-control">
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Review & Ratings Summary Section -->
                    <div class=" row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm rounded-3 p-4">
                                <h4 class="mb-3 ">Review & Ratings Summary ✨</h4>
                                <div class="d-flex flex-column flex-md-row justify-content-around align-items-center">
                                    <div class="text-center mb-3 mb-md-0">
                                        <h5 class="fw-semibold">Total Ratings</h5>
                                        <p class="display-6 fw-bold text-dark">{{ $technician->jobs_completed }}</p>
                                    </div>
                                    <div class="vr mx-md-4 d-none d-md-block"></div> <!-- Vertical divider -->
                                    <div class="text-center">
                                        <h5 class="fw-semibold">Overall Score</h5>
                                        <p class="display-6 fw-bold text-dark">{{ $technician->weighted_score_rating }}<span class="text-warning">&#9733;</span></p> <!-- Star icon -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @include('dashboard/partials/row1')

                    @include('dashboard/partials/row2')

                    @include('dashboard/partials/row3')
                    @endif


                    @if(Auth::user()->is_admin)
                    @include('dashboard/partials/admin-reports')
                    @endif







                </div>

        </div>
        </main>



    </div>
    </div>
</body>

</html>