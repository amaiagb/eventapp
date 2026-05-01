@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Panel de Administración</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.events') }}">
                            <i class="fas fa-calendar-alt me-2"></i> Eventos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.reports') }}">
                            <i class="fas fa-flag me-2"></i> Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestión de Reportes</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Estadísticas de Reportes -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Reportes</h5>
                            <h3>{{ \App\Models\Report::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Pendientes</h5>
                            <h3>{{ \App\Models\Report::where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Resueltos</h5>
                            <h3>{{ \App\Models\Report::where('status', 'resolved')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Rechazados</h5>
                            <h3>{{ \App\Models\Report::where('status', 'rejected')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.reports') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resueltos</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazados</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Todos</option>
                                <option value="App\Models\Event" {{ request('type') == 'App\Models\Event' ? 'selected' : '' }}>Eventos</option>
                                <option value="App\Models\User" {{ request('type') == 'App\Models\User' ? 'selected' : '' }}>Usuarios</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Buscar reportes...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Filtrar
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Limpiar filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Reportes -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-flag me-2"></i>Lista de Reportes ({{ $reports->total() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Reportado por</th>
                                        <th>Tipo</th>
                                        <th>Elemento</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>{{ $report->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 25px; height: 25px; font-size: 10px;">
                                                        {{ strtoupper(substr($report->reporter->username ?? 'N/A', 0, 1)) }}
                                                    </div>
                                                    {{ $report->reporter->username ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ class_basename($report->reportable_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($report->reportable_type === 'App\Models\Event')
                                                    <strong>Evento:</strong> {{ Str::limit($report->reportable->title ?? 'N/A', 30) }}
                                                @elseif($report->reportable_type === 'App\Models\User')
                                                    <strong>Usuario:</strong> {{ $report->reportable->username ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted" title="{{ $report->reason }}">
                                                    {{ Str::limit($report->reason, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    {{ $report->status === 'pending' ? 'bg-warning' : '' }}
                                                    {{ $report->status === 'resolved' ? 'bg-success' : '' }}
                                                    {{ $report->status === 'rejected' ? 'bg-danger' : '' }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-info" title="Ver detalles" 
                                                            onclick="showReportDetails({{ $report->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($report->status === 'pending')
                                                        <button class="btn btn-sm btn-success" title="Marcar como resuelto">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" title="Rechazar reporte">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-flag-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron reportes</h5>
                            <p class="text-muted">No hay reportes que coincidan con los filtros seleccionados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de detalles del reporte -->
<div class="modal fade" id="reportDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Reporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reportDetailsContent">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.sidebar {
    position: fixed;
    top: 76px;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}
.sidebar-heading {
    font-size: .75rem;
    text-transform: uppercase;
}
@media (max-width: 767.8px) {
    .sidebar {
        position: static;
        height: auto;
    }
}
</style>

<script>
function showReportDetails(reportId) {
    // Aquí podrías añadir una llamada AJAX para cargar los detalles del reporte
    // Por ahora, mostraremos un mensaje simple
    document.getElementById('reportDetailsContent').innerHTML = 
        '<div class="text-center">' +
        '<i class="fas fa-spinner fa-spin fa-2x mb-3"></i>' +
        '<p>Cargando detalles del reporte #' + reportId + '...</p>' +
        '</div>';
    
    // Mostrar el modal
    var modal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
    modal.show();
    
    // Simular carga de datos (en una implementación real, harías una llamada AJAX)
    setTimeout(function() {
        document.getElementById('reportDetailsContent').innerHTML = 
            '<div class="alert alert-info">' +
            '<strong>Reporte #' + reportId + '</strong><br>' +
            'Los detalles completos del reporte se cargarían aquí mediante una llamada AJAX al servidor.' +
            '</div>';
    }, 1000);
}
</script>
@endsection
