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
                        <label for="subtitle" class="form-label">Subtítulo / Modelo</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle"
                               value="{{ old('subtitle', $product->subtitle ?? '') }}">
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
                    <div class="form-text mb-3">Ingresá las especificaciones en formato JSON: <code>[{"label":"Capacidad","value":"1.7 L"},{"label":"Potencia","value":"1500W"}]</code></div>
                    <textarea class="form-control font-monospace" id="specifications_json" name="specifications_json"
                              rows="6" placeholder='[{"label":"Capacidad","value":"1.7 L"}]'>{{ old('specifications_json', isset($product) && $product->specifications ? json_encode($product->specifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
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
                               value="{{ old('order', $product->order ?? 0) }}" min="0">
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
                            data-target="media_id" data-preview="previewFeaturedImage">
                        <i class="bi bi-folder2-open me-1"></i>Seleccionar imagen
                    </button>
                </div>
            </div>

            {{-- Galería --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Galería de imágenes</h6></div>
                <div class="card-body">
                    @if(isset($product) && $product->gallery->count())
                        <div class="row g-1 mb-2">
                            @foreach($product->gallery as $img)
                            <div class="col-4">
                                <img src="{{ $img->url }}" alt="" class="w-100 rounded"
                                     style="height:60px;object-fit:cover">
                            </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-text mb-2">IDs de imágenes separados por coma</div>
                    <input type="text" class="form-control form-control-sm" name="gallery_ids[]"
                           placeholder="Ej: 2,5,8"
                           value="{{ isset($product) ? $product->gallery->pluck('id')->implode(',') : '' }}">
                    <div class="form-text">Próximamente: selector visual de galería.</div>
                </div>
            </div>

            {{-- Manual PDF --}}
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Manual PDF (URL)</h6></div>
                <div class="card-body">
                    <input type="text" class="form-control" name="attachment"
                           value="{{ old('attachment', $product->attachment ?? '') }}"
                           placeholder="URL del manual en PDF">
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.partials.media-picker-modal')
@endsection
