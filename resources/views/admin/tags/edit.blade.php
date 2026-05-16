@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.tags.edit') }}</h1>
    <p class="page-subtitle">{{ __('admin.tags.edit_subtitle') }}</p>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.tags.edit') }}: {{ $tag->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.tags') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('admin.tags.back') }}
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
            <i class="fas fa-tag me-2"></i>{{ __('admin.tags.info') }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('admin.tags.name') }} *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $tag->name) }}" required maxlength="50"
                       placeholder="{{ __('admin.tags.name_placeholder') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">{{ __('admin.tags.name_help') }}</div>
            </div>

            <!-- Información adicional -->
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>{{ __('admin.tags.info') }}
                </h6>
                <p class="mb-0">
                    <strong>{{ __('admin.tags.events_count') }}:</strong> {{ $tag->events->count() }}
                </p>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.tags') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>{{ __('admin.tags.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ __('admin.tags.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
