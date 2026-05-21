<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
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
                    <img src="{{ asset('assets/img/eventapplogo.png') }}" alt="EventApp" style="height: 35px;">
                </a>
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
