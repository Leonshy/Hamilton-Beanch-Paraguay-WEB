@extends('layouts.admin')
@section('title', 'Categorías')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Categorías</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nueva categoría
    </a>
</div>

<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:36px"></th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th style="width:60px">Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="sortable-categories"
                   data-url="{{ route('admin.categories.reorder') }}">
                @forelse($categories as $category)
                <tr data-id="{{ $category->id }}">
                    <td class="hb-sort-handle text-center" style="cursor:grab;color:#aaa">
                        <i class="bi bi-grip-vertical"></i>
                    </td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td class="text-muted small font-monospace">{{ $category->slug }}</td>
                    <td class="text-muted small">{{ $category->products_count }}</td>
                    <td>
                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="text-muted small sort-order-num">{{ $category->order }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">No hay categorías aún.</td>
                </tr>
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
    var tbody = document.getElementById('sortable-categories');
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
