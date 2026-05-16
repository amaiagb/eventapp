@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.events.details_title') }}</h1>
    <p class="page-subtitle">{{ __('admin.events.details_subtitle') }}</p>
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

<!-- Event Details -->
<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-alt me-2"></i>{{ __('admin.events.info') }}
                </h6>
                <div class="d-flex align-items-center gap-2">
                    @if($event->status == 'pending')
                        <span class="badge bg-secondary fs-6">
                            <i class="fas fa-clock me-1"></i>{{ __('admin.events.pending_approval') }}
                        </span>
                    @elseif($event->status == 'approved')
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>{{ __('events.status.approved') }}
                        </span>
                    @elseif($event->status == 'rejected')
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-times-circle me-1"></i>{{ __('events.status.rejected') }}
                        </span>
                    @endif
                    <button id="editBtn" class="btn btn-sm btn-primary" onclick="toggleEditMode()">
                        <i class="fas fa-edit me-1"></i>{{ __('admin.common.edit') }}
                    </button>
                    <button id="saveBtn" class="btn btn-sm btn-success d-none" onclick="saveChanges()">
                        <i class="fas fa-save me-1"></i>{{ __('admin.common.save') }}
                    </button>
                    <button id="cancelBtn" class="btn btn-sm btn-secondary d-none" onclick="cancelEditMode()">
                        <i class="fas fa-times me-1"></i>{{ __('admin.common.cancel') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="editEventForm" action="{{ route('admin.events.update', $event) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Cover Image -->
                    @if($event->cover_image)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/img/events/' . $event->cover_image) }}"
                                 class="img-fluid rounded"
                                 alt="{{ $event->title }}"
                                 style="max-height: 400px;">
                        </div>
                    @endif
                    <div class="mb-4">
                        <strong>{{ __('events.cover_image') }}:</strong>
                        <div id="coverImageDisplay" class="mt-2">
                            @if($event->cover_image)
                                <span class="text-muted">{{ $event->cover_image }}</span>
                            @else
                                <span class="text-muted">{{ __('admin.events.no_image') }}</span>
                            @endif
                        </div>
                        <div id="coverImageInput" class="d-none mt-2">
                            <input type="file" class="form-control" name="cover_image" accept="image/*">
                            <small class="text-muted">{{ __('admin.events.image_help') }}</small>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <span id="titleDisplay">{{ $event->title }}</span>
                                <input type="text" id="titleInput" class="form-control d-none" name="title" value="{{ $event->title }}">
                            </h5>
                            <p class="text-muted">
                                <span id="descriptionDisplay">{{ $event->description }}</span>
                                <textarea id="descriptionInput" class="form-control d-none" name="description" rows="4">{{ $event->description }}</textarea>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>{{ __('admin.events.category') }}:</strong>
                                <span class="badge bg-info ms-2" id="categoryDisplay">{{ $event->category->name ?? 'N/A' }}</span>
                                <select id="categoryInput" class="form-select d-none ms-2" name="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('admin.events.date') }}:</strong>
                                <span id="dateDisplay">{{ $event->event_date->format('d/m/Y') }}</span>
                                <input type="date" id="dateInput" class="form-control d-none ms-2" name="event_date" value="{{ $event->event_date->format('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('admin.events.time') }}:</strong>
                                <span id="timeDisplay">{{ $event->event_time->format('H:i') }}</span>
                                <input type="time" id="timeInput" class="form-control d-none ms-2" name="event_time" value="{{ $event->event_time->format('H:i') }}">
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('admin.events.capacity') }}:</strong>
                                <span id="capacityDisplay">
                                    @if($event->max_attendees)
                                        {{ $event->max_attendees }}
                                    @else
                                        {{ __('common.no_limit') }}
                                    @endif
                                </span>
                                <input type="number" id="capacityInput" class="form-control d-none ms-2" name="max_attendees" value="{{ $event->max_attendees ?? '' }}" placeholder="{{ __('common.no_limit') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ __('admin.events.location') }}
                            </h6>
                            <p class="mb-1">
                                <strong>{{ __('auth.city') }}:</strong>
                                <span id="cityDisplay">{{ $event->city->name ?? 'N/A' }}</span>
                                <div id="cityInput" class="d-none ms-2">
                                    <x-city-autocomplete
                                        :cities="$cities"
                                        id="edit_city_input"
                                        name="city_name"
                                        :value="$event->city->name ?? null"
                                        :city_id="$event->city_id ?? null"
                                        :required="true"
                                        label=""
                                        :placeholder="__('common.city_placeholder')"
                                    />
                                </div>
                            </p>
                            <p class="mb-0">
                                <strong>{{ __('admin.events.location') }}:</strong>
                                <span id="locationDisplay">{{ $event->location }}</span>
                                <input type="text" id="locationInput" class="form-control d-none ms-2" name="location" value="{{ $event->location }}">
                            </p>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-tags me-2"></i>{{ __('admin.events.tags') }}
                            </h6>
                            <div id="tagsDisplay">
                                @if($event->tags && $event->tags->count() > 0)
                                    @foreach($event->tags as $tag)
                                        <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">{{ __('admin.events.no_tags') }}</span>
                                @endif
                            </div>
                            <div id="tagsInput" class="d-none mt-2">
                                <div class="multiselect-container">
                                    @foreach($tags as $tag)
                                    <label class="multiselect-item">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="multiselect-checkbox" 
                                               {{ $event->tags && $event->tags->contains($tag->id) ? 'checked' : '' }}>
                                        <span class="multiselect-text">{{ $tag->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <small class="text-muted">{{ __('admin.events.tags_help') }}</small>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Rejection Reason (if rejected) -->
                @if($event->status == 'rejected' && $event->rejection_reason)
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ __('admin.events.reject') }}
                        </h6>
                        <p class="mb-0">{{ $event->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Organizer Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user me-2"></i>{{ __('admin.events.organizer') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" 
                         style="width: 60px; height: 60px; font-size: 24px;">
                        {{ strtoupper(substr($event->user->username ?? 'U', 0, 1)) }}
                    </div>
                    <h6 class="mb-0">{{ $event->user->username ?? 'N/A' }}</h6>
                    <small class="text-muted">
                        @if($event->user->role)
                            {{ $event->user->role->name }}
                        @endif
                    </small>
                </div>
                <div class="mb-2">
                    <strong>{{ __('auth.email') }}:</strong><br>
                    <small>{{ $event->user->email ?? 'N/A' }}</small>
                </div>
                <div class="mb-2">
                    <strong>{{ __('admin.users.register_date') }}:</strong><br>
                    <small>{{ $event->user->created_at->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($event->status == 'pending')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gavel me-2"></i>{{ __('admin.events.actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>{{ __('admin.events.approve') }}
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-2"></i>{{ __('admin.events.reject') }}
                    </button>
                </div>
            </div>
        @endif

        <!-- Event Stats -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>{{ __('admin.events.stats') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>{{ __('admin.events.status') }}:</strong><br>
                    <span class="badge {{ $event->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $event->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>{{ __('admin.events.created') }}:</strong><br>
                    <small>{{ $event->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="mb-3">
                    <strong>{{ __('admin.events.last_updated') }}:</strong><br>
                    <small>{{ $event->updated_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($event->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.events.reject', $event) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ __('admin.events.reject_modal_title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('admin.events.reject_reason') }}</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">{{ __('admin.events.reject_reason_label') }} *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" required placeholder="{{ __('admin.events.reject_reason_placeholder') }}"></textarea>
                        <div class="form-text">{{ __('admin.events.reject_reason_help') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.events.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>{{ __('admin.events.reject') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
let isEditMode = false;

// Función para alternar el modo de edición
function toggleEditMode() {
    isEditMode = !isEditMode;

    // Alternar visibilidad de botones
    document.getElementById('editBtn').classList.toggle('d-none');
    document.getElementById('saveBtn').classList.toggle('d-none');
    document.getElementById('cancelBtn').classList.toggle('d-none');

    // Alternar visibilidad de campos de visualización y edición
    const displayFields = ['titleDisplay', 'descriptionDisplay', 'categoryDisplay', 'dateDisplay', 'timeDisplay', 'capacityDisplay', 'cityDisplay', 'locationDisplay', 'tagsDisplay', 'coverImageDisplay'];
    const inputFields = ['titleInput', 'descriptionInput', 'categoryInput', 'dateInput', 'timeInput', 'capacityInput', 'cityInput', 'locationInput', 'tagsInput', 'coverImageInput'];

    displayFields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.toggle('d-none');
        }
    });

    inputFields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.toggle('d-none');
        }
    });
}

// Función para cancelar el modo de edición
function cancelEditMode() {
    isEditMode = false;

    // Mostrar botón de editar, ocultar botones de guardar y cancelar
    document.getElementById('editBtn').classList.remove('d-none');
    document.getElementById('saveBtn').classList.add('d-none');
    document.getElementById('cancelBtn').classList.add('d-none');

    // Mostrar campos de visualización, ocultar campos de edición
    const displayFields = ['titleDisplay', 'descriptionDisplay', 'categoryDisplay', 'dateDisplay', 'timeDisplay', 'capacityDisplay', 'cityDisplay', 'locationDisplay', 'tagsDisplay', 'coverImageDisplay'];
    const inputFields = ['titleInput', 'descriptionInput', 'categoryInput', 'dateInput', 'timeInput', 'capacityInput', 'cityInput', 'locationInput', 'tagsInput', 'coverImageInput'];

    displayFields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.remove('d-none');
        }
    });

    inputFields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.add('d-none');
        }
    });

    // Restablecer el valor del input de archivo
    const fileInput = document.getElementById('coverImageInput').querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.value = '';
    }
}

// Función para guardar los cambios
function saveChanges() {
    // Obtener tags seleccionados de los checkboxes
    const tagCheckboxes = document.querySelectorAll('.multiselect-checkbox:checked');
    const selectedTags = Array.from(tagCheckboxes).map(checkbox => checkbox.value);

    // Crear input oculto para enviar los tags
    const form = document.getElementById('editEventForm');
    let tagsInput = form.querySelector('input[name="tags[]"]');

    // Eliminar inputs de tags anteriores si existen
    const existingTagsInputs = form.querySelectorAll('input[name="tags[]"]');
    existingTagsInputs.forEach(input => input.remove());

    // Añadir nuevos inputs para cada tag seleccionado
    selectedTags.forEach(tagId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tagId;
        form.appendChild(input);
    });

    document.getElementById('editEventForm').submit();
}
</script>
@endsection
