@php
if(auth()->user()->email_verified_at !== null){
return redirect()->route('dashboard');
}
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @include('partials/bootstrap')

    <style>
        /* =========================
           ServeIT Core Theme (Dark)
           ========================= */
        :root {
            --brand-500: #3b82f6;
            --brand-600: #2563eb;
            --brand-700: #1d4ed8;
            --bg-900: #0b1220;
            --border: #1f2a44;
            --text: #e5e7eb;
            --text-muted: #94a3b8;
            --radius-card: 24px;
            --radius-btn: 12px;
            --glow: 0 0 20px rgba(37, 99, 235, 0.3);
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text);
            background: var(--bg-900) !important;
            overflow: hidden;
            /* Prevent scroll on this centered page */
        }

        /* ---- Background Effects ---- */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(37, 99, 235, 0.12), transparent 60%);
            pointer-events: none;
            z-index: -2;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(11, 18, 32, 0.85), rgba(11, 18, 32, 0.85)),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231f2a44' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: -1;
        }

        /* ---- Glass Card ---- */
        .verify-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), var(--glow);
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        /* Top Accent Line */
        .verify-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand-500), var(--brand-700));
        }

        /* ---- Typography & Icons ---- */
        .icon-circle {
            width: 80px;
            height: 80px;
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.15);
        }

        .heading-grad {
            background: linear-gradient(90deg, #fff, var(--brand-500));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 700;
        }

        /* ---- Buttons ---- */
        .btn-brand {
            background: var(--brand-600);
            color: white;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
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

        .btn-brand:disabled {
            background: var(--brand-700);
            opacity: 0.7;
            transform: none;
        }

        .btn-logout {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
            background: none;
            border: none;
            padding: 0;
        }

        .btn-logout:hover {
            color: var(--brand-500);
            text-decoration: underline;
        }

        /* ---- Alerts ---- */
        .alert-success-custom {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            border-radius: 12px;
            font-size: 0.9rem;
        }
    </style>
</head>


<body class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="container d-flex justify-content-center">

        <div class="verify-card p-5 text-center">

            <div class="icon-circle">
                <i class="bi bi-envelope-check-fill fs-1 text-primary"></i>
            </div>

            <h2 class="heading-grad mb-3">Verify Your Account</h2>

            <p class="text-muted mb-4">
                We've sent a verification email to your inbox. Please click the link inside to activate your full access to ServeIT.
            </p>

            @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success-custom mb-4 fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> A new verification link has been sent!
            </div>
            @endif

            @if (session('status') !== 'verification-link-sent')
            <form id="resendForm" method="POST" action="{{ route('verification.resend') }}" class="mb-3">
                @csrf
                <button type="submit" id="resendButton" class="btn btn-brand">
                    <span id="buttonText">Resend Verification Email</span>
                    <div id="spinner" class="spinner-border spinner-border-sm text-white ms-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </form>

            <p class="small text-muted mb-4">
                Didn't receive it? Check your spam folder.
            </p>
            @endif

            <hr style="border-color: var(--border); opacity: 0.5; margin: 1.5rem 0;">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-left me-1"></i> Log out
                </button>
            </form>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const resendForm = document.getElementById('resendForm');
            const resendButton = document.getElementById('resendButton');
            const spinner = document.getElementById('spinner');
            const buttonText = document.getElementById('buttonText');

            if (resendForm) {
                resendForm.addEventListener('submit', function() {
                    // Show spinner and disable button
                    spinner.classList.remove('d-none');
                    buttonText.textContent = 'Sending...';
                    resendButton.disabled = true;
                    resendButton.style.cursor = 'not-allowed';
                });
            }
        });
    </script>

</body>

</html>