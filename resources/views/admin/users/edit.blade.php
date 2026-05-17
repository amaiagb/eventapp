@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.common.edit') }}</h1>
    <p class="page-subtitle">{{ __('admin.common.edit') }}: {{ $user->username }}</p>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.common.edit') }}: {{ $user->username }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('admin.common.back') }}
            </a>
        </div>
    </div>
</div>

<!-- Alertas -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Formulario de Edición -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user-edit me-2"></i>{{ __('admin.users.table_username') }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('auth.name') }} *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                               placeholder="{{ __('auth.name_placeholder') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="surname" class="form-label">{{ __('auth.surname') }}</label>
                        <input type="text" class="form-control @error('surname') is-invalid @enderror" 
                               id="surname" name="surname" value="{{ old('surname', $user->surname) }}" maxlength="255"
                               placeholder="{{ __('auth.surname_placeholder') }}">
                        @error('surname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('auth.email') }} *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required
                       placeholder="{{ __('auth.email_placeholder') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role_id" class="form-label">{{ __('admin.users.table_role') }} *</label>
                        <select class="form-select @error('role_id') is-invalid @enderror" 
                                id="role_id" name="role_id" required>
                            <option value="">{{ __('admin.users.filter_all_roles') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="city_id" class="form-label">{{ __('auth.city') }}</label>
                        <select class="form-select @error('city_id') is-invalid @enderror" 
                                id="city_id" name="city_id">
                            <option value="">{{ __('auth.select_city') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" 
                                        {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
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

            <div class="mb-3">
                <label for="bio" class="form-label">{{ __('auth.bio') }}</label>
                <textarea class="form-control @error('bio') is-invalid @enderror" 
                          id="bio" name="bio" rows="4" maxlength="500"
                          placeholder="{{ __('auth.bio_placeholder') }}">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('admin.users.table_status') }}</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" 
                               type="radio" name="is_active" id="is_active_true" value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active_true">
                            {{ __('admin.common.active') }}
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" 
                               type="radio" name="is_active" id="is_active_false" value="0"
                               {{ !old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active_false">
                            {{ __('admin.common.inactive') }}
                        </label>
                    </div>
                    @error('is_active')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Información adicional -->
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>{{ __('admin.users.table_username') }}
                </h6>
                <p class="mb-1">
                    <strong>{{ __('auth.username') }}:</strong> {{ $user->username }}
                </p>
                <p class="mb-1">
                    <strong>{{ __('admin.users.table_created') }}:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="mb-0">
                    <strong>{{ __('common.id') }}:</strong> {{ $user->id }}
                </p>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>{{ __('admin.categories.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ __('admin.categories.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
