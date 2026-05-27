@extends('layouts.admin')
@section('title', 'Mensaje de ' . $contact->full_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Mensaje de {{ $contact->full_name }}</h2>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card hb-admin-card">
            <div class="card-header"><h6 class="mb-0">Mensaje</h6></div>
            <div class="card-body">
                <p class="text-muted small mb-1">
                    <strong>Asunto:</strong> {{ $contact->subject ?? '(sin asunto)' }}
                </p>
                <hr>
                <p style="white-space:pre-wrap">{{ $contact->message }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card hb-admin-card mb-4">
            <div class="card-header"><h6 class="mb-0">Datos del contacto</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2"><i class="bi bi-person me-2"></i><strong>{{ $contact->full_name }}</strong></li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </li>
                    @if($contact->phone)
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $contact->phone }}</li>
                    @endif
                    <li class="mb-2"><i class="bi bi-calendar me-2"></i>{{ $contact->created_at->format('d/m/Y H:i') }}</li>
                    @if($contact->ip_address)
                    <li class="mb-2"><i class="bi bi-geo me-2"></i>IP: {{ $contact->ip_address }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="card hb-admin-card">
            <div class="card-header"><h6 class="mb-0">Estado</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.contacts.status', $contact) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <select class="form-select" name="status">
                            @foreach(['new' => 'Nuevo', 'read' => 'Leído', 'replied' => 'Respondido', 'archived' => 'Archivado'] as $val => $label)
                            <option value="{{ $val }}" {{ $contact->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">Actualizar estado</button>
                </form>
                <hr>
                <a href="mailto:{{ $contact->email }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-envelope me-2"></i>Responder por email
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
