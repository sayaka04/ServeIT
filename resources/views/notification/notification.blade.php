<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Dashboard - SB Admin</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script defer src="{{ asset('js/scripts.js') }}"></script>

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />



    @include('partials/bootstrap')

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
                <div class="container-fluid px-4">

                    <h1 class="mt-4">Notifications</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">User_type / Notifications</li>
                    </ol>



                </div>
            </main>
            <!------------------------------>
            <!-----------Partials----------->
            <!-----------SideBar------------>
            <!------------------------------>
            @include('partials/footer')


        </div>
    </div>
</body>

</html>