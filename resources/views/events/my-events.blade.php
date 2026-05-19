@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@endsection

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        {{ __('events.my') }}
    </h2>

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

    <!-- Main Tabs: Creados vs Asistidos -->
    <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="created-tab" data-bs-toggle="tab" data-bs-target="#created" type="button" role="tab">
                <i class="fas fa-plus-circle me-2"></i>{{ __('events.my_created') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="attended-tab" data-bs-toggle="tab" data-bs-target="#attended" type="button" role="tab">
                <i class="fas fa-user-check me-2"></i>{{ __('events.attending') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="mainTabsContent">
        <!-- Eventos Creados Tab -->
        <div class="tab-pane fade show active" id="created" role="tabpanel">
            <!-- Sub-tabs: Próximos vs Pasados -->
            <ul class="nav nav-pills mb-4" id="createdSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="created-upcoming-tab" data-bs-toggle="pill" data-bs-target="#created-upcoming" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>{{ __('events.upcoming') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="created-past-tab" data-bs-toggle="pill" data-bs-target="#created-past" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>{{ __('events.past') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="createdSubTabsContent">
                <!-- Próximos Eventos -->
                <div class="tab-pane fade show active" id="created-upcoming" role="tabpanel">
                    @if($createdUpcoming->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($createdUpcoming as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card event-card-enhanced">
                                            @if($event->cover_image)
                                                <div class="card-image-wrapper">
                                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-image-wrapper">
                                                    <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <!-- Badge de estado -->
                                                    <div class="mb-2">
                                                        @if($event->status == 'approved')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>{{ __('events.status.approved') }}
                                                            </span>
                                                        @elseif($event->status == 'pending')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock me-1"></i>{{ __('events.status.pending') }}
                                                            </span>
                                                        @elseif($event->status == 'rejected')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times-circle me-1"></i>{{ __('events.status.rejected') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? __('common.general_category') }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-users me-1"></i>{{ $event->attendees()->count() }} {{ __('events.attendees') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('events.no_upcoming_created') }}</h4>
                            <p class="text-muted">{{ __('events.no_upcoming_created_help') }}</p>
                            <a href="{{ route('events.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>{{ __('events.create') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Eventos Pasados -->
                <div class="tab-pane fade" id="created-past" role="tabpanel">
                    @if($createdPast->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($createdPast as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card event-card-enhanced opacity-75">
                                            @if($event->cover_image)
                                                <div class="card-image-wrapper">
                                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-image-wrapper">
                                                    <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <!-- Badge de estado -->
                                                    <div class="mb-2">
                                                        @if($event->status == 'approved')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>{{ __('events.status.approved') }}
                                                            </span>
                                                        @elseif($event->status == 'pending')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock me-1"></i>{{ __('events.status.pending') }}
                                                            </span>
                                                        @elseif($event->status == 'rejected')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times-circle me-1"></i>{{ __('events.status.rejected') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? __('common.general_category') }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-users me-1"></i>{{ $event->attendees()->count() }} {{ __('events.attendees') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('events.no_past_created') }}</h4>
                            <p class="text-muted">{{ __('events.no_past_created_help') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Eventos Asistidos Tab -->
        <div class="tab-pane fade" id="attended" role="tabpanel">
            <!-- Sub-tabs: Próximos vs Pasados -->
            <ul class="nav nav-pills mb-4" id="attendedSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="attended-upcoming-tab" data-bs-toggle="pill" data-bs-target="#attended-upcoming" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>{{ __('events.upcoming') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="attended-past-tab" data-bs-toggle="pill" data-bs-target="#attended-past" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>{{ __('events.past') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="attendedSubTabsContent">
                <!-- Próximos Eventos -->
                <div class="tab-pane fade show active" id="attended-upcoming" role="tabpanel">
                    @if($attendedUpcoming->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($attendedUpcoming as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card event-card-enhanced">
                                            @if($event->cover_image)
                                                <div class="card-image-wrapper">
                                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-image-wrapper">
                                                    <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? __('common.general_category') }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-user me-1"></i>{{ __('events.organizer_label') }} {{ $event->user->name ?? __('common.anonymous') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('events.no_upcoming_attending') }}</h4>
                            <p class="text-muted">{{ __('events.no_upcoming_attending_help') }}</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search me-2"></i>{{ __('events.search') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Eventos Pasados -->
                <div class="tab-pane fade" id="attended-past" role="tabpanel">
                    @if($attendedPast->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            @foreach($attendedPast as $event)
                                <div class="col">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none">
                                        <div class="card event-card-enhanced opacity-75">
                                            @if($event->cover_image)
                                                <div class="card-image-wrapper">
                                                    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-image-wrapper">
                                                    <img src="https://via.placeholder.com/280x200?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                                    <div class="card-overlay">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="card-content">
                                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? __('common.general_category') }}</span>
                                                    <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                                    </p>
                                                    
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-user me-1"></i>{{ __('events.organizer_label') }} {{ $event->user->name ?? __('common.anonymous') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('events.no_past_attending') }}</h4>
                            <p class="text-muted">{{ __('events.no_past_attending_help') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
