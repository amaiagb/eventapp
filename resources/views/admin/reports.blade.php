@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.reports.title') }}</h1>
    <p class="page-subtitle">{{ __('admin.reports.subtitle') }}</p>
</div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('admin.reports.title') }}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('admin.common.back_dashboard') }}
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
                            <h5 class="card-title">{{ __('admin.reports.total') }}</h5>
                            <h3>{{ \App\Models\Report::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('admin.reports.pending') }}</h5>
                            <h3>{{ \App\Models\Report::where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('admin.reports.resolved') }}</h5>
                            <h3>{{ \App\Models\Report::where('status', 'resolved')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('admin.reports.reviewed') }}</h5>
                            <h3>{{ \App\Models\Report::where('status', 'reviewed')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Lista de Reportes -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-flag me-2"></i>{{ __('admin.reports.list') }} ({{ $reports->total() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.reports.table_reporter') }}</th>
                                        <th>Evento</th>
                                        <th>Creador</th>
                                        <th>{{ __('admin.reports.table_reason') }}</th>
                                        <th>{{ __('admin.reports.table_status') }}</th>
                                        <th>{{ __('admin.reports.table_date') }}</th>
                                        <th>{{ __('admin.reports.table_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
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
                                                <a href="{{ route('events.show', $report->reportable) }}" class="text-decoration-none">
                                                    {{ $report->reportable->title ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 25px; height: 25px; font-size: 10px;">
                                                        {{ strtoupper(substr($report->reportable->user->username ?? 'N/A', 0, 1)) }}
                                                    </div>
                                                    {{ $report->reportable->user->username ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted" title="{{ $report->reason }}">
                                                    {{ Str::limit($report->reason, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $report->status_badge_class }}">
                                                    {{ $report->status_label }}
                                                </span>
                                            </td>
                                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- <button class="btn btn-sm btn-info" title="{{ __('admin.reports.view_details') }}" 
                                                            onclick="showReportDetails({{ $report->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button> -->
                                                    @if($report->status === 'pending')
                                                        <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success" title="{{ __('admin.reports.resolve') }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.reports.reject', $report) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('admin.reports.reject') }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
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
                            <h5 class="text-muted">{{ __('admin.reports.no_found') }}</h5>
                            <p class="text-muted">{{ __('admin.reports.no_found_help') }}</p>
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
                <h5 class="modal-title">{{ __('admin.reports.details_modal_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reportDetailsContent">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.reports.close') }}</button>
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
const reportTranslations = {
    loadingDetails: "{{ __('admin.reports.loading_details') }}",
    detailsModalTitle: "{{ __('admin.reports.details_modal_title') }}",
    detailsNote: "{{ __('admin.reports.details_note') }}"
};

function showReportDetails(reportId) {
    // Aquí podrías añadir una llamada AJAX para cargar los detalles del reporte
    // Por ahora, mostraremos un mensaje simple
    document.getElementById('reportDetailsContent').innerHTML = 
        '<div class="text-center">' +
        '<i class="fas fa-spinner fa-spin fa-2x mb-3"></i>' +
        '<p>' + reportTranslations.loadingDetails + ' #' + reportId + '...</p>' +
        '</div>';
    
    // Mostrar el modal
    var modal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
    modal.show();
    
    // Simular carga de datos (en una implementación real, harías una llamada AJAX)
    setTimeout(function() {
        document.getElementById('reportDetailsContent').innerHTML = 
            '<div class="alert alert-info">' +
            '<strong>' + reportTranslations.detailsModalTitle + ' #' + reportId + '</strong><br>' +
            reportTranslations.detailsNote +
            '</div>';
    }, 1000);
}
</script>
@endsection
