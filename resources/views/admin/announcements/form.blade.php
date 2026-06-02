@extends('layouts.admin')
@section('title', isset($announcement) ? 'Editar Anuncio' : 'Nuevo Anuncio')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($announcement) ? 'Editar anuncio' : 'Nuevo anuncio' }}</h2>
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card hb-admin-card" style="max-width:640px;">
    <div class="card-body">
        <form action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
              method="POST">
            @csrf
            @if(isset($announcement)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Texto del anuncio <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('text') is-invalid @enderror"
                       name="text"
                       value="{{ old('text', $announcement->text ?? '') }}"
                       placeholder="Ej: DISTRIBUIDOR OFICIAL EN PARAGUAY"
                       required>
                @error('text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">El texto se mostrará en mayúsculas en la barra del sitio.</div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Orden</label>
                    <input type="number" class="form-control" name="order"
                           value="{{ old('order', $announcement->order ?? $nextOrder ?? 0) }}" min="0">
                </div>
                <div class="col-sm-6 d-flex align-items-end pb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Activo (visible en el sitio)</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-hb-primary">
                <i class="bi bi-save me-2"></i>{{ isset($announcement) ? 'Actualizar' : 'Guardar' }}
            </button>
        </form>
    </div>
</div>
@endsection
