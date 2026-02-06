<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ServeIT - About Us</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* =========================
           ServeIT Tech Theme (Global)
           ========================= */
        :root {
            --brand-500: #3b82f6;
            --brand-600: #2563eb;
            --brand-700: #1d4ed8;
            --bg-900: #0b1220;
            --card-900: #0f172a;
            --border: #1f2a44;
            --text: #e5e7eb;
            --text-muted: #94a3b8;
            --glow: 0 10px 30px rgba(37, 99, 235, .25);
            --radius-card: 22px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-900);
            color: var(--text);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ---- Background Effects ---- */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: radial-gradient(1200px 600px at 50% -10%, rgba(37, 99, 235, .15), transparent 60%),
                radial-gradient(circle at 80% 80%, rgba(16, 185, 129, .05), transparent 40%);
            pointer-events: none;
            z-index: -2;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(transparent 31px, rgba(59, 130, 246, .05) 32px),
                linear-gradient(90deg, transparent 31px, rgba(59, 130, 246, .05) 32px);
            background-size: 32px 32px;
            mix-blend-mode: screen;
            opacity: .2;
            pointer-events: none;
            z-index: -1;
        }

        /* ---- Terms Content Card ---- */
        .policy-card {
            background: rgba(15, 23, 42, 0.7);
            /* Glass effect */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            color: var(--text-muted);
            position: relative;
            overflow: hidden;
        }

        /* Top Blue Line Accent */
        .policy-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand-500), var(--brand-700));
        }

        /* Typography */
        h1,
        h3,
        h5 {
            color: #fff;
            font-weight: 700;
        }

        h1 {
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        h3 {
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Numbering Accent (Removed for About Page) */
        h3::before {
            /* content: "#";  <-- Removed this so headings don't have hashtags */
            color: var(--brand-500);
            opacity: 0.7;
        }

        h5 {
            margin-top: 1.5rem;
            font-size: 1.1rem;
            color: var(--text);
            border-left: 3px solid var(--brand-600);
            padding-left: 10px;
        }

        p,
        li {
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        strong {
            color: var(--brand-500);
            font-weight: 600;
        }

        hr {
            border-color: var(--border);
            opacity: 0.5;
            margin: 2rem 0;
        }

        /* Links */
        a {
            color: var(--brand-500);
            text-decoration: none;
            transition: 0.2s;
        }

        a:hover {
            color: #fff;
            text-shadow: 0 0 10px var(--brand-500);
        }

        /* Team Grid Styles (Added for About Page) */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .team-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--border);
        }

        .team-avatar {
            width: 60px;
            height: 60px;
            background: var(--border);
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--brand-500);
        }

        /* Footer Styling */
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
        #isolated-navbar-container {
            all: initial;
            display: block !important;
            width: 100% !important;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 99999 !important;
            background-color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

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

        #isolated-navbar-container .custom-brand {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #333 !important;
            text-decoration: none !important;
            white-space: nowrap !important;
        }

        #isolated-navbar-container .custom-toggler {
            display: none !important;
            background: transparent !important;
            border: 1px solid #ddd !important;
            border-radius: 5px !important;
            padding: 5px 10px !important;
            cursor: pointer !important;
        }

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

        @media (max-width: 991px) {
            #isolated-navbar-container .custom-toggler {
                display: block !important;
            }

            #isolated-navbar-container .custom-menu {
                display: none !important;
                width: 100% !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                padding-top: 20px !important;
                gap: 15px !important;
            }

            #isolated-navbar-container .custom-menu.show {
                display: flex !important;
            }

            #isolated-navbar-container .custom-divider {
                display: none !important;
            }
        }
    </style>

    <div id="isolated-navbar-container">
        <div class="custom-nav">
            <a href="/" class="custom-brand">
                Serve<span style="color: #0d6efd;">IT</span>
            </a>
            <button class="custom-toggler" onclick="toggleCustomMenu()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <div id="customMenuLinks" class="custom-menu">
                <a href="/" class="custom-link">Home</a>
                <a href="#" class="custom-link" style="color: #0d6efd;">About</a> <a href="/terms" class="custom-link">Terms</a>
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
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="policy-card p-4 p-md-5">

                    <div class="text-center mb-5">
                        <h1>About ServeIT</h1>
                        <p>Bridging the gap between <span class="text-white">Gadget Owners</span> and <span class="text-white">Technicians</span>.</p>
                    </div>

                    <p class="lead" style="text-align: justify;">
                        Welcome to <strong>ServeIT</strong>. We are a web-based service matching platform driven by a simple observation in Davao City: finding a reliable, fair-priced repair technician is often a game of chance. Meanwhile, many skilled technicians struggle to find clients simply because they lack a physical shop.
                    </p>

                    <hr>

                    <h3>Our Story</h3>
                    <p>
                        ServeIT began as a <strong>Capstone Project</strong> with a clear goal: to digitalize the local repair economy. We realized that trust is the currency of the service industry. By leveraging technology, we created a platform where visibility is based on <strong>merit and skill</strong>, not just location.
                    </p>
                    <p>
                        Our team of student innovators developed this solution to ensure that every repair transaction is secure, transparent, and mutually beneficial.
                    </p>

                    <hr>

                    <h3>Our Mission</h3>
                    <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid var(--brand-500); padding: 15px; border-radius: 0 8px 8px 0; margin-bottom: 20px;">
                        <p style="margin-bottom: 0; font-style: italic;">
                            "To provide a secure, transparent, and efficient marketplace that empowers informal technicians with a digital identity while giving gadget owners peace of mind."
                        </p>
                    </div>
                    <p>
                        We align our goals with <strong>SDG 8 (Decent Work and Economic Growth)</strong> by formalizing the informal sector. We believe that every skilled technician deserves a "Digital Storefront" to compete fairly with established businesses.
                    </p>

                    <hr>

                    <h3>What We Do</h3>
                    <p>ServeIT offers a suite of tools designed to modernize the repair experience:</p>

                    <h5>Verified Professionals</h5>
                    <p>We use automated <strong>TESDA Verification</strong> to confirm the legitimacy of technicians, ensuring that you are dealing with qualified experts.</p>

                    <h5>Smart Matching</h5>
                    <p>Our <strong>Weighted Scoring Algorithm</strong> and Proximity Mapping help you find the best technician for your specific needs, closest to your location.</p>

                    <h5>Transparent Workflow</h5>
                    <p>From the moment you hand over your device, you can track the repair status in real-time—from "Diagnosing" to "Repairing" to "Completed"—via your dashboard.</p>

                    <hr>

                    {{--

                    <h3>The Team</h3>
                    <p>We are a group of passionate developers and researchers.</p>

                    <div class="team-grid">
                        <div class="team-card">
                            <div class="team-avatar">RL</div>
                            <div style="color: white; font-weight: bold;">Richard L. Limama</div>
                            <div style="font-size: 0.9em; color: var(--brand-500);">Developer / Lead</div>
                        </div>
                        <div class="team-card">
                            <div class="team-avatar">JA</div>
                            <div style="color: white; font-weight: bold;">Jade Elize P. Asgapo</div>
                            <div style="font-size: 0.9em; color: var(--brand-500);">UI/UX & Research</div>
                        </div>
                        <div class="team-card">
                            <div class="team-avatar">M3</div>
                            <div style="color: white; font-weight: bold;">[Member Name]</div>
                            <div style="font-size: 0.9em; color: var(--brand-500);">System Analyst</div>
                        </div>
                    </div>

--}}

                </div>
            </div>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>