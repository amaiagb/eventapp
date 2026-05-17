@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.events.title') }}</h1>
    <p class="page-subtitle">{{ __('admin.events.subtitle') }}</p>
</div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('admin.common.back_dashboard') }}
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

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.events') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">{{ __('admin.events.filter_status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">{{ __('admin.events.filter_all') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('admin.events.filter_approved') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.events.filter_pending') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('admin.events.filter_rejected') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">{{ __('admin.events.filter_category') }}</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">{{ __('admin.events.filter_all_categories') }}</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">{{ __('admin.events.filter_search') }}</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="{{ __('admin.events.search_placeholder') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i> {{ __('admin.events.filter_button') }}
                            </button>
                            <a href="{{ route('admin.events') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> {{ __('admin.events.clear_filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Eventos -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>{{ __('admin.events.list') }} ({{ $events->total() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.events.table_name') }}</th>
                                        <th>{{ __('admin.events.organizer') }}</th>
                                        <th>{{ __('admin.events.category') }}</th>
                                        <th>{{ __('admin.events.location') }}</th>
                                        <th>{{ __('admin.events.date') }}</th>
                                        <th>{{ __('admin.events.table_status') }}</th>
                                        <th>{{ __('admin.events.table_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.events.show', $event) }}" class="text-decoration-none">
                                                    {{ Str::limit($event->title, 40) }}
                                                </a>
                                            </td>
                                            <td>{{ $event->user->username ?? 'N/A' }}</td>
                                            <td>{{ $event->category->name ?? 'N/A' }}</td>
                                            <td>{{ $event->city->name ?? $event->location }}</td>
                                            <td>{{ $event->event_date->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge {{ $event->status == 'approved' ? 'bg-success' : ($event->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ $event->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('admin.events.toggle', $event) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $event->is_active ? 'btn-warning' : 'btn-success' }}"
                                                                title="{{ $event->is_active ? __('admin.events.deactivate') : __('admin.events.activate') }}">
                                                            <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteEventModal"
                                                            data-event-id="{{ $event->id }}"
                                                            data-event-title="{{ $event->title }}"
                                                            data-delete-url="{{ route('admin.events.delete', $event) }}"
                                                            title="{{ __('admin.common.delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin.events.no_found') }}</h5>
                            <p class="text-muted">{{ __('admin.events.no_found_help') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.sidebar {
    position: fixed;
    top: 76px;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}
.sidebar-heading {
    font-size: .75rem;
    text-transform: uppercase;
}
@media (max-width: 767.8px) {
    .sidebar {
        position: static;
        height: auto;
    }
}
</style>

<!-- Modal de confirmación de eliminación de evento -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEventModalLabel">{{ __('admin.events.delete_confirm_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.events.delete_confirm_message') }} <strong id="deleteEventTitle"></strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="eventOrganizerMessage">{{ __('admin.events.loading_info') }}</span>
                </p>
                <form id="deleteEventForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</button>
                <button type="submit" form="deleteEventForm" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>{{ __('admin.events.delete_confirm_button') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteEventModal = document.getElementById('deleteEventModal');
    const deleteEventTitleSpan = document.getElementById('deleteEventTitle');
    const eventOrganizerMessage = document.getElementById('eventOrganizerMessage');
    const deleteEventForm = document.getElementById('deleteEventForm');

    // Textos de traducción
    const texts = {
        organizerInfo: "{{ __('admin.events.organizer_info', ['organizer' => 'X']) }}",
        errorLoadingInfo: "{{ __('admin.events.error_loading_info') }}"
    };

    // Evento show.bs.modal de Bootstrap
    deleteEventModal.addEventListener('show.bs.modal', async function(event) {
        const button = event.relatedTarget;
        
        if (!button) return;
        
        const eventId = button.dataset.eventId;
        const eventTitle = button.dataset.eventTitle;
        const deleteUrl = button.dataset.deleteUrl;

        // Establecer el título del evento
        deleteEventTitleSpan.textContent = eventTitle;
        
        // Establecer la acción del formulario
        deleteEventForm.action = deleteUrl;

        // Mostrar mensaje de carga
        eventOrganizerMessage.textContent = "{{ __('admin.events.loading_info') }}";

        // Obtener información del evento
        try {
            const response = await fetch(`{{ route('admin.events.info', ':eventId') }}`.replace(':eventId', eventId));
            const data = await response.json();
            
            // Mostrar el mensaje con el organizador
            eventOrganizerMessage.textContent = texts.organizerInfo.replace('X', data.organizer);
        } catch (error) {
            eventOrganizerMessage.textContent = texts.errorLoadingInfo;
        }
    });

    // Limpiar al cerrar el modal
    deleteEventModal.addEventListener('hidden.bs.modal', function() {
        deleteEventTitleSpan.textContent = '';
        eventOrganizerMessage.textContent = '';
        deleteEventForm.action = '';
    });
});
</script>

@endsection
