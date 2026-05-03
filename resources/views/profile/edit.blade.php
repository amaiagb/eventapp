@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Editar Perfil
                    </h4>
                    <a href="{{ route('profile.details') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Image Section -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if(Auth::user()->profile_image)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                         alt="Profile Image" 
                                         class="rounded-circle" 
                                         style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                         style="width: 120px; height: 120px; font-size: 48px; color: white;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <label for="profile_image" class="btn btn-sm btn-primary position-absolute bottom-0 end-0" 
                                       style="margin-right: -10px; margin-bottom: -10px;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="profile_image" name="profile_image" class="d-none" 
                                       accept="image/*">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" 
                                               value="{{ old('username', Auth::user()->username) }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', Auth::user()->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="surname" class="col-md-4 col-form-label text-md-end">{{ __('Surname') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" id="surname" name="surname" class="form-control @error('surname') is-invalid @enderror" 
                                               value="{{ old('surname', Auth::user()->surname) }}" required>
                                        @error('surname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                    <div class="col-md-8">
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', Auth::user()->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="city_id" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>
                                    <div class="col-md-8">
                                        <select id="city_id" name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                            <option value="">Seleccionar ciudad</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id', Auth::user()->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bio" class="col-md-2 col-form-label text-md-end">{{ __('Bio') }}</label>
                            <div class="col-md-10">
                                <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" 
                                          rows="4" placeholder="Cuéntanos sobre ti...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning">
                                        <i class="fas fa-key me-2"></i>Cambiar Contraseña
                                    </a>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
