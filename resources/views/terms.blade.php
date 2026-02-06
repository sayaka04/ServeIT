<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ServeIT - Terms and Conditions</title>

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

        /* Numbering Accent */
        h3::before {
            content: "#";
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
                <a href="/about" class="custom-link">About</a>
                <a href="#" class="custom-link">Terms</a>
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
                        <h1>Terms and Conditions</h1>
                        <p>Last Updated: <span class="text-white">1/12/2025</span></p>
                    </div>

                    <p>Welcome to <strong>ServeIT</strong>. These Terms and Conditions (“Terms”) govern your access and use of the ServeIT platform. By creating an account or using any part of the platform, you agree to be bound by these Terms. If you do not agree, you must discontinue use of the service.</p>

                    <hr>

                    <h3>1. About ServeIT</h3>
                    <p>ServeIT is a web platform that helps clients in Davao City find trusted gadget technicians. ServeIT provides:</p>
                    <ul>
                        <li>Technician matching via a <strong>Weighted Scoring Algorithm</strong></li>
                        <li>A <strong>Proximity Mapping System</strong> that shows approximate technician locations</li>
                        <li>Encrypted chat</li>
                        <li>Technician portfolios</li>
                        <li>Repair task management and progress tracking</li>
                    </ul>
                    <p>ServeIT is a <strong>facilitator</strong>, not the provider of the repair service. All repair services are performed by independent technicians.</p>

                    <hr>

                    <h3>2. Acceptance of Terms</h3>
                    <p>By accessing or using ServeIT, you agree to:</p>
                    <ul>
                        <li>Comply with these Terms and Conditions</li>
                        <li>Provide accurate information</li>
                        <li>Use the platform only for lawful and intended purposes</li>
                    </ul>
                    <p>If you violate these Terms, ServeIT may suspend or terminate your access.</p>

                    <hr>

                    <h3>3. User Accounts</h3>
                    <p>To use ServeIT, users must create an account and provide accurate, complete information. You are responsible for:</p>
                    <ul>
                        <li>Keeping your login credentials secure</li>
                        <li>All activity under your account</li>
                        <li>Updating your information when necessary</li>
                    </ul>
                    <p>ServeIT may suspend accounts suspected of fraud, misuse, or violations.</p>

                    <hr>

                    <h3>4. Platform Features and Usage</h3>

                    <h5>4.1 Technician Search & Profiles</h5>
                    <p>Users may browse technician profiles, reviews, skills, portfolios, and estimated proximity. ServeIT does not guarantee the accuracy of information posted by technicians.</p>

                    <h5>4.2 Encrypted Messaging</h5>
                    <p>ServeIT provides encrypted chat for communication between clients and technicians.</p>
                    <ul>
                        <li>The chat is encrypted, meaning <strong>ServeIT does not access or monitor message content</strong>.</li>
                        <li>Users must not use the chat for illegal activities or harassment.</li>
                    </ul>

                    <h5>4.3 Repair Tracking</h5>
                    <p>Technicians can update repair status, progress, and timelines. ServeIT is not responsible for delays or inaccuracies posted by technicians.</p>

                    <hr>

                    <h3>5. Technician Recommendations</h3>
                    <p>ServeIT uses a <strong>Weighted Scoring Algorithm</strong> to recommend technicians. The weights were determined from a <strong>completed user survey</strong>, where respondents allocated importance rankings totaling 100 points.</p>
                    <p>The algorithm helps users find suitable technicians but <strong>does not guarantee quality, performance, or outcomes</strong>.</p>
                    <p>Users are encouraged to review technician credentials and feedback before making any commitments.</p>

                    <hr>

                    <h3>6. Proximity Mapping System</h3>
                    <p>ServeIT displays only <strong>approximate locations</strong> of technicians for privacy. Exact GPS coordinates are <strong>never</strong> shown or shared. ServeIT is not responsible for any inaccuracies in proximity estimates.</p>

                    <hr>

                    <h3>7. Payments and Pricing</h3>
                    <p>Payment terms, methods, and pricing are set <strong>directly between the client and technician</strong>. ServeIT:</p>
                    <ul>
                        <li>Does not dictate repair prices</li>
                        <li>Is not responsible for payment disputes</li>
                        <li>Is not involved in handling money unless a separate secure payment system is added in the future</li>
                    </ul>
                    <p>Users should clarify pricing before approving repairs.</p>

                    <hr>

                    <h3>8. User Responsibilities</h3>

                    <h5>Clients must:</h5>
                    <ul>
                        <li>Provide accurate information about their device and repair needs</li>
                        <li>Evaluate technicians before agreeing to service</li>
                        <li>Act respectfully and professionally</li>
                    </ul>

                    <h5>Technicians must:</h5>
                    <ul>
                        <li>Provide truthful profile information and qualifications</li>
                        <li>Maintain professional conduct</li>
                        <li>Deliver services with reasonable care and skill</li>
                    </ul>

                    <hr>

                    <h3>9. Prohibited Activities</h3>
                    <p>Users are prohibited from:</p>
                    <ul>
                        <li>Misrepresenting identity or skills</li>
                        <li>Using the platform for illegal purposes</li>
                        <li>Attempting to hack, disrupt, or exploit ServeIT</li>
                        <li>Abusing or harassing other users</li>
                        <li>Posting false, harmful, or inappropriate content</li>
                        <li>Circumventing the platform’s safety features (e.g., location privacy)</li>
                    </ul>
                    <p>ServeIT may take action against violators, including warnings, suspension, or account termination.</p>

                    <hr>

                    <h3>10. Misconduct Reports and Investigations</h3>
                    <p>If misconduct, fraud, or policy violations are reported:</p>
                    <ul>
                        <li>ServeIT <strong>does not access encrypted messages</strong></li>
                        <li>Investigations rely solely on:
                            <ul>
                                <li>Repair progress logs</li>
                                <li>Account activity records</li>
                                <li>Evidence voluntarily submitted by users (photos, screenshots, files, etc.)</li>
                            </ul>
                        </li>
                    </ul>
                    <p>ServeIT may apply appropriate consequences based on verified information.</p>

                    <hr>

                    <h3>11. Data and Privacy</h3>
                    <p>ServeIT collects only essential information needed to operate the platform. We do not sell or leak user data.</p>
                    <p>For full details, refer to the <a href="/privacy"><strong>ServeIT Privacy Policy</strong></a>.</p>

                    <hr>

                    <h3>12. Intellectual Property</h3>
                    <p>All ServeIT branding, content, designs, and platform features are owned by ServeIT and protected by copyright laws.</p>
                    <p>Users may not:</p>
                    <ul>
                        <li>Copy, modify, distribute, or sell any part of the platform</li>
                        <li>Use ServeIT branding without permission</li>
                    </ul>

                    <hr>

                    <h3>13. Third-Party Content</h3>
                    <p>Technician profiles, reviews, and user-submitted content reflect the views of individual users, not ServeIT.</p>
                    <p>ServeIT is not responsible for:</p>
                    <ul>
                        <li>Misleading information provided by users</li>
                        <li>Technician qualifications or performance</li>
                        <li>Damages caused by technicians</li>
                    </ul>

                    <hr>

                    <h3>14. Disclaimer of Warranties</h3>
                    <p>ServeIT is provided <strong>“as is”</strong> without warranties of any kind. We do not guarantee:</p>
                    <ul>
                        <li>Technician availability</li>
                        <li>Accuracy of user-submitted data</li>
                        <li>Error-free or uninterrupted service</li>
                        <li>Quality of services provided by technicians</li>
                    </ul>

                    <hr>

                    <h3>15. Limitation of Liability</h3>
                    <p>ServeIT is <strong>not liable</strong> for:</p>
                    <ul>
                        <li>Damages caused by technicians or repair outcomes</li>
                        <li>Device loss or unrecoverable data</li>
                        <li>Payment issues</li>
                        <li>User disputes</li>
                        <li>Any indirect or consequential damages</li>
                    </ul>
                    <p>Your use of ServeIT is at your own risk.</p>

                    <hr>

                    <h3>16. Account Termination</h3>
                    <p>ServeIT may suspend or terminate accounts for:</p>
                    <ul>
                        <li>Violations of these Terms</li>
                        <li>Fraudulent activity</li>
                        <li>Confirmed misconduct</li>
                        <li>Security threats</li>
                    </ul>
                    <p>Users may also delete their accounts at any time.</p>

                    <hr>

                    <h3>17. Modifications to Terms</h3>
                    <p>ServeIT may update these Terms from time to time. Revised versions become effective upon posting on the platform.</p>

                    {{--
                    <hr>
                    <h3>18. Governing Law</h3>
                    <p>These Terms are governed by the laws of the <strong>Philippines</strong>. Any disputes shall be resolved under Philippine jurisdiction.</p>
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