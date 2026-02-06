<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Profile & Security</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <script defer src="{{ asset('js/scripts.js') }}"></script>

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


                    <h1 class="mb-4">Profile</h1>

                    {{-- Success message --}}
                    @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                    @endif



                    <div class="mb-5">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                Update Profile Information
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                Update Password
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card shadow-sm border-danger">
                            <div class="card-header bg-danger text-white">
                                Delete Account
                            </div>
                            <div class="card-body">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>


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