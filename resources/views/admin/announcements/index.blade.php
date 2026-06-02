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
                    <th style="width:36px"></th>
                    <th style="width:60px">Orden</th>
                    <th>Texto</th>
                    <th style="width:100px">Estado</th>
                    <th style="width:120px">Acciones</th>
                </tr>
            </thead>
            <tbody id="sortable-announcements"
                   data-url="{{ route('admin.announcements.reorder') }}">
                @forelse($announcements as $ann)
                <tr data-id="{{ $ann->id }}">
                    <td class="hb-sort-handle text-center" style="cursor:grab;color:#aaa">
                        <i class="bi bi-grip-vertical"></i>
                    </td>
                    <td class="text-muted small sort-order-num">{{ $ann->order }}</td>
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
                <tr><td colspan="5" class="text-center text-muted py-5">No hay anuncios aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="sort-toast" class="d-none position-fixed bottom-0 end-0 m-3 p-2 px-3 bg-success text-white rounded shadow small" style="z-index:9999">
    Orden guardado
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function () {
    var csrf = document.querySelector('meta[name=csrf-token]').content;
    var tbody = document.getElementById('sortable-announcements');
    if (!tbody) return;

    function showToast() {
        var t = document.getElementById('sort-toast');
        t.classList.remove('d-none');
        clearTimeout(window._sortTimer);
        window._sortTimer = setTimeout(function () { t.classList.add('d-none'); }, 2500);
    }

    new Sortable(tbody, {
        animation: 150,
        handle: '.hb-sort-handle',
        ghostClass: 'table-active',
        onEnd: function () {
            var ids = Array.from(tbody.querySelectorAll('tr[data-id]')).map(function (tr) {
                return parseInt(tr.dataset.id);
            });
            fetch(tbody.dataset.url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ ids: ids })
            }).then(function (r) {
                if (r.ok) {
                    ids.forEach(function (id, i) {
                        var cell = tbody.querySelector('tr[data-id="' + id + '"] .sort-order-num');
                        if (cell) cell.textContent = i + 1;
                    });
                    showToast();
                }
            });
        }
    });
})();
</script>
@endpush
