@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.categories.title') }}</h1>
    <p class="page-subtitle">{{ __('admin.categories.subtitle') }}</p>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('admin.categories.back_dashboard') }}
            </a>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> {{ __('admin.categories.new') }}
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Lista de Categorías -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-folder me-2"></i>{{ __('admin.categories.list') }} ({{ $categories->total() }})
        </h6>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.categories.table_name') }}</th>
                            <th>{{ __('admin.categories.table_description') }}</th>
                            <th>{{ __('admin.categories.table_icon') }}</th>
                            <th>{{ __('admin.categories.table_events') }}</th>
                            <th>{{ __('admin.categories.table_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                                <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="">{{ $category->events->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-secondary" title="{{ __('admin.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.delete', $category) }}" method="POST" style="display: inline;" data-confirm="{{ __('admin.categories.confirm_delete') }}" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('admin.common.delete') }}" 
                                                    {{ $category->events->count() > 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('admin.categories.no_found') }}</h5>
                <p class="text-muted">{{ __('admin.categories.no_found_help') }}</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> {{ __('admin.categories.create_first') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
