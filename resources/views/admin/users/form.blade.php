@extends('layouts.admin')
@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($user) ? 'Editar: ' . $user->name : 'Nuevo Usuario' }}</h2>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}"
      method="POST">
    @csrf
    @if(isset($user)) @method('PUT') @endif

    <div class="row g-4 justify-content-center">
        <div class="col-lg-6">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Datos del usuario</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name"
                               value="{{ old('name', $user->name ?? '') }}" required minlength="2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email"
                               value="{{ old('email', $user->email ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña{{ isset($user) ? ' (dejar vacío para no cambiar)' : '' }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="password"
                               {{ isset($user) ? '' : 'required' }} minlength="10"
                               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{10,}"
                               autocomplete="new-password">
                        <small class="form-text text-muted">Mínimo 10 caracteres, con mayúsculas, minúsculas, números y un símbolo.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" name="password_confirmation" data-match="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" required>
                            <option value="editor" {{ old('role', isset($user) ? $user->roles->first()?->name : '') === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="admin" {{ old('role', isset($user) ? $user->roles->first()?->name : '') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Usuario activo</label>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($user) ? 'Actualizar' : 'Crear usuario' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
