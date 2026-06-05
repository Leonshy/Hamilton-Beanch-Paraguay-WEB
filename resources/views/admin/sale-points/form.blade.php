@extends('layouts.admin')
@section('title', isset($salePoint) ? 'Editar Punto de Venta' : 'Nuevo Punto de Venta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($salePoint) ? 'Editar: ' . $salePoint->name : 'Nuevo Punto de Venta' }}</h2>
    <a href="{{ route('admin.sale-points.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($salePoint) ? route('admin.sale-points.update', $salePoint) : route('admin.sale-points.store') }}"
      method="POST">
    @csrf
    @if(isset($salePoint)) @method('PUT') @endif

    <div class="row g-4">
        {{-- Logo --}}
        <div class="col-lg-8">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Logo del punto de venta</h6></div>
                <div class="card-body">
                    <div class="mb-3 rounded d-flex align-items-center justify-content-center" style="height:160px;background:#f5f5f5;">
                        @if(isset($salePoint) && $salePoint->logo)
                            <img src="{{ $salePoint->logo->url }}" alt=""
                                 id="previewLogo"
                                 style="max-height:140px;max-width:90%;object-fit:contain;">
                        @else
                            <div id="previewLogo" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;">
                                <i class="bi bi-shop fs-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="media_id" id="media_id"
                           value="{{ old('media_id', $salePoint->media_id ?? '') }}">
                    <button type="button" class="btn btn-outline-secondary w-100 hb-media-picker-btn"
                            data-target="media_id" data-preview="previewLogo"
                            data-preview-fit="contain" data-preview-height="160px" data-preview-bg="#f5f5f5">
                        <i class="bi bi-folder2-open me-2"></i>Seleccionar logo de la biblioteca
                    </button>
                    <div class="form-text mt-2">
                        Formato recomendado: <strong>PNG o WebP con fondo transparente</strong> — mínimo 200 × 100 px.
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
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" required
                               value="{{ old('name', $salePoint->name ?? '') }}"
                               placeholder="Ej: Stock Center">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL del sitio web</label>
                        <input type="url" class="form-control @error('url') is-invalid @enderror"
                               name="url"
                               value="{{ old('url', $salePoint->url ?? '') }}"
                               placeholder="https://...">
                        <div class="form-text">Se abre en una nueva pestaña al hacer clic.</div>
                        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" class="form-control" name="order"
                               value="{{ old('order', $salePoint->order ?? $nextOrder ?? 0) }}" min="0">
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $salePoint->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Activo (visible en el sitio)</label>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($salePoint) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.partials.media-picker-modal')
@endsection
