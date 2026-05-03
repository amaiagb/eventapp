@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Evento
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Información Básica -->
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-info-circle me-2"></i>Información Básica
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Nombre del evento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           name="title" placeholder="Ej: Concierto de Rock en el Parque" 
                                           value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Descripción <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" rows="4" 
                                              placeholder="Describe tu evento, actividades, requisitos, etc..."
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
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
                                
                                <div class="col-md-6">
                                    <label class="form-label">Ciudad <span class="text-danger">*</span></label>
                                    <select class="form-select @error('city_id') is-invalid @enderror" 
                                            name="city_id" required>
                                        <option value="">Seleccionar ciudad</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" 
                                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}, {{ $city->country->name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-clock me-2"></i>Fecha y Hora
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                           name="event_date" value="{{ old('event_date') }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Hora <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                           name="event_time" value="{{ old('event_time') }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación Específica -->
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Ubicación Específica
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Dirección o lugar específico <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           name="location" placeholder="Ej: Plaza Mayor, Centro de Convenciones, Parque Central" 
                                           value="{{ old('location') }}" required>
                                    <div class="form-text">
                                        Indica el lugar exacto donde se realizará el evento (dirección, nombre del lugar, etc.)
                                    </div>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Imagen -->
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-image me-2"></i>Imagen del Evento
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Imagen de portada</label>
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
                        </div>

                        <!-- Información Adicional -->
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-plus-square me-2"></i>Información Adicional
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               name="price" placeholder="0.00" step="0.01" min="0" 
                                               value="{{ old('price') }}">
                                        <span class="input-group-text">EUR</span>
                                    </div>
                                    <div class="form-text">Deja en blanco si el evento es gratuito</div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Capacidad máxima</label>
                                    <input type="number" class="form-control @error('max_capacity') is-invalid @enderror" 
                                           name="max_capacity" placeholder="Ej: 100" min="1" 
                                           value="{{ old('max_capacity') }}">
                                    <div class="form-text">Número máximo de asistentes (opcional)</div>
                                    @error('max_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Crear Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
