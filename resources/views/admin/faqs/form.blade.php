@extends('layouts.admin')
@section('title', isset($faq) ? 'Editar Pregunta' : 'Nueva Pregunta Frecuente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ isset($faq) ? 'Editar pregunta' : 'Nueva Pregunta Frecuente' }}</h2>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<form action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST">
    @csrf
    @if(isset($faq)) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Pregunta y respuesta</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Pregunta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror"
                               name="question"
                               value="{{ old('question', $faq->question ?? '') }}" required>
                        @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Respuesta <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('answer') is-invalid @enderror"
                                  name="answer" rows="6" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
                        @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card hb-admin-card">
                <div class="card-header"><h6 class="mb-0">Opciones</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="category">
                            @foreach(['general','productos','garantia','servicio'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $faq->category ?? 'general') === $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" class="form-control" name="order"
                               value="{{ old('order', $faq->order ?? $nextOrder ?? 0) }}" min="0">
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Activa (visible en el sitio)</label>
                    </div>
                    <button type="submit" class="btn btn-hb-primary w-100">
                        <i class="bi bi-save me-2"></i>{{ isset($faq) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
