@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<!-- Banner Hero -->
<section class="hero-banner">
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">{{ __('home.hero.title') }}</h1>
            <p class="hero-subtitle">{{ __('home.hero.subtitle') }}</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <span class="stat-label">{{ __('home.hero.events') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">{{ __('home.hero.cities') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">{{ __('home.hero.activity') }}</span>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="floating-card card-1">
                <i class="fas fa-calendar-star"></i>
            </div>
            <div class="floating-card card-2">
                <i class="fas fa-users"></i>
            </div>
            <div class="floating-card card-3">
                <i class="fas fa-map-marked-alt"></i>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <!-- Especialmente para ti -->
    @if($forYouEvents->count() > 0)
        <section class="events-section mb-5">
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h4 class="section-title">
                        <i class="fas fa-heart me-2 text-danger"></i>{{ __('home.for_you_title') }}
                    </h4>
                    <p class="section-subtitle">{{ __('home.for_you_subtitle') }}</p>
                </div>
@auth
                <a href="{{ route('events.filtered', 'following') }}" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
                @endauth
            </div>
            <div class="modern-carousel" data-carousel="following">
                <div class="carousel-track">
                    @foreach($forYouEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card-enhanced h-100">
                                    @if($event->cover_image)
                                        <div class="card-image-wrapper">
                                            <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-image-wrapper">
                                            <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? __('common.general_category') }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 45) }}</h5>
                                        <div class="event-meta">
                                            <div class="meta-item">
                                                <i class="far fa-calendar"></i>
                                                <span>{{ $event->event_date->format('d M') }}</span>
                                            </div>
                                            @if($event->event_time)
                                            <div class="meta-item">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $event->event_time->format('H:i') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->city->name ?? $event->location }}</span>
                                        </div>
                                        @if($event->user)
                                        <div class="event-organizer">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $event->user->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="carousel-dots"></div>
            </div>
        </section>
    @endif

    <!-- En tu ciudad -->
    @if($cityEvents->count() > 0)
        <section class="events-section mb-5">
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h4 class="section-title">
                        <i class="fas fa-map-pin me-2 text-primary"></i>{{ __('home.city_title') }} {{ $userCityName }}
                    </h4>
                    <p class="section-subtitle">{{ __('home.city_subtitle') }}</p>
                </div>
@auth
                <a href="{{ route('events.filtered', 'city') }}" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
                @endauth
            </div>
            <div class="modern-carousel" data-carousel="city">
                <div class="carousel-track">
                    @foreach($cityEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card-enhanced h-100">
                                    @if($event->cover_image)
                                        <div class="card-image-wrapper">
                                            <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-image-wrapper">
                                            <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? __('common.general_category') }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 45) }}</h5>
                                        <div class="event-meta">
                                            <div class="meta-item">
                                                <i class="far fa-calendar"></i>
                                                <span>{{ $event->event_date->format('d M') }}</span>
                                            </div>
                                            @if($event->event_time)
                                            <div class="meta-item">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $event->event_time->format('H:i') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->city->name ?? $event->location }}</span>
                                        </div>
                                        @if($event->user)
                                        <div class="event-organizer">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $event->user->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="carousel-dots"></div>
            </div>
        </section>
    @endif

    <!-- Según tus intereses -->
    @if($interestEvents->count() > 0)
        <section class="events-section mb-5">
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h4 class="section-title">
                        <i class="fas fa-star me-2 text-warning"></i>{{ __('home.interests_title') }}
                    </h4>
                    <p class="section-subtitle">{{ __('home.interests_subtitle') }}</p>
                </div>
@auth
                <a href="{{ route('events.filtered', 'interests') }}" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
                @endauth
            </div>
            <div class="modern-carousel" data-carousel="interests">
                <div class="carousel-track">
                    @foreach($interestEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card-enhanced h-100">
                                    @if($event->cover_image)
                                        <div class="card-image-wrapper">
                                            <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-image-wrapper">
                                            <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? __('common.general_category') }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 45) }}</h5>
                                        <div class="event-meta">
                                            <div class="meta-item">
                                                <i class="far fa-calendar"></i>
                                                <span>{{ $event->event_date->format('d M') }}</span>
                                            </div>
                                            @if($event->event_time)
                                            <div class="meta-item">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $event->event_time->format('H:i') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->city->name ?? $event->location }}</span>
                                        </div>
                                        @if($event->user)
                                        <div class="event-organizer">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $event->user->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="carousel-dots"></div>
            </div>
        </section>
    @endif

    <!-- Eventos destacados - Sección genérica -->
    @if($genericEvents->count() > 0)
        <section class="events-section mb-5">
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h4 class="section-title">
                        <i class="fas fa-fire me-2 text-danger"></i>{{ __('home.generic_title') }}
                    </h4>
                    <p class="section-subtitle">{{ __('home.generic_subtitle') }}</p>
                </div>
                <a href="{{ route('events.index') }}" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-carousel" data-carousel="generic">
                <div class="carousel-track">
                    @foreach($genericEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card-enhanced h-100">
                                    @if($event->cover_image)
                                        <div class="card-image-wrapper">
                                            <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-image-wrapper">
                                            <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                            <div class="card-overlay">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? __('common.general_category') }}</span>
                                        <h5 class="card-title">{{ Str::limit($event->title, 45) }}</h5>
                                        <div class="event-meta">
                                            <div class="meta-item">
                                                <i class="far fa-calendar"></i>
                                                <span>{{ $event->event_date->format('d M') }}</span>
                                            </div>
                                            @if($event->event_time)
                                            <div class="meta-item">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $event->event_time->format('H:i') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->city->name ?? $event->location }}</span>
                                        </div>
                                        @if($event->user)
                                        <div class="event-organizer">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $event->user->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="carousel-dots"></div>
            </div>
        </section>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar carruseles modernos
    const carousels = document.querySelectorAll('.modern-carousel');
    
    carousels.forEach(carousel => {
        const track = carousel.querySelector('.carousel-track');
        const dotsContainer = carousel.querySelector('.carousel-dots');
        const cards = carousel.querySelectorAll('.event-card-modern');
        
        if (cards.length === 0) return;
        
        // Calcular cuántas cards caben por vista
        const cardWidth = 300; // 280px + 20px gap
        const containerWidth = carousel.offsetWidth;
        const cardsPerView = Math.floor(containerWidth / cardWidth);
        const totalSlides = Math.ceil(cards.length / cardsPerView);
        
        // Crear puntos de navegación
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('div');
            dot.className = 'dot';
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }
        
        let currentSlide = 0;
        
        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            const offset = slideIndex * cardsPerView * cardWidth;
            track.style.transform = `translateX(-${offset}px)`;
            
            // Actualizar puntos
            dotsContainer.querySelectorAll('.dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === slideIndex);
            });
        }
        
        // Auto-avance opcional (cada 5 segundos)
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            goToSlide(currentSlide);
        }, 5000);
        
        // Navegación con teclado
        carousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                currentSlide = Math.max(0, currentSlide - 1);
                goToSlide(currentSlide);
            } else if (e.key === 'ArrowRight') {
                currentSlide = Math.min(totalSlides - 1, currentSlide + 1);
                goToSlide(currentSlide);
            }
        });
    });
});
</script>
@endsection
