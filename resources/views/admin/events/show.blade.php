@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">Detalles del Evento</h1>
    <p class="page-subtitle">Revisa la información del evento y decide si aprobarlo o rechazarlo</p>
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

<!-- Event Details -->
<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-alt me-2"></i>Información del Evento
                </h6>
                <div>
                    @if($event->status == 'pending')
                        <span class="badge bg-warning">
                            <i class="fas fa-clock me-1"></i>Pendiente de Aprobación
                        </span>
                    @elseif($event->status == 'approved')
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Aprobado
                        </span>
                    @elseif($event->status == 'rejected')
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle me-1"></i>Rechazado
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Cover Image -->
                @if($event->cover_image)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" 
                             class="img-fluid rounded" 
                             alt="{{ $event->title }}"
                             style="max-height: 400px;">
                    </div>
                @endif

                <!-- Event Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary mb-3">{{ $event->title }}</h5>
                        <p class="text-muted">{{ $event->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Categoría:</strong>
                            <span class="badge bg-info ms-2">{{ $event->category->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Fecha:</strong> {{ $event->event_date->format('d/m/Y') }}
                        </div>
                        <div class="mb-3">
                            <strong>Hora:</strong> {{ $event->event_time->format('H:i') }}
                        </div>
                        <div class="mb-3">
                            <strong>Capacidad:</strong>
                            @if($event->max_attendees)
                                {{ $event->attendees->count() ?? 0 }} / {{ $event->max_attendees }} asistentes
                            @else
                                Sin límite
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                        </h6>
                        <p class="mb-1">
                            <strong>Ciudad:</strong> {{ $event->city->name ?? 'N/A' }}
                            @if($event->city && $event->city->country)
                                ({{ $event->city->country->name }})
                            @endif
                        </p>
                        <p class="mb-0">
                            <strong>Lugar específico:</strong> {{ $event->location }}
                        </p>
                    </div>
                </div>

                <!-- Rejection Reason (if rejected) -->
                @if($event->status == 'rejected' && $event->rejection_reason)
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>Motivo de Rechazo
                        </h6>
                        <p class="mb-0">{{ $event->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Organizer Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user me-2"></i>Organizador
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" 
                         style="width: 60px; height: 60px; font-size: 24px;">
                        {{ strtoupper(substr($event->user->username ?? 'U', 0, 1)) }}
                    </div>
                    <h6 class="mb-0">{{ $event->user->username ?? 'N/A' }}</h6>
                    <small class="text-muted">
                        @if($event->user->role)
                            {{ $event->user->role->name }}
                        @endif
                    </small>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong><br>
                    <small>{{ $event->user->email ?? 'N/A' }}</small>
                </div>
                <div class="mb-2">
                    <strong>Registrado:</strong><br>
                    <small>{{ $event->user->created_at->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($event->status == 'pending')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gavel me-2"></i>Acciones de Aprobación
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>Aprobar Evento
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-2"></i>Rechazar Evento
                    </button>
                </div>
            </div>
        @endif

        <!-- Event Stats -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Estadísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Estado:</strong><br>
                    <span class="badge {{ $event->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $event->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Creado:</strong><br>
                    <small>{{ $event->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="mb-3">
                    <strong>Última actualización:</strong><br>
                    <small>{{ $event->updated_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation -->
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.events') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver a Lista de Eventos
    </a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-tachometer-alt me-2"></i>Ir al Dashboard
    </a>
</div>

<!-- Reject Modal -->
@if($event->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.events.reject', $event) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Rechazar Evento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Por favor, indica el motivo por el cual estás rechazando este evento:</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Motivo de rechazo *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" required placeholder="Explica por qué este evento no puede ser aprobado..."></textarea>
                        <div class="form-text">Mínimo 10 caracteres, máximo 500 caracteres.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Rechazar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
