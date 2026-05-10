@extends('layouts.app')

@section('navbar')
@include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="filter-sidebar ">
                <h5 class="mb-4"><i class="fas fa-filter me-2"></i>{{ __('search.filters') }}</h5>

                <form id="searchForm" method="GET" action="{{ route('search.index') }}">

                    <!-- Apply Button -->
                    <button type="button" id="applyFilters" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-search me-2"></i>{{ __('search.apply_filters') }}
                    </button>

                    
                    <!-- Location Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('search.location') }}</label>
                        <x-city-autocomplete 
                            :cities="$cities ?? []"
                            id="search_location"
                            name="location"
                            :value="request('location')"
                            placeholder="Localidad"
                            label=""
                        />
                    </div>

                    <!-- Date Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('search.date') }}</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('search.category') }}</label>
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
                        <label class="form-label fw-bold">{{ __('search.tags') }}</label>
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
                        <i class="fas fa-undo me-2"></i>{{ __('search.clear_filters') }}
                    </a>

                </form>
            </div>
        </div>

        <!-- Results List -->
        <div class="col-lg-6 results-container">
            <h5 class="mb-4"><i class="fas fa-list me-2"></i>{{ __('search.results') }} ({{ $events->total() }})</h5>

            @if($events->count() > 0)
            <div class="events-list">
                @foreach($events as $event)
                <div class="card card-horizontal mb-3">
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
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-sm w-100">Ver detalles</a>
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
                <i class="fas fa-info-circle me-2"></i>{{ __('search.no_events_found') }}
            </div>
            @endif
        </div>

        <div class="col-lg-3">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resultsContainer = document.querySelector('.results-container');
    const resultsTitle = document.querySelector('.results-container h5');
    
    // Function to update search results
    function updateSearchResults() {
        const formData = new FormData(searchForm);
        const params = new URLSearchParams(formData);
        
        // Show loading state
        resultsContainer.style.opacity = '0.5';
        applyFiltersBtn.disabled = true;
        applyFiltersBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('search.searching') }}';
        
        // Make AJAX request
        fetch(`{{ route('search.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update results
            const newResultsContainer = doc.querySelector('.results-container');
            const newResultsTitle = doc.querySelector('.results-container h5');
            
            if (newResultsContainer && newResultsTitle) {
                resultsContainer.innerHTML = newResultsContainer.innerHTML;
                resultsTitle.innerHTML = newResultsTitle.innerHTML;
            }
            
            // Update URL without reload
            history.pushState(null, '', `{{ route('search.index') }}?${params.toString()}`);
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback to regular form submission if AJAX fails
            searchForm.submit();
        })
        .finally(() => {
            // Reset loading state
            resultsContainer.style.opacity = '1';
            applyFiltersBtn.disabled = false;
            applyFiltersBtn.innerHTML = '<i class="fas fa-search me-2"></i>Aplicar Filtros';
        });
    }
    
    // Handle apply filters button click
    applyFiltersBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateSearchResults();
    });
    
    // Handle clear filters link
    const clearFiltersLink = document.querySelector('a[href*="buscador"]');
    if (clearFiltersLink) {
        clearFiltersLink.addEventListener('click', function(e) {
            e.preventDefault();
            // Reset form and update results
            searchForm.reset();
            window.location.href = '{{ route('search.index') }}';
        });
    }
    
});
</script>