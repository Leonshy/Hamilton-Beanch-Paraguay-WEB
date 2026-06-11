@extends('layouts.admin')
@section('title', isset($product) ? 'Editar Producto' : 'Nuevo Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($product) ? 'Editar: ' . $product->title : 'Nuevo Producto' }}</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="row g-4">
        {{-- Columna principal --}}
        <div class="col-lg-8">
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Información principal</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title"
                               value="{{ old('title', $product->title ?? '') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Descripción corta</label>
                        <textarea class="form-control" id="subtitle" name="subtitle"
                                  rows="3">{{ old('subtitle', $product->subtitle ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU <span class="text-muted small">(código de producto)</span></label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku"
                               value="{{ old('sku', $product->sku ?? '') }}"
                               placeholder="Ej: HB-54321">
                        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Opcional. Debe ser único entre todos los productos.</div>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               value="{{ old('slug', $product->slug ?? '') }}"
                               placeholder="se-genera-automaticamente">
                    </div>
                    <div class="mb-0">
                        <label for="content" class="form-label">Descripción completa</label>
                        <textarea class="form-control hb-wysiwyg" id="content" name="content"
                                  rows="12">{{ old('content', $product->content ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Especificaciones técnicas --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-table me-2"></i>Especificaciones técnicas</h6></div>
                <div class="card-body">
                    <textarea class="form-control hb-wysiwyg" id="specifications" name="specifications"
                              rows="8">{{ old('specifications', $product->specifications ?? '') }}</textarea>
                </div>
            </div>

            {{-- Puntos de venta --}}
            @php
                $selectedSalePoints = isset($product) ? $product->salePoints->keyBy('id') : collect();
                $isNew = !isset($product);
            @endphp
            <div class="card hb-admin-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-shop me-2"></i>Puntos de venta</h6>
                </div>
                <div class="card-body">
                    @forelse($salePoints as $sp)
                    @php
                        $isSelected = $isNew || $selectedSalePoints->has($sp->id);
                        $customUrl  = $selectedSalePoints->get($sp->id)?->pivot->custom_url ?? '';
                    @endphp
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom">

                        {{-- Toggle --}}
                        <div class="form-check form-switch mb-0" style="min-width:3rem;">
                            <input class="form-check-input sp-check" type="checkbox"
                                   role="switch"
                                   name="sale_point_ids[]"
                                   value="{{ $sp->id }}"
                                   id="sp_{{ $sp->id }}"
                                   data-target="sp_url_{{ $sp->id }}"
                                   {{ $isSelected ? 'checked' : '' }}
                                   style="cursor:pointer;">
                        </div>

                        {{-- Logo + Nombre --}}
                        <label for="sp_{{ $sp->id }}" class="d-flex align-items-center gap-2 mb-0 flex-shrink-0" style="cursor:pointer;min-width:160px;">
                            @if($sp->logo)
                                <img src="{{ $sp->logo->url }}" alt="{{ $sp->name }}"
                                     style="height:22px;max-width:70px;object-fit:contain;">
                            @else
                                <span class="badge bg-secondary">{{ strtoupper(substr($sp->name,0,1)) }}</span>
                            @endif
                            <span class="small fw-semibold">{{ $sp->name }}</span>
                        </label>

                        {{-- URL personalizada — siempre ocupa espacio, solo visible cuando activo --}}
                        <div class="flex-grow-1" id="sp_url_{{ $sp->id }}"
                             style="visibility:{{ $isSelected ? 'visible' : 'hidden' }};">
                            <input type="url" class="form-control form-control-sm"
                                   name="sale_point_url[{{ $sp->id }}]"
                                   placeholder="URL personalizada (opcional)"
                                   value="{{ old('sale_point_url.'.$sp->id, $customUrl) }}">
                        </div>

                    </div>
                    @empty
                    <p class="text-muted small mb-0">No hay puntos de venta activos.</p>
                    @endforelse
                </div>
            </div>

            {{-- Puntos de venta personalizados --}}
            @php $existingRetailers = old('retailers', isset($product) ? ($product->retailers ?? []) : []); @endphp
            <div class="card hb-admin-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-pin-map me-2"></i>Puntos de venta personalizados</h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addRetailerBtn">
                        <i class="bi bi-plus-lg me-1"></i>Agregar
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-text mb-3">Puntos de venta exclusivos de este producto (no necesitan estar en el listado global).</div>
                    <div id="retailersList">
                        @foreach($existingRetailers as $i => $retailer)
                        <div class="d-flex gap-2 mb-2 retailer-row">
                            <input type="text" class="form-control form-control-sm"
                                   name="retailers[name][]"
                                   placeholder="Nombre del punto de venta"
                                   value="{{ $retailer['name'] ?? '' }}" required>
                            <input type="url" class="form-control form-control-sm"
                                   name="retailers[url][]"
                                   placeholder="URL (https://...)"
                                   value="{{ $retailer['url'] ?? '' }}">
                            <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0 remove-retailer">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @if(empty($existingRetailers))
                    <p class="text-muted small mb-0" id="retailersEmpty">Ninguno agregado aún.</p>
                    @endif
                </div>
            </div>

            {{-- SEO --}}
            @php $model = $product ?? new \App\Models\Product; @endphp
            @include('admin.partials.seo-fields')
        </div>

        {{-- Columna lateral --}}
        <div class="col-lg-4">
            {{-- Publicar --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Publicar</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="status">
                            <option value="published" {{ old('status', $product->status ?? 'published') === 'published' ? 'selected' : '' }}>Publicado</option>
                            <option value="draft" {{ old('status', $product->status ?? '') === 'draft' ? 'selected' : '' }}>Borrador</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio (Gs.)</label>
                        <input type="number" class="form-control" name="price"
                               value="{{ old('price', $product->price ?? '') }}"
                               min="0" placeholder="850000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" class="form-control" name="order"
                               value="{{ old('order', $product->order ?? $nextOrder ?? 0) }}" min="0">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">Destacado (aparece en Inicio)</label>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($product) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>

            {{-- Categoría --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Categoría</h6></div>
                <div class="card-body">
                    <select class="form-select" name="category_id">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Imagen destacada --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Imagen destacada</h6></div>
                <div class="card-body">
                    @if(isset($product) && $product->featuredImage)
                        <img src="{{ $product->featuredImage->url }}" alt=""
                             id="previewFeaturedImage"
                             class="rounded mb-2 w-100" style="height:120px;object-fit:cover">
                    @else
                        <div id="previewFeaturedImage" class="hb-admin-no-img-lg mb-2">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                    <input type="hidden" name="media_id" id="media_id"
                           value="{{ old('media_id', $product->media_id ?? '') }}">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 hb-media-picker-btn"
                            data-target="media_id" data-preview="previewFeaturedImage"
                            data-preview-fit="cover" data-preview-height="220px">
                        <i class="bi bi-folder2-open me-1"></i>Seleccionar imagen
                    </button>
                    <div class="form-text mt-2">Tamaño recomendado: <strong>800 × 800 px</strong> (cuadrada) — formato WebP o JPG.</div>
                </div>
            </div>

            {{-- Galería --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-images me-2"></i>Galería de imágenes</h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="galleryPickerBtn">
                        <i class="bi bi-plus-lg me-1"></i>Seleccionar imágenes
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-1 mb-2" id="galleryPreview">
                        @if(isset($product) && $product->gallery->count())
                            @foreach($product->gallery as $img)
                            <div class="col-4 gallery-thumb-wrap" data-id="{{ $img->id }}" data-url="{{ $img->url }}">
                                <div class="position-relative">
                                    <img src="{{ $img->url }}" alt="" class="w-100 rounded"
                                         style="height:70px;object-fit:cover">
                                    <span class="gallery-order-badge">{{ $loop->index + 1 }}</span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div id="galleryInputs">
                        @if(isset($product) && $product->gallery->count())
                            @foreach($product->gallery as $img)
                            <input type="hidden" name="gallery_ids[]" value="{{ $img->id }}">
                            @endforeach
                        @endif
                    </div>
                    @if(!isset($product) || !$product->gallery->count())
                    <p class="text-muted small mb-0" id="galleryEmpty">Ninguna imagen seleccionada aún. Hacé clic en "Seleccionar imágenes".</p>
                    @endif
                    <div class="form-text mt-2">Tamaño recomendado: <strong>800 × 800 px</strong> (cuadrada) — formato WebP o JPG.</div>
                </div>
            </div>

            {{-- Manual PDF --}}
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Manual PDF</h6></div>
                <div class="card-body">
                    <input type="hidden" name="attachment" id="attachmentUrl"
                           value="{{ old('attachment', $product->attachment ?? '') }}">
                    <div id="attachmentPreview" class="mb-2">
                        @if(isset($product) && $product->attachment)
                        <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                            <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                            <a href="{{ $product->attachment }}" target="_blank"
                               class="small text-truncate flex-grow-1">{{ basename($product->attachment) }}</a>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0" id="attachmentClear">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 hb-media-picker-btn"
                            data-target="attachmentUrl"
                            data-url-preview="attachmentPreview"
                            data-store-url="true"
                            data-type="document">
                        <i class="bi bi-folder2-open me-1"></i>Seleccionar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.partials.media-picker-modal')

@push('scripts')
<script>
document.querySelectorAll('.sp-check').forEach(function (cb) {
    cb.addEventListener('change', function () {
        var target = document.getElementById(this.dataset.target);
        if (target) target.style.visibility = this.checked ? 'visible' : 'hidden';
    });
});

// Puntos de venta personalizados
function retailerRow() {
    var div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2 retailer-row';
    div.innerHTML =
        '<input type="text" class="form-control form-control-sm" name="retailers[name][]" placeholder="Nombre del punto de venta" required>' +
        '<input type="url" class="form-control form-control-sm" name="retailers[url][]" placeholder="URL (https://...)">' +
        '<button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0 remove-retailer"><i class="bi bi-x-lg"></i></button>';
    return div;
}

document.getElementById('addRetailerBtn').addEventListener('click', function () {
    var empty = document.getElementById('retailersEmpty');
    if (empty) empty.remove();
    document.getElementById('retailersList').appendChild(retailerRow());
});

document.getElementById('retailersList').addEventListener('click', function (e) {
    var btn = e.target.closest('.remove-retailer');
    if (!btn) return;
    btn.closest('.retailer-row').remove();
    if (!document.querySelector('.retailer-row')) {
        var p = document.createElement('p');
        p.className = 'text-muted small mb-0';
        p.id = 'retailersEmpty';
        p.textContent = 'Ninguno agregado aún.';
        document.getElementById('retailersList').insertAdjacentElement('afterend', p);
    }
});
</script>
@endpush
@endsection
