@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>Mi Perfil
                    </h4>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
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
                                       accept="image/*" onchange="document.getElementById('profile-form').submit();">
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
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', Auth::user()->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="surname" class="col-md-4 col-form-label text-md-end">{{ __('Surname') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" id="surname" name="surname" class="form-control @error('surname') is-invalid @enderror" 
                                               value="{{ old('surname', Auth::user()->surname) }}" required>
                                        @error('surname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                    <div class="col-md-8">
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', Auth::user()->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="bio" class="col-md-4 col-form-label text-md-end">{{ __('Bio') }}</label>
                                    <div class="col-md-8">
                                        <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" 
                                                  rows="4" placeholder="Tell us about yourself...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                        @error('bio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="city_id" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>
                                    <div class="col-md-8">
                                        <select id="city_id" name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                            <option value="">{{ __('Select a city') }}</option>
                                            @if(isset($cities))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" 
                                                            {{ old('city_id', Auth::user()->city_id) == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('city_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>{{ __('Save Changes') }}
                                        </button>
                                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                                            {{ __('Cancel') }}
                                        </a>
                                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning ms-2">
                                            <i class="fas fa-lock me-1"></i>{{ __('Change Password') }}
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            <i class="fas fa-trash me-1"></i>{{ __('Delete Account') }}
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

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>{{ __('Delete Account') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.') }}</p>
                <div class="alert alert-warning">
                    <strong>{{ __('Warning:') }}</strong> {{ __('This will permanently delete your profile, events, and all associated data.') }}
                </div>
                
                <div class="mb-3">
                    <label for="delete_password" class="form-label">{{ __('Please enter your password to confirm:') }}</label>
                    <input type="password" class="form-control" id="delete_password" name="password" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form action="{{ route('profile.delete') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>{{ __('Delete Account') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
