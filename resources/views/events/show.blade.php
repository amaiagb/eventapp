@extends('layouts.app')

@section('navbar')
@include('partials.navbar')
@endsection

@section('content')
<!-- Cover Image Full Width -->
@if($event->cover_image)
<div class="event-cover-image">
    <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-100">
    <div class="cover-overlay">
        <div class="container">
            <h1 class="event-title">{{ $event->title }}</h1>
        </div>
    </div>
</div>
@else
<div class="event-cover-image default-cover">
    <div class="default-cover-content">
        <div class="container">
            <h1 class="event-title">{{ $event->title }}</h1>
        </div>
    </div>
</div>
@endif

@if($event->status === 'pending')
<!-- Pending Event Warning Banner -->
<div class="alert alert-warning mb-4">
    <div class="container">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
            <div>
                <h5 class="alert-heading mb-1">{{ __('events.pending_approval_title') }}</h5>
                <p class="mb-0">{{ __('events.pending_approval_msg') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Content -->
<div class="container py-4">
    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Event Basic Info -->
            <div class="event-basic-info mb-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="category-badge-outline me-3">{{ $event->category->name ?? 'General' }}</span>
                    <div class="event-meta">
                        <span class="me-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            {{ $event->city->name ?? $event->location }}
                        </span>
                        <span>
                            <i class="far fa-calendar text-primary"></i>
                            {{ $event->event_date->format('d M Y') }}
                            @if($event->event_time)
                            {{ $event->event_time->format('H:i') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            <div class="event-description mb-5">
                <h3 class="section-title mb-3">{{ __('events.description_title') }}</h3>
                <div class="description-content">
                    <p>{{ $event->description }}</p>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="organizer-info mb-5">
                <h3 class="section-title mb-3">{{ __('events.organized_by') }}</h3>
                <div class="card organizer-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="organizer-avatar me-3">
                                @if($event->user && $event->user->profile_image)
                                <img src="{{ asset('storage/' . $event->user->profile_image) }}" alt="{{ $event->user->name }}" class="rounded-circle">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ $event->user ? strtoupper(substr($event->user->username ?? $event->user->name, 0, 1)) : '?' }}
                                </div>
                                @endif
                            </div>
                            <div class="organizer-details flex-grow-1">
                                <h5 class="mb-1">{{ $event->user->name ?? 'Anónimo' }}</h5>
                                <p class="text-muted small mb-0">{{ __('events.organizer') }}</p>
                            </div>
                            <div class="organizer-actions">
                                @if(auth()->check() && $event->status === 'approved')
                                <a href="{{ route('users.show', $event->user->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>{{ __('events.view_profile') }}
                                </a>
                                @if(auth()->id() !== $event->user->id)
                                <form id="followForm" action="{{ route('users.follow', $event->user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" id="followBtn" class="btn @if($isFollowing) btn-outline-secondary @else btn-primary @endif btn-sm">
                                        <i class="fas @if($isFollowing) fa-user-minus @else fa-user-plus @endif me-1"></i>
                                        <span id="followText">@if($isFollowing) {{ __('events.unfollow') }} @else {{ __('events.follow') }} @endif</span>
                                    </button>
                                </form>
                                @endif
                                @endif
                                
                                @if(auth()->check() && auth()->id() === $event->user_id)
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>{{ __('events.edit') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forum Section -->
            <div class="forum-section mb-5">
                <h3 class="section-title mb-3">{{ __('events.forum') }}</h3>
                <div class="forum-container">
                    @if($event->status === 'approved')
                    <!-- New Message Form -->
                    @if(auth()->check())
                    <div class="new-message-form mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">{{ __('events.forum_write') }}</h5>
                                <form action="{{ route('messages.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="content" rows="3" placeholder="{{ __('events.forum_placeholder') }}" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>{{ __('events.forum_send') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('events.forum_login_required') }}
                    </div>
                    @endif

                    <!-- Messages List -->
                    <div class="messages-list">
                        @if(auth()->check())
                            @if($messages->count() > 0)
                                @foreach($messages as $message)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="message-avatar me-3">
                                                @if($message->user && $message->user->profile_image)
                                                <img src="{{ asset('storage/' . $message->user->profile_image) }}" alt="{{ $message->user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    {{ $message->user ? strtoupper(substr($message->user->username ?? $message->user->name, 0, 1)) : '?' }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="message-content flex-grow-1">
                                                <div class="message-header mb-2">
                                                    <strong>{{ $message->user->name ?? 'Anónimo' }}</strong>
                                                    <span class="text-muted small ms-2">{{ $message->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="mb-0">{{ $message->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('events.forum_no_messages') }}
                            </div>
                            @endif
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('events.forum_login_required_view') }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('events.forum_approval_required') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Report Event -->
            @if($event->status === 'approved')
            <div class="report-event mb-5">
                @if(auth()->check() && $event->user_id !== auth()->id())
                <div class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="fas fa-flag me-2"></i>{{ __('events.report') }}
                    </button>
                </div>

                <!-- Report Modal -->
                <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reportModalLabel">
                                    <i class="fas fa-flag me-2"></i>{{ __('events.report') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('report.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="reportable_type" value="App\Models\Event">
                                    <input type="hidden" name="reportable_id" value="{{ $event->id }}">

                                    <div class="mb-3">
                                        <label for="reason" class="form-label">{{ __('events.report_reason') }}</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="4"
                                            placeholder="{{ __('events.report_placeholder') }}" required minlength="10" maxlength="500"></textarea>
                                        <div class="form-text">{{ __('events.report_chars') }}</div>
                                    </div>

                                    @error('reason')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    @error('reportable_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('events.cancel') }}</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-paper-plane me-2"></i>{{ __('events.report_send') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @elseif(!auth()->check())
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-flag me-2"></i>{{ __('events.report_login') }}
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Column - Sticky Registration Card -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <!-- Registration Card -->
                <div class="card registration-card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ __('events.register') }}</h4>

                        <div class="event-summary mb-4">
                            <div class="summary-item mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <span>{{ $event->event_date->format('d M Y') }}</span>
                            </div>
                            <div class="summary-item mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span>{{ $event->event_time?->format('H:i') ?? __('events.tbd') }}</span>
                            </div>
                            <div class="summary-item mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span>{{ $event->city->name ?? $event->location }}</span>
                            </div>
                            <div class="summary-item">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span>{{ $event->attendees()->count() }} {{ __('events.attendees') }}</span>
                            </div>
                        </div>

                        @if(auth()->check() && $event->status === 'approved')
                        @if(auth()->id() !== $event->user_id)
                        @if($isRegistered)
                        <button type="button" class="btn btn-success w-100 mb-3" style="pointer-events: none; cursor: default;">
                            <i class="fas fa-check me-2"></i>{{ __('events.registered') }}
                        </button>
                        <form action="{{ route('events.cancel', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link w-100 text-decoration-none text-muted">
                                {{ __('events.cancel_attendance') }}
                            </button>
                        </form>
                        @else
                        <form action="{{ route('events.register', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>{{ __('events.register') }}
                            </button>
                        </form>
                        @endif
                        @else
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('events.creator') }}
                        </div>
                        @endif
                        @elseif(auth()->check() && $event->status === 'pending' && auth()->id() === $event->user_id)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('events.pending_approval_actions') }}
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('events.login_required_register') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Share Event -->
                <!-- <div class="card share-card mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Compartir evento</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm flex-fill">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="btn btn-outline-success btn-sm flex-fill">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Other Events in Same City -->
@if(isset($otherEventsInCity) && $otherEventsInCity->count() > 0)
<div class="other-events-section bg-light py-5">
    <div class="container">
        <h3 class="section-title mb-4">
            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
            {{ __('events.other_in') }} {{ $event->city->name ?? __('events.same_city') }}
        </h3>
        <div class="carousel-container">
            @foreach($otherEventsInCity as $otherEvent)
            <div class="event-card-inline">
                <div class="card card-vertical">
                    @if($otherEvent->cover_image)
                    <img src="{{ asset('storage/img/events/' . $otherEvent->cover_image) }}" class="card-img-top" alt="{{ $otherEvent->title }}">
                    @else
                    <img src="https://via.placeholder.com/280x180?text=Evento" class="card-img-top" alt="{{ $otherEvent->title }}">
                    @endif
                    <div class="card-body">
                        <div class="card-content">
                            <span class="category-badge-outline mb-2 d-inline-block">{{ $otherEvent->category->name ?? 'General' }}</span>
                            <h5 class="card-title">{{ Str::limit($otherEvent->title, 40) }}</h5>
                            <p class="card-text text-muted small mb-2">
                                <i class="far fa-calendar me-1"></i>{{ $otherEvent->event_date->format('d M Y') }}
                            </p>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $otherEvent->city->name ?? $otherEvent->location }}
                            </p>
                        </div>
                        <a href="{{ route('events.show', $otherEvent->id) }}" class="btn btn-primary btn-sm w-100">{{ __('events.view_details') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const followForm = document.getElementById('followForm');
    const followBtn = document.getElementById('followBtn');
    const followText = document.getElementById('followText');
    
    if (followForm) {
        followForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const isCurrentlyFollowing = followBtn.classList.contains('btn-outline-secondary');
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar aspecto del botón
                    if (isCurrentlyFollowing) {
                        // Estaba siguiendo, ahora deja de seguir
                        followBtn.classList.remove('btn-outline-secondary');
                        followBtn.classList.add('btn-primary');
                        followBtn.querySelector('i').classList.remove('fa-user-minus');
                        followBtn.querySelector('i').classList.add('fa-user-plus');
                        followText.textContent = 'Seguir';
                    } else {
                        // No estaba siguiendo, ahora sigue
                        followBtn.classList.remove('btn-primary');
                        followBtn.classList.add('btn-outline-secondary');
                        followBtn.querySelector('i').classList.remove('fa-user-plus');
                        followBtn.querySelector('i').classList.add('fa-user-minus');
                        followText.textContent = 'Dejar de seguir';
                    }
                    
                    // Mostrar mensaje de éxito
                    showToast(data.message);
                } else {
                    showToast(data.message || 'Error al procesar la solicitud', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error de conexión', 'error');
            });
        });
    }
    
    function showToast(message, type = 'success') {
        // Crear elemento toast
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '1050';
        toast.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Eliminar automáticamente después de 3 segundos
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }
});
</script>
@endpush