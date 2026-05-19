@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<!-- Banner Hero para Eventos Filtrados -->
<section class="filtered-hero-banner">
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">{{ $title }}</h1>
            <p class="hero-subtitle">{{ $subtitle }}</p>
        </div>
    </div>
</section>

<div class="container py-5">
    <!-- Botón de volver con count de eventos -->
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h5 class="events-count">
            <strong>{{ $events->count() }}</strong> {{ __('events.filtered_count') }}
        </h5>
        <a href="{{ route('home') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>{{ __('common.back') }}
        </a>
    </div>

    @if($events->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($events as $event)
                <div class="col">
                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                        <div class="card event-card-enhanced">
                            @if($event->cover_image)
                                <div class="card-image-wrapper">
                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                    <div class="card-overlay">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                            @else
                                <div class="card-image-wrapper">
                                    <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                    <div class="card-overlay">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <div class="card-content">
                                    <span class="category-badge mb-2 d-inline-block">{{ $event->category->name ?? __('common.general_category') }}</span>
                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                    
                                    <div class="event-meta-enhanced">
                                        <div class="meta-item">
                                            <i class="far fa-calendar"></i>
                                            <span>{{ $event->event_date->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="event-location-enhanced">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $event->city->name ?? $event->location }}</span>
                                    </div>
                                    
                                    @if($event->user)
                                        <div class="event-organizer-enhanced">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $event->user->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Paginación mejorada -->
        <div class="pagination-wrapper">
            {{ $events->links() }}
        </div>

        <!-- Sección CTA -->
        <section class="cta-section mt-5">
            <div class="cta-content">
                <div class="cta-text">
                    <h3 class="cta-title">{{ __('events.filtered_cta_title') }}</h3>
                    <p class="cta-subtitle">{{ __('events.filtered_cta_subtitle') }}</p>
                </div>
                <a href="{{ route('search.index') }}" class="cta-button">
                    <i class="fas fa-search me-2"></i>{{ __('events.filtered_cta_button') }}
                </a>
            </div>
        </section>
    @else
        <!-- Estado vacío mejorado -->
        <div class="empty-state-enhanced">
            <div class="empty-state-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h4 class="empty-state-title">{{ __('events.none') }}</h4>
            <p class="empty-state-text">{{ __('events.no_approved') }}</p>
            <div class="empty-state-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>{{ __('footer.home') }}
                </a>
                <a href="{{ route('events.index') }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-search me-2"></i>{{ __('events.search') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
