@extends('layouts.app')

@section('navbar')
@include('partials.navbar')
@endsection

@section('content')
<!-- Profile Header -->
<div class="profile-header bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <!-- Profile Image -->
                <div class="profile-avatar mb-3">
                    @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="rounded-circle profile-image">
                    @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center profile-image">
                        <h2 class="mb-0">{{ strtoupper(substr($user->username ?? $user->name, 0, 1)) }}</h2>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <!-- Profile Info -->
                <div class="profile-info">
                    <h1 class="profile-name mb-2">{{ $user->name }} {{ $user->surname }}</h1>
                    <p class="profile-username text-muted mb-3"><span>@</span>{{ $user->username }}</p>
                    
                    @if($user->bio)
                    <p class="profile-bio mb-3">{{ $user->bio }}</p>
                    @endif
                    
                    <div class="profile-location mb-3">
                        @if($user->city)
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <span>{{ $user->city->name }}</span>
                        @endif
                    </div>
                    
                    <!-- Follow Stats -->
                    <div class="profile-stats d-flex gap-4 mb-3">
                        <div class="stat-item">
                            <strong id="followersCount" class="text-primary">{{ $followersCount }}</strong>
                            <span class="text-muted">{{ __('user.followers') }}</span>
                        </div>
                        <div class="stat-item">
                            <strong class="text-primary">{{ $followingCount }}</strong>
                            <span class="text-muted">{{ __('user.following') }}</span>
                        </div>
                        <div class="stat-item">
                            <strong class="text-primary">{{ $events->total() }}</strong>
                            <span class="text-muted">{{ __('user.events') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <!-- Action Buttons -->
                <div class="profile-actions">
                    @if(auth()->check() && auth()->id() !== $user->id)
                    <form id="followForm" action="{{ route('users.follow', $user->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" id="followBtn" class="btn @if($isFollowing) btn-outline-secondary @else btn-primary @endif w-100">
                            <i class="fas @if($isFollowing) fa-user-minus @else fa-user-plus @endif me-2"></i>
                            <span id="followText">@if($isFollowing) {{ __('common.unfollow') }} @else {{ __('common.follow') }} @endif</span>
                        </button>
                    </form>
                    @endif
                    
                    @if(auth()->check() && auth()->id() === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>{{ __('profile.edit_title') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Events Section -->
<div class="user-events-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title mb-4">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    {{ __('user.events_by') }} {{ $user->name }}
                </h2>
                
                @if($events->count() > 0)
                <div class="row">
                    @foreach($events as $event)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card event-card-enhanced h-100">
                            @if($event->cover_image)
                            <div class="card-image-wrapper">
                                <img src="{{ asset('storage/img/events/' . $event->cover_image) }}" class="card-img-top" alt="{{ $event->title }}">
                                <div class="card-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            @else
                            <div class="card-image-wrapper">
                                <img src="https://via.placeholder.com/280x180?text=Evento" class="card-img-top" alt="{{ $event->title }}">
                                <div class="card-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="card-content flex-grow-1">
                                    <span class="category-badge-outline mb-2 d-inline-block">{{ $event->category->name ?? __('common.general_category') }}</span>
                                    <h5 class="card-title">{{ $event->title }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="far fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->city->name ?? $event->location }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-users me-1"></i>{{ $event->attendees()->count() }} {{ __('user.attendees') }}
                                    </p>
                                </div>
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-sm w-100 mt-auto">{{ __('user.view_details') }}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($events->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $events->links() }}
                </div>
                @endif
                
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ $user->name }} {{ __('user.no_events_created') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Success Messages -->
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const followForm = document.getElementById('followForm');
    const followBtn = document.getElementById('followBtn');
    const followText = document.getElementById('followText');
    const followersCount = document.getElementById('followersCount');
    
    // Traducciones para JavaScript
    const translations = {
        follow: "{{ __('common.follow') }}",
        unfollow: "{{ __('common.unfollow') }}",
        errorProcessing: "{{ __('common.error_processing') }}",
        connectionError: "{{ __('common.connection_error') }}"
    };
    
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
                        followText.textContent = translations.follow;
                        
                        // Disminuir contador de seguidores
                        followersCount.textContent = parseInt(followersCount.textContent) - 1;
                    } else {
                        // No estaba siguiendo, ahora sigue
                        followBtn.classList.remove('btn-primary');
                        followBtn.classList.add('btn-outline-secondary');
                        followBtn.querySelector('i').classList.remove('fa-user-plus');
                        followBtn.querySelector('i').classList.add('fa-user-minus');
                        followText.textContent = translations.unfollow;
                        
                        // Aumentar contador de seguidores
                        followersCount.textContent = parseInt(followersCount.textContent) + 1;
                    }
                    
                    // Mostrar mensaje de éxito
                    showToast(data.message);
                } else {
                    showToast(data.message || translations.errorProcessing, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(translations.connectionError, 'error');
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

