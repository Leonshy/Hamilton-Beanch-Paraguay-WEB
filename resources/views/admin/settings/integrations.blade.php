@extends('layouts.admin')
@section('title', 'Configuración — Integraciones')

@section('content')
<h2 class="mb-4">Configuración</h2>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.general') }}">General</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.contact') }}">Contacto</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.social') }}">Redes Sociales</a></li>
    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings.integrations') }}">Integraciones</a></li>
</ul>

<form action="{{ route('admin.settings.integrations.save') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Analytics y Tracking</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Google Analytics ID</label>
                        <input type="text" class="form-control" name="google_analytics_id"
                               value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}"
                               placeholder="G-XXXXXXXXXX">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Meta Pixel ID</label>
                        <input type="text" class="form-control" name="meta_pixel_id"
                               value="{{ old('meta_pixel_id', $settings['meta_pixel_id'] ?? '') }}"
                               placeholder="1234567890">
                    </div>
                </div>
            </div>
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">WhatsApp flotante</h6></div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="whatsapp_float_enabled" value="1"
                               {{ old('whatsapp_float_enabled', $settings['whatsapp_float_enabled'] ?? '1') ? 'checked' : '' }}>
                        <label class="form-check-label">Mostrar botón WhatsApp flotante en el sitio</label>
                    </div>
                </div>
            </div>
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Scripts personalizados</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Scripts en &lt;head&gt;</label>
                        <textarea class="form-control font-monospace" name="custom_scripts_head"
                                  rows="4">{{ old('custom_scripts_head', $settings['custom_scripts_head'] ?? '') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Scripts antes de &lt;/body&gt;</label>
                        <textarea class="form-control font-monospace" name="custom_scripts_body"
                                  rows="4">{{ old('custom_scripts_body', $settings['custom_scripts_body'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card hb-admin-card">
                <div class="card-body">
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
