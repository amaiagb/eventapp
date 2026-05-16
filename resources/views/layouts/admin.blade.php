<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EventApp') }} - Admin</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Dark mode toggle in admin sidebar */
        .sidebar-header .dark-mode-toggle {
            background-color: transparent !important;
            border: 1px solid var(--border-color, #dee2e6) !important;
            color: var(--text-primary, #212529) !important;
            padding: 0.5rem !important;
            border-radius: 0.375rem !important;
            transition: all 0.3s ease !important;
        }
        
        [data-theme="dark"] .sidebar-header .dark-mode-toggle {
            background-color: transparent !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }
        
        .sidebar-header .dark-mode-toggle:hover {
            background-color: var(--hover-bg, #f1f3f5) !important;
            transform: scale(1.05) !important;
        }
        
        [data-theme="dark"] .sidebar-header .dark-mode-toggle:hover {
            background-color: var(--hover-bg) !important;
        }
        
        /* Admin tables and cards in dark mode */
        [data-theme="dark"] .admin-card {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .admin-card .card-header {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .admin-card .card-body {
            background-color: var(--card-bg) !important;
        }
        
        [data-theme="dark"] .admin-table {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .admin-table .table {
            background-color: var(--card-bg) !important;
        }
        
        [data-theme="dark"] .admin-table .table thead {
            background-color: var(--bg-tertiary) !important;
        }
        
        [data-theme="dark"] .admin-table .table th {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .admin-table .table td {
            background-color: var(--card-bg) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .admin-table .table tbody tr:hover {
            background-color: var(--hover-bg) !important;
        }
        
        [data-theme="dark"] .admin-table .table tbody tr {
            background-color: var(--card-bg) !important;
        }
        
        /* Fix for all tables in dark mode */
        [data-theme="dark"] .table {
            background-color: var(--card-bg) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .table th {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .table td {
            background-color: var(--card-bg) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .table tbody tr:hover {
            background-color: var(--hover-bg) !important;
        }
        
        /* Fix for card backgrounds */
        [data-theme="dark"] * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Universal dark mode overrides - AGGRESSIVE APPROACH */
        [data-theme="dark"] body,
        [data-theme="dark"] html,
        [data-theme="dark"] .admin-layout {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }
        
        /* Fix ALL text elements */
        [data-theme="dark"] *,
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6,
        [data-theme="dark"] p,
        [data-theme="dark"] span,
        [data-theme="dark"] div,
        [data-theme="dark"] td,
        [data-theme="dark"] th,
        [data-theme="dark"] li,
        [data-theme="dark"] a,
        [data-theme="dark"] .card-title,
        [data-theme="dark"] .card-text,
        [data-theme="dark"] .page-title,
        [data-theme="dark"] .admin-header * {
            color: var(--text-primary) !important;
        }
        
        /* Fix secondary text */
        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .text-secondary,
        [data-theme="dark"] .page-subtitle,
        [data-theme="dark"] .card-text.text-muted,
        [data-theme="dark"] .small {
            color: var(--text-muted) !important;
        }
        
        /* Fix admin headers specifically */
        [data-theme="dark"] .admin-header,
        [data-theme="dark"] .admin-header h1,
        [data-theme="dark"] .admin-header .page-title,
        [data-theme="dark"] .admin-header .page-subtitle {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .admin-header .page-subtitle {
            color: var(--text-secondary) !important;
        }
        
        /* Fix card headers and titles */
        [data-theme="dark"] .card-header,
        [data-theme="dark"] .card-header *,
        [data-theme="dark"] .card-title,
        [data-theme="dark"] .card-title * {
            color: var(--text-primary) !important;
        }
        
        /* Fix any element that might have dark text */
        [data-theme="dark"] [class*="text-"],
        [data-theme="dark"] [class*="header"],
        [data-theme="dark"] [class*="title"],
        [data-theme="dark"] [class*="label"] {
            color: var(--text-primary) !important;
        }
        
        /* Override any potential inline styles or Bootstrap overrides */
        [data-theme="dark"] .text-dark,
        [data-theme="dark"] .text-black,
        [data-theme="dark"] .text-gray-800,
        [data-theme="dark"] .text-gray-900 {
            color: var(--text-primary) !important;
        }
        
        /* Force all backgrounds to be dark */
        [data-theme="dark"] .card,
        [data-theme="dark"] .card-body,
        [data-theme="dark"] .card-header,
        [data-theme="dark"] .admin-card,
        [data-theme="dark"] .stat-card {
            background-color: var(--card-bg) !important;
        }
        
        /* Force borders to be dark theme appropriate */
        [data-theme="dark"] .card,
        [data-theme="dark"] .border,
        [data-theme="dark"] [class*="border-"] {
            border-color: var(--border-color) !important;
        }
        
        /* Universal dark mode overrides - AGGRESSIVE APPROACH */
        [data-theme="dark"] body,
        [data-theme="dark"] html,
        [data-theme="dark"] .admin-layout {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .card .card-body {
            background-color: var(--card-bg) !important;
        }
        
        /* Fix for accordion/FAQ in dark mode */
        [data-theme="dark"] .accordion {
            background-color: var(--card-bg) !important;
        }
        
        [data-theme="dark"] .accordion-item {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .accordion-button {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .accordion-button:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        }
        
        [data-theme="dark"] .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23e9ecef'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
        }
        
        [data-theme="dark"] .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23e9ecef'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
        }
        
        [data-theme="dark"] .accordion-collapse {
            background-color: var(--card-bg) !important;
        }
        
        [data-theme="dark"] .accordion-body {
            background-color: var(--card-bg) !important;
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .accordion-body p {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .accordion-body h5, [data-theme="dark"] .accordion-body h6 {
            color: var(--text-primary) !important;
        }
        
        /* Fix for all collapse components */
        [data-theme="dark"] .collapse {
            background-color: var(--card-bg) !important;
        }
        
        [data-theme="dark"] .collapsing {
            background-color: var(--card-bg) !important;
        }
        
        /* Fix for admin stats cards - preserve distinctive colors */
        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .card[class*="border-"],
        [data-theme="dark"] .card.border-left-primary,
        [data-theme="dark"] .card.border-left-success,
        [data-theme="dark"] .card.border-left-warning,
        [data-theme="dark"] .card.border-left-danger,
        [data-theme="dark"] .card.border-left-info {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
        }
        
        /* Preserve left border colors */
        [data-theme="dark"] .card.border-left-primary {
            border-left: 0.25rem solid #0d6efd !important;
        }
        
        [data-theme="dark"] .card.border-left-success {
            border-left: 0.25rem solid #198754 !important;
        }
        
        [data-theme="dark"] .card.border-left-warning {
            border-left: 0.25rem solid #ffc107 !important;
        }
        
        [data-theme="dark"] .card.border-left-danger {
            border-left: 0.25rem solid #dc3545 !important;
        }
        
        [data-theme="dark"] .card.border-left-info {
            border-left: 0.25rem solid #0dcaf0 !important;
        }
        
        /* Fix stat icons with universal selectors */
        [data-theme="dark"] .stat-icon,
        [data-theme="dark"] .card .fa,
        [data-theme="dark"] .card-body .fa,
        [data-theme="dark"] .card .fa-2x {
            color: inherit !important;
        }
        
        [data-theme="dark"] .stat-icon.primary,
        [data-theme="dark"] .card.border-left-primary .fa-2x {
            color: #0d6efd !important;
        }
        
        [data-theme="dark"] .stat-icon.success,
        [data-theme="dark"] .card.border-left-success .fa-2x {
            color: #198754 !important;
        }
        
        [data-theme="dark"] .stat-icon.warning,
        [data-theme="dark"] .card.border-left-warning .fa-2x {
            color: #ffc107 !important;
        }
        
        [data-theme="dark"] .stat-icon.danger,
        [data-theme="dark"] .card.border-left-danger .fa-2x {
            color: #dc3545 !important;
        }
        
        [data-theme="dark"] .stat-icon.info,
        [data-theme="dark"] .card.border-left-info .fa-2x {
            color: #0dcaf0 !important;
        }
        
        /* Fix text in stat cards */
        [data-theme="dark"] .stat-value,
        [data-theme="dark"] .card-body .h5,
        [data-theme="dark"] .card-body .font-weight-bold {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .stat-label,
        [data-theme="dark"] .card-body .text-xs,
        [data-theme="dark"] .card-body .text-uppercase {
            color: var(--text-secondary) !important;
        }
        
        /* Universal override for any card content */
        [data-theme="dark"] .card * {
            color: inherit !important;
        }
        
        [data-theme="dark"] .card .card-body * {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .card .text-muted,
        [data-theme="dark"] .card .text-secondary {
            color: var(--text-muted) !important;
        }
        
        /* Fix for any colored cards that should preserve their colors */
        [data-theme="dark"] .card.border-primary {
            border-color: #0d6efd !important;
        }
        
        [data-theme="dark"] .card.border-success {
            border-color: #198754 !important;
        }
        
        [data-theme="dark"] .card.border-warning {
            border-color: #ffc107 !important;
        }
        
        [data-theme="dark"] .card.border-danger {
            border-color: #dc3545 !important;
        }
        
        [data-theme="dark"] .card.border-info {
            border-color: #0dcaf0 !important;
        }
        
        /* Fix for badges that should preserve colors */
        [data-theme="dark"] .badge.bg-primary {
            background-color: #0d6efd !important;
            color: white !important;
        }
        
        [data-theme="dark"] .badge.bg-success {
            background-color: #198754 !important;
            color: white !important;
        }
        
        [data-theme="dark"] .badge.bg-warning {
            background-color: #ffc107 !important;
            color: black !important;
        }
        
        [data-theme="dark"] .badge.bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }
        
        [data-theme="dark"] .badge.bg-info {
            background-color: #0d6efd !important;
            color: white !important;
        }
        
        /* Fix for buttons that should preserve colors */
        [data-theme="dark"] .btn.btn-primary {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: white !important;
        }
        
        [data-theme="dark"] .btn.btn-success {
            background-color: #198754 !important;
            border-color: #198754 !important;
            color: white !important;
        }
        
        [data-theme="dark"] .btn.btn-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: black !important;
        }
        
        [data-theme="dark"] .btn.btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }
        
        [data-theme="dark"] .btn.btn-info {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: white !important;
        }
    </style>
</head>
<body class="admin-layout">
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="logo">
                    <i class="fas fa-calendar-alt"></i>
                    <span>EventApp</span>
                </a>
                <button class="dark-mode-toggle btn btn-outline-secondary btn-sm theme-toggle" 
                        type="button" 
                        title="{{ __('nav.dark_mode') }}"
                        onclick="toggleDarkMode()">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-title">{{ __('admin.sidebar.main') }}</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('admin.sidebar.dashboard') }}</span>
                </a>
                <a href="{{ route('admin.events') }}" class="nav-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ __('admin.sidebar.events') }}</span>
                </a>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>{{ __('admin.sidebar.users') }}</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-title">{{ __('admin.sidebar.content_management') }}</div>
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>{{ __('admin.sidebar.categories') }}</span>
                </a>
                <a href="{{ route('admin.tags') }}" class="nav-link {{ request()->routeIs('admin.tags*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('admin.sidebar.tags') }}</span>
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">{{ __('admin.sidebar.moderation') }}</div>
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>{{ __('admin.sidebar.reports') }}</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-title">{{ __('admin.sidebar.system') }}</div>
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>{{ __('admin.sidebar.back_to_site') }}</span>
                </a>
                <a href="{{ route('profile.details') }}" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>{{ __('admin.sidebar.my_profile') }}</span>
                </a>
                <a href="{{ route('logout.direct') }}" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ __('admin.sidebar.logout') }}</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        @yield('content')
    </main>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Manejar formularios de eliminación con data-attributes
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const confirmMessage = this.getAttribute('data-confirm');
                    if (confirmMessage && !confirm(confirmMessage)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
