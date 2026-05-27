@extends('layouts.admin')
@section('title', isset($page) ? 'Editar Página' : 'Nueva Página')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($page) ? 'Editar: ' . $page->title : 'Nueva Página' }}</h2>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      method="POST">
    @csrf
    @if(isset($page)) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card mb-4">
                <div class="card-header"><h6 class="mb-0">Contenido</h6></div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Sección <span class="text-danger">*</span></label>
                            <select class="form-select @error('section') is-invalid @enderror" name="section" required>
                                @foreach(['servicio-tecnico','manuales','garantia'] as $sec)
                                <option value="{{ $sec }}" {{ old('section', $page->section ?? '') === $sec ? 'selected' : '' }}>
                                    {{ $sec }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="status">
                                <option value="published" {{ old('status', $page->status ?? 'published') === 'published' ? 'selected' : '' }}>Publicada</option>
                                <option value="draft" {{ old('status', $page->status ?? '') === 'draft' ? 'selected' : '' }}>Borrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title"
                               value="{{ old('title', $page->title ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtítulo</label>
                        <input type="text" class="form-control" name="subtitle"
                               value="{{ old('subtitle', $page->subtitle ?? '') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Contenido</label>
                        <textarea class="form-control hb-wysiwyg" name="content"
                                  rows="15">{{ old('content', $page->content ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            @php $model = $page ?? new \App\Models\Page; @endphp
            @include('admin.partials.seo-fields')
        </div>
        <div class="col-lg-4">
            <div class="card hb-admin-card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               value="{{ old('slug', $page->slug ?? '') }}"
                               placeholder="se-genera-automaticamente">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" class="form-control" name="order"
                               value="{{ old('order', $page->order ?? 0) }}" min="0">
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($page) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
