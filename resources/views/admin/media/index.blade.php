@extends('layouts.admin')
@section('title', 'Biblioteca de Medios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Biblioteca de Medios</h2>
    <button type="button" class="btn btn-hb-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload me-2"></i>Subir archivo
    </button>
</div>

<div class="card hb-admin-card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.media.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Tipo</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Todos</option>
                    <option value="image" {{ $type === 'image' ? 'selected' : '' }}>Imágenes</option>
                    <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Videos</option>
                    <option value="document" {{ $type === 'document' ? 'selected' : '' }}>Documentos</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Carpeta</label>
                <select name="folder" class="form-select form-select-sm">
                    <option value="" {{ !$folder ? 'selected' : '' }}>Todas las carpetas</option>
                    @foreach($folders as $f)
                    <option value="{{ $f }}" {{ $folder === $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Buscar</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       value="{{ $search }}" placeholder="Nombre del archivo...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-hb-primary w-100">
                    <i class="bi bi-search me-1"></i>Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card hb-admin-card">
    <div class="card-body">
        @if($media->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
            No hay archivos con los filtros seleccionados.
        </div>
        @else
        <div class="row g-2">
            @foreach($media as $file)
            @php
                $icon = match(true) {
                    str_contains($file->mime_type ?? '', 'pdf')  => 'bi-file-earmark-pdf text-danger',
                    str_contains($file->mime_type ?? '', 'word') => 'bi-file-earmark-word text-primary',
                    $file->type === 'video'                      => 'bi-play-circle text-secondary',
                    default                                      => 'bi-file-earmark text-secondary',
                };
            @endphp
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card h-100 border hb-media-item" style="cursor:pointer"
                     data-bs-toggle="modal" data-bs-target="#mediaDetailModal"
                     data-type="{{ $file->type }}"
                     data-url="{{ $file->url }}"
                     data-name="{{ $file->name }}"
                     data-mime="{{ $file->mime_type }}"
                     data-size="{{ $file->human_size }}"
                     data-folder="{{ $file->folder }}"
                     data-date="{{ $file->created_at->format('d/m/Y H:i') }}"
                     data-icon="{{ $icon }}">
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light position-relative overflow-hidden"
                         style="height:100px">
                        @if($file->type === 'image')
                            <img src="{{ $file->url }}" alt="{{ $file->name }}"
                                 class="w-100 h-100" style="object-fit:cover">
                        @else
                            <i class="bi {{ $icon }} fs-2"></i>
                        @endif
                        <div class="position-absolute top-0 end-0 m-1" onclick="event.stopPropagation()">
                            <form action="{{ route('admin.media.destroy', $file) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este archivo?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger p-0"
                                        style="width:22px;height:22px;line-height:1">
                                    <i class="bi bi-x" style="font-size:12px"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <p class="card-text small text-truncate mb-0" title="{{ $file->name }}">{{ $file->name }}</p>
                        @if($file->size)
                            <small class="text-muted">{{ $file->human_size }}</small>
                        @endif
                        @if($file->folder)
                            <br><small class="text-muted"><i class="bi bi-folder2 me-1"></i>{{ $file->folder }}</small>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @if($media->hasPages())
    <div class="card-footer">{{ $media->links() }}</div>
    @endif
</div>

{{-- Modal subida --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadModalForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Archivos <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                               name="file" id="mediaIndexFile" multiple required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Imágenes (JPG, PNG, WebP, SVG), PDFs y documentos. Máx. 64 MB por archivo.</div>
                        <div id="mediaIndexSizeError" class="text-danger small mt-1 d-none"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Carpeta</label>
                        <input type="text" class="form-control" name="folder" id="uploadModalFolder"
                               list="folderList" placeholder="ej: productos, banners"
                               value="{{ $folder }}">
                        <datalist id="folderList">
                            @foreach($folders as $f)
                            <option value="{{ $f }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div id="uploadModalProgress" class="d-none">
                        <div class="progress mb-1" style="height:6px;">
                            <div id="uploadModalBar" class="progress-bar bg-success" style="width:0%"></div>
                        </div>
                        <p id="uploadModalStatus" class="small text-muted mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-hb-primary" id="uploadModalBtn">
                        <i class="bi bi-cloud-upload me-2"></i>Subir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal detalle --}}
<div class="modal fade" id="mediaDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-truncate me-3" id="mdTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="mdPreview" class="text-center mb-3"></div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold mb-1">URL del archivo</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="mdUrl" class="form-control font-monospace" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="mdCopyBtn">
                            <i class="bi bi-clipboard" id="mdCopyIcon"></i>
                        </button>
                    </div>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tipo</span><span id="mdMime"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tamaño</span><span id="mdSize"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0" id="mdFolderRow">
                        <span class="text-muted">Carpeta</span><span id="mdFolder"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Subido el</span><span id="mdDate"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var detailModal = document.getElementById('mediaDetailModal');
    detailModal.addEventListener('show.bs.modal', function (e) {
        var c = e.relatedTarget;
        document.getElementById('mdTitle').textContent = c.dataset.name;
        document.getElementById('mdUrl').value = c.dataset.url;
        document.getElementById('mdMime').textContent = c.dataset.mime || '—';
        document.getElementById('mdSize').textContent = c.dataset.size || '—';
        document.getElementById('mdDate').textContent = c.dataset.date || '—';
        var folderRow = document.getElementById('mdFolderRow');
        if (c.dataset.folder) {
            document.getElementById('mdFolder').textContent = c.dataset.folder;
            folderRow.style.display = '';
        } else {
            folderRow.style.display = 'none';
        }
        var preview = document.getElementById('mdPreview');
        if (c.dataset.type === 'image') {
            preview.innerHTML = '<img src="' + c.dataset.url + '" class="img-fluid rounded" style="max-height:220px;object-fit:contain">';
        } else {
            preview.innerHTML = '<i class="bi ' + c.dataset.icon + '" style="font-size:4rem"></i>';
        }
        document.getElementById('mdCopyIcon').className = 'bi bi-clipboard';
    });
    document.getElementById('mdCopyBtn').addEventListener('click', function () {
        navigator.clipboard.writeText(document.getElementById('mdUrl').value).then(function () {
            document.getElementById('mdCopyIcon').className = 'bi bi-clipboard-check text-success';
            setTimeout(() => document.getElementById('mdCopyIcon').className = 'bi bi-clipboard', 2000);
        });
    });

    var MAX_SIZE = 64 * 1024 * 1024;

    // Validación de tamaño al seleccionar archivos
    var mediaIndexFile = document.getElementById('mediaIndexFile');
    if (mediaIndexFile) {
        mediaIndexFile.addEventListener('change', function () {
            var errorEl = document.getElementById('mediaIndexSizeError');
            var submitBtn = document.getElementById('uploadModalBtn');
            var oversized = Array.from(this.files).filter(function (f) { return f.size > MAX_SIZE; });
            if (oversized.length) {
                errorEl.textContent = oversized.map(function (f) { return '"' + f.name + '" supera 64 MB.'; }).join(' ');
                errorEl.classList.remove('d-none');
                submitBtn.disabled = true;
                this.value = '';
            } else {
                errorEl.classList.add('d-none');
                submitBtn.disabled = false;
            }
        });
    }

    // Upload múltiple AJAX
    var uploadModalForm = document.getElementById('uploadModalForm');
    if (uploadModalForm) {
        uploadModalForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            var files    = mediaIndexFile.files;
            var folder   = document.getElementById('uploadModalFolder').value;
            var submitBtn = document.getElementById('uploadModalBtn');
            var progress  = document.getElementById('uploadModalProgress');
            var bar       = document.getElementById('uploadModalBar');
            var status    = document.getElementById('uploadModalStatus');
            var csrf      = document.querySelector('meta[name="csrf-token"]').content;

            if (!files.length) return;

            submitBtn.disabled = true;
            progress.classList.remove('d-none');

            var total = files.length;
            var failed = [];

            for (var i = 0; i < total; i++) {
                status.textContent = 'Subiendo ' + (i + 1) + ' de ' + total + ': ' + files[i].name;
                bar.style.width = Math.round((i / total) * 100) + '%';

                var formData = new FormData();
                formData.append('file', files[i]);
                formData.append('folder', folder);
                formData.append('_token', csrf);

                try {
                    var res = await fetch('{{ route('admin.media.store') }}', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: formData,
                    });
                    var data = await res.json();
                    if (!data.success) failed.push(files[i].name);
                } catch (err) {
                    failed.push(files[i].name);
                }
            }

            bar.style.width = '100%';
            if (failed.length) {
                status.textContent = 'Error al subir: ' + failed.join(', ');
                status.classList.add('text-danger');
            } else {
                status.textContent = total === 1 ? 'Archivo subido.' : total + ' archivos subidos correctamente.';
            }

            submitBtn.disabled = false;
            setTimeout(function () { window.location.reload(); }, 800);
        });
    }
});
</script>
@endpush

@endsection
