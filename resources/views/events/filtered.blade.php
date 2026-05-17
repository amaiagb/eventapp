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
            <strong>{{ $events->count() }}</strong> eventos encontrados
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
                                    <span class="category-badge-enhanced mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
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
                    <h3 class="cta-title">¿No encontraste lo que buscabas?</h3>
                    <p class="cta-subtitle">Explora todos los eventos disponibles y descubre nuevas experiencias</p>
                </div>
                <a href="{{ route('search.index') }}" class="cta-button">
                    <i class="fas fa-search me-2"></i>Buscar más eventos
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

<style>
/* Banner Hero para Eventos Filtrados */
.filtered-hero-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 0;
    position: relative;
    overflow: hidden;
}

.filtered-hero-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.hero-content {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 1;
}

.hero-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1rem;
    margin-bottom: 0;
    opacity: 0.9;
}

/* Count de eventos */
.events-count {
    margin: 0;
    color: #2d3748;
    font-weight: 500;
}

/* Botón de volver mejorado */
.btn-back {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: white;
    color: #667eea;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-back:hover {
    background: #667eea;
    color: white;
    transform: translateX(-5px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Sección CTA */
.cta-section {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 20px;
    padding: 40px;
    margin-top: 40px;
}

.cta-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.cta-text {
    flex: 1;
}

.cta-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.cta-subtitle {
    color: #718096;
    font-size: 1rem;
    margin: 0;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    padding: 14px 32px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    white-space: nowrap;
}

.cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Card de evento mejorada */
.event-card-enhanced {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.event-card-enhanced:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
}

.card-image-wrapper {
    position: relative;
    overflow: hidden;
}

.event-card-enhanced .card-img-top {
    height: 160px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card-enhanced:hover .card-img-top {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.event-card-enhanced:hover .card-overlay {
    opacity: 1;
}

.card-overlay i {
    color: white;
    font-size: 2rem;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.event-card-enhanced .card-body {
    padding: 18px;
}

.category-badge-enhanced {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.event-card-enhanced .card-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
    line-height: 1.4;
}

.event-meta-enhanced,
.event-location-enhanced,
.event-organizer-enhanced {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #718096;
    font-size: 0.85rem;
    margin-bottom: 8px;
}

.event-meta-enhanced i,
.event-location-enhanced i,
.event-organizer-enhanced i {
    color: #667eea;
}

/* Paginación mejorada */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.pagination-wrapper .pagination {
    margin-bottom: 0;
}

.pagination-wrapper .page-item .page-link {
    color: #667eea;
    border-color: #e2e8f0;
    padding: 8px 16px;
    margin: 0 4px;
    border-radius: 8px;
    font-weight: 500;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
}

.pagination-wrapper .page-item .page-link:hover {
    background: #f7fafc;
    color: #764ba2;
}

/* Estado vacío mejorado */
.empty-state-enhanced {
    text-align: center;
    padding: 80px 20px;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 20px;
    margin-top: 20px;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: float 4s ease-in-out infinite;
}

.empty-state-icon i {
    color: white;
    font-size: 3rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.empty-state-text {
    color: #718096;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.empty-state-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.empty-state-actions .btn {
    padding: 12px 28px;
    border-radius: 25px;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 0.9rem;
    }
    
    .events-count {
        font-size: 1rem;
    }
    
    .cta-content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .cta-button {
        width: 100%;
        justify-content: center;
    }
    
    .empty-state-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .empty-state-actions .btn {
        width: 100%;
        max-width: 250px;
    }
}
</style>
@endsection
