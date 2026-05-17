@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.users.view_details') }}</h1>
    <p class="page-subtitle">{{ __('admin.users.view_details') }}: {{ $user->username }}</p>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $user->username }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('admin.common.back') }}
            </a>
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
</div>

<!-- Alertas -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Detalles del Usuario -->
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user me-2"></i>{{ __('admin.users.table_username') }}
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 user-avatar-large">
                    {{ strtoupper(substr($user->username, 0, 1)) }}
                </div>
                <h4>{{ $user->username }}</h4>
                <p class="text-muted">{{ $user->name }} {{ $user->surname ?? '' }}</p>
                <div class="mt-3">
                    <span class="badge {{ $user->role && $user->role->name === 'admin' ? 'bg-danger' : 'bg-primary' }} me-2">
                        {{ $user->role?->name ?? 'N/A' }}
                    </span>
                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $user->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                    </span>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <h5 class="mb-0">{{ $user->followers->count() ?? 0 }}</h5>
                        <small class="text-muted">{{ __('user.followers') }}</small>
                    </div>
                    <div class="col-6 mb-2">
                        <h5 class="mb-0">{{ $user->following->count() ?? 0 }}</h5>
                        <small class="text-muted">{{ __('user.following') }}</small>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-0">{{ $user->events->count() }}</h5>
                        <small class="text-muted">{{ __('user.created_events') }}</small>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-0">{{ $user->eventAttendees->count() }}</h5>
                        <small class="text-muted">{{ __('user.attended_events') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-info-circle me-2"></i>{{ __('admin.common.view_details') }}
                </h6>
            </div>
            <div class="card-body">
                <form id="editUserForm" action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ __('auth.username') }}</th>
                            <td>
                                <span id="usernameDisplay">{{ $user->username }}</span>
                                <input type="text" id="usernameInput" class="form-control d-none" name="username" value="{{ $user->username }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('auth.name') }}</th>
                            <td>
                                <span id="nameDisplay">{{ $user->name }}</span>
                                <input type="text" id="nameInput" class="form-control d-none" name="name" value="{{ $user->name }}" required maxlength="255">
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('auth.surname') }}</th>
                            <td>
                                <span id="surnameDisplay">{{ $user->surname ?? '-' }}</span>
                                <input type="text" id="surnameInput" class="form-control d-none" name="surname" value="{{ $user->surname ?? '' }}" maxlength="255">
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('auth.email') }}</th>
                            <td>
                                <span id="emailDisplay">{{ $user->email }}</span>
                                <input type="email" id="emailInput" class="form-control d-none" name="email" value="{{ $user->email }}" required>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('auth.city') }}</th>
                            <td>
                                <span id="cityDisplay">{{ $user->city?->name ?? 'N/A' }}</span>
                                <div id="cityInput" class="d-none">
                                    <x-city-autocomplete
                                        :cities="$cities"
                                        id="edit_city_input"
                                        name="city_name"
                                        :value="$user->city?->name ?? null"
                                        :city_id="$user->city_id ?? null"
                                        :required="false"
                                        label=""
                                        :placeholder="__('auth.select_city')"
                                    />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('admin.users.table_role') }}</th>
                            <td>
                                <span id="roleDisplay">{{ $user->role?->name ?? 'N/A' }}</span>
                                <select id="roleInput" class="form-select d-none" name="role_id" required>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('admin.users.table_status') }}</th>
                            <td>
                                <span id="statusDisplay">
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                    </span>
                                </span>
                                <div id="statusInput" class="d-none">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" id="is_active_true" value="1" {{ $user->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active_true">
                                            {{ __('admin.common.active') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" id="is_active_false" value="0" {{ !$user->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active_false">
                                            {{ __('admin.common.inactive') }}
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('auth.bio') }}</th>
                            <td>
                                <span id="bioDisplay">{{ $user->bio ?? '-' }}</span>
                                <textarea id="bioInput" class="form-control d-none" name="bio" rows="3" maxlength="500">{{ $user->bio ?? '' }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('admin.users.table_created') }}</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Eventos del Usuario -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success text-capitalize">
            <i class="fas fa-calendar-alt me-2"></i>{{ __('user.events') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3">{{ __('user.created_events') }}</h6>
                @if($user->events && $user->events->count() > 0)
                    <div class="list-group">
                        @foreach($user->events as $event)
                            <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <small class="text-muted">{{ $event->event_date->format('d/m/Y') }}</small>
                                </div>
                                <small class="text-muted">{{ $event->city?->name ?? 'N/A' }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">{{ __('user.no_events_created') }}</p>
                @endif
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">{{ __('user.attended_events') }}</h6>
                @if($user->attendedEvents && $user->attendedEvents->count() > 0)
                    <div class="list-group">
                        @foreach($user->attendedEvents as $event)
                            <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <small class="text-muted">{{ $event->event_date->format('d/m/Y') }}</small>
                                </div>
                                <small class="text-muted">{{ $event->city?->name ?? 'N/A' }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">{{ __('user.no_events_attended') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
let isEditMode = false;

// Activar modo de edición si se pasa el parámetro edit=true
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('edit') === 'true') {
        toggleEditMode();
    }
});

// Función para alternar el modo de edición
function toggleEditMode() {
    isEditMode = !isEditMode;

    // Alternar visibilidad de botones
    document.getElementById('editBtn').classList.toggle('d-none');
    document.getElementById('saveBtn').classList.toggle('d-none');
    document.getElementById('cancelBtn').classList.toggle('d-none');

    // Alternar visibilidad de campos de visualización y edición
    const displayFields = ['usernameDisplay', 'nameDisplay', 'surnameDisplay', 'emailDisplay', 'cityDisplay', 'roleDisplay', 'statusDisplay', 'bioDisplay'];
    const inputFields = ['usernameInput', 'nameInput', 'surnameInput', 'emailInput', 'cityInput', 'roleInput', 'statusInput', 'bioInput'];

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
    const displayFields = ['usernameDisplay', 'nameDisplay', 'surnameDisplay', 'emailDisplay', 'cityDisplay', 'roleDisplay', 'statusDisplay', 'bioDisplay'];
    const inputFields = ['usernameInput', 'nameInput', 'surnameInput', 'emailInput', 'cityInput', 'roleInput', 'statusInput', 'bioInput'];

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
}

// Función para guardar los cambios
function saveChanges() {
    document.getElementById('editUserForm').submit();
}
</script>
@endsection
