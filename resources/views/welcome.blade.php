<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ServeIT</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @include('partials/bootstrap')

  <style>
    /* =========================
           ServeIT Tech Theme (Preserved & Expanded)
           ========================= */
    :root {
      --brand-500: #3b82f6;
      --brand-600: #2563eb;
      --brand-700: #1d4ed8;
      --accent-500: #10b981;
      --bg-900: #0b1220;
      --card-900: #0f172a;
      --border: #1f2a44;
      --text: #e5e7eb;
      --text-muted: #94a3b8;
      --glow: 0 10px 30px rgba(37, 99, 235, .35);
      --radius-card: 22px;
      --radius-btn: 14px;
    }

    html,
    body {
      height: 100%;
      font-family: Inter, system-ui, sans-serif;
      color: var(--text);
      background: var(--bg-900) !important;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      /* Needed for sticky footer */
    }

    /* ---- Background Effects (Preserved) ---- */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: radial-gradient(1200px 600px at 50% -10%, rgba(37, 99, 235, .18), transparent 60%),
        linear-gradient(180deg, rgba(59, 130, 246, .10), rgba(16, 185, 129, .04));
      pointer-events: none;
      z-index: -2;
    }

    body::after {
      content: "";
      position: fixed;
      inset: 0;
      background: linear-gradient(transparent 31px, rgba(59, 130, 246, .08) 32px),
        linear-gradient(90deg, transparent 31px, rgba(59, 130, 246, .08) 32px);
      background-size: 32px 32px;
      mix-blend-mode: screen;
      opacity: .25;
      animation: scan 14s linear infinite;
      pointer-events: none;
      z-index: -1;
    }

    @keyframes scan {
      0% {
        transform: translate3d(0, 0, 0) rotate(0.001deg);
      }

      100% {
        transform: translate3d(-32px, -32px, 0) rotate(0.001deg);
      }
    }

    /* ---- NEW: Navigation Bar Styling ---- */
    .tech-navbar {
      background: rgba(11, 18, 32, 0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .nav-link {
      color: var(--text-muted) !important;
      font-weight: 500;
      transition: color 0.2s;
    }

    .nav-link:hover {
      color: var(--brand-500) !important;
    }

    /* ---- Hero Section ---- */
    .stage {
      flex: 1;
      /* Pushes footer down */
      padding: 80px 0;
    }

    /* ---- Card Styles (Preserved) ---- */
    .hero-card {
      position: relative;
      border-radius: var(--radius-card);
      background: linear-gradient(180deg, rgba(15, 23, 42, .85), rgba(11, 18, 32, .85));
      border: 1px solid var(--border);
      box-shadow: var(--glow);
      backdrop-filter: blur(10px);
      overflow: hidden;
    }

    .hero-card::before {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      top: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--brand-600), var(--brand-700));
      opacity: .95;
    }

    .gradient-text {
      background: linear-gradient(90deg, var(--brand-500), var(--brand-700));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 0 0 18px rgba(37, 99, 235, .25);
    }

    /* ---- Buttons ---- */
    .btn-brand {
      background: var(--brand-600);
      color: #fff;
      border: none;
      border-radius: var(--radius-btn);
      padding: .68rem 1.5rem;
      box-shadow: var(--glow);
      transition: all 0.2s ease;
    }

    .btn-brand:hover {
      background: var(--brand-700);
      transform: translateY(-2px);
      box-shadow: 0 16px 48px rgba(37, 99, 235, .45);
      color: white;
    }

    .btn-outline {
      background: rgba(15, 23, 42, .4);
      border: 1px solid rgba(59, 130, 246, .35);
      color: var(--text);
      border-radius: var(--radius-btn);
      padding: .68rem 1.5rem;
      transition: all 0.2s ease;
    }

    .btn-outline:hover {
      border-color: var(--brand-500);
      color: white;
      transform: translateY(-2px);
    }

    /* ---- NEW: Features Section (Below Hero) ---- */
    .feature-item {
      text-align: center;
      padding: 20px;
      color: var(--text-muted);
    }

    .feature-icon {
      font-size: 2rem;
      margin-bottom: 1rem;
      text-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    }

    .feature-title {
      color: var(--text);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    /* ---- NEW: Footer ---- */
    .tech-footer {
      background: var(--card-900);
      border-top: 1px solid var(--border);
      padding: 30px 0;
      margin-top: auto;
    }

    .footer-logo {
      height: 35px;
      opacity: 0.7;
      transition: opacity 0.3s;
    }

    .footer-logo:hover {
      opacity: 1;
    }
  </style>
</head>

<body>

  <style>
    /* 1. Force the container to behave like a standard block, ignoring dashboard flex rules */
    #isolated-navbar-container {
      all: initial;
      /* Resets inheritance */
      display: block !important;
      width: 100% !important;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
      position: relative !important;
      z-index: 99999 !important;
      background-color: #ffffff !important;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }

    /* 2. Re-implement Bootstrap basics strictly for this container */
    #isolated-navbar-container .custom-nav {
      display: flex !important;
      flex-wrap: wrap !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 1rem 1.5rem !important;
      max-width: 1200px !important;
      margin: 0 auto !important;
      background-color: white !important;
    }

    /* 3. Brand Styling */
    #isolated-navbar-container .custom-brand {
      font-size: 1.5rem !important;
      font-weight: 700 !important;
      color: #333 !important;
      text-decoration: none !important;
      white-space: nowrap !important;
    }

    /* 4. Toggler Button (Mobile) */
    #isolated-navbar-container .custom-toggler {
      display: none !important;
      /* Hidden on desktop */
      background: transparent !important;
      border: 1px solid #ddd !important;
      border-radius: 5px !important;
      padding: 5px 10px !important;
      cursor: pointer !important;
    }

    /* 5. The Menu Links */
    #isolated-navbar-container .custom-menu {
      display: flex !important;
      align-items: center !important;
      gap: 20px !important;
      list-style: none !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    #isolated-navbar-container .custom-link {
      color: #555 !important;
      text-decoration: none !important;
      font-weight: 600 !important;
      font-size: 1rem !important;
    }

    #isolated-navbar-container .custom-link:hover {
      color: #0d6efd !important;
    }

    /* 6. RESPONSIVE LOGIC (The part that was breaking) */
    @media (max-width: 991px) {

      /* Show the hamburger button */
      #isolated-navbar-container .custom-toggler {
        display: block !important;
      }

      /* Hide the menu by default */
      #isolated-navbar-container .custom-menu {
        display: none !important;
        width: 100% !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        padding-top: 20px !important;
        gap: 15px !important;
      }

      /* SHOW the menu when the .show class is added by JS */
      #isolated-navbar-container .custom-menu.show {
        display: flex !important;
      }

      /* Hide the divider on mobile */
      #isolated-navbar-container .custom-divider {
        display: none !important;
      }
    }
  </style>

  <div id="isolated-navbar-container">
    <div class="custom-nav">

      <a href="#" class="custom-brand">
        Serve<span style="color: #0d6efd;">IT</span>
      </a>
      <!-- <a href="/" class="custom-brand">
        <img src="{{ asset('img/serveit-logo.jpg') }}"
          alt="ServeIT Logo"
          style="height: 40px; width: auto; object-fit: contain;">
      </a> -->

      <button class="custom-toggler" onclick="toggleCustomMenu()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>

      <div id="customMenuLinks" class="custom-menu">
        <a href="#" class="custom-link">Home</a>
        <a href="/about" class="custom-link">About</a>
        <a href="/terms" class="custom-link">Terms</a>
        <a href="/privacy" class="custom-link">Privacy</a>

        <div class="custom-divider" style="border-left: 1px solid #ccc; height: 20px;"></div>

        @if(Auth::check())
        <a href="/dashboard" class="custom-link" style="color: #0d6efd;">Dashboard</a>
        @else
        <a href="{{ route('login') }}" class="custom-link">Login</a>
        <a href="{{ route('register') }}" class="custom-link" style="background: #0d6efd; color: white !important; padding: 8px 20px; border-radius: 50px;">Get Started</a>
        @endif
      </div>

    </div>
  </div>

  <script>
    function toggleCustomMenu() {
      var menu = document.getElementById('customMenuLinks');
      if (menu.classList.contains('show')) {
        menu.classList.remove('show');
      } else {
        menu.classList.add('show');
      }
    }
  </script>

  <main class="stage d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">

          <div class="hero-card text-center p-5 mb-5">
            <h1 class="mb-3 fw-bold display-5 text-white">
              Tech Repair <span class="gradient-text">Redefined</span>
            </h1>
            <p class="mb-4" style="color: var(--text-muted); font-size: 1.1rem;">
              Your trusted platform for finding expert technicians, tracking repairs in real-time, and ensuring quality service.
            </p>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
              @if(Auth::check())
              <a href="/dashboard" class="btn btn-brand">Go to Dashboard</a>
              @else
              <a href="{{ route('register') }}" class="btn btn-brand">Register</a>
              <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
              @endif
            </div>
          </div>

          <div class="row g-4 justify-content-center">
            <div class="col-md-4">
              <div class="feature-item">
                <div class="feature-icon">🛡️</div>
                <div class="feature-title">Verified Experts</div>
                <div class="small">Certified & trusted professionals.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-item">
                <div class="feature-icon">⚡</div>
                <div class="feature-title">Real-time Tracking</div>
                <div class="small">Monitor repair status instantly.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-item">
                <div class="feature-icon">📍</div>
                <div class="feature-title">Geo-Location</div>
                <div class="small">Find the nearest repair shop.</div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <footer class="tech-footer text-center">
    <div class="container">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-3 mb-md-0 text-start">
          <div class="fw-bold text-white">ServeIT Platform</div>
          <small style="color: var(--text-muted)">&copy; {{ date('Y') }} All rights reserved.</small>
        </div>

        <div class="text-end">
          <div class="small mb-1" style="color: var(--text-muted)">Developed by</div>
          <img src="{{ asset('img/company-logo-small-white.png') }}" alt="iThinkers" class="footer-logo">
        </div>
      </div>
    </div>
  </footer>

</body>

</html>