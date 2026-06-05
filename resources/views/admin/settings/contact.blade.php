@extends('layouts.admin')
@section('title', 'Configuración — Contacto')

@section('content')
<h2 class="mb-4">Datos de contacto</h2>

<form action="{{ route('admin.settings.contact.save') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Datos de contacto</h6></div>
                <div class="card-body">
                    @foreach([
                        'phone'         => ['Teléfono', 'text', '+595 (9) 1234-567'],
                        'whatsapp'      => ['WhatsApp (número sin +)', 'text', '595911234567'],
                        'email'         => ['Email público', 'email', 'info@hamiltonbeach.com.py'],
                        'contact_email' => ['Email para notificaciones internas', 'email', ''],
                        'address'       => ['Dirección', 'text', 'Asunción, Paraguay'],
                        'schedule'      => ['Horarios de atención', 'text', 'Lun–Vie: 09:00–18:00'],
                    ] as $key => [$label, $type, $placeholder])
                    <div class="mb-3">
                        <label class="form-label">{{ $label }}</label>
                        <input type="{{ $type }}" class="form-control" name="{{ $key }}"
                               value="{{ old($key, $settings[$key] ?? '') }}"
                               placeholder="{{ $placeholder }}">
                    </div>
                    @endforeach
                    <div class="mb-0">
                        <label class="form-label">Embed Google Maps (código iframe)</label>
                        <textarea class="form-control font-monospace" name="map_embed"
                                  rows="3">{{ old('map_embed', $settings['map_embed'] ?? '') }}</textarea>
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
