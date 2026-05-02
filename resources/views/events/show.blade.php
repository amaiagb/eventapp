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
                <h5 class="alert-heading mb-1">Evento Pendiente de Aprobación</h5>
                <p class="mb-0">Este evento está pendiente de aprobación por un administrador. Mientras sea aprobado, solo tú como creador puedes ver esta página.</p>
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
                <h3 class="section-title mb-3">Descripción del evento</h3>
                <div class="description-content">
                    <p>{{ $event->description }}</p>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="organizer-info mb-5">
                <h3 class="section-title mb-3">Organizado por</h3>
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
                                <p class="text-muted small mb-0">Organizador del evento</p>
                            </div>
                            <div class="organizer-actions">
                                @if(auth()->check() && $event->status === 'approved')
                                <a href="" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>Ver perfil
                                </a>
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-user-plus me-1"></i>Seguir
                                </button>
                                @elseif(auth()->check() && $event->status === 'pending' && auth()->id() === $event->user_id)
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Editar evento
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQs Section -->
            <!-- <div class="faqs-section mb-5">
                <h3 class="section-title mb-3">Preguntas Frecuentes</h3>
                <div class="accordion" id="faqsAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿Cómo puedo apuntarme al evento?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body">
                                Puedes apuntarte al evento haciendo clic en el botón "Apuntarse" que encontrarás en la columna derecha. Solo necesitas estar registrado en la plataforma.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿Es necesario pagar para participar?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body">
                                La mayoría de nuestros eventos son gratuitos. Si este evento tiene algún coste, se indicará específicamente en la descripción del evento.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                ¿Puedo cancelar mi asistencia?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body">
                                Sí, puedes cancelar tu asistencia en cualquier momento desde tu panel de eventos. Te recomendamos hacerlo con al menos 24 horas de antelación.
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Forum Section -->
            <div class="forum-section mb-5">
                <h3 class="section-title mb-3">Foro del evento</h3>
                <div class="forum-container">
                    @if($event->status === 'approved')
                    <!-- New Message Form -->
                    @if(auth()->check())
                    <div class="new-message-form mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Escribe un mensaje</h5>
                                <form>
                                    <div class="mb-3">
                                        <textarea class="form-control" rows="3" placeholder="Comparte algo sobre este evento..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar mensaje
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Debes estar <a href="{{ route('login') }}">iniciado sesión</a> para participar en el foro.
                    </div>
                    @endif

                    <!-- Messages List -->
                    <div class="messages-list">
                        @if(auth()->check())
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="message-avatar me-3">
                                        <div class="avatar-placeholder rounded-circle">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="message-content flex-grow-1">
                                        <div class="message-header mb-2">
                                            <strong>Usuario Ejemplo</strong>
                                            <span class="text-muted small ms-2">Hace 2 horas</span>
                                        </div>
                                        <p class="mb-0">¡Qué interesante este evento! ¿Alguien más va a asistir?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="message-avatar me-3">
                                        <div class="avatar-placeholder rounded-circle">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="message-content flex-grow-1">
                                        <div class="message-header mb-2">
                                            <strong>Otro Usuario</strong>
                                            <span class="text-muted small ms-2">Hace 1 hora</span>
                                        </div>
                                        <p class="mb-0">Sí, yo voy a estar ahí. Nos vemos allí!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Debes estar <a href="{{ route('login') }}">iniciado sesión</a> para ver los mensajes del foro.
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        El foro del evento estará disponible cuando el evento sea aprobado.
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
                        <i class="fas fa-flag me-2"></i>Reportar evento
                    </button>
                </div>

                <!-- Report Modal -->
                <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reportModalLabel">
                                    <i class="fas fa-flag me-2"></i>Reportar evento
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('report.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="reportable_type" value="App\Models\Event">
                                    <input type="hidden" name="reportable_id" value="{{ $event->id }}">

                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Motivo del reporte</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="4"
                                            placeholder="Describe el motivo por el cual reportas este evento..." required minlength="10" maxlength="500"></textarea>
                                        <div class="form-text">Mínimo 10 caracteres, máximo 500 caracteres</div>
                                    </div>

                                    @error('reason')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    @error('reportable_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar reporte
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @elseif(!auth()->check())
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-flag me-2"></i>Inicia sesión para reportar
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
                        <h4 class="card-title mb-4">Apuntarse al evento</h4>

                        <div class="event-summary mb-4">
                            <div class="summary-item mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <span>{{ $event->event_date->format('d M Y') }}</span>
                            </div>
                            <div class="summary-item mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span>{{ $event->event_time?->format('H:i') ?? 'Por determinar' }}</span>
                            </div>
                            <div class="summary-item mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span>{{ $event->city->name ?? $event->location }}</span>
                            </div>
                            <div class="summary-item">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span>{{ $event->attendees_count ?? 0 }} asistentes</span>
                            </div>
                        </div>

                        @if(auth()->check() && $event->status === 'approved')
                        <button class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Apuntarse al evento
                        </button>
                        @elseif(auth()->check() && $event->status === 'pending' && auth()->id() === $event->user_id)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tu evento está pendiente de aprobación. Las acciones estarán disponibles cuando sea aprobado.
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Debes <a href="{{ route('login') }}">iniciar sesión</a> para apuntarte a este evento.
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
            Otros eventos en {{ $event->city->name ?? 'la misma ciudad' }}
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
                        <a href="{{ route('events.show', $otherEvent->id) }}" class="btn btn-primary btn-sm w-100">Ver detalles</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection