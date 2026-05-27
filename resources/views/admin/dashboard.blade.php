@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="hb-stat-card hb-stat-card--blue">
            <div class="hb-stat-card__icon"><i class="bi bi-box-seam"></i></div>
            <div class="hb-stat-card__value">{{ $stats['products'] }}</div>
            <div class="hb-stat-card__label">Productos</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hb-stat-card hb-stat-card--green">
            <div class="hb-stat-card__icon"><i class="bi bi-layout-text-window"></i></div>
            <div class="hb-stat-card__value">{{ $stats['pages'] }}</div>
            <div class="hb-stat-card__label">Páginas</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hb-stat-card hb-stat-card--orange">
            <div class="hb-stat-card__icon"><i class="bi bi-question-circle"></i></div>
            <div class="hb-stat-card__value">{{ $stats['faqs'] }}</div>
            <div class="hb-stat-card__label">Preguntas frecuentes</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hb-stat-card hb-stat-card--red">
            <div class="hb-stat-card__icon"><i class="bi bi-envelope"></i></div>
            <div class="hb-stat-card__value">{{ $stats['contacts_new'] }}</div>
            <div class="hb-stat-card__label">Mensajes nuevos</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card hb-admin-card h-100">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Accesos rápidos</h5></div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo producto
                    </a>
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-plus-circle me-2"></i>Nueva pregunta frecuente
                    </a>
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo banner
                    </a>
                    <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary text-start">
                        <i class="bi bi-folder2-open me-2"></i>Biblioteca de medios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card hb-admin-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Últimos mensajes</h5>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover hb-admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Asunto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentContacts as $contact)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact) }}">{{ $contact->full_name }}</a>
                                </td>
                                <td class="text-muted small">{{ $contact->email }}</td>
                                <td class="text-muted small">{{ Str::limit($contact->subject ?? '—', 30) }}</td>
                                <td>
                                    <span class="badge {{ $contact->status === 'new' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $contact->status === 'new' ? 'Nuevo' : ucfirst($contact->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $contact->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No hay mensajes aún</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
