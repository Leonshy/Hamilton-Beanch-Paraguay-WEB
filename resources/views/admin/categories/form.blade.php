@extends('layouts.admin')
@section('title', isset($category) ? 'Editar Categoría' : 'Nueva Categoría')

@section('content')

@php
$svgIcons = [
    ['file' => 'coffee-maker.svg',    'label' => 'Cafetera'],
    ['file' => 'toaster.svg',         'label' => 'Tostadora'],
    ['file' => 'water-heater.svg',    'label' => 'Pava Eléctrica'],
    ['file' => 'blender.svg',         'label' => 'Licuadora'],
    ['file' => 'microwave-oven.svg',  'label' => 'Microondas'],
    ['file' => 'electric-stove.svg',  'label' => 'Cocina Eléctrica'],
    ['file' => 'rice-cooker.svg',     'label' => 'Arrocera'],
    ['file' => 'iron.svg',            'label' => 'Plancha'],
    ['file' => 'fan.svg',             'label' => 'Ventilador'],
    ['file' => 'electrical-plugs.svg','label' => 'Enchufes'],
    ['file' => 'washing-machine.svg', 'label' => 'Lavarropas'],
    ['file' => 'refrigerator.svg',    'label' => 'Refrigerador'],
    ['file' => 'hair-dryer.svg',      'label' => 'Secador'],
    ['file' => 'vacuum-cleaner.svg',  'label' => 'Aspiradora'],
    ['file' => 'robot-vacuum.svg',    'label' => 'Robot Aspirador'],
    ['file' => 'disher.svg',          'label' => 'Escurridor'],
    ['file' => 'television.svg',      'label' => 'Televisión'],
    ['file' => 'radio.svg',           'label' => 'Radio'],
];

$currentIconType = old('icon_type', $category->icon_type ?? 'svg');
$currentIcon     = old('icon',      $category->icon      ?? '');
$currentMediaId  = old('media_id',  $category->media_id  ?? '');
$currentImageUrl = isset($category) && $category->image ? $category->image->url : '';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($category) ? 'Editar: ' . $category->name : 'Nueva Categoría' }}</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Datos principales --}}
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Datos de la categoría</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name"
                               value="{{ old('name', $category->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               value="{{ old('slug', $category->slug ?? '') }}"
                               placeholder="se-genera-automaticamente">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description"
                                  rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Orden</label>
                            <input type="number" class="form-control" name="order"
                                   value="{{ old('order', $category->order ?? $nextOrder ?? 0) }}" min="0">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label">Categoría activa</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Icono / Imagen --}}
            <div class="card hb-admin-card">
                <div class="card-header">
                    <h6 class="mb-0">Icono en la página de inicio</h6>
                    <small class="text-muted">Elegí un icono SVG predefinido o una imagen de la biblioteca de medios.</small>
                </div>
                <div class="card-body">

                    {{-- Toggle tipo --}}
                    <div class="d-flex gap-3 mb-4 flex-wrap">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon_type" id="type_svg"
                                   value="svg" {{ $currentIconType === 'svg' ? 'checked' : '' }}
                                   onchange="toggleIconType('svg')">
                            <label class="form-check-label fw-semibold" for="type_svg">
                                <i class="bi bi-lightning-charge me-1"></i> Ícono del rubro
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon_type" id="type_icon"
                                   value="icon" {{ $currentIconType === 'icon' ? 'checked' : '' }}
                                   onchange="toggleIconType('icon')">
                            <label class="form-check-label fw-semibold" for="type_icon">
                                <i class="bi bi-vector-pen me-1"></i> Path SVG personalizado
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon_type" id="type_image"
                                   value="image" {{ $currentIconType === 'image' ? 'checked' : '' }}
                                   onchange="toggleIconType('image')">
                            <label class="form-check-label fw-semibold" for="type_image">
                                <i class="bi bi-image me-1"></i> Imagen de biblioteca
                            </label>
                        </div>
                    </div>

                    {{-- Panel: SVG del rubro --}}
                    <div id="panel-svg" style="{{ $currentIconType !== 'svg' ? 'display:none' : '' }}">
                        <p class="text-muted small mb-3">Elegí el ícono que mejor representa la categoría.</p>
                        <div class="row g-2" id="svg-icon-grid">
                            @foreach($svgIcons as $ico)
                            @php $isSelected = $currentIconType === 'svg' && $currentIcon === $ico['file']; @endphp
                            <div class="col-4 col-sm-3 col-md-2">
                                <button type="button"
                                        class="btn w-100 p-2 d-flex flex-column align-items-center border svg-icon-option {{ $isSelected ? 'btn-hb-primary' : 'btn-outline-secondary' }}"
                                        onclick="selectSvgIcon(this, '{{ $ico['file'] }}')"
                                        title="{{ $ico['label'] }}"
                                        style="gap:6px; min-height:80px;">
                                    <img src="/images/icons/{{ $ico['file'] }}" width="40" height="40"
                                         style="object-fit:contain; {{ $isSelected ? 'filter:brightness(10)' : 'filter:opacity(.7)' }}"
                                         class="svg-icon-img">
                                    <span style="font-size:.65rem; line-height:1.2;">{{ $ico['label'] }}</span>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        {{-- campo oculto que guarda el filename --}}
                        <input type="hidden" name="icon" id="svg-icon-input" value="{{ $currentIconType === 'svg' ? $currentIcon : '' }}">
                    </div>

                    {{-- Panel: Path SVG personalizado --}}
                    <div id="panel-icon" style="{{ $currentIconType !== 'icon' ? 'display:none' : '' }}">
                        <p class="text-muted small mb-3">Pegá el contenido del atributo <code>d=""</code> de un path SVG (viewBox 0 0 24 24).</p>
                        <textarea id="icon-input" name="icon" class="form-control form-control-sm font-monospace"
                                  rows="2" placeholder='M12 3v1m6.364...'>{{ $currentIconType === 'icon' ? $currentIcon : '' }}</textarea>
                        <div class="mt-3 d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-light border"
                                 style="width:52px;height:52px;">
                                <svg id="icon-preview-svg" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path id="icon-preview-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="{{ $currentIconType === 'icon' ? $currentIcon : '' }}"/>
                                </svg>
                            </div>
                            <span class="text-muted small">Vista previa</span>
                        </div>
                    </div>

                    {{-- Panel: Imagen de biblioteca --}}
                    <div id="panel-image" style="{{ $currentIconType !== 'image' ? 'display:none' : '' }}">
                        <input type="hidden" name="media_id" id="media_id_input" value="{{ $currentMediaId }}">
                        <div class="d-flex align-items-start gap-3">
                            <div id="cat-img-preview-wrap">
                                @if($currentImageUrl)
                                <img id="cat-img-preview" src="{{ $currentImageUrl }}"
                                     class="img-fluid rounded border"
                                     style="width:80px;height:80px;object-fit:cover;">
                                @else
                                <div id="cat-img-preview" class="d-flex align-items-center justify-content-center rounded border bg-light text-muted"
                                     style="width:80px;height:80px;font-size:2rem;">
                                    <i class="bi bi-image"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm hb-media-picker-btn"
                                        data-target="media_id_input" data-preview="cat-img-preview">
                                    <i class="bi bi-folder2-open me-1"></i>
                                    Elegir imagen de la biblioteca
                                </button>
                                <p class="text-muted small mt-2 mb-0">Se mostrará como imagen cuadrada en la tarjeta de categoría.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">SEO</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" class="form-control" name="meta_title"
                               value="{{ old('meta_title', $category->meta_title ?? '') }}" maxlength="255">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Meta Description</label>
                        <textarea class="form-control" name="meta_description"
                                  rows="2">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card hb-admin-card">
                <div class="card-body">
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($category) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.partials.media-picker-modal')

<script>
function toggleIconType(type) {
    document.getElementById('panel-svg').style.display   = type === 'svg'   ? '' : 'none';
    document.getElementById('panel-icon').style.display  = type === 'icon'  ? '' : 'none';
    document.getElementById('panel-image').style.display = type === 'image' ? '' : 'none';

    // Limpiar inputs del panel que no se usa
    if (type !== 'svg')   document.getElementById('svg-icon-input').value = '';
    if (type !== 'icon')  document.getElementById('icon-input').value = '';
    if (type !== 'image') document.getElementById('media_id_input').value = '';
}

function selectSvgIcon(btn, filename) {
    document.querySelectorAll('.svg-icon-option').forEach(b => {
        b.classList.remove('btn-hb-primary');
        b.classList.add('btn-outline-secondary');
        b.querySelector('img').style.filter = 'opacity(.7)';
    });
    btn.classList.add('btn-hb-primary');
    btn.classList.remove('btn-outline-secondary');
    btn.querySelector('img').style.filter = 'brightness(10)';
    document.getElementById('svg-icon-input').value = filename;
}

document.getElementById('icon-input')?.addEventListener('input', function () {
    document.getElementById('icon-preview-path').setAttribute('d', this.value);
});
</script>
@endsection
