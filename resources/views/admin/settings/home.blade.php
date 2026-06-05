@extends('layouts.admin')
@section('title', 'Configuración de Inicio')

@section('content')
<h2 class="mb-4">Configuración</h2>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.general') }}">General</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.contact') }}">Contacto</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.social') }}">Redes Sociales</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.integrations') }}">Integraciones</a></li>
    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings.home') }}">Página de Inicio</a></li>
</ul>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.settings.home.save') }}" method="POST">
    @csrf

    <div class="card hb-admin-card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-layout-text-window me-2"></i>Bloques destacados (debajo del hero)</h6>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-4">Estos 3 bloques aparecen debajo del banner principal en la página de inicio. Cada uno tiene un ícono, un título y una descripción corta.</p>

            @php
            $iconOptions = [
                'location' => ['label' => 'Ubicación / Mapa',     'bi' => 'bi-geo-alt'],
                'shield'   => ['label' => 'Garantía / Seguridad', 'bi' => 'bi-shield-check'],
                'gear'     => ['label' => 'Servicio técnico',     'bi' => 'bi-gear'],
                'truck'    => ['label' => 'Envío / Entrega',      'bi' => 'bi-truck'],
                'star'     => ['label' => 'Calidad / Destacado',  'bi' => 'bi-star'],
                'phone'    => ['label' => 'Teléfono / Contacto',  'bi' => 'bi-telephone'],
                'check'    => ['label' => 'Aprobado / Correcto',  'bi' => 'bi-check-circle'],
                'clock'    => ['label' => 'Horario / Tiempo',     'bi' => 'bi-clock'],
                'users'    => ['label' => 'Clientes / Personas',  'bi' => 'bi-people'],
                'award'    => ['label' => 'Premio / Certificado', 'bi' => 'bi-award'],
                'heart'    => ['label' => 'Confianza / Favorito', 'bi' => 'bi-heart'],
                'tag'      => ['label' => 'Precio / Promoción',   'bi' => 'bi-tag'],
            ];
            @endphp

            @foreach($features as $i => $feature)
            <div class="border rounded-3 p-4 mb-3 bg-light">
                <div class="fw-semibold text-muted mb-3 small">BLOQUE {{ $i + 1 }}</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Ícono</label>
                        <select class="form-select" name="features[{{ $i }}][icon]">
                            @foreach($iconOptions as $key => $opt)
                            <option value="{{ $key }}" {{ ($feature['icon'] ?? '') === $key ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                            @endforeach
                        </select>
                        <div class="mt-2 d-flex align-items-center gap-2 text-muted small">
                            <i class="bi {{ $iconOptions[$feature['icon'] ?? 'shield']['bi'] ?? 'bi-shield-check' }} fs-4" id="iconPreview{{ $i }}"></i>
                            <span>Vista previa</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="features[{{ $i }}][title]"
                               value="{{ old("features.{$i}.title", $feature['title'] ?? '') }}"
                               placeholder="Ej: Garantía oficial 1 año" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Descripción</label>
                        <input type="text" class="form-control" name="features[{{ $i }}][description]"
                               value="{{ old("features.{$i}.description", $feature['description'] ?? '') }}"
                               placeholder="Ej: Con respaldo de servicio técnico autorizado">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Enlace <span class="text-muted small">(opcional)</span></label>
                        <input type="url" class="form-control" name="features[{{ $i }}][url]"
                               value="{{ old("features.{$i}.url", $feature['url'] ?? '') }}"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-hb-primary">
                <i class="bi bi-save me-2"></i>Guardar bloques
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Actualizar ícono de vista previa al cambiar el select
document.querySelectorAll('select[name^="features"]').forEach(function(select) {
    var idx = select.name.match(/\[(\d+)\]/)[1];
    var biMap = {
        location: 'bi-geo-alt', shield: 'bi-shield-check', gear: 'bi-gear',
        truck: 'bi-truck', star: 'bi-star', phone: 'bi-telephone',
        check: 'bi-check-circle', clock: 'bi-clock', users: 'bi-people',
        award: 'bi-award', heart: 'bi-heart', tag: 'bi-tag'
    };
    select.addEventListener('change', function () {
        var preview = document.getElementById('iconPreview' + idx);
        if (preview) {
            preview.className = 'bi ' + (biMap[this.value] || 'bi-shield-check') + ' fs-4';
        }
    });
});
</script>
@endpush
