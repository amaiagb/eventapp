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
                                                @if($event->user)
                                                    <i class="fas fa-user me-1"></i>{{ $event->user->name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
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
                        <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                            <div class="card card-vertical">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                @else
                                    <img src="https://via.placeholder.com/280x200?text=Destacado" class="card-img-top" alt="{{ $event->title }}">
                                @endif
                                <div class="card-body">
                                    <div class="card-content">
                                        <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                            @if($event->event_time)
                                                <i class="far fa-clock ms-2 me-1"></i>{{ $event->event_time->format('H:i') }}
                                            @endif
                                        </p>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                        </p>
                                        <p class="card-text text-muted small mb-3">
                                            @if($event->user)
                                                <i class="fas fa-user me-1"></i>{{ $event->user->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
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
                        <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                            <div class="card card-vertical">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                @else
                                    <img src="https://via.placeholder.com/280x200?text=Mi+Evento" class="card-img-top" alt="{{ $event->title }}">
                                @endif
                                <div class="card-body">
                                    <div class="card-content">
                                        <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                            @if($event->event_time)
                                                <i class="far fa-clock ms-2 me-1"></i>{{ $event->event_time->format('H:i') }}
                                            @endif
                                        </p>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                        </p>
                                        <p class="card-text text-muted small mb-3">
                                            @if($event->user)
                                                <i class="fas fa-user me-1"></i>{{ $event->user->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
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
                        <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                            <div class="card card-vertical">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                @else
                                    <img src="https://via.placeholder.com/280x200?text=Recomendado" class="card-img-top" alt="{{ $event->title }}">
                                @endif
                                <div class="card-body">
                                    <div class="card-content">
                                        <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                            @if($event->event_time)
                                                <i class="far fa-clock ms-2 me-1"></i>{{ $event->event_time->format('H:i') }}
                                            @endif
                                        </p>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                        </p>
                                        <p class="card-text text-muted small mb-3">
                                            @if($event->user)
                                                <i class="fas fa-user me-1"></i>{{ $event->user->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
