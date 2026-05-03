<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EventApp') }}</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Dark Mode Styles - Inline for immediate effect */
        [data-theme="dark"] {
            --bg-primary: #1a1d23;
            --bg-secondary: #22262e;
            --bg-tertiary: #2d3139;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --text-muted: #6c757d;
            --border-color: #495057;
            --card-bg: #22262e;
        }
        
        [data-theme="dark"] .card {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .card-title {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .card-text {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .card-text.text-muted {
            color: var(--text-muted) !important;
        }
        
        [data-theme="dark"] .category-badge-outline {
            background-color: transparent !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .card-text i {
            color: var(--text-muted) !important;
        }
        
        /* Fix for titles and headers */
        [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3, 
        [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .page-title, [data-theme="dark"] .page-subtitle {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .page-subtitle {
            color: var(--text-secondary) !important;
        }
        
        /* Fix for all text elements */
        [data-theme="dark"] p, [data-theme="dark"] span, [data-theme="dark"] div {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-muted, [data-theme="dark"] .text-secondary {
            color: var(--text-muted) !important;
        }
        
        /* Fix for specific elements */
        [data-theme="dark"] .badge, [data-theme="dark"] .category-badge {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .category-badge-outline {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        
        /* Fix for navbar and navigation */
        [data-theme="dark"] .navbar-brand {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .nav-link {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .nav-link:hover {
            color: var(--text-primary) !important;
        }
        
        /* Fix for form elements */
        [data-theme="dark"] .form-control {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .form-control::placeholder {
            color: var(--text-muted) !important;
        }
        
        /* Fix for dropdown menus in dark mode */
        [data-theme="dark"] .dropdown-menu {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.4) !important;
        }
        
        [data-theme="dark"] .dropdown-item {
            background-color: transparent !important;
            color: var(--text-primary) !important;
            border: none !important;
        }
        
        [data-theme="dark"] .dropdown-item:hover {
            background-color: var(--hover-bg) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .dropdown-item:focus {
            background-color: var(--hover-bg) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .dropdown-item.active {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .dropdown-item.disabled {
            color: var(--text-muted) !important;
        }
        
        [data-theme="dark"] .dropdown-header {
            color: var(--text-primary) !important;
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .dropdown-divider {
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .dropdown-menu.show {
            background-color: var(--card-bg) !important;
        }
        
        /* Fix for custom dropdown in navbar */
        [data-theme="dark"] #userDropdownMenu {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.4) !important;
        }
        
        [data-theme="dark"] #userDropdownMenu .dropdown-item {
            background-color: transparent !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] #userDropdownMenu .dropdown-item:hover {
            background-color: var(--hover-bg) !important;
            color: var(--text-primary) !important;
        }
        
        /* Fix for dropdown text and icons specifically */
        [data-theme="dark"] .dropdown-item *,
        [data-theme="dark"] .dropdown-item span,
        [data-theme="dark"] .dropdown-item i,
        [data-theme="dark"] #userDropdownMenu *,
        [data-theme="dark"] #userDropdownMenu span,
        [data-theme="dark"] #userDropdownMenu i {
            color: var(--text-primary) !important;
        }
        
        /* Override any inline styles or specific colors */
        [data-theme="dark"] .dropdown-item[style*="color"],
        [data-theme="dark"] #userDropdownMenu .dropdown-item[style*="color"] {
            color: var(--text-primary) !important;
        }
        
        /* Force all dropdown content to be white */
        [data-theme="dark"] .dropdown-menu a,
        [data-theme="dark"] .dropdown-menu a *,
        [data-theme="dark"] .dropdown-menu .dropdown-item,
        [data-theme="dark"] .dropdown-menu .dropdown-item * {
            color: var(--text-primary) !important;
        }
        
        /* Specific fix for navbar dropdown */
        [data-theme="dark"] .navbar-nav .dropdown-menu .dropdown-item {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .navbar-nav .dropdown-menu .dropdown-item i,
        [data-theme="dark"] .navbar-nav .dropdown-menu .dropdown-item span {
            color: var(--text-primary) !important;
        }
        
        /* SPECIFIC FIX FOR MI PERFIL - Override inline styles */
        [data-theme="dark"] .dropdown-item.text-dark,
        [data-theme="dark"] .dropdown-item[style*="color: #212529"],
        [data-theme="dark"] .dropdown-item[style*="#212529"],
        [data-theme="dark"] a.dropdown-item.text-dark,
        [data-theme="dark"] a.dropdown-item[style*="color: #212529"] {
            color: var(--text-primary) !important;
        }
        
        /* Force override for the specific Mi Perfil link */
        [data-theme="dark"] a[href*="profile"],
        [data-theme="dark"] a[href*="profile"] i,
        [data-theme="dark"] a[href*="profile"] * {
            color: var(--text-primary) !important;
        }
        
        /* Override any text-dark class in dropdowns */
        [data-theme="dark"] .dropdown-menu .text-dark,
        [data-theme="dark"] .dropdown-menu .text-dark *,
        [data-theme="dark"] .dropdown-menu a.text-dark,
        [data-theme="dark"] .dropdown-menu a.text-dark * {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .card-vertical {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .card-vertical .card-body {
            background-color: var(--card-bg) !important;
        }
        
        [data-theme="dark"] .card-vertical .card-title {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .card-vertical .card-text {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .card-vertical .card-text.text-muted {
            color: var(--text-muted) !important;
        }
        
        [data-theme="dark"] .card-vertical .category-badge-outline {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
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
        
        /* Fix for all cards and tables */
        [data-theme="dark"] .card {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .card .card-header {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .card .card-body {
            background-color: var(--card-bg) !important;
        }
        
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
        
        [data-theme="dark"] * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @yield('navbar')

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Custom Dropdown Function -->
    <script>
        function toggleDropdown(event) {
            event.preventDefault();
            var dropdown = document.getElementById('userDropdownMenu');
            
            // Close all other dropdowns
            var allDropdowns = document.querySelectorAll('.dropdown-menu');
            allDropdowns.forEach(function(d) {
                if (d !== dropdown) {
                    d.style.display = 'none';
                }
            });
            
            // Toggle current dropdown
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('userDropdownMenu');
            var dropdownToggle = event.target.closest('a[onclick="toggleDropdown(event)"]');
            
            // Only proceed if dropdown exists
            if (dropdown && !dropdownToggle && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
