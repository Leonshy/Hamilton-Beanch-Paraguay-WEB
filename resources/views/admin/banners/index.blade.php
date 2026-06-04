@extends('layouts.admin')
@section('title', 'Banners')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Banners</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nuevo banner
    </a>
</div>

@foreach(['home' => 'Inicio (hero principal)', 'home_mid' => 'Inicio (nuevos ingresos)'] as $pos => $label)
<h6 class="text-muted text-uppercase small fw-semibold mb-2 mt-4">Posición: {{ $label }}</h6>
<div class="card hb-admin-card mb-3">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:36px"></th>
                    <th style="width:60px">Img</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th style="width:60px">Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="hb-sortable" data-url="{{ route('admin.banners.reorder') }}" data-position="{{ $pos }}">
                @forelse($grouped->get($pos, collect()) as $banner)
                <tr data-id="{{ $banner->id }}">
                    <td class="hb-sort-handle text-center" style="cursor:grab;color:#aaa">
                        <i class="bi bi-grip-vertical"></i>
                    </td>
                    <td>
                        @if($banner->image)
                            <img src="{{ $banner->image->url }}" alt="" width="60" height="36"
                                 class="rounded" style="object-fit:cover">
                        @else
                            <div class="hb-admin-no-img"><i class="bi bi-image"></i></div>
                        @endif
                    </td>
                    <td><strong>{{ $banner->title ?? '(sin título)' }}</strong></td>
                    <td>
                        <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $banner->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-muted small sort-order-num">{{ $banner->order }}</td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar este banner?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4 small">Sin banners en esta posición.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endforeach

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
                    body: JSON.stringify({ ids: ids, position: tbody.dataset.position })
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
