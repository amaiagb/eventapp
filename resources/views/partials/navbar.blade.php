<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="fas fa-calendar-alt me-2 text-primary"></i>
            <span>EventApp</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Search Bar -->
            <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 400px;" action="{{ route('search.index') }}" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Buscar eventos..." aria-label="Search" value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto align-items-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="{{ route('register') }}">
                            Registrarse
                        </a>
                    </li>
                @else
                    <!-- Quick Access Links -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Crear evento">
                            <i class="fas fa-plus-circle fa-lg"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Mis eventos">
                            <i class="fas fa-calendar-check fa-lg"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Notificaciones">
                            <i class="fas fa-bell fa-lg"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Configuración">
                            <i class="fas fa-cog fa-lg"></i>
                        </a>
                    </li>

                    <!-- User Profile Dropdown -->
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link d-flex align-items-center" href="#" onclick="toggleDropdown(event)" style="cursor: pointer;">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ Auth::user()->username }}</span>
                            <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <ul id="userDropdownMenu" class="dropdown-menu dropdown-menu-end" style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #dee2e6; border-radius: 0.375rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175); min-width: 200px; z-index: 1000;">
                            <li>
                                <a class="dropdown-item d-block px-4 py-2 text-decoration-none text-dark" href="{{ route('profile.details') }}" style="color: #212529 !important;">
                                    <i class="fas fa-user me-2"></i> Mi Perfil
                                </a>
                            </li>
                            @if(Auth::user()->role && Auth::user()->role->name === 'admin')
                                <li>
                                    <a class="dropdown-item d-block px-4 py-2 text-decoration-none text-warning" href="{{ route('admin.dashboard') }}" style="color: #f6c23e !important;">
                                        <i class="fas fa-tachometer-alt me-2"></i> Panel de Administración
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <a class="dropdown-item d-block px-4 py-2 text-decoration-none text-danger" href="{{ route('logout.direct') }}" style="color: #dc3545 !important;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</nav>
