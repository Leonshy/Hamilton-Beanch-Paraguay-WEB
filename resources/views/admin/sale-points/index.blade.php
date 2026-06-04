@extends('layouts.admin')
@section('title', 'Puntos de Venta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Puntos de Venta</h2>
    <a href="{{ route('admin.sale-points.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nuevo punto de venta
    </a>
</div>

<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:36px"></th>
                    <th style="width:60px">Logo</th>
                    <th>Nombre</th>
                    <th>URL</th>
                    <th>Estado</th>
                    <th style="width:60px">Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="hb-sortable" data-url="{{ route('admin.sale-points.reorder') }}">
                @forelse($salePoints as $sp)
                <tr data-id="{{ $sp->id }}">
                    <td class="hb-sort-handle text-center" style="cursor:grab;color:#aaa">
                        <i class="bi bi-grip-vertical"></i>
                    </td>
                    <td>
                        @if($sp->logo)
                            <img src="{{ $sp->logo->url }}" alt="{{ $sp->name }}"
                                 width="60" height="36" class="rounded" style="object-fit:contain;background:#f5f5f5">
                        @else
                            <div class="hb-admin-no-img"><i class="bi bi-shop"></i></div>
                        @endif
                    </td>
                    <td><strong>{{ $sp->name }}</strong></td>
                    <td class="text-muted small">
                        @if($sp->url)
                            <a href="{{ $sp->url }}" target="_blank" rel="noopener" class="text-muted">
                                {{ Str::limit($sp->url, 40) }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $sp->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $sp->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-muted small sort-order-num">{{ $sp->order }}</td>
                    <td>
                        <a href="{{ route('admin.sale-points.edit', $sp) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.sale-points.destroy', $sp) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar este punto de venta?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-5">No hay puntos de venta aún.</td></tr>
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

    function showToast() {
        var t = document.getElementById('sort-toast');
        t.classList.remove('d-none');
        clearTimeout(window._sortTimer);
        window._sortTimer = setTimeout(function () { t.classList.add('d-none'); }, 2500);
    }

    document.querySelectorAll('.hb-sortable').forEach(function (tbody) {
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
    });
})();
</script>
@endpush
