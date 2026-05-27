@extends('layouts.admin')
@section('title', 'Preguntas Frecuentes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Preguntas Frecuentes</h2>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nueva pregunta
    </a>
</div>
<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr><th>#</th><th>Pregunta</th><th>Categoría</th><th>Estado</th><th>Orden</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr>
                    <td class="text-muted small">{{ $faq->id }}</td>
                    <td>{{ Str::limit($faq->question, 80) }}</td>
                    <td><span class="badge bg-secondary">{{ $faq->category }}</span></td>
                    <td>
                        <span class="badge {{ $faq->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $faq->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $faq->order }}</td>
                    <td>
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar esta pregunta?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No hay preguntas frecuentes aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
