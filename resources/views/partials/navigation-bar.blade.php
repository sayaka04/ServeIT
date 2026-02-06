<nav class="sb-topnav navbar navbar-expand serveit-topnav fixed-top">
  <!-- Navbar Brand-->
  <a class="navbar-brand ps-3" href="{{ url('/') }}"> Serve<span class="text-dark" style="color: #0d6efd !important;">IT</span>
  </a>

  <!-- Sidebar Toggle-->
  <button class="btn btn-link btn-sm order-1 order-lg-0 ms-2" id="sidebarToggle" href="#!" style="margin-left:2rem;">
    <i class="fas fa-bars"></i>
  </button>


  <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>

  <!-- Navbar-->
  <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-user fa-fw"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

        @if(!Auth::user()->is_technician)
        <li>
          <a class="dropdown-item"
            href="{{ route('users.show', Auth::id()) }}">
            <i class="bi bi-person-lines-fill"></i> Profile Page
          </a>
        </li>
        @elseif(Auth::user()->is_technician)
        <li>
          <a class="dropdown-item"
            href="{{ route('technicians.show', ['technician' => Auth::user()->technician->technician_code]) }}">
            <i class="bi bi-person-lines-fill"></i> Profile Page
          </a>
        </li>
        @endif



        <li><a class="dropdown-item" href="{{ route('profile.update') }}"><i class="bi bi-person-fill-gear"></i> Account Information</a></li>
        <li>
          <hr class="dropdown-divider" />
        </li>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="dropdown-item">
            <i class="bi bi-box-arrow-left"></i> Log Out
          </button>
        </form>
      </ul>
    </li>
  </ul>
</nav>

<!-- ====== ServeIT Techy Navbar Styles ====== -->
<style>
  :root {
    --brand-500: #3b82f6;
    --brand-600: #2563eb;
    --brand-700: #1d4ed8;
    --text: #e5e7eb;
    --muted: #94a3b8;
    --border: #1f2a44;
    --bg-900: #0b1220;
    --panel: #0d1424;
  }

  .serveit-topnav {
    background: linear-gradient(180deg, rgba(13, 20, 36, .95), rgba(10, 17, 32, .95));
    border-bottom: 1px solid var(--border);
    color: var(--text);
    backdrop-filter: blur(8px);
    z-index: 1030;
    /* stays above everything */
  }

  .serveit-topnav .navbar-brand {
    color: var(--text);
    font-weight: 700;
    letter-spacing: -0.5px;
    transition: color .2s ease;
  }

  .serveit-topnav .navbar-brand:hover {
    color: var(--brand-500);
  }

  .serveit-topnav .nav-link {
    color: var(--muted);
    transition: color .2s ease;
  }

  .serveit-topnav .nav-link:hover {
    color: var(--text);
  }

  .serveit-topnav .dropdown-menu {
    background: #0f172a;
    border: 1px solid var(--border);
    border-radius: 10px;
  }

  .serveit-topnav .dropdown-item {
    color: var(--muted);
    transition: background .15s ease, color .15s ease;
  }

  .serveit-topnav .dropdown-item:hover {
    background: rgba(37, 99, 235, .15);
    color: var(--text);
  }

  #layoutSidenav_content {
    padding-top: 64px;
  }
</style>