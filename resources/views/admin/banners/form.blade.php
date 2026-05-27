@extends('layouts.admin')
@section('title', isset($banner) ? 'Editar Banner' : 'Nuevo Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($banner) ? 'Editar Banner' : 'Nuevo Banner' }}</h2>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
      method="POST">
    @csrf
    @if(isset($banner)) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Contenido del banner</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="title"
                               value="{{ old('title', $banner->title ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtítulo</label>
                        <input type="text" class="form-control" name="subtitle"
                               value="{{ old('subtitle', $banner->subtitle ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="description"
                                  rows="3">{{ old('description', $banner->description ?? '') }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Texto del botón CTA</label>
                            <input type="text" class="form-control" name="cta_text"
                                   value="{{ old('cta_text', $banner->cta_text ?? '') }}"
                                   placeholder="Ver catálogo">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">URL del botón CTA</label>
                            <input type="text" class="form-control" name="cta_url"
                                   value="{{ old('cta_url', $banner->cta_url ?? '') }}"
                                   placeholder="/productos">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Configuración</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Posición <span class="text-danger">*</span></label>
                        <select class="form-select" name="position" required>
                            <option value="home" {{ old('position', $banner->position ?? 'home') === 'home' ? 'selected' : '' }}>Inicio (hero)</option>
                            <option value="productos" {{ old('position', $banner->position ?? '') === 'productos' ? 'selected' : '' }}>Catálogo de productos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" class="form-control" name="order"
                               value="{{ old('order', $banner->order ?? 0) }}" min="0">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Activo (visible en el sitio)</label>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($banner) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>

            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Imagen del banner</h6></div>
                <div class="card-body">
                    @if(isset($banner) && $banner->image)
                        <img src="{{ $banner->image->url }}" alt=""
                             id="previewBannerImage"
                             class="rounded mb-2 w-100" style="height:120px;object-fit:cover">
                    @else
                        <div id="previewBannerImage" class="hb-admin-no-img-lg mb-2">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                    <input type="hidden" name="media_id" id="media_id"
                           value="{{ old('media_id', $banner->media_id ?? '') }}">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 hb-media-picker-btn"
                            data-target="media_id" data-preview="previewBannerImage">
                        <i class="bi bi-folder2-open me-1"></i>Seleccionar imagen
                    </button>
                    <div class="form-text mt-1">Recomendado: 1920×600 px, formato WebP o JPG.</div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.partials.media-picker-modal')
@endsection
