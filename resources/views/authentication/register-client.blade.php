<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <title>Client Registration</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  @include('partials/bootstrap')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* =========================
           ServeIT Core Theme
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

    /* ---- Background Effects (Matches Selection Page) ---- */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.15), transparent 70%);
      pointer-events: none;
      z-index: -2;
    }

    /* Tech Grid Pattern */
    body::after {
      content: "";
      position: fixed;
      inset: 0;
      background: linear-gradient(rgba(11, 18, 32, 0.8), rgba(11, 18, 32, 0.8)),
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231f2a44' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      z-index: -1;
    }

    /* ---- Layout ---- */
    .stage {
      min-height: 100vh;
      padding: 40px 20px;
    }

    /* ---- Glass Card ---- */
    .auth-card {
      width: 100%;
      max-width: 600px;
      /* Slightly wider for grid layout */
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid var(--border);
      border-radius: var(--radius-card);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      position: relative;
      overflow: hidden;
    }

    /* Top Gradient Border */
    .auth-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--brand-500), transparent);
      opacity: 0.8;
    }

    /* ---- Typography ---- */
    .heading-grad {
      background: linear-gradient(90deg, #fff, var(--brand-500));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 0 0 30px rgba(37, 99, 235, 0.3);
    }

    /* ---- Forms ---- */
    .form-label {
      color: var(--text);
      font-weight: 500;
      font-size: 0.9rem;
      margin-bottom: 0.4rem;
    }

    .form-control {
      background: rgba(11, 18, 32, 0.6);
      border: 1px solid var(--border);
      color: white;
      padding: 0.7rem 1rem;
      border-radius: var(--radius-btn);
      transition: all 0.2s;
    }

    .form-control:focus {
      background: rgba(11, 18, 32, 0.9);
      border-color: var(--brand-500);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
      color: white;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.2);
    }

    /* Input Group (Password Eyes) */
    .input-group-text,
    .btn-eye {
      background: rgba(11, 18, 32, 0.6);
      border: 1px solid var(--border);
      border-left: none;
      color: var(--text-muted);
    }

    .btn-eye:hover {
      color: var(--brand-500);
      background: rgba(11, 18, 32, 0.8);
      border-color: var(--border);
    }

    /* ---- Buttons ---- */
    .btn-brand {
      background: var(--brand-600);
      color: white;
      font-weight: 600;
      padding: 0.8rem;
      border-radius: var(--radius-btn);
      border: none;
      width: 100%;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-brand:hover {
      background: var(--brand-700);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
      color: white;
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

    /* ---- Loading Overlay ---- */
    #loadingSpinner {
      backdrop-filter: blur(5px);
    }
  </style>
</head>

<body>

  <a href="{{ route('register') }}" class="back-link">
    <i class="bi bi-arrow-left"></i>
    <span>Change Role</span>
  </a>

  <div id="loadingSpinner" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(11, 18, 32, 0.8); z-index: 2000; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
    <h5 class="text-white fw-light animate-pulse">Creating your account...</h5>
  </div>

  <div class="container stage d-flex justify-content-center align-items-center">

    <div class="auth-card p-4 p-md-5">

      <div class="text-center mb-4">
        <div class="mb-2">
          <span style="height: 50px; width: 50px; background: rgba(59, 130, 246, 0.1); color: var(--brand-500); display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid rgba(59, 130, 246, 0.2);">
            <i class="bi bi-person-fill fs-4"></i>
          </span>
        </div>
        <h2 class="fw-bold heading-grad mb-1">Client Registration</h2>
        <p class="text-muted small">Create an account to find technicians</p>
      </div>

      <form method="POST" action="{{ route('register-client-create') }}">
        @csrf

        @if (session('error'))
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4">
          <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
        @endif

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label for="first-name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first-name" name="first_name" required placeholder="Juan" value="{{old('first_name')}}">
          </div>
          <div class="col-md-4">
            <label for="middle-name" class="form-label">Middle</label>
            <input type="text" class="form-control" id="middle-name" name="middle_name" placeholder="S." value="{{old('middle_name')}}">
          </div>
          <div class="col-md-4">
            <label for="last-name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last-name" name="last_name" required placeholder="Cruz" value="{{old('last_name')}}">
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="name@email.com" value="{{old('email')}}">
          </div>
          <div class="col-md-6">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone_number" name="phone_number" required placeholder="0917xxxxxxx" pattern="[0-9]{11}" value="{{old('phone_number')}}">
          </div>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-12">
            <div id="passwordAlert" class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning d-none py-2 small">
              <i class="bi bi-exclamation-triangle me-1"></i> Passwords do not match
            </div>
          </div>

          <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <input type="password" class="form-control border-end-0" id="password" name="password" required placeholder="••••••••" minlength="8">
              <button class="btn btn-eye" type="button" id="togglePassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
          <div class="col-md-6">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="input-group">
              <input type="password" class="form-control border-end-0" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
              <button class="btn btn-eye" type="button" id="toggleConfirmPassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="flexCheckDefault" required style="background-color: transparent; border-color: var(--border);">
            <label class="form-check-label text-muted small" for="flexCheckDefault">
              I agree to the <a href="/terms" class="text-primary text-decoration-none">Terms of Service</a> & <a href="/privacy" class="text-primary text-decoration-none">Privacy Policy</a>
            </label>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-brand btn-lg">Complete Registration</button>
        </div>

        <div class="text-center mt-4">
          <span class="text-muted small">Already have an account?</span>
          <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1">Sign In</a>
        </div>

      </form>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Password Toggle Logic
      document.querySelectorAll('#togglePassword, #toggleConfirmPassword').forEach(btn => {
        btn.addEventListener('click', () => {
          const input = btn.previousElementSibling;
          const icon = btn.querySelector('i');
          input.type = input.type === 'password' ? 'text' : 'password';
          icon.classList.toggle('bi-eye');
          icon.classList.toggle('bi-eye-slash');
        });
      });

      // Live Password Match Check
      const p1 = document.getElementById("password");
      const p2 = document.getElementById("password_confirmation");
      const alertBox = document.getElementById("passwordAlert");

      function checkMatch() {
        if (p2.value && p1.value !== p2.value) {
          alertBox.classList.remove("d-none");
        } else {
          alertBox.classList.add("d-none");
        }
      }
      p1.addEventListener("input", checkMatch);
      p2.addEventListener("input", checkMatch);

      // Form Submit Loading State
      document.querySelector('form').addEventListener('submit', function(e) {
        if (this.checkValidity()) {
          document.getElementById('loadingSpinner').classList.remove('d-none');
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>