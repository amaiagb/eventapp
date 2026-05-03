@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- AI Header Section -->
            <div class="text-center mb-5">
                <h1 class="h2 mb-3 fw-bold">Crear un evento</h1>
                <p class="text-muted">Crea tu página de evento en minutos</p>
            </div>

            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                        
                        <!-- Event Name Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">¿Cómo se llama tu evento?</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nombre del evento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       name="title" placeholder="Ej: Concierto de Rock en el Parque" 
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date and Time Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">¿Cuándo empieza tu evento?</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-lg @error('event_date') is-invalid @enderror" 
                                           name="event_date" value="{{ old('event_date') }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Hora <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                           name="event_time" value="{{ old('event_time') }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">¿Dónde se ubica?</h5>
                            
                            <!-- City Selection -->
                            <x-city-autocomplete 
                                :cities="$cities" 
                                id="city_input" 
                                name="city_name" 
                                :value="old('city_name')"
                                :required="true"
                                label="Ciudad"
                                :error="$errors->first('city_id')"
                            />
                            
                            <!-- Location Type -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Ubicación</label>
                                <div class="btn-group d-flex" role="group">
                                    <input type="radio" class="btn-check" name="location_type" id="location_specific" value="specific" checked>
                                    <label class="btn btn-outline-primary" for="location_specific">
                                        <i class="fas fa-map-marker-alt me-2"></i>Especificar ubicación
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="location_type" id="location_pending" value="pending">
                                    <label class="btn btn-outline-primary" for="location_pending">
                                        <i class="fas fa-clock me-2"></i>Por anunciar
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Location Input -->
                            <div id="location_input_container">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ubicación específica</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           name="location" placeholder="Ej: Plaza Mayor, Madrid" 
                                           value="{{ old('location') }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">Describe tu evento</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Descripción <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="5" 
                                          placeholder="Describe tu evento, actividades, requisitos, etc..."
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">¿Qué tipo de evento es?</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        name="category_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <h5 class="mb-4 fw-semibold">Etiquetas (Tags)</h5>
                            <p class="text-muted mb-4">Selecciona las etiquetas que mejor describan tu evento.</p>
                            
                            <div class="multiselect-container">
                                @foreach($tags as $tag)
                                <label class="multiselect-item">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="multiselect-checkbox" @if(in_array($tag->id, old('tags', []))) checked @endif>
                                    <span class="multiselect-text">{{ $tag->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Capacity Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">¿Cuál es la capacidad para tu evento?</h5>
                            <p class="text-muted mb-4">Este es el número total de entradas que pondrás a la venta.</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Capacidad total</label>
                                        <input type="number" class="form-control @error('max_attendees') is-invalid @enderror" 
                                               name="max_attendees" placeholder="0" min="0" 
                                               value="{{ old('max_attendees') }}">
                                        @error('max_attendees')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Upload Section -->
                        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                            <h5 class="mb-4 fw-semibold">Imagen del evento</h5>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Imagen de portada</label>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                       name="cover_image" accept="image/*">
                                <div class="form-text">
                                    Formatos: JPG, PNG, GIF. Máximo 5MB. La imagen ayudará a que tu evento destaque.
                                </div>
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Footer Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                Salir
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Crear evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Location type toggle
document.querySelectorAll('input[name="location_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const locationInputContainer = document.getElementById('location_input_container');
        const locationInput = document.querySelector('input[name="location"]');
        
        if (this.value === 'pending') {
            locationInputContainer.style.display = 'none';
            locationInput.value = 'pendiente';
            locationInput.removeAttribute('required');
        } else {
            locationInputContainer.style.display = 'block';
            if (locationInput.value === 'pendiente') {
                locationInput.value = '';
            }
            locationInput.setAttribute('required', 'required');
        }
    });
});

</script>
@endsection
