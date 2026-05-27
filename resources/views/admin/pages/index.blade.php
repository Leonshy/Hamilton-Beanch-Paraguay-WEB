@extends('layouts.admin')
@section('title', 'Páginas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Páginas</h2>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nueva página
    </a>
</div>
<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr><th>#</th><th>Sección</th><th>Título</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td class="text-muted small">{{ $page->id }}</td>
                    <td><span class="badge bg-secondary">{{ $page->section }}</span></td>
                    <td><strong>{{ $page->title }}</strong></td>
                    <td>
                        <span class="badge {{ $page->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $page->status === 'published' ? 'Publicada' : 'Borrador' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar esta página?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-5">No hay páginas aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
