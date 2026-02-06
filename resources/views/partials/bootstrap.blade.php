<style>
    /* ===== Sidebar (white, dark text, no glow, no big rounding) ===== */
    #layoutSidenav_nav,
    .sb-sidenav,
    .sb-sidenav.sb-sidenav-dark,
    .sb-sidenav.bg-dark {
        background: #fffdfa !important;
        color: var(--text-dark) !important;
        border-right: 1px solid var(--line) !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    .sb-sidenav .list-group,
    .sb-sidenav .list-group-flush,
    .sb-sidenav .collapse {
        background: #fffdfa !important;
    }

    .sb-sidenav .list-group-item {
        background: transparent !important;
        color: var(--text-dark) !important;
        border: 0 !important;
        border-bottom: 1px solid var(--line) !important;
        padding: .9rem 1rem;
        margin: 0 !important;
        border-radius: 0 !important;
        transition: background-color .15s ease, color .15s ease, transform .15s ease;
    }

    .sb-sidenav .list-group-item:hover {
        background: #f8fafc !important;
        color: var(--text-dark) !important;
        transform: translateX(2px);
    }

    .sb-sidenav .list-group-item:last-child {
        border-bottom: 0 !important;
    }

    .sb-sidenav .collapse .list-group-item {
        border-bottom: 1px solid var(--line) !important;
    }

    .sb-sidenav a {
        color: var(--text-dark) !important;
        text-decoration: none;
    }

    .sb-sidenav a:hover {
        color: var(--muted-dark) !important;
    }

    /* Sidebar footer block -> white with dark text */
    .sb-sidenav-footer {
        background: #fffdfa !important;
        border-top: 1px solid var(--line) !important;
        color: var(--muted-dark) !important;
    }
</style>

<style>
    /* ===== Sidebar (white, dark text, no glow, no big rounding) ===== */
    #layoutSidenav_nav,
    .sb-sidenav,
    .sb-sidenav.sb-sidenav-dark,
    .sb-sidenav.bg-dark {
        background: #fffdfa !important;
        color: var(--text-dark) !important;
        border-right: 1px solid var(--line) !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* ...existing sidebar styles... */

    /* ===== Modern UI Overrides ===== */

    :root {
        --primary: #2d6cdf;
        --primary-hover: #1a4ca0;
        --accent: #f7b32b;
        --background: #fffdfa;
        --surface: #f8fafc;
        --text-dark: #22223b;
        --text-light: #fff;
        --muted-dark: #6c757d;
        --line: #e5e7eb;
        --radius: 0.5rem;
        --shadow: 0 2px 8px rgba(44, 62, 80, 0.07);
    }

    body,
    .bg-light {
        background: var(--background) !important;
        color: var(--text-dark) !important;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        color: var(--text-dark);
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    a {
        color: var(--primary);
        text-decoration: none;
        transition: color .15s;
    }

    a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* Buttons */
    .btn,
    .btn-primary {
        background: var(--primary) !important;
        color: var(--text-light) !important;
        border-radius: var(--radius) !important;
        border: none !important;
        box-shadow: var(--shadow);
        font-weight: 600;
        transition: background .15s, box-shadow .15s;
    }


    .btn:hover,
    .btn-primary:hover {
        background: var(--primary-hover) !important;
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.12);
    }

    .btn-white:hover {
        background: var(--line) !important;
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.12);
    }

    .btn-outline-primary {
        color: var(--primary) !important;
        border-color: var(--primary) !important;
        background: transparent !important;
    }

    .btn-outline-primary:hover {
        background: var(--primary) !important;
        color: var(--text-light) !important;
    }

    /* Cards */
    .card {
        background: var(--surface) !important;
        border-radius: var(--radius) !important;
        box-shadow: var(--shadow);
        border: 1px solid var(--line) !important;
    }

    .card-header,
    .card-footer {
        background: transparent !important;
        border-bottom: 1px solid var(--line) !important;
        color: var(--text-dark) !important;
    }

    /* Forms */
    .form-control {
        background: #fff !important;
        color: var(--text-dark) !important;
        border-radius: var(--radius) !important;
        border: 1px solid var(--line) !important;
        box-shadow: none !important;
        transition: border-color .15s;
    }

    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 2px rgba(45, 108, 223, 0.08);
    }

    .form-label {
        color: var(--muted-dark) !important;
        font-weight: 500;
    }

    /* Tables */
    .table {
        background: #fff !important;
        color: var(--text-dark) !important;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .table th {
        background: var(--surface) !important;
        color: var(--primary) !important;
        font-weight: 600;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background: var(--surface) !important;
    }

    /* Navbar */
    .navbar {
        background: var(--background) !important;
        border-bottom: 1px solid var(--line) !important;
        box-shadow: var(--shadow);
    }

    .navbar-brand,
    .navbar-nav .nav-link {
        color: var(--primary) !important;
        font-weight: 600;
    }

    .navbar-nav .nav-link.active {
        color: var(--primary-hover) !important;
    }

    /* Alerts */
    .alert {
        border-radius: var(--radius) !important;
        box-shadow: var(--shadow);
        border: none !important;
    }

    .alert-primary {
        background: var(--primary) !important;
        color: var(--text-light) !important;
    }

    .alert-warning {
        background: var(--accent) !important;
        color: var(--text-dark) !important;
    }

    /* Misc */
    .rounded,
    .rounded-lg,
    .rounded-pill {
        border-radius: var(--radius) !important;
    }

    .shadow,
    .shadow-sm,
    .shadow-lg {
        box-shadow: var(--shadow) !important;
    }

    .dropdown-menu {
        background: #fffdfa !important;
        border: 1px solid var(--line) !important;
        border-radius: var(--radius) !important;
        box-shadow: var(--shadow);
    }

    .dropdown-item {
        color: var(--text-dark) !important;
        transition: background .15s, color .15s;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        background: var(--surface) !important;
        color: var(--primary) !important;
    }
</style>

<link href="{{ asset('bootstrap-5.3.7-dist/css/bootstrap-slate.min.css') }}" rel="stylesheet">
<script src="{{ asset('bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>