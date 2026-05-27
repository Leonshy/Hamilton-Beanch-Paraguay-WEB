@extends('layouts.admin')
@section('title', 'Anuncios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Anuncios</h2>
        <p class="text-muted small mb-0">Textos que se desplazan en la barra superior del sitio.</p>
    </div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nuevo anuncio
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:50px">Orden</th>
                    <th>Texto</th>
                    <th style="width:100px">Estado</th>
                    <th style="width:120px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $ann)
                <tr>
                    <td class="text-muted small">{{ $ann->order }}</td>
                    <td>{{ $ann->text }}</td>
                    <td>
                        <span class="badge {{ $ann->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $ann->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.announcements.edit', $ann) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar este anuncio?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-5">No hay anuncios aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
