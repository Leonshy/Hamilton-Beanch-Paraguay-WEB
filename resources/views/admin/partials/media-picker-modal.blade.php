{{-- Modal selector de medios --}}
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaPickerModalLabel"><i class="bi bi-folder2-open me-2"></i>Biblioteca de Medios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-2 mb-3">
                    <input type="text" class="form-control" id="mediaPickerSearch" placeholder="Buscar...">
                    <select class="form-select" id="mediaPickerType" style="max-width:150px">
                        <option value="image">Imágenes</option>
                        <option value="document">Documentos</option>
                        <option value="video">Videos</option>
                    </select>
                    <button class="btn btn-hb-primary" id="mediaPickerSearchBtn">Buscar</button>
                </div>
                <div class="row g-2" id="mediaPickerGrid">
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="bi bi-arrow-repeat d-block fs-2 mb-2"></i>Cargando biblioteca...
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <div class="small text-muted mb-1">También podés subir archivos: <span class="text-secondary">(máx. 64 MB c/u)</span></div>
                    <div id="mediaPickerUploadStatus" class="small text-muted d-none"></div>
                </div>
                <form id="mediaPickerUploadForm" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                    @csrf
                    <input type="file" class="form-control form-control-sm" name="file"
                           id="mediaPickerUploadFile" accept="image/*,application/pdf,video/*" multiple>
                    <button type="submit" class="btn btn-sm btn-hb-primary">Subir</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-hb-primary d-none" id="galleryConfirmBtn">
                    <i class="bi bi-check2 me-1"></i>Confirmar selección (0)
                </button>
            </div>
        </div>
    </div>
</div>
