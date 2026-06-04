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
        {{-- Imagen --}}
        <div class="col-lg-8">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Imagen del banner</h6></div>
                <div class="card-body">
                    @if(isset($banner) && $banner->image)
                        <img src="{{ $banner->image->url }}" alt=""
                             id="previewBannerImage"
                             class="rounded mb-3 w-100" style="max-height:320px;object-fit:cover">
                    @else
                        <div id="previewBannerImage" class="hb-admin-no-img-lg mb-3" style="height:200px">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    @endif
                    <input type="hidden" name="media_id" id="media_id"
                           value="{{ old('media_id', $banner->media_id ?? '') }}">
                    <button type="button" class="btn btn-outline-secondary w-100 hb-media-picker-btn"
                            data-target="media_id" data-preview="previewBannerImage">
                        <i class="bi bi-folder2-open me-2"></i>Seleccionar imagen de la biblioteca
                    </button>
                    <div class="form-text mt-2">
                        Tamaño recomendado: <strong>1280 × 720 px</strong> (ratio 16:9) — formato WebP o JPG.
                    </div>
                </div>
            </div>
        </div>

        {{-- Configuración --}}
        <div class="col-lg-4">
            <div class="card hb-admin-card">
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
                               value="{{ old('order', $banner->order ?? $nextOrder ?? 0) }}" min="0">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Activo (visible en el sitio)</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enlace al hacer clic</label>
                        <input type="url" class="form-control" name="link_url"
                               placeholder="https://..."
                               value="{{ old('link_url', $banner->link_url ?? '') }}">
                        <div class="form-text">Opcional — si se completa, el banner redirige a esa URL al hacer clic.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Intervalo del carrusel (segundos)</label>
                        @php $heroInterval = \App\Models\SiteSetting::get('hero_slide_interval', '6'); @endphp
                        <input type="number" class="form-control" name="hero_slide_interval"
                               value="{{ old('hero_slide_interval', $heroInterval) }}" min="2" max="30">
                        <div class="form-text">Segundos entre slides (aplica a todos los banners).</div>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($banner) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.partials.media-picker-modal')
@endsection
