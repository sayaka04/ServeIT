<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login • ServeIT</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


  <!-- Inter font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  @include('partials/bootstrap')


  <style>
    /* =========================
       ServeIT Tech Theme (Dark)
       ========================= */
    :root {
      --brand-500: #3b82f6;
      --brand-600: #2563eb;
      /* neon blue */
      --brand-700: #1d4ed8;
      --accent-500: #10b981;
      --bg-900: #0b1220;
      /* page bg */
      --card-900: #0f172a;
      /* card bg */
      --border: #1f2a44;
      /* bluish border */
      --text: #e5e7eb;
      /* light text */
      --text-muted: #94a3b8;
      /* muted text */
      --glow: 0 10px 30px rgba(37, 99, 235, .35);
      --glow-strong: 0 16px 48px rgba(37, 99, 235, .45);
      --radius-card: 22px;
      --radius-btn: 14px;
    }

    html,
    body {
      height: 100%;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color: var(--text);
      background: var(--bg-900) !important;
      overflow-x: hidden;
    }

    /* Background aura + subtle animated grid */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background:
        radial-gradient(1100px 520px at 50% -8%, rgba(37, 99, 235, .18), transparent 60%),
        linear-gradient(180deg, rgba(59, 130, 246, .10), rgba(16, 185, 129, .04));
      pointer-events: none;
      z-index: -2;
    }

    body::after {
      content: "";
      position: fixed;
      inset: 0;
      background:
        linear-gradient(transparent 31px, rgba(59, 130, 246, .08) 32px),
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

    /* Center stage (keeps your navbar include if you leave it) */
    .stage {
      min-height: calc(100vh - 72px);
    }

    /* If you removed the navbar, use: .stage{ min-height:100vh; } */

    /* Glassy auth card */
    .auth-card {
      position: relative;
      background: linear-gradient(180deg, rgba(15, 23, 42, .85), rgba(11, 18, 32, .85));
      border: 1px solid var(--border);
      border-radius: var(--radius-card);
      box-shadow: var(--glow);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      overflow: hidden;
      color: var(--text);
    }

    .auth-card::before {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      top: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--brand-600), var(--brand-700));
      opacity: .95;
    }

    /* Labels & muted text */
    .form-label {
      color: var(--text);
      font-weight: 600;
    }

    .text-muted,
    .form-text {
      color: var(--text-muted) !important;
    }

    /* Inputs: dark glass + neon focus */
    .form-control {
      color: var(--text);
      background: linear-gradient(180deg, rgba(11, 18, 32, .35), rgba(15, 23, 42, .35));
      border: 1px solid rgba(59, 130, 246, .35);
      border-radius: 12px;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, .03);
    }

    .form-control::placeholder {
      color: #7a8aa1;
    }

    .form-control:focus {
      color: var(--text);
      background: linear-gradient(180deg, rgba(11, 18, 32, .45), rgba(15, 23, 42, .45));
      border-color: var(--brand-600);
      box-shadow: 0 0 0 .15rem rgba(37, 99, 235, .35), inset 0 1px 0 rgba(255, 255, 255, .04);
    }

    /* Checkbox */
    .form-check-input {
      background-color: rgba(15, 23, 42, .6);
      border-color: rgba(59, 130, 246, .45);
    }

    .form-check-input:checked {
      background-color: var(--brand-600);
      border-color: var(--brand-600);
      box-shadow: 0 0 0 .15rem rgba(37, 99, 235, .35);
    }

    /* Buttons */
    .btn-primary {
      --bs-btn-color: #fff;
      --bs-btn-bg: var(--brand-600);
      --bs-btn-border-color: var(--brand-600);
      --bs-btn-hover-bg: var(--brand-700);
      --bs-btn-hover-border-color: var(--brand-700);
      --bs-btn-focus-shadow-rgb: 37, 99, 235;
      border-radius: var(--radius-btn);
      padding: .68rem 1.2rem;
      box-shadow: var(--glow);
      transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--glow-strong);
    }

    /* Links */
    a,
    .link-primary {
      color: var(--brand-500);
      text-decoration: none;
    }

    a:hover,
    .link-primary:hover {
      color: var(--brand-700);
      text-decoration: underline;
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

      /* ---- Layout ---- */
      .stage {
        min-height: 100vh;
        padding: 70px 20px 40px 20px;
        /* Top padding for fixed back button */
      }
    }

    /* Alerts to match dark card */
    .alert-danger {
      background: linear-gradient(180deg, rgba(153, 27, 27, .15), rgba(127, 29, 29, .10));
      border-color: rgba(239, 68, 68, .35);
      color: #fecaca;
      border-radius: 12px;
    }

    /* Reduce motion */
    @media (prefers-reduced-motion: reduce) {

      .btn-primary,
      body::after {
        transition: none;
        animation: none;
      }
    }
  </style>
</head>

<body>

  <!-- Back to welcome -->
  <a href="{{ url('/') }}" class="back-link">
    <i class="bi bi-arrow-left"></i>
    <span>Back</span>
  </a>


  <div class="container d-flex justify-content-center align-items-center stage">
    <div class="auth-card p-4 p-md-5" style="width: 100%; max-width: 420px;">
      <h1 class="h4 fw-bold mb-3 text-center">
        Log in to
        <a href="{{ url('/') }}" class="text-decoration-none" style="color:var(--brand-500); transition:color .2s;">
          ServeIT
        </a>
      </h1>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        @if ($errors->has('email'))
        <div class="alert alert-danger mb-3">
          {{ $errors->first('email') }}
        </div>
        @endif

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="username"
            placeholder="username@email.com">
        </div>


        <div class="mb-2">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <input type="password"
              class="form-control"
              id="password"
              name="password"
              required
              placeholder="••••••••">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <script>
          document.addEventListener("DOMContentLoaded", function() {
            const password = document.getElementById("password");

            // Show/Hide password buttons
            document.querySelectorAll('#togglePassword, #toggleConfirmPassword').forEach(btn => {
              btn.addEventListener('click', () => {
                const input = btn.previousElementSibling;
                const icon = btn.querySelector('i');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-fill');
              });
            });

          });
        </script>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">Remember me</label>
          </div>
          <a class="text-muted" href="/forgot-password">Forgot your password?</a>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            Log in
          </button>
        </div>
      </form>
    </div>
  </div>

</body>

</html>