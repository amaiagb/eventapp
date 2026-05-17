@extends('layouts.admin')

@section('content')
<!-- Admin Header -->
<div class="admin-header">
    <h1 class="page-title">{{ __('admin.users.title') }}</h1>
    <p class="page-subtitle">{{ __('admin.users.subtitle') }}</p>
</div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('admin.users.title') }}</h1>
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
                    <form action="{{ route('admin.users') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="role" class="form-label">{{ __('admin.users.filter_role') }}</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">{{ __('admin.users.filter_all_roles') }}</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('admin.users.filter_admins') }}</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>{{ __('admin.users.filter_users') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">{{ __('admin.users.filter_status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">{{ __('admin.users.filter_all_status') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.users.filter_active') }}</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.users.filter_inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">{{ __('admin.users.filter_search') }}</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="{{ __('admin.users.search_placeholder') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> {{ __('admin.users.filter_button') }}
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> {{ __('admin.users.clear_filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Usuarios -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-users me-2"></i>{{ __('admin.users.list') }} ({{ $users->total() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.users.table_username') }}</th>
                                        <th>{{ __('auth.name') }}</th>
                                        <th>{{ __('admin.users.table_email') }}</th>
                                        <th>{{ __('admin.users.table_role') }}</th>
                                        <th>{{ __('admin.users.table_status') }}</th>
                                        <th>{{ __('admin.users.table_created') }}</th>
                                        <th>{{ __('admin.users.table_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 30px; height: 30px; font-size: 12px;">
                                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                                    </div>
                                                    <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                                        <strong>{{ $user->username }}</strong>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ $user->name }} {{ $user->surname ?? '' }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge {{ $user->role && $user->role->name === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                                    {{ $user->role->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $user->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-secondary"
                                                                title="{{ $user->is_active ? __('admin.users.deactivate_user') : __('admin.users.activate_user') }}">
                                                            <i class="fas {{ $user->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.users.show', ['user' => $user, 'edit' => 'true']) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="{{ __('admin.common.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteUserModal"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-username="{{ $user->username }}"
                                                            data-delete-url="{{ route('admin.users.delete', $user) }}"
                                                            @if($user->role && $user->role->name === 'admin') disabled @endif 
                                                            title="@if($user->role && $user->role->name === 'admin') {{ __('admin.users.cannot_delete_admin') }} @else {{ __('admin.users.delete_user') }} @endif">
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
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin.users.no_found') }}</h5>
                            <p class="text-muted">{{ __('admin.users.no_found_help') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">{{ __('admin.users.delete_confirm_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.users.delete_confirm_message') }} <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="eventsCountMessage">{{ __('admin.users.loading_events') }}</span>
                </p>
                <form id="deleteUserForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.common.cancel') }}</button>
                <button type="submit" form="deleteUserForm" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>{{ __('admin.users.delete_confirm_button') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteUserModal = document.getElementById('deleteUserModal');
    const deleteUserNameSpan = document.getElementById('deleteUserName');
    const eventsCountMessage = document.getElementById('eventsCountMessage');
    const deleteUserForm = document.getElementById('deleteUserForm');

    // Textos de traducción
    const texts = {
        eventsWillBeDeleted: "{{ __('admin.users.events_will_be_deleted', ['count' => 'X']) }}",
        noEventsToDelete: "{{ __('admin.users.no_events_to_delete') }}",
        errorLoadingEvents: "{{ __('admin.users.error_loading_events') }}"
    };

    // Evento show.bs.modal de Bootstrap
    deleteUserModal.addEventListener('show.bs.modal', async function(event) {
        const button = event.relatedTarget;
        
        if (!button) return;
        
        const userId = button.dataset.userId;
        const username = button.dataset.userUsername;
        const deleteUrl = button.dataset.deleteUrl;

        // Establecer el nombre del usuario
        deleteUserNameSpan.textContent = username;
        
        // Establecer la acción del formulario
        deleteUserForm.action = deleteUrl;

        // Mostrar mensaje de carga
        eventsCountMessage.textContent = "{{ __('admin.users.loading_events') }}";

        // Obtener el conteo de eventos del usuario
        try {
            const response = await fetch(`{{ route('admin.users.events-count', ':userId') }}`.replace(':userId', userId));
            const data = await response.json();
            
            // Mostrar el mensaje con el conteo de eventos
            if (data.events_count > 0) {
                eventsCountMessage.textContent = texts.eventsWillBeDeleted.replace('X', data.events_count);
            } else {
                eventsCountMessage.textContent = texts.noEventsToDelete;
            }
        } catch (error) {
            eventsCountMessage.textContent = texts.errorLoadingEvents;
        }
    });

    // Limpiar al cerrar el modal
    deleteUserModal.addEventListener('hidden.bs.modal', function() {
        deleteUserNameSpan.textContent = '';
        eventsCountMessage.textContent = '';
        deleteUserForm.action = '';
    });
});
</script>
@endsection
