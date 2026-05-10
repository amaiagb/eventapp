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
                <a href="#" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-carousel" data-carousel="for-you">
                <div class="carousel-track">
                    @foreach($forYouEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card h-100">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? 'General' }}</span>
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
                <a href="#" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-carousel" data-carousel="city">
                <div class="carousel-track">
                    @foreach($cityEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card h-100">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? 'General' }}</span>
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
                <a href="#" class="view-all-link">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-carousel" data-carousel="interests">
                <div class="carousel-track">
                    @foreach($interestEvents as $event)
                        <div class="event-card-modern">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                <div class="card event-card h-100">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                    @endif
                                    <div class="card-body">
                                        <span class="category-badge">{{ $event->category->name ?? 'General' }}</span>
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

<style>
/* Hero Banner Styles */
.hero-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.hero-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 60px;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 40px;
    opacity: 0.9;
}

.hero-stats {
    display: flex;
    gap: 40px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.hero-visual {
    position: relative;
    width: 300px;
    height: 300px;
}

.floating-card {
    position: absolute;
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    animation: float 6s ease-in-out infinite;
}

.card-1 {
    top: 20px;
    left: 20px;
    animation-delay: 0s;
}

.card-2 {
    top: 100px;
    right: 40px;
    animation-delay: 2s;
}

.card-3 {
    bottom: 40px;
    left: 60px;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Section Styles */
.events-section {
    margin-bottom: 60px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 30px;
}

.section-title-wrapper {
    flex: 1;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #2d3748;
}

.section-subtitle {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
}

.view-all-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.view-all-link:hover {
    color: #764ba2;
    transform: translateX(5px);
}

/* Modern Carousel Styles */
.modern-carousel {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
}

.carousel-track {
    display: flex;
    gap: 20px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.event-card-modern {
    flex: 0 0 280px;
    scroll-snap-align: start;
}

.event-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    height: 100%;
}

.event-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.event-card .card-img-top {
    height: 150px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card:hover .card-img-top {
    transform: scale(1.05);
}

.event-card .card-body {
    padding: 16px;
}

.category-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.event-card .card-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
    line-height: 1.4;
}

.event-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #718096;
    font-size: 0.85rem;
}

.event-location,
.event-organizer {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #718096;
    font-size: 0.85rem;
    margin-bottom: 5px;
}

/* Carousel Dots */
.carousel-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #cbd5e0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    background: #667eea;
    width: 24px;
    border-radius: 4px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
        gap: 40px;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .hero-visual {
        width: 200px;
        height: 200px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .event-card-modern {
        flex: 0 0 220px;
    }
}
</style>

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
