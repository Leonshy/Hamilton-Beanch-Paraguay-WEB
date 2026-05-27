@extends('layouts.admin')
@section('title', 'Configuración General')

@section('content')
<h2 class="mb-4">Configuración</h2>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings.general') }}">General</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.contact') }}">Contacto</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.social') }}">Redes Sociales</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.integrations') }}">Integraciones</a></li>
</ul>

<form action="{{ route('admin.settings.general.save') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Identidad del sitio</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del sitio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="site_name"
                               value="{{ old('site_name', $settings['site_name'] ?? 'Hamilton Beach Paraguay') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Eslogan / Tagline</label>
                        <input type="text" class="form-control" name="site_tagline"
                               value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Descripción del sitio</label>
                        <textarea class="form-control" name="site_description"
                                  rows="3">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        <div class="form-text">Se usa como meta description predeterminada.</div>
                    </div>
                </div>
            </div>
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Logo y Favicon</h6></div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Logo del sitio</label>
                            @if(!empty($settings['logo']))
                            <div class="mb-2">
                                <img src="{{ $settings['logo'] }}" alt="Logo" class="img-thumbnail" style="max-height:60px">
                            </div>
                            @endif
                            <input type="file" class="form-control" name="logo_file" accept="image/*">
                            <div class="form-text">PNG, SVG, WebP. Fondo transparente recomendado.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Favicon</label>
                            @if(!empty($settings['favicon']))
                            <div class="mb-2">
                                <img src="{{ $settings['favicon'] }}" alt="Favicon" class="img-thumbnail" style="max-height:32px">
                            </div>
                            @endif
                            <input type="file" class="form-control" name="favicon_file" accept="image/*,.ico">
                            <div class="form-text">ICO o PNG, 32×32 px.</div>
                        </div>
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
