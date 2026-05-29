@extends('layouts.admin')
@section('title', 'Configuración General')

@section('content')
<h2 class="mb-4">Configuración</h2>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings.general') }}">General</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.contact') }}">Contacto</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.social') }}">Redes Sociales</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.integrations') }}">Integraciones</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.home') }}">Página de Inicio</a></li>
</ul>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Form principal de configuración general --}}
<form action="{{ route('admin.settings.general.save') }}" method="POST"
      enctype="multipart/form-data" id="generalForm">
    @csrf
</form>

<div class="row g-4">
    {{-- Columna principal --}}
    <div class="col-lg-8">
        <form action="{{ route('admin.settings.general.save') }}" method="POST"
              enctype="multipart/form-data">
            @csrf
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

            <div class="mt-3">
                <button type="submit" class="btn btn-hb-primary">
                    <i class="bi bi-save me-2"></i>Guardar cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Modo mantenimiento — form independiente, NO anidado --}}
        @php $maintenance = \App\Models\SiteSetting::get('maintenance_mode') === '1'; @endphp
        <div class="card hb-admin-card {{ $maintenance ? 'border-warning' : '' }}">
            <div class="card-header {{ $maintenance ? 'bg-warning bg-opacity-10' : '' }}">
                <h6 class="mb-0 {{ $maintenance ? 'text-warning' : '' }}">
                    <i class="bi bi-cone-striped me-2"></i>Modo mantenimiento
                </h6>
            </div>
            <div class="card-body">
                @if($maintenance)
                <div class="alert alert-warning py-2 px-3 small mb-3">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    El sitio está en mantenimiento. Solo los usuarios logueados pueden verlo.
                </div>
                @else
                <p class="text-muted small mb-3">
                    Al activar, los visitantes verán una página de mantenimiento.
                    Los administradores logueados pueden navegar normalmente.
                </p>
                @endif
                <form action="{{ route('admin.settings.maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="value" value="{{ $maintenance ? '0' : '1' }}">
                    <button type="submit" class="btn w-100 btn-{{ $maintenance ? 'warning' : 'outline-secondary' }}">
                        <i class="bi bi-{{ $maintenance ? 'check-circle' : 'cone-striped' }} me-2"></i>
                        {{ $maintenance ? 'Desactivar mantenimiento' : 'Activar mantenimiento' }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
