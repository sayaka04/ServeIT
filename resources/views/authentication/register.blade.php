<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Join ServeIT</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  @include('partials/bootstrap')
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* =========================
           ServeIT Core Theme (Shared)
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
      --radius-card: 24px;
      --radius-btn: 12px;
    }

    html,
    body {
      height: 100%;
      font-family: 'Inter', system-ui, sans-serif;
      color: var(--text);
      background: var(--bg-900) !important;
      overflow-x: hidden;
    }

    /* ---- Background Effects (Consistent with Home) ---- */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.15), transparent 70%);
      pointer-events: none;
      z-index: -2;
    }

    body::after {
      content: "";
      position: fixed;
      inset: 0;
      background: linear-gradient(rgba(11, 18, 32, 0.8), rgba(11, 18, 32, 0.8)),
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231f2a44' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      z-index: -1;
    }

    /* ---- Layout Helper ---- */
    .stage {
      min-height: 100vh;
      padding: 20px;
    }

    /* ---- Back Button Base Styles ---- */
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;

      /* Glass Style */
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 50px;

      color: #e2e8f0;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      z-index: 9999;
      transition: all 0.2s ease;
    }

    .back-link:hover {
      background: rgba(37, 99, 235, 0.2);
      color: white;
      transform: translateX(-3px);
    }

    /* ---- DESKTOP (Floating) ---- */
    @media (min-width: 768px) {
      .back-link {
        position: fixed;
        /* Floats in corner */
        top: 30px;
        left: 30px;
      }
    }

    /* ---- MOBILE (In-Flow) ---- */
    @media (max-width: 767px) {
      .back-link {
        position: relative;
        /* Takes up physical space */
        margin: 20px 0 0 20px;
        /* Add margin to position it nicely */
        display: inline-flex;
        /* This ensures the content below gets pushed down */
      }

      /* Optional: Adjust the stage padding so there isn't a double gap */
      .stage {
        padding-top: 10px !important;
        min-height: auto !important;
        /* Prevents double scrollbars on mobile */
        height: auto !important;
        padding-bottom: 40px;
      }
    }

    /* ---- The Split Card Container ---- */
    .selection-container {
      width: 100%;
      max-width: 900px;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(12px);
      border: 1px solid var(--border);
      border-radius: var(--radius-card);
      overflow: hidden;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      position: relative;
    }

    /* Gradient Top Border */
    .selection-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--brand-500), transparent);
      opacity: 0.5;
    }

    /* ---- Interactive Choice Cards ---- */
    .choice-card {
      padding: 3rem 2rem;
      text-align: center;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid transparent;
      cursor: pointer;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-decoration: none !important;
      /* Remove link underline */
      position: relative;
      background: transparent;
    }

    /* Vertical Divider (Desktop only) */
    .choice-col:first-child {
      border-right: 1px solid var(--border);
    }

    /* Hover Effects */
    .choice-card:hover {
      background: rgba(37, 99, 235, 0.05);
    }

    .choice-card:hover .icon-circle {
      background: var(--brand-600);
      box-shadow: 0 0 30px rgba(37, 99, 235, 0.4);
      transform: scale(1.1) rotate(-5deg);
      color: white;
    }

    .choice-card:hover .choice-title {
      color: white;
    }

    .choice-card:hover .btn-fake {
      background: var(--brand-600);
      border-color: var(--brand-600);
      color: white;
    }

    /* Icons */
    .icon-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: rgba(30, 41, 59, 0.5);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      font-size: 2rem;
      color: var(--brand-500);
      transition: all 0.3s ease;
    }

    /* Typography */
    .choice-title {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: var(--text);
      transition: color 0.2s;
    }

    .choice-desc {
      color: var(--text-muted);
      font-size: 0.95rem;
      margin-bottom: 2rem;
      line-height: 1.5;
      max-width: 280px;
    }

    /* Fake Button Visual */
    .btn-fake {
      padding: 0.75rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      border: 1px solid var(--border);
      color: var(--text-muted);
      transition: all 0.2s;
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
    }

    /* Mobile Adjustments */
    @media (max-width: 767px) {
      .choice-col:first-child {
        border-right: none;
        border-bottom: 1px solid var(--border);
      }

      .choice-card {
        padding: 2.5rem 1.5rem;
      }
    }
  </style>
</head>

<body>

  <a href="{{ url('/') }}" class="back-link">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M19 12H5"></path>
      <path d="M12 19l-7-7 7-7"></path>
    </svg>
    <span>Back to Home</span>
  </a>

  <div class="stage d-flex flex-column justify-content-center align-items-center">

    <div class="text-center mb-5 fade-in">
      <h1 class="fw-bold display-6 mb-2 text-white">Join <span style="color: var(--brand-500)">ServeIT</span></h1>
      <p class="text-muted fs-5">Select your account type to get started</p>
    </div>

    <div class="selection-container container-fluid p-0">
      <div class="row g-0">

        <div class="col-md-6 choice-col">
          <a href="{{ route('register-client') }}" class="choice-card">
            <div class="icon-circle">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <h2 class="choice-title">Client</h2>
            <p class="choice-desc">I am looking for expert technicians to fix my devices.</p>
            <span class="btn-fake">Register as Client</span>
          </a>
        </div>

        <div class="col-md-6 choice-col">
          <a href="{{ route('register-technician') }}" class="choice-card">
            <div class="icon-circle">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
              </svg>
            </div>
            <h2 class="choice-title">Technician</h2>
            <p class="choice-desc">I am a professional who wants to offer repair services.</p>
            <span class="btn-fake">Register as Tech</span>
          </a>
        </div>

      </div>
    </div>

    <div class="mt-4 text-center">
      <span class="text-muted">Already have an account?</span>
      <a href="{{ route('login') }}" class="fw-bold ms-1" style="color: var(--brand-500)">Log In</a>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>