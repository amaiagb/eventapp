@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="h2 mb-3 fw-bold">{{ __('events.edit') }}</h1>
                <p class="text-muted">{{ __('events.edit_subtitle') }}</p>
                @if($event->status === 'pending')
                    <div class="alert alert-warning d-inline-block">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('events.pending_approval') }}
                    </div>
                @endif
            </div>

            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                        
                        <!-- Event Name Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.name_question') }}</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('events.name_label') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       name="title" placeholder="{{ __('common.name_placeholder') }}" 
                                       value="{{ old('title', $event->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date and Time Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.start_question') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('events.date') }} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-lg @error('event_date') is-invalid @enderror" 
                                           name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('events.time') }} <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                           name="event_time" value="{{ old('event_time', $event->event_time->format('H:i')) }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.location_question') }}</h5>
                            
                            <!-- City Selection -->
                            <x-city-autocomplete 
                                :cities="$cities" 
                                id="city_input" 
                                name="city_name" 
                                :value="old('city_name', $event->city->name ?? '')"
                                :required="true"
                                label="{{ __('auth.city') }}"
                                :error="$errors->first('city_id')"
                            />
                            
                            <!-- Location Type -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('events.location') }}</label>
                                <div class="btn-group d-flex" role="group">
                                    <input type="radio" class="btn-check" name="location_type" id="location_specific" value="specific" 
                                           @if(old('location_type', 'specific') === 'specific') checked @endif>
                                    <label class="btn btn-outline-primary" for="location_specific">
                                        <i class="fas fa-map-marker-alt me-2"></i>{{ __('events.location_specify') }}
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="location_type" id="location_pending" value="pending"
                                           @if(old('location_type', 'specific') === 'pending') checked @endif>
                                    <label class="btn btn-outline-primary" for="location_pending">
                                        <i class="fas fa-clock me-2"></i>{{ __('events.location_tba') }}
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Location Input -->
                            <div id="location_input_container" @if(old('location_type', 'specific') === 'pending') style="display: none;" @endif>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">{{ __('events.location_specific') }}</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           name="location" placeholder="{{ __('common.location_placeholder') }}" 
                                           value="{{ old('location', $event->location) }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.describe') }}</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('events.description') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="5" 
                                          placeholder="{{ __('common.description_placeholder') }}"
                                          required>{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.type_question') }}</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('events.category') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        name="category_id" required>
                                    <option value="">{{ __('events.category_select') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tags Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.tags') }}</h5>
                            <p class="text-muted mb-4">{{ __('events.tags_help') }}</p>
                            
                            <div class="multiselect-container">
                                @foreach($tags as $tag)
                                <label class="multiselect-item">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="multiselect-checkbox" 
                                           @if(in_array($tag->id, old('tags', $event->tags->pluck('id')->toArray()))) checked @endif>
                                    <span class="multiselect-text">{{ $tag->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Capacity Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.capacity_question') }}</h5>
                            <p class="text-muted mb-4">{{ __('events.capacity_help') }}</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">{{ __('events.capacity_total') }}</label>
                                        <input type="number" class="form-control @error('max_attendees') is-invalid @enderror" 
                                               name="max_attendees" placeholder="0" min="0" 
                                               value="{{ old('max_attendees', $event->max_attendees) }}">
                                        @error('max_attendees')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Upload Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">{{ __('events.image') }}</h5>
                            @if($event->cover_image && $event->cover_image !== 'default.png')
                                <div class="mb-3">
                                    <label class="form-label fw-medium">{{ __('events.current_image') }}</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $event->cover_image_url }}" alt="{{ __('common.current_image_alt') }}" class="img-thumbnail" style="max-width: 150px; max-height: 100px;">
                                        <div>
                                            <p class="mb-0 small text-muted">{{ __('events.current_image') }}</p>
                                            <p class="mb-0 small">{{ __('events.current_image_help') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('events.change_image') }}</label>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                       name="cover_image" accept="image/*">
                                <div class="form-text">
                                    {{ __('events.change_image_help') }}
                                </div>
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Footer Buttons -->
                        <div class="d-flex justify-content-between gap-3 mt-5">
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-secondary">
                                {{ __('events.cancel') }}
                            </a>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    {{ __('events.save_changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cities data loaded from server
var citiesData = {!! json_encode($cities) !!};
var pendingValue = "{{ __('common.pending_value') }}";

// City autocomplete functionality
var selectedCityId = null;
var cityInput = document.getElementById('city_input');
var cityIdInput = document.getElementById('city_id');
var citySuggestions = document.getElementById('city_suggestions');

// Debounce function for search
function debounce(func, wait) {
    var timeout;
    return function() {
        var context = this;
        var args = arguments;
        var later = function() {
            timeout = null;
            func.apply(context, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search cities function
function performSearch(query) {
    if (query.length < 2) {
        citySuggestions.style.display = 'none';
        return;
    }
    
    // Filter cities locally
    var filteredCities = citiesData.filter(function(city) {
        return city.name.toLowerCase().includes(query.toLowerCase());
    }).slice(0, 20); // Limit to 20 results
    
    if (filteredCities.length === 0) {
        citySuggestions.style.display = 'none';
        return;
    }
    
    citySuggestions.innerHTML = filteredCities.map(function(city) {
        return '<div class="city-suggestion p-3 border-bottom cursor-pointer hover:bg-light" data-city-id="' + city.id + '" data-city-name="' + city.name + '">' +
            '<div class="fw-medium">' + city.name + '</div>' +
            '</div>';
    }).join('');
    
    citySuggestions.style.display = 'block';
    
    // Add click handlers to suggestions
    document.querySelectorAll('.city-suggestion').forEach(function(suggestion) {
        suggestion.addEventListener('click', function() {
            var cityName = this.dataset.cityName;
            var cityId = this.dataset.cityId;
            
            cityInput.value = cityName;
            cityIdInput.value = cityId;
            selectedCityId = cityId;
            citySuggestions.style.display = 'none';
        });
    });
}

// Create debounced version
var searchCities = debounce(performSearch, 300);

// Input event handler
cityInput.addEventListener('input', function(e) {
    var query = e.target.value;
    
    // Clear selection if user is typing
    if (selectedCityId) {
        var selectedCity = citiesData.find(function(city) {
            return city.id == selectedCityId;
        });
        if (!selectedCity || selectedCity.name !== query) {
            selectedCityId = null;
            cityIdInput.value = '';
        }
    }
    
    searchCities(query);
});

// Close suggestions when clicking outside
document.addEventListener('click', function(e) {
    if (!cityInput.contains(e.target) && !citySuggestions.contains(e.target)) {
        citySuggestions.style.display = 'none';
    }
});

// Establecer valor inicial si hay una ciudad seleccionada
@if($event->city_id)
    var initialCityId = {{ $event->city_id }};
    var initialCity = citiesData.find(function(city) {
        return city.id == initialCityId;
    });
    if (initialCity) {
        cityInput.value = initialCity.name;
        cityIdInput.value = initialCity.id;
        selectedCityId = initialCity.id;
    }
@endif

// Location type toggle
document.querySelectorAll('input[name="location_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const locationInputContainer = document.getElementById('location_input_container');
        const locationInput = document.querySelector('input[name="location"]');
        
        if (this.value === 'pending') {
            locationInputContainer.style.display = 'none';
            locationInput.value = pendingValue;
            locationInput.removeAttribute('required');
        } else {
            locationInputContainer.style.display = 'block';
            if (locationInput.value === pendingValue) {
                locationInput.value = '';
            }
            locationInput.setAttribute('required', 'required');
        }
    });
});

</script>
@endsection
