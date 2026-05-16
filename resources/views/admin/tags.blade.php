@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.tags.title') }}</h1>
    <p class="page-subtitle">{{ __('admin.tags.subtitle') }}</p>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('admin.common.back_dashboard') }}
            </a>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> {{ __('admin.tags.new') }}
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

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.tags') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <label for="search" class="form-label">{{ __('admin.tags.search') }}</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="{{ __('admin.tags.search_placeholder') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i> {{ __('admin.tags.filter') }}
                    </button>
                </div>
            </div>
            <div class="col-12">
                <a href="{{ route('admin.tags') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> {{ __('admin.tags.clear_filters') }}
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Tags -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-tags me-2"></i>{{ __('admin.tags.list') }} ({{ $tags->total() }})
        </h6>
    </div>
    <div class="card-body">
        @if($tags->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.tags.table_name') }}</th>
                            <th>{{ __('admin.tags.table_events') }}</th>
                            <th>{{ __('admin.tags.table_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            <tr>
                                <td>
                                    <strong>{{ $tag->name }}</strong>
                                </td>
                                <td>
                                    <span class="">{{ $tag->events->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-secondary" title="{{ __('admin.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.tags.delete', $tag) }}" method="POST" style="display: inline;" data-confirm="{{ __('admin.tags.confirm_delete') }}" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('admin.common.delete') }}">
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
                {{ $tags->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('admin.tags.no_found') }}</h5>
                <p class="text-muted">{{ __('admin.tags.no_found_help') }}</p>
                <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> {{ __('admin.tags.create_first') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
