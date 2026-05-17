@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="fas fa-calendar-alt me-2"></i>{{ $title }}
            </h2>
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>{{ __('common.back') }}
        </a>
    </div>

    @if($events->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($events as $event)
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

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">{{ __('events.none') }}</h4>
            <p class="text-muted">{{ __('events.no_approved') }}</p>
        </div>
    @endif
</div>
@endsection
