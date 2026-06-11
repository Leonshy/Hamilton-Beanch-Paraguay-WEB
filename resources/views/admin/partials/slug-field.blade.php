{{--
    Slug field with URL preview and copy button.
    Props:
      $slugValue  — current slug string
      $urlPrefix  — full URL up to (and including) the slug, e.g. "https://example.com/productos/"
                    For query-param slugs: "https://example.com/productos?categoria="
--}}
@php
    $slugValue = $slugValue ?? '';
    $urlPrefix = $urlPrefix ?? (config('app.url') . '/');
@endphp

<label class="form-label">URL pública</label>
<div class="input-group hb-slug-group">
    <span class="input-group-text text-muted pe-1"
          style="font-size:.78rem; max-width:55%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
          title="{{ $urlPrefix }}">{{ $urlPrefix }}</span>
    <input type="text" class="form-control" id="slug" name="slug"
           value="{{ old('slug', $slugValue) }}"
           placeholder="se-genera-automaticamente"
           style="min-width:80px;">
    <button type="button" class="btn btn-outline-secondary px-2"
            onclick="hbCopySlugUrl(this)" title="Copiar URL completa">
        <i class="bi bi-clipboard"></i>
    </button>
</div>
<div class="form-text">Si se deja vacío, se genera automáticamente desde el título.</div>
