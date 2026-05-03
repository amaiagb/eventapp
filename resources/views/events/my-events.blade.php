@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-calendar-alt me-2"></i>Mis Eventos
        </h2>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Crear Nuevo Evento
        </a>
    </div>

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

    @if($events->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($events as $event)
                <div class="col">
                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                        <div class="card card-vertical h-100">
                            @if($event->cover_image)
                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                            @else
                                <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                            @endif
                            
                            <div class="card-body">
                                <div class="card-content">
                                    <!-- Status badge -->
                                    <div class="mb-2">
                                        @if($event->status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Aprobado
                                            </span>
                                        @elseif($event->status == 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pendiente
                                            </span>
                                        @elseif($event->status == 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Rechazado
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                    
                                    <p class="card-text text-muted small mb-2">
                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                    </p>
                                    
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                    </p>
                                    
                                    <p class="card-text text-muted small mb-3">
                                        <i class="fas fa-users me-1"></i>
                                        @if($event->max_attendees)
                                            {{ $event->attendees->count() ?? 0 }} / {{ $event->max_attendees }} asistentes
                                        @else
                                            Sin límite de asistentes
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No tienes eventos creados</h4>
            <p class="text-muted">Comienza creando tu primer evento para que otros puedan unirse.</p>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Mi Primer Evento
            </a>
        </div>
    @endif
</div>
@endsection
