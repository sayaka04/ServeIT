<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ServeIT - Privacy Policy</title>

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

        /* ---- Privacy Content Card ---- */
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

        /* Typography Overrides */
        h1,
        h3 {
            color: #fff;
            font-weight: 700;
        }

        h1 {
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        h3 {
            margin-top: 2rem;
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

        p,
        li {
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        strong {
            color: var(--brand-500);
            /* Neon Blue for emphasis */
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
            /* Made sticky for better UX on long text pages */
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
                <a href="/terms" class="custom-link">Terms</a>
                <a href="#" class="custom-link">Privacy</a>
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
                        <h1>Privacy Policy</h1>
                        <p>Last Updated: <span class="text-white">1/12/2025</span></p>
                    </div>

                    <hr>

                    <h3>1. Information We Collect</h3>
                    <p>ServeIT collects <strong>only the essential information</strong> required for operating and securing the platform. This includes:</p>
                    <ul>
                        <li><strong>Account Information:</strong> name, email address, contact number, and profile data</li>
                        <li><strong>Service Information:</strong> device details, repair requests, technician selections</li>
                        <li><strong>Repair Progress Data:</strong> repair updates, timestamps, and activity logs</li>
                        <li><strong>Technical Data:</strong> approximate location (for proximity mapping), device/browser information, and security logs</li>
                    </ul>
                    <p>We <strong>do not</strong> collect or access the content of user conversations unless voluntarily provided during dispute resolution.</p>

                    <hr>

                    <h3>2. Encrypted Messaging</h3>
                    <p>ServeIT provides in-platform messaging for communication between clients and technicians.</p>
                    <ul>
                        <li>All messages are <strong>encrypted</strong> to protect user privacy.</li>
                        <li>ServeIT <strong>does not access, read, or store</strong> the content of chat messages in plain text.</li>
                        <li>Chat data is only accessible to the sender and the recipient.</li>
                    </ul>
                    <p>Messaging is encrypted; ServeIT does not access chat content for security reviews or dispute resolution unless users of either party provide it themselves as evidence.</p>

                    <hr>

                    <h3>3. How We Use Your Data</h3>
                    <p>ServeIT uses collected data strictly for:</p>
                    <ul>
                        <li>Operating features such as technician recommendations, repair tracking, and portfolio viewing.</li>
                        <li>Maintaining the platform’s <strong>security, performance, and integrity</strong>.</li>
                        <li>Investigating reports of misconduct using <strong>only</strong>:
                            <ul>
                                <li>Repair logs</li>
                                <li>Account and activity history</li>
                                <li>User-submitted evidence (images, files, screenshots, recordings, etc.)</li>
                            </ul>
                        </li>
                        <li>Improving services using non-personal, aggregated analytics.</li>
                    </ul>
                    <p>ServeIT <strong>does not sell, trade, or leak</strong> any user data.</p>

                    <hr>

                    <h3>4. Misconduct Review and Investigation</h3>
                    <p>If a user is reported for misconduct, fraud, or violations of the Terms:</p>
                    <ul>
                        <li>ServeIT <strong>does not</strong> access private chats.</li>
                        <li>Investigations rely strictly on:
                            <ul>
                                <li>Repair progress history</li>
                                <li>Account actions and timestamps</li>
                                <li>Evidence provided voluntarily by users (Including chats)</li>
                            </ul>
                        </li>
                        <li>ServeIT will take appropriate action (warnings, restrictions, suspension) based on verified findings.</li>
                        <li>All investigations are handled confidentially.</li>
                    </ul>

                    <hr>

                    <h3>5. Data Security</h3>
                    <p>ServeIT uses appropriate security practices, including:</p>
                    <ul>
                        <li>Encrypted messaging</li>
                        <li>Secure data storage</li>
                        <li>Limited access only to authorized personnel</li>
                        <li>Monitoring for suspicious activity</li>
                    </ul>
                    <p>We aim to protect your data from unauthorized access, misuse, or exposure.</p>

                    <hr>

                    <h3>6. Information Sharing</h3>
                    <p>ServeIT <strong>does not</strong> share personal data with third parties, except when:</p>
                    <ul>
                        <li>Required by law or regulatory authorities.</li>
                        <li>Necessary to protect user safety or prevent fraudulent activity.</li>
                        <li>Needed to resolve platform-related disputes.</li>
                    </ul>
                    <p>Data is <strong>never</strong> shared for advertising or marketing purposes.</p>

                    <hr>

                    <h3>7. Data Retention</h3>
                    <p>ServeIT retains necessary data only for:</p>
                    <ul>
                        <li>Operating your account</li>
                        <li>Maintaining system security</li>
                        <li>Complying with legal requirements</li>
                    </ul>
                    <p>Information not needed anymore is securely deleted or anonymized.</p>

                    <hr>

                    <h3>8. Your Rights</h3>
                    <p>You may request to:</p>
                    <ul>
                        <li>Access your stored personal data</li>
                        <li>Update or correct details</li>
                        <li>Request account deletion (with certain legal or security exceptions)</li>
                    </ul>

                    <hr>

                    <h3>9. Updates to This Policy</h3>
                    <p>ServeIT may update this Privacy Policy when needed. The updated version will be posted with the revision date.</p>

                    <div class="mt-5 pt-3 text-center">
                        <a href="/terms" class="fw-bold">Back to Terms and Conditions</a>
                    </div>

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