@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <!-- Events by Category Carousels -->
    @foreach($eventsByCategory as $categoryName => $events)
        @if($events->count() > 0)
            <section class="mb-5">
                <h4 class="section-title">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>{{ $categoryName }}
                </h4>
                <div class="carousel-container">
                    @foreach($events as $event)
                        <div class="event-card-inline">
                            <div class="card event-card-home">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                @else
                                    <img src="https://via.placeholder.com/280x180?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                @endif
                                <div class="card-body">
                                    <div class="card-content">
                                        <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 40) }}</h5>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                        </p>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                        </p>
                                        <p class="card-text text-muted small mb-3">
                                            @if($event->user)
                                                <i class="fas fa-user organizer-icon me-1"></i>{{ $event->user->name }}
                                            @endif
                                        </p>
                                    </div>
                                    <a href="#" class="btn btn-primary btn-sm w-100">Ver detalles</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    <!-- Featured Events Section -->
    @if($featuredEvents->count() > 0)
        <section class="mb-5">
            <h4 class="section-title">
                <i class="fas fa-star me-2 text-warning"></i>Eventos Destacados
            </h4>
            <div class="carousel-container">
                @foreach($featuredEvents as $event)
                    <div class="event-card-inline">
                        <div class="card event-card-home">
                            @if($event->cover_image)
                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                            @else
                                <img src="https://via.placeholder.com/280x180?text=Destacado" class="card-img-top" alt="{{ $event->title }}">
                            @endif
                            <div class="card-body">
                                <div class="card-content">
                                    <span class="badge bg-warning text-dark mb-2">Destacado</span>
                                    <h5 class="card-title">{{ Str::limit($event->title, 40) }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                    </p>
                                </div>
                                <a href="#" class="btn btn-primary btn-sm w-100">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- My Events Section -->
    @if(auth()->check() && $myEvents->count() > 0)
        <section class="mb-5">
            <h4 class="section-title">
                <i class="fas fa-calendar-check me-2 text-success"></i>Mis Eventos
            </h4>
            <div class="carousel-container">
                @foreach($myEvents as $event)
                    <div class="event-card-inline">
                        <div class="card event-card-home">
                            @if($event->cover_image)
                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                            @else
                                <img src="https://via.placeholder.com/280x180?text=Mi+Evento" class="card-img-top" alt="{{ $event->title }}">
                            @endif
                            <div class="card-body">
                                <div class="card-content">
                                    <span class="badge bg-success mb-2">Mi evento</span>
                                    <h5 class="card-title">{{ Str::limit($event->title, 40) }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary btn-sm flex-fill">Ver detalles</a>
                                    <a href="#" class="btn btn-outline-danger btn-sm">Dejar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Recommended Events Section -->
    @if($recommendedEvents->count() > 0)
        <section class="mb-5">
            <h4 class="section-title">
                <i class="fas fa-heart me-2 text-danger"></i>Recomendados para ti
            </h4>
            <div class="carousel-container">
                @foreach($recommendedEvents as $event)
                    <div class="event-card-inline">
                        <div class="card event-card-home">
                            @if($event->cover_image)
                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                            @else
                                <img src="https://via.placeholder.com/280x180?text=Recomendado" class="card-img-top" alt="{{ $event->title }}">
                            @endif
                            <div class="card-body">
                                <div class="card-content">
                                    <span class="badge bg-danger mb-2">Recomendado</span>
                                    <h5 class="card-title">{{ Str::limit($event->title, 40) }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary btn-sm flex-fill">Ver detalles</a>
                                    <button class="btn btn-outline-warning btn-sm" title="Me interesa">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

<!-- Floating Action Button for Creating Events -->
<button class="fab-create-event" data-bs-toggle="modal" data-bs-target="#createEventModal" title="Crear evento">
    <i class="fas fa-plus"></i>
</button>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Evento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nombre del evento</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="event_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" name="event_time" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Imagen de portada</label>
                            <input type="file" class="form-control" name="cover_image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
