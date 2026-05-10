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
            <div class="stat-icon primary">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-value">{{ $stats['total_events'] }}</div>
            <div class="stat-label">{{ __('admin.stats.total_events') }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>12% {{ __('admin.stats.new_this_month') }}</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['active_events'] }}</div>
            <div class="stat-label">{{ __('admin.stats.active_events') }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>8% {{ __('admin.stats.new_this_month') }}</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">{{ __('admin.stats.total_users') }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>23% {{ __('admin.stats.new_this_month') }}</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-flag"></i>
            </div>
            <div class="stat-value">{{ $stats['total_reports'] }}</div>
            <div class="stat-label">{{ __('admin.reports.title') }}</div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                <span>5% {{ __('admin.stats.new_this_month') }}</span>
            </div>
        </div>
    </div>
</div>

            <!-- Eventos Activos Recientes -->
<div class="row">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="fas fa-calendar-alt"></i>
                    {{ __('admin.events.recent_active') }}
                </h6>
            </div>
            <div class="card-body">
                @if($activeEvents->count() > 0)
                    <div class="admin-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.events.name') }}</th>
                                    <th>{{ __('admin.events.category') }}</th>
                                    <th>{{ __('admin.events.date') }}</th>
                                    <th>{{ __('admin.events.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeEvents->take(5) as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->title, 30) }}</td>
                                        <td>{{ $event->category->name ?? 'N/A' }}</td>
                                        <td>{{ $event->event_date->format('d/m/Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.events.toggle', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-admin btn-warning btn-sm" title="Desactivar">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.events') }}" class="btn-admin btn-primary">{{ __('admin.events.view_all') }}</a>
                    </div>
                @else
                    <p class="text-muted">{{ __('admin.events.no_recent_active') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Reportes Recientes -->
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="fas fa-flag"></i>
                    {{ __('admin.reports.recent') }}
                </h6>
            </div>
            <div class="card-body">
                @if($reports->count() > 0)
                    <div class="admin-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.reports.reporter') }}</th>
                                    <th>{{ __('admin.reports.type') }}</th>
                                    <th>{{ __('admin.reports.reason') }}</th>
                                    <th>{{ __('admin.reports.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports->take(5) as $report)
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
                        <a href="{{ route('admin.reports') }}" class="btn-admin btn-warning">{{ __('admin.reports.view_all') }}</a>
                    </div>
                @else
                    <p class="text-muted">{{ __('admin.reports.no_recent') }}</p>
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
                                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
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
@endsection
