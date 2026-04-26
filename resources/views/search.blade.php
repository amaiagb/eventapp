@extends('layouts.app')

@section('navbar')
@include('partials.navbar')
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="filter-sidebar ">
                <h5 class="mb-4"><i class="fas fa-filter me-2"></i>Filtros</h5>

                <form method="GET" action="{{ route('search.index') }}">

                    <!-- Apply Button -->
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-search me-2"></i>Aplicar Filtros
                    </button>

                    
                    <!-- Location Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ubicación</label>
                        <input type="text" class="form-control" name="location" placeholder="Ciudad o proximidad" value="{{ request('location') }}">
                    </div>

                    <!-- Date Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Categoría</label>
                        <div class="multiselect-container">
                            @foreach($categories as $category)
                            <label class="multiselect-item">
                                <input type="checkbox" name="category[]" value="{{ $category->id }}" class="multiselect-checkbox" @if(in_array($category->id, old('category', request('category', [])))) checked @endif>
                                <span class="multiselect-text">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tags Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tags</label>
                        <div class="multiselect-container">
                            @foreach($tags as $tag)
                            <label class="multiselect-item">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="multiselect-checkbox" @if(in_array($tag->id, old('tags', request('tags', [])))) checked @endif>
                                <span class="multiselect-text">{{ $tag->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('search.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-undo me-2"></i>Limpiar Filtros
                    </a>

                </form>
            </div>
        </div>

        <!-- Results List -->
        <div class="col-lg-5">
            <h5 class="mb-4"><i class="fas fa-list me-2"></i>Resultados ({{ $events->total() }})</h5>

            @if($events->count() > 0)
            <div class="events-list">
                @foreach($events as $event)
                <div class="card event-card mb-3">
                    <div class="row g-0">
                        <div class="col-md-5">
                            @if($event->cover_image)
                            <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top h-100" alt="{{ $event->title }}">
                            @else
                            <img src="https://via.placeholder.com/400x200?text=Evento" class="card-img-top h-100" alt="{{ $event->title }}">
                            @endif
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? 'General' }}</span>
                                <h5 class="card-title">{{ $event->title }}</h5>
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
                                <a href="#" class="btn btn-primary btn-sm w-100">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            {{ $events->appends(request()->query())->links() }}
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>No se encontraron eventos con los filtros aplicados.
            </div>
            @endif
        </div>

        <!-- Map -->
        <div class="col-lg-4">
            <div class="map-container">
                <h5 class="mb-4"><i class="fas fa-map me-2"></i>Mapa</h5>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>El mapa estará disponible próximamente.
                </div>
            </div>
        </div>
    </div>
</div>