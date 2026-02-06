<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Notifications</title>

  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">


  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
        <div class="container-fluid py-4">

          <h2 class="mb-4">Notifications</h2>

          @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif

          @forelse ($notifications as $notification)
          <div class="btn btn-white list-group-item text-start p-4 mb-3 shadow-sm rounded-4 border border-light-subtle 
        {{ $notification->has_seen ? 'bg-light text-muted' : 'bg-white' }}">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">

              <a href="{{ route('notifications.show', $notification->id) }}" class="text-decoration-none flex-grow-1 me-md-3">
                <h5 class="mb-1 {{ $notification->has_seen ? 'fw-normal text-muted' : 'fw-semibold text-dark' }}">
                  {{ $notification->subject }}
                </h5>
                <p class="mb-2 text-truncate text-muted" style="max-width: 100%;">
                  {{ \Illuminate\Support\Str::limit($notification->description, 100) }}
                </p>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
              </a>

              <div class="d-flex align-items-center mt-3 mt-md-0">
                @if ($notification->notifiable_type)
                <a href="{{ route('notifications.view-resource', $notification->id) }}" class="btn btn-outline-primary btn-sm me-2">
                  View Resource
                </a>
                @endif

                @if (!$notification->has_seen)
                <span class="badge bg-primary rounded-pill px-3 py-2">New</span>
                @endif
              </div>
            </div>

          </div>
          @empty
          <div class="alert alert-info text-center" role="alert">
            No notifications found.
          </div>
          @endforelse
        </div>

      </main>

      @include('partials/footer')
    </div>
  </div>

</body>

</html>