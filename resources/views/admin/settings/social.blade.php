@extends('layouts.admin')
@section('title', 'Configuración — Redes Sociales')

@section('content')
<h2 class="mb-4">Configuración</h2>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.general') }}">General</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.contact') }}">Contacto</a></li>
    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings.social') }}">Redes Sociales</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.integrations') }}">Integraciones</a></li>
</ul>

<form action="{{ route('admin.settings.social.save') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">URLs de redes sociales</h6></div>
                <div class="card-body">
                    @foreach([
                        'social_instagram' => ['Instagram', 'bi-instagram', 'https://instagram.com/hamiltonbeachpy'],
                        'social_facebook'  => ['Facebook', 'bi-facebook', 'https://facebook.com/hamiltonbeachpy'],
                        'social_tiktok'    => ['TikTok', 'bi-tiktok', 'https://tiktok.com/@hamiltonbeachpy'],
                        'social_youtube'   => ['YouTube', 'bi-youtube', ''],
                        'social_twitter'   => ['Twitter / X', 'bi-twitter-x', ''],
                    ] as $key => [$label, $icon, $placeholder])
                    <div class="mb-3">
                        <label class="form-label"><i class="bi {{ $icon }} me-2"></i>{{ $label }}</label>
                        <input type="url" class="form-control" name="{{ $key }}"
                               value="{{ old($key, $settings[$key] ?? '') }}"
                               placeholder="{{ $placeholder }}">
                    </div>
                    @endforeach
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
