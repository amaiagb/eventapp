@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.dashboard.title') }}</h1>
    <p class="page-subtitle">{{ __('admin.dashboard.subtitle') }}</p>
</div>

<!-- Alertas -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $stats['pending_events'] }}</div>
            <div class="stat-label">{{ __('admin.dashboard.pending_events_label') }}</div>
            <div class="stat-action">
                <a href="{{ route('admin.events', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning">
                    {{ __('admin.common.review') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-flag"></i>
            </div>
            <div class="stat-value">{{ $stats['pending_reports'] }}</div>
            <div class="stat-label">{{ __('admin.dashboard.pending_reports_label') }}</div>
            <div class="stat-action">
                <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-danger">
                    {{ __('admin.common.review') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-value">{{ $stats['active_events'] }}</div>
            <div class="stat-label">{{ __('admin.dashboard.active_events_label') }}</div>
            <div class="stat-action">
                <a href="{{ route('admin.events', ['status' => 'active']) }}" class="btn btn-sm btn-outline-success">
                    {{ __('admin.common.view_all') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $stats['active_users'] }}</div>
            <div class="stat-label">{{ __('admin.dashboard.active_users_label') }}</div>
            <div class="stat-action">
                <a href="{{ route('admin.users', ['status' => 'active']) }}" class="btn btn-sm btn-outline-primary">
                    {{ __('admin.common.view_all') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mb-4">
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-alt me-2"></i>{{ __('admin.dashboard.events_chart_title') }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="eventsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-users me-2"></i>{{ __('admin.dashboard.users_chart_title') }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-user-check me-2"></i>{{ __('admin.dashboard.attendees_chart_title') }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="attendeesChart"></canvas>
            </div>
        </div>
    </div>
</div>

            <!-- Eventos Pendientes de Aprobación -->
<div class="row">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="fas fa-clock"></i>
                    {{ __('admin.dashboard.pending_approval_title') }}
                </h6>
            </div>
            <div class="card-body">
                @if($pendingEvents->count() > 0)
                    <div class="admin-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.dashboard.table_name') }}</th>
                                    <th>{{ __('admin.dashboard.table_category') }}</th>
                                    <th>{{ __('admin.dashboard.table_date') }}</th>
                                    <th>{{ __('admin.events.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingEvents->take(5) as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->title, 30) }}</td>
                                        <td>{{ $event->category->name ?? 'N/A' }}</td>
                                        <td>{{ $event->event_date->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info" title="{{ __('admin.common.view_details') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.events', ['status' => 'pending']) }}" class="btn btn-primary">{{ __('admin.common.view_all') }}</a>
                    </div>
                @else
                    <p class="text-muted">{{ __('admin.dashboard.no_pending_events') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Reportes Pendientes -->
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="fas fa-flag"></i>
                    {{ __('admin.dashboard.pending_reports_title') }}
                </h6>
            </div>
            <div class="card-body">
                @if($pendingReports->count() > 0)
                    <div class="admin-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.dashboard.table_reported_by') }}</th>
                                    <th>{{ __('admin.dashboard.table_type') }}</th>
                                    <th>{{ __('admin.dashboard.table_reason') }}</th>
                                    <th>{{ __('admin.dashboard.table_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingReports->take(5) as $report)
                                    <tr>
                                        <td>{{ $report->reporter->username ?? 'N/A' }}</td>
                                        <td>{{ class_basename($report->reportable_type) }}</td>
                                        <td>{{ Str::limit($report->reason, 20) }}</td>
                                        <td>{{ $report->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.reports') }}" class="btn btn-danger">{{ __('admin.common.view_all') }}</a>
                    </div>
                @else
                    <p class="text-muted">{{ __('admin.dashboard.no_pending_reports') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

            <!-- Usuarios Recientes -->
<div class="row">
    <div class="col-lg-12">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="fas fa-users"></i>
                    {{ __('admin.users.recent_registered') }}
                </h6>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="admin-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.users.name') }}</th>
                                    <th>{{ __('admin.users.email') }}</th>
                                    <th>{{ __('admin.users.role') }}</th>
                                    <th>{{ __('admin.users.status') }}</th>
                                    <th>{{ __('admin.users.register_date') }}</th>
                                    <th>{{ __('admin.users.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users->take(5) as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ $user->role && $user->role->name === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                                {{ $user->role->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $user->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-admin {{ $user->is_active ? 'btn-warning' : 'btn-success' }} btn-sm" 
                                                        title="{{ $user->is_active ? __('admin.users.deactivate') : __('admin.users.activate') }}">
                                                    <i class="fas {{ $user->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users') }}" class="btn-admin btn-primary">{{ __('admin.users.view_all') }}</a>
                    </div>
                @else
                    <p class="text-muted">{{ __('admin.users.no_registered') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Datos de los gráficos
const chartData = @json($chartData);

// Configuración común para todos los gráficos
const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1
            }
        }
    }
};

// Gráfico de eventos creados por mes
const eventsCtx = document.getElementById('eventsChart').getContext('2d');
new Chart(eventsCtx, {
    type: 'line',
    data: {
        labels: chartData.months,
        datasets: [{
            label: "{{ __('admin.dashboard.events_chart_title') }}",
            data: chartData.events,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: commonOptions
});

// Gráfico de usuarios registrados por mes
const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
    type: 'bar',
    data: {
        labels: chartData.months,
        datasets: [{
            label: "{{ __('admin.dashboard.users_chart_title') }}",
            data: chartData.users,
            backgroundColor: 'rgba(25, 135, 84, 0.8)',
            borderColor: '#198754',
            borderWidth: 1
        }]
    },
    options: commonOptions
});

// Gráfico de asistencias a eventos por mes
const attendeesCtx = document.getElementById('attendeesChart').getContext('2d');
new Chart(attendeesCtx, {
    type: 'line',
    data: {
        labels: chartData.months,
        datasets: [{
            label: "{{ __('admin.dashboard.attendees_chart_title') }}",
            data: chartData.attendees,
            borderColor: '#0dcaf0',
            backgroundColor: 'rgba(13, 202, 240, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: commonOptions
});
</script>
@endsection
