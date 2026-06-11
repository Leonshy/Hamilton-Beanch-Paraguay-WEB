@extends('layouts.admin')
@section('title', 'Centro de Ayuda')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Centro de Ayuda</h2>
        <p class="text-muted small mb-0">Gestioná el ícono, título y texto de previsualización de cada sección.</p>
    </div>
    <a href="{{ route('frontend.help') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-box-arrow-up-right me-1"></i>Ver en el sitio
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@php
$predefinedIcons = [
    ['label' => 'Pregunta',       'path' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['label' => 'Serv. Técnico', 'path' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z||M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ['label' => 'Manual/Libro',  'path' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
    ['label' => 'Garantía',      'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
    ['label' => 'Cafetera',      'path' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
    ['label' => 'Pava Eléctrica','path' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
    ['label' => 'Electricidad',  'path' => 'M13 10V3L4 14h7v7l9-11h-7z'],
    ['label' => 'Documento',     'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
];

$sectionOrder = ['preguntas-frecuentes', 'servicio-tecnico', 'manuales', 'garantia'];
@endphp

<div class="row g-4">
    @foreach($sectionOrder as $section)
    @php $item = $items->get($section); @endphp
    @if($item) {{-- siempre existe gracias al controlador --}}
    <div class="col-12">
        <div class="card hb-admin-card">
            <div class="card-header d-flex align-items-center gap-3">
                {{-- Preview del ícono actual --}}
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;" id="preview-circle-{{ $item->id }}">
                    <svg width="22" height="22" fill="none" stroke="#666" stroke-width="1.5" viewBox="0 0 24 24" id="preview-svg-{{ $item->id }}">
                        @foreach(explode('||', $item->icon ?? '') as $path)
                            @if($path)<path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>@endif
                        @endforeach
                    </svg>
                </div>
                <h6 class="mb-0">{{ $item->title }}</h6>
                <span class="badge bg-secondary ms-auto">{{ $section }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.help-center.update', $item) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Título</label>
                            <input type="text" class="form-control" name="title"
                                   value="{{ old('title', $item->title) }}" required minlength="2">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Texto de previsualización</label>
                            <input type="text" class="form-control" name="subtitle"
                                   value="{{ old('subtitle', $item->subtitle) }}"
                                   placeholder="Texto corto que aparece en la tarjeta del Centro de Ayuda">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ícono</label>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach($predefinedIcons as $ico)
                                <button type="button"
                                        class="btn btn-outline-secondary btn-sm icon-option p-2 {{ $item->icon === $ico['path'] ? 'btn-hb-primary' : '' }}"
                                        data-path="{{ $ico['path'] }}"
                                        data-target="icon_{{ $item->id }}"
                                        data-preview="preview-svg-{{ $item->id }}"
                                        title="{{ $ico['label'] }}"
                                        onclick="selectHelpIcon(this)">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        @foreach(explode('||', $ico['path']) as $p)
                                            @if($p)<path stroke-linecap="round" stroke-linejoin="round" d="{{ $p }}"/>@endif
                                        @endforeach
                                    </svg>
                                </button>
                                @endforeach
                            </div>
                            <div class="row g-2 align-items-start">
                                <div class="col-md-8">
                                    <label class="form-label small text-muted">O pegá el path SVG personalizado (usá <code>||</code> para separar múltiples paths)</label>
                                    <textarea class="form-control form-control-sm font-monospace"
                                              name="icon"
                                              id="icon_{{ $item->id }}"
                                              rows="2"
                                              oninput="updatePreview('{{ $item->id }}', this.value)">{{ old('icon', $item->icon) }}</textarea>
                                </div>
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center" style="width:64px;height:64px;">
                                        <svg width="32" height="32" fill="none" stroke="#b91c1c" stroke-width="1.5" viewBox="0 0 24 24" id="preview-svg-lg-{{ $item->id }}">
                                            @foreach(explode('||', $item->icon ?? '') as $path)
                                                @if($path)<path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>@endif
                                            @endforeach
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-hb-primary">
                            <i class="bi bi-save me-2"></i>Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>

@push('scripts')
<script>
function selectHelpIcon(btn) {
    const path    = btn.dataset.path;
    const targetId = btn.dataset.target;
    const previewId = btn.dataset.preview;

    // Actualizar textarea
    document.getElementById(targetId).value = path;

    // Actualizar preview pequeño (header)
    updateSvgPaths(document.getElementById(previewId), path);

    // Actualizar preview grande
    const lgId = previewId.replace('preview-svg-', 'preview-svg-lg-');
    updateSvgPaths(document.getElementById(lgId), path);

    // Toggle clase activa en botones del mismo grupo
    btn.closest('.d-flex').querySelectorAll('.icon-option').forEach(b => {
        b.classList.remove('btn-hb-primary');
        b.classList.add('btn-outline-secondary');
    });
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('btn-hb-primary');
}

function updatePreview(id, value) {
    updateSvgPaths(document.getElementById('preview-svg-' + id), value);
    updateSvgPaths(document.getElementById('preview-svg-lg-' + id), value);
}

function updateSvgPaths(svgEl, pathStr) {
    if (!svgEl) return;
    svgEl.innerHTML = '';
    pathStr.split('||').forEach(p => {
        if (!p.trim()) return;
        const el = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        el.setAttribute('stroke-linecap', 'round');
        el.setAttribute('stroke-linejoin', 'round');
        el.setAttribute('d', p.trim());
        svgEl.appendChild(el);
    });
}
</script>
@endpush
@endsection
