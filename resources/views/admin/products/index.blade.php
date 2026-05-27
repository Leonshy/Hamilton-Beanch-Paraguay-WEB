@extends('layouts.admin')
@section('title', 'Productos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Productos</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-hb-primary">
        <i class="bi bi-plus-circle me-2"></i>Nuevo producto
    </a>
</div>

<div class="card hb-admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover hb-admin-table mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th style="width:60px">Img</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-muted small">{{ $product->id }}</td>
                        <td>
                            @if($product->featuredImage)
                                <img src="{{ $product->featuredImage->url }}" alt="" width="44" height="44"
                                     class="rounded object-fit-cover" style="object-fit:cover">
                            @else
                                <div class="hb-admin-no-img"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->title }}</strong>
                            @if($product->subtitle)
                                <br><small class="text-muted">{{ Str::limit($product->subtitle, 60) }}</small>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $product->category?->name ?? '—' }}</td>
                        <td class="text-muted small">{{ $product->formatted_price ?: '—' }}</td>
                        <td>
                            <span class="badge {{ $product->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->status === 'published' ? 'Publicado' : 'Borrador' }}
                            </span>
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark ms-1">Destacado</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $product->order }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            No hay productos aún.
                            <a href="{{ route('admin.products.create') }}">Crear el primero</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="card-footer">{{ $products->links() }}</div>
    @endif
</div>
@endsection
