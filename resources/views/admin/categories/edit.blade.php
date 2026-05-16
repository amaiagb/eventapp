@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.categories.edit') }}</h1>
    <p class="page-subtitle">{{ __('admin.categories.edit_subtitle') }}</p>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.categories.edit') }}: {{ $category->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('admin.categories.back') }}
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
            <i class="fas fa-folder-edit me-2"></i>{{ __('admin.categories.info') }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('admin.categories.name') }} *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100"
                       placeholder="{{ __('admin.categories.name_placeholder') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">{{ __('admin.categories.name_help') }}</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">{{ __('admin.categories.description') }}</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" maxlength="500"
                          placeholder="{{ __('admin.categories.description_placeholder') }}">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">{{ __('admin.categories.description_help') }}</div>
            </div>

            <div class="mb-3">
                <label for="icon" class="form-label">{{ __('admin.categories.icon') }}</label>
                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                       id="icon" name="icon" value="{{ old('icon', $category->icon) }}" maxlength="50"
                       placeholder="{{ __('admin.categories.icon_placeholder') }}">
                @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">{{ __('admin.categories.icon_help') }}</div>
            </div>

            <!-- Información adicional -->
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>{{ __('admin.categories.info') }}
                </h6>
                <p class="mb-1">
                    <strong>{{ __('admin.categories.events_count') }}:</strong> {{ $category->events->count() }}
                </p>
                <p class="mb-0">
                    <small>{{ __('admin.categories.cannot_delete') }}</small>
                </p>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
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
