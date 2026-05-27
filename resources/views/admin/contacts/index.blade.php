@extends('layouts.admin')
@section('title', 'Mensajes de Contacto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Mensajes de Contacto</h2>
</div>

<div class="card hb-admin-card mb-3">
    <div class="card-body py-2">
        <div class="d-flex gap-2 flex-wrap">
            @foreach(['all' => 'Todos', 'new' => 'Nuevos', 'read' => 'Leídos', 'replied' => 'Respondidos', 'archived' => 'Archivados'] as $val => $label)
            <a href="{{ route('admin.contacts.index', ['status' => $val]) }}"
               class="btn btn-sm {{ $status === $val ? 'btn-hb-primary' : 'btn-outline-secondary' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr><th>Nombre</th><th>Email</th><th>Asunto</th><th>Estado</th><th>Fecha</th><th>Acción</th></tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td><strong>{{ $contact->full_name }}</strong></td>
                    <td class="text-muted small">{{ $contact->email }}</td>
                    <td class="text-muted small">{{ Str::limit($contact->subject ?? '—', 40) }}</td>
                    <td>
                        @php
                        $badgeClass = match($contact->status) {
                            'new'      => 'bg-danger',
                            'read'     => 'bg-primary',
                            'replied'  => 'bg-success',
                            'archived' => 'bg-secondary',
                            default    => 'bg-secondary',
                        };
                        $statusLabel = match($contact->status) {
                            'new'      => 'Nuevo',
                            'read'     => 'Leído',
                            'replied'  => 'Respondido',
                            'archived' => 'Archivado',
                            default    => $contact->status,
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="text-muted small">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $contact) }}"
                           class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar este mensaje?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No hay mensajes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contacts->hasPages())
    <div class="card-footer">{{ $contacts->links() }}</div>
    @endif
</div>
@endsection
