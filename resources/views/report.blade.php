@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-flag me-2"></i>{{ __('report.title') }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('report.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="reportable_type" class="form-label">{{ __('report.type') }}</label>
                            <select class="form-select" id="reportable_type" name="reportable_type" required>
                                <option value="">{{ __('report.select_what') }}</option>
                                <option value="App\Models\Event">{{ __('report.event') }}</option>
                                <option value="App\Models\User">{{ __('report.user') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="reportable_id" class="form-label">{{ __('report.element_id') }}</label>
                            <input type="number" class="form-control" id="reportable_id" name="reportable_id" required 
                                   placeholder="{{ __('report.element_id_placeholder') }}">
                            <small class="form-text text-muted">{{ __('report.element_id_help') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">{{ __('report.reason') }}</label>
                            <textarea class="form-control" id="reason" name="reason" rows="4" required 
                                      placeholder="{{ __('report.reason_placeholder') }}"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ request()->header('referer') ?: route('home') }}" class="btn btn-secondary me-md-2">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-flag me-2"></i>{{ __('report.send') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
