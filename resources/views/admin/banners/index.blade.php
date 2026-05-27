@extends('layouts.admin')
@section('title', 'Banners')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Banners</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nuevo banner
    </a>
</div>
<div class="card hb-admin-card">
    <div class="card-body p-0">
        <table class="table table-hover hb-admin-table mb-0">
            <thead>
                <tr><th style="width:60px">Img</th><th>Título</th><th>Posición</th><th>Estado</th><th>Orden</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>
                        @if($banner->image)
                            <img src="{{ $banner->image->url }}" alt="" width="60" height="36"
                                 class="rounded" style="object-fit:cover">
                        @else
                            <div class="hb-admin-no-img"><i class="bi bi-image"></i></div>
                        @endif
                    </td>
                    <td><strong>{{ $banner->title ?? '(sin título)' }}</strong></td>
                    <td><span class="badge bg-info text-dark">{{ $banner->position }}</span></td>
                    <td>
                        <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $banner->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $banner->order }}</td>
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
                <tr><td colspan="6" class="text-center text-muted py-5">No hay banners aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
