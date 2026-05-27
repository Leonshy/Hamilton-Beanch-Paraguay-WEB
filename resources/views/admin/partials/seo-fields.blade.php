<div class="card hb-admin-card">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-search me-2"></i>SEO</h6></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Meta Title</label>
            <input type="text" class="form-control" name="meta_title"
                   value="{{ old('meta_title', $model->meta_title ?? '') }}" maxlength="255">
            <div class="form-text">Recomendado: máx. 60 caracteres.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Meta Description</label>
            <textarea class="form-control" name="meta_description" rows="2"
                      maxlength="500">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
            <div class="form-text">Recomendado: máx. 155 caracteres.</div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">OG Title</label>
                <input type="text" class="form-control" name="og_title"
                       value="{{ old('og_title', $model->og_title ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">OG Image (URL)</label>
                <input type="text" class="form-control" name="og_image"
                       value="{{ old('og_image', $model->og_image ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">OG Description</label>
                <textarea class="form-control" name="og_description"
                          rows="2">{{ old('og_description', $model->og_description ?? '') }}</textarea>
            </div>
        </div>
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="no_index" value="1"
                   {{ old('no_index', $model->no_index ?? false) ? 'checked' : '' }}>
            <label class="form-check-label small">No indexar esta página (noindex, nofollow)</label>
        </div>
    </div>
</div>
