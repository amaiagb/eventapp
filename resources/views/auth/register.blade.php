@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white text-center py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ __('auth.register') }}</h4>
                        </div>
                        <!-- Dark Mode Toggle -->
                        <button class="dark-mode-toggle btn btn-outline-light btn-sm theme-toggle"
                                type="button"
                                title="{{ __('nav.dark_mode') }}"
                                onclick="toggleDarkMode()">
                            <i class="fas fa-moon" id="theme-icon"></i>
                        </button>
                    </div>
                    <p class="mb-0 small opacity-75">{{ __('auth.create_account') }}</p>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label fw-semibold">
                                        <i class="fas fa-user me-2 text-primary"></i>{{ __('auth.username') }}
                                    </label>
                                    <input id="username" type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Ingrese su nombre de usuario">
                                    @error('username')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-id-card me-2 text-primary"></i>{{ __('auth.name') }}
                                    </label>
                                    <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('auth.name_placeholder') }}">
                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="surname" class="form-label fw-semibold">
                                        <i class="fas fa-id-card me-2 text-primary"></i>{{ __('auth.surname') }}
                                    </label>
                                    <input id="surname" type="text" class="form-control form-control-lg @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" placeholder="{{ __('auth.surname_placeholder') }}">
                                    @error('surname')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-2 text-primary"></i>{{ __('auth.email_address') }}
                                    </label>
                                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('auth.email_placeholder') }}">
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bio" class="form-label fw-semibold">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>{{ __('auth.bio') }}
                                    </label>
                                    <textarea id="bio" class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3" placeholder="{{ __('auth.bio_placeholder') }}">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profile_image" class="form-label fw-semibold">
                                        <i class="fas fa-image me-2 text-primary"></i>{{ __('auth.profile_image') }}
                                    </label>
                                    <input id="profile_image" type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="city_id" class="form-label fw-semibold">
                                        <i class="fas fa-city me-2 text-primary"></i>{{ __('auth.city') }}
                                    </label>
                                    <select id="city_id" class="form-select form-select-lg @error('city_id') is-invalid @enderror" name="city_id" required>
                                        <option value="">{{ __('auth.select_city') }}</option>
                                        @if(isset($cities))
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-2 text-primary"></i>{{ __('auth.password') }}
                                    </label>
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password-confirm" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-2 text-primary"></i>{{ __('auth.confirm_password') }}
                                    </label>
                                    <input id="password-confirm" type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>{{ __('auth.register') }}
                            </button>

                            @if (Route::has('login'))
                                <div class="text-center mt-3">
                                    <span class="text-muted">{{ __('auth.already_account') }}</span>
                                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none">
                                        {{ __('auth.login') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
