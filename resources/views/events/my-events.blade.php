@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        Mis Eventos
    </h2>

    <!-- Main Tabs: Creados vs Asistidos -->
    <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="created-tab" data-bs-toggle="tab" data-bs-target="#created" type="button" role="tab">
                <i class="fas fa-plus-circle me-2"></i>Mis Eventos Creados
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="attended-tab" data-bs-toggle="tab" data-bs-target="#attended" type="button" role="tab">
                <i class="fas fa-user-check me-2"></i>Eventos a los que Asisto
            </button>
        </li>
    </ul>

    <div class="tab-content" id="mainTabsContent">
        <!-- Eventos Creados Tab -->
        <div class="tab-pane fade show active" id="created" role="tabpanel">
            <!-- Sub-tabs: Próximos vs Pasados -->
            <ul class="nav nav-pills mb-4" id="createdSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="created-upcoming-tab" data-bs-toggle="pill" data-bs-target="#created-upcoming" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Próximos Eventos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="created-past-tab" data-bs-toggle="pill" data-bs-target="#created-past" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>Eventos Pasados
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="createdSubTabsContent">
                <!-- Próximos Eventos -->
                <div class="tab-pane fade show active" id="created-upcoming" role="tabpanel">
                    @if($createdUpcoming->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($createdUpcoming as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card card-vertical">
                                            @if($event->cover_image)
                                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            @else
                                                <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-users me-1"></i>{{ $event->attendees()->count() }} asistentes
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No tienes próximos eventos creados</h4>
                            <p class="text-muted">Aún no has creado eventos próximos. ¡Crea tu primer evento!</p>
                            <a href="{{ route('events.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Crear Evento
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Eventos Pasados -->
                <div class="tab-pane fade" id="created-past" role="tabpanel">
                    @if($createdPast->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($createdPast as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card card-vertical opacity-75">
                                            @if($event->cover_image)
                                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            @else
                                                <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-users me-1"></i>{{ $event->attendees()->count() }} asistentes
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No tienes eventos pasados creados</h4>
                            <p class="text-muted">Aún no has creado eventos que hayan finalizado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Eventos Asistidos Tab -->
        <div class="tab-pane fade" id="attended" role="tabpanel">
            <!-- Sub-tabs: Próximos vs Pasados -->
            <ul class="nav nav-pills mb-4" id="attendedSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="attended-upcoming-tab" data-bs-toggle="pill" data-bs-target="#attended-upcoming" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Próximos Eventos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="attended-past-tab" data-bs-toggle="pill" data-bs-target="#attended-past" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>Eventos Pasados
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="attendedSubTabsContent">
                <!-- Próximos Eventos -->
                <div class="tab-pane fade show active" id="attended-upcoming" role="tabpanel">
                    @if($attendedUpcoming->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($attendedUpcoming as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card card-vertical">
                                            @if($event->cover_image)
                                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            @else
                                                <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-user me-1"></i>Organizador: {{ $event->user->name ?? 'Anónimo' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No tienes próximos eventos a los que asistir</h4>
                            <p class="text-muted">Aún no te has apuntado a ningún evento próximo.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search me-2"></i>Buscar Eventos
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Eventos Pasados -->
                <div class="tab-pane fade" id="attended-past" role="tabpanel">
                    @if($attendedPast->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($attendedPast as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card card-vertical opacity-75">
                                            @if($event->cover_image)
                                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            @else
                                                <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-user me-1"></i>Organizador: {{ $event->user->name ?? 'Anónimo' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No tienes eventos pasados a los que hayas asistido</h4>
                            <p class="text-muted">Aún no has asistido a ningún evento que haya finalizado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
