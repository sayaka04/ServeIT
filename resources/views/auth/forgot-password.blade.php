<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ServeIT - Forgot Password</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @include('partials/bootstrap') {{-- your Bootstrap include --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =========================
           ServeIT Tech Theme (Dark)
           ========================= */
        :root {
            --brand-500: #3b82f6;
            --brand-600: #2563eb;
            --brand-700: #1d4ed8;
            --accent-500: #10b981;
            --accent-600: #059669;
            --bg-900: #0b1220;
            --card-900: #0f172a;
            --border: #1f2a44;
            --text: #e5e7eb;
            --text-muted: #94a3b8;
            --glow: 0 10px 30px rgba(37, 99, 235, .35);
            --glow-strong: 0 16px 48px rgba(37, 99, 235, .45);
            --glow-green: 0 10px 30px rgba(16, 185, 129, .30);
            --radius-card: 22px;
            --radius-btn: 14px;
        }

        body {
            height: 100%;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            color: var(--text);
            background: var(--bg-900) !important;
        }

        .stage {
            min-height: calc(100vh - 72px);
        }

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

        .gradient-text {
            background: linear-gradient(90deg, var(--brand-500), var(--brand-700));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 18px rgba(37, 99, 235, .25);
        }

        .btn-brand {
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

        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow-strong);
        }

        .back-link {
            position: fixed;
            top: 24px;
            left: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: .5rem .75rem;
            border-radius: 12px;
            color: var(--text);
            text-decoration: none;
            border: 1px solid rgba(59, 130, 246, .35);
            background: linear-gradient(180deg, rgba(11, 18, 32, .40), rgba(15, 23, 42, .40));
            box-shadow: 0 8px 24px rgba(2, 6, 23, .25);
            z-index: 10;
        }

        .back-link:hover {
            border-color: rgba(59, 130, 246, .65);
            color: var(--brand-500);
            box-shadow: var(--glow);
        }
    </style>
</head>

<body>

    <!-- Back to welcome -->
    <a href="{{ url('/') }}" class="back-link" aria-label="Back to ServeIT welcome">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M15 18l-6-6 6-6"></path>
        </svg>
        <span>Back</span>
    </a>

    <div class="container d-flex justify-content-center align-items-center stage">
        <div class="auth-card p-4 p-md-5 shadow-lg border-0" style="width: 100%; max-width: 440px;">
            <h1 class="h4 fw-bold text-center mb-4">
                <span class="gradient-text">Reset Password</span>
            </h1>

            <!-- Session Status -->
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-brand btn-lg">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>