@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="h2 mb-3 fw-bold">{{ __('profile.title') }}</h1>
                <p class="text-muted">{{ __('profile.subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Image Section -->
                <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h5 class="mb-4 fw-semibold">{{ __('profile.photo') }}</h5>
                    <div class="text-center">
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
                        <p class="text-muted mt-3 mb-0">{{ __('profile.photo_help') }}</p>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h5 class="mb-4 fw-semibold">{{ __('profile.personal_info') }}</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">{{ __('auth.username') }} <span class="text-danger">*</span></label>
                            <input type="text" id="username" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                   value="{{ old('username', Auth::user()->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">{{ __('auth.email_address') }} <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">{{ __('auth.name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">{{ __('auth.surname') }} <span class="text-danger">*</span></label>
                            <input type="text" id="surname" name="surname" class="form-control form-control-lg @error('surname') is-invalid @enderror" 
                                   value="{{ old('surname', Auth::user()->surname) }}" required>
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">{{ __('profile.bio') }}</label>
                            <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" 
                                      rows="4" placeholder="{{ __('profile.bio_placeholder') }}">{{ old('bio', Auth::user()->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <x-city-autocomplete 
                                :cities="$cities ?? []"
                                name="city_name"
                                id="city_input"
                                :value="old('city_name', Auth::user()->city->name ?? null)"
                                required="false"
                                placeholder="{{ __('common.select_city') }}"
                                :label="__('common.city')"
                                :error="$errors->first('city_id')"
                            />
                        </div>
                    </div>
                </div>

                <!-- Interests Section -->
                <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h5 class="mb-4 fw-semibold">{{ __('profile.interests') }}</h5>
                    <p class="text-muted mb-4">{{ __('profile.interests_help') }}</p>
                    
                    <div class="multiselect-container">
                        @if(isset($tags))
                            @foreach($tags as $tag)
                            <label class="multiselect-item">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="multiselect-checkbox" @if(in_array($tag->id, $userTags)) checked @endif>
                                <span class="multiselect-text">{{ $tag->name }}</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="d-flex justify-content-between gap-3 mt-5">
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-lock me-1"></i> {{ __('profile.change_password') }}
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-1"></i> {{ __('common.delete_account') }}
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            {{ __('common.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> {{ __('profile.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de eliminación de cuenta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>{{ __('common.delete_account') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('common.delete_account_confirm') }}</p>
                <div class="alert alert-warning">
                    <strong>{{ __('common.delete_account_warning') }}</strong>
                </div>
                
                <div class="mb-3">
                    <label for="delete_password" class="form-label">{{ __('common.delete_password_confirm') }}</label>
                    <input type="password" class="form-control" id="delete_password" name="password" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                <form action="{{ route('profile.delete') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>{{ __('common.delete_account') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
