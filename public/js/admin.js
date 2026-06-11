/**
 * Hamilton Beach Paraguay — Admin JS
 */
(function () {
  'use strict';

  // ── Sidebar toggle (mobile) ──
  var openBtn  = document.getElementById('openSidebar');
  var closeBtn = document.getElementById('closeSidebar');
  var sidebar  = document.getElementById('adminSidebar');

  if (openBtn && sidebar) {
    openBtn.addEventListener('click', function () { sidebar.classList.toggle('open'); });
  }
  if (closeBtn && sidebar) {
    closeBtn.addEventListener('click', function () { sidebar.classList.remove('open'); });
  }

  // ── Slug autogenerado desde el título ──
  var titleInput = document.getElementById('title');
  var slugInput  = document.getElementById('slug');

  if (titleInput && slugInput && !slugInput.value) {
    titleInput.addEventListener('input', function () {
      if (!slugInput.dataset.manual) {
        slugInput.value = titleInput.value
          .toLowerCase().trim()
          .replace(/[^\w\s-]/g, '')
          .replace(/[\s_-]+/g, '-')
          .replace(/^-+|-+$/g, '');
      }
    });
    slugInput.addEventListener('input', function () { slugInput.dataset.manual = '1'; });
  }

  // ── TinyMCE init ──
  if (typeof tinymce !== 'undefined') {
    tinymce.init({
      selector: '.hb-wysiwyg',
      license_key: 'gpl',
      height: 450,
      plugins: 'anchor autolink charmap codesample image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | link image table | align | numlist bullist indent outdent | removeformat',
      content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 15px; color: #1a2a3a; line-height: 1.6; }',
      automatic_uploads: true,
      images_upload_handler: function (blobInfo) {
        return new Promise(function (resolve, reject) {
          var formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          formData.append('folder', 'editor');
          var csrf = document.querySelector('meta[name="csrf-token"]');
          fetch('/admin/media', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrf ? csrf.content : '',
              'Accept': 'application/json',
            },
            body: formData,
          })
            .then(function (r) { return r.json(); })
            .then(function (data) {
              if (data.success) { resolve(data.media.url); }
              else { reject('Error al subir imagen'); }
            })
            .catch(function () { reject('Error de red al subir imagen'); });
        });
      },
      file_picker_callback: function (cb, value, meta) {
        if (meta.filetype === 'image' && window.ADMIN_MEDIA_PICKER_URL) {
          openMediaPicker('image', function (url) { cb(url); });
        }
      },
    });
  }

  // ── Media Picker Modal ──
  var mediaPickerModal = null;
  var currentTarget    = null;
  var currentPreview   = null;
  var currentPickerBtn = null;

  // ── Gallery multi-select state ──
  var galleryMode      = false;
  var gallerySelection = []; // [{id, url}]

  function openMediaPicker(type, cb) {
    var modalEl = document.getElementById('mediaPickerModal');
    if (!modalEl) return;
    if (!mediaPickerModal) {
      mediaPickerModal = new bootstrap.Modal(modalEl);
    }
    loadMediaGrid(type || 'image', cb);
    mediaPickerModal.show();
  }

  function openGalleryPicker() {
    var modalEl = document.getElementById('mediaPickerModal');
    if (!modalEl) return;
    if (!mediaPickerModal) {
      mediaPickerModal = new bootstrap.Modal(modalEl);
    }

    // Reconstruir selección actual desde el DOM de preview
    gallerySelection = [];
    document.querySelectorAll('#galleryPreview .gallery-thumb-wrap').forEach(function (el) {
      gallerySelection.push({ id: el.dataset.id, url: el.dataset.url });
    });

    galleryMode = true;
    updateConfirmBtn();
    loadMediaGrid('image', null);
    mediaPickerModal.show();
  }

  function updateConfirmBtn() {
    var btn = document.getElementById('galleryConfirmBtn');
    if (!btn) return;
    btn.classList.toggle('d-none', !galleryMode);
    btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Confirmar selección (' + gallerySelection.length + ')';
  }

  function updateGalleryBadges() {
    document.querySelectorAll('#mediaPickerGrid .hb-media-grid-item').forEach(function (item) {
      var idx = gallerySelection.findIndex(function (s) { return s.id === item.dataset.id; });
      var badge = item.querySelector('.gallery-order-badge');
      if (idx >= 0) {
        if (!badge) {
          badge = document.createElement('span');
          badge.className = 'gallery-order-badge';
          item.appendChild(badge);
        }
        badge.textContent = idx + 1;
        item.classList.add('gallery-selected');
      } else {
        if (badge) badge.remove();
        item.classList.remove('gallery-selected');
      }
    });
    updateConfirmBtn();
  }

  function confirmGallerySelection() {
    var inputsEl  = document.getElementById('galleryInputs');
    var previewEl = document.getElementById('galleryPreview');
    var emptyEl   = document.getElementById('galleryEmpty');

    if (inputsEl) {
      inputsEl.innerHTML = '';
      gallerySelection.forEach(function (item) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'gallery_ids[]';
        inp.value = item.id;
        inputsEl.appendChild(inp);
      });
    }

    if (previewEl) {
      previewEl.innerHTML = '';
      gallerySelection.forEach(function (item, idx) {
        var wrap = document.createElement('div');
        wrap.className = 'col-4 gallery-thumb-wrap';
        wrap.dataset.id  = item.id;
        wrap.dataset.url = item.url;
        wrap.innerHTML =
          '<div class="position-relative">' +
          '<img src="' + item.url + '" alt="" class="w-100 rounded" style="height:70px;object-fit:cover">' +
          '<span class="gallery-order-badge">' + (idx + 1) + '</span>' +
          '</div>';
        previewEl.appendChild(wrap);
      });
    }

    if (emptyEl) emptyEl.remove();
    if (!gallerySelection.length) {
      var p = document.createElement('p');
      p.className = 'text-muted small mb-0';
      p.id = 'galleryEmpty';
      p.textContent = 'Ninguna imagen seleccionada aún. Hacé clic en "Seleccionar imágenes".';
      if (inputsEl) inputsEl.insertAdjacentElement('afterend', p);
    }

    galleryMode = false;
    updateConfirmBtn();
    if (mediaPickerModal) mediaPickerModal.hide();
  }

  // Reset gallery mode cuando se cierra el modal
  var _modalEl = document.getElementById('mediaPickerModal');
  if (_modalEl) {
    _modalEl.addEventListener('hidden.bs.modal', function () {
      if (galleryMode) {
        galleryMode = false;
        updateConfirmBtn();
      }
    });
  }

  // Botón confirmar galería
  var galleryConfirmBtn = document.getElementById('galleryConfirmBtn');
  if (galleryConfirmBtn) {
    galleryConfirmBtn.addEventListener('click', confirmGallerySelection);
  }

  // Botón abrir galería
  var galleryPickerBtn = document.getElementById('galleryPickerBtn');
  if (galleryPickerBtn) {
    galleryPickerBtn.addEventListener('click', openGalleryPicker);
  }

  var currentMediaType = 'image';
  var currentMediaCb   = null;

  function loadMediaGrid(type, cb) {
    currentMediaType = type || 'image';
    currentMediaCb   = cb;
    var typeSelect = document.getElementById('mediaPickerType');
    if (typeSelect) typeSelect.value = currentMediaType;
    var grid = document.getElementById('mediaPickerGrid');
    if (!grid) return;
    grid.innerHTML = '<div class="col-12 text-center py-4 text-muted"><i class="bi bi-arrow-repeat"></i> Cargando...</div>';
    appendMediaPage(1, true);
  }

  function appendMediaPage(page, replace) {
    var grid   = document.getElementById('mediaPickerGrid');
    var url    = window.ADMIN_MEDIA_PICKER_URL;
    var search = (document.getElementById('mediaPickerSearch') || {}).value || '';
    if (!grid || !url) return;

    fetch(url + '?type=' + currentMediaType + '&page=' + page + '&search=' + encodeURIComponent(search))
      .then(function (r) { return r.json(); })
      .then(function (data) {
        // Eliminar botón "cargar más" anterior si existe
        var oldBtn = grid.querySelector('.hb-load-more');
        if (oldBtn) oldBtn.parentNode.remove();

        if (replace) {
          // Limpiar todo excepto el spinner (que ya no existe a este punto)
          grid.innerHTML = '';
        }

        var items = data.items || [];

        if (replace && !items.length) {
          grid.innerHTML = '<div class="col-12 text-center py-4 text-muted">No hay archivos</div>';
          return;
        }

        items.forEach(function (item) {
          var col = document.createElement('div');
          col.className = 'col-6 col-md-3 col-lg-2';
          col.innerHTML =
            '<div class="hb-media-grid-item" data-url="' + item.value + '" data-id="' + item.id + '" data-title="' + item.title + '">' +
            (item.thumb
              ? '<img src="' + item.thumb + '" alt="">'
              : '<div class="hb-media-grid-item__doc"><i class="bi bi-file-earmark"></i></div>') +
            '<div class="hb-media-grid-item__name">' + item.title + '</div>' +
            '</div>';

          col.querySelector('.hb-media-grid-item').addEventListener('click', function () {
            if (galleryMode) {
              var id  = this.dataset.id;
              var idx = gallerySelection.findIndex(function (s) { return s.id === id; });
              if (idx >= 0) {
                gallerySelection.splice(idx, 1);
              } else {
                gallerySelection.push({ id: id, url: this.dataset.url });
              }
              updateGalleryBadges();
              return;
            }

            if (currentMediaCb) {
              currentMediaCb(this.dataset.url, this.dataset.id);
            } else if (currentTarget) {
              var storeUrl  = currentPickerBtn && currentPickerBtn.dataset.storeUrl;
              var urlPreviewId = currentPickerBtn && currentPickerBtn.dataset.urlPreview;
              var inp = document.getElementById(currentTarget);
              if (inp) inp.value = storeUrl ? this.dataset.url : this.dataset.id;

              // Preview de documento (PDF)
              if (urlPreviewId) {
                var urlPrev = document.getElementById(urlPreviewId);
                if (urlPrev) {
                  var fileUrl  = this.dataset.url;
                  var fileName = this.dataset.title || fileUrl.split('/').pop();
                  urlPrev.innerHTML =
                    '<div class="d-flex align-items-center gap-2 p-2 bg-light rounded">' +
                    '<i class="bi bi-file-earmark-pdf text-danger fs-5"></i>' +
                    '<a href="' + fileUrl + '" target="_blank" class="small text-truncate flex-grow-1">' + fileName + '</a>' +
                    '<button type="button" class="btn btn-sm btn-link text-danger p-0" id="attachmentClear">' +
                    '<i class="bi bi-x-lg"></i></button></div>';
                  setupAttachmentClear();
                }
              } else if (currentPreview) {
                // Preview de imagen (comportamiento original)
                var prev = document.getElementById(currentPreview);
                if (prev) {
                  var fit    = currentPickerBtn ? (currentPickerBtn.dataset.previewFit    || 'cover')  : 'cover';
                  var height = currentPickerBtn ? (currentPickerBtn.dataset.previewHeight || '120px') : '120px';
                  var bg     = currentPickerBtn ? (currentPickerBtn.dataset.previewBg     || '')       : '';
                  if (prev.tagName === 'IMG') {
                    prev.src = this.dataset.url;
                    prev.style.objectFit = fit;
                    prev.style.maxHeight = height;
                    if (bg) prev.style.background = bg;
                  } else {
                    var img = document.createElement('img');
                    img.src = this.dataset.url;
                    img.id = currentPreview;
                    img.className = 'img-fluid rounded mb-2 w-100';
                    img.style.maxHeight = height;
                    img.style.objectFit = fit;
                    img.style.display = 'block';
                    img.style.margin = '0 auto';
                    if (bg) img.style.background = bg;
                    prev.replaceWith(img);
                  }
                }
              }
            }
            if (mediaPickerModal) mediaPickerModal.hide();
          });
          grid.appendChild(col);
        });

        // Botón "Cargar más" si hay más páginas
        if (data.has_more) {
          var loadMoreCol = document.createElement('div');
          loadMoreCol.className = 'col-12 text-center mt-2';
          loadMoreCol.innerHTML =
            '<button type="button" class="btn btn-outline-secondary btn-sm hb-load-more">' +
            '<i class="bi bi-arrow-down me-1"></i>Cargar más (' + data.total + ' en total)' +
            '</button>';
          loadMoreCol.querySelector('.hb-load-more').addEventListener('click', function () {
            appendMediaPage(data.next_page, false);
          });
          grid.appendChild(loadMoreCol);
        }

        if (galleryMode) updateGalleryBadges();
      })
      .catch(function () {
        var grid = document.getElementById('mediaPickerGrid');
        if (grid) grid.innerHTML = '<div class="col-12 text-center py-4 text-danger">Error al cargar archivos</div>';
      });
  }

  // Búsqueda y filtro de tipo en el modal
  var mediaSearchBtn = document.getElementById('mediaPickerSearchBtn');
  var mediaSearchInput = document.getElementById('mediaPickerSearch');
  var mediaTypeSelect  = document.getElementById('mediaPickerType');

  if (mediaSearchBtn) {
    mediaSearchBtn.addEventListener('click', function () {
      appendMediaPage(1, true);
    });
  }
  if (mediaSearchInput) {
    mediaSearchInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); appendMediaPage(1, true); }
    });
  }
  if (mediaTypeSelect) {
    mediaTypeSelect.addEventListener('change', function () {
      currentMediaType = this.value;
      appendMediaPage(1, true);
    });
  }

  // Botones que abren el media picker (modo single)
  document.querySelectorAll('.hb-media-picker-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      currentTarget    = this.dataset.target;
      currentPreview   = this.dataset.preview || null;
      currentPickerBtn = this;
      var type = this.dataset.type || 'image';
      openMediaPicker(type, null);
    });
  });

  // Limpiar attachment
  function setupAttachmentClear() {
    var clearBtn = document.getElementById('attachmentClear');
    if (!clearBtn) return;
    clearBtn.addEventListener('click', function () {
      var inp = document.getElementById('attachmentUrl');
      if (inp) inp.value = '';
      var preview = document.getElementById('attachmentPreview');
      if (preview) preview.innerHTML = '';
    });
  }
  setupAttachmentClear();

  // Upload desde modal picker
  var MAX_FILE_SIZE = 64 * 1024 * 1024; // 64 MB en bytes

  var uploadFileInput = document.getElementById('mediaPickerUploadFile');
  if (uploadFileInput) {
    uploadFileInput.addEventListener('change', function () {
      var errorEl = document.getElementById('mediaUploadSizeError');
      if (!errorEl) {
        errorEl = document.createElement('div');
        errorEl.id = 'mediaUploadSizeError';
        errorEl.className = 'text-danger small mt-1';
        this.parentNode.insertBefore(errorEl, this.nextSibling);
      }
      var oversized = Array.from(this.files).filter(function (f) { return f.size > MAX_FILE_SIZE; });
      if (oversized.length) {
        errorEl.textContent = oversized.map(function (f) { return '"' + f.name + '" supera 64 MB.'; }).join(' ');
        this.value = '';
      } else {
        errorEl.textContent = '';
      }
    });
  }

  var uploadForm = document.getElementById('mediaPickerUploadForm');
  if (uploadForm) {
    uploadForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      var submitBtn = uploadForm.querySelector('[type="submit"]');
      var fileInput = document.getElementById('mediaPickerUploadFile');
      var statusEl = document.getElementById('mediaPickerUploadStatus');
      if (!fileInput || !fileInput.files.length) return;

      var files = fileInput.files;
      var oversized = Array.from(files).filter(function (f) { return f.size > MAX_FILE_SIZE; });
      if (oversized.length) {
        var errorEl = document.getElementById('mediaUploadSizeError');
        if (errorEl) errorEl.textContent = oversized.map(function (f) { return '"' + f.name + '" supera 64 MB.'; }).join(' ');
        return;
      }

      var csrf = document.querySelector('meta[name="csrf-token"]').content;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Subiendo...';
      if (statusEl) { statusEl.textContent = ''; statusEl.classList.remove('d-none', 'text-danger'); }

      var total = files.length;
      var failed = [];

      for (var i = 0; i < total; i++) {
        if (statusEl) { statusEl.classList.remove('d-none'); statusEl.textContent = 'Subiendo ' + (i + 1) + ' de ' + total + ': ' + files[i].name; }
        var formData = new FormData();
        formData.append('file', files[i]);
        formData.append('_token', csrf);
        try {
          var res = await fetch('/admin/media', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: formData,
          });
          var data = await res.json();
          if (!data.success) failed.push(files[i].name);
        } catch (err) {
          failed.push(files[i].name);
        }
      }

      fileInput.value = '';
      if (statusEl) {
        statusEl.classList.remove('d-none');
        if (failed.length) {
          statusEl.classList.add('text-danger');
          statusEl.textContent = 'Error al subir: ' + failed.join(', ');
        } else {
          statusEl.textContent = total === 1 ? 'Archivo subido.' : total + ' archivos subidos.';
        }
      }

      submitBtn.disabled = false;
      submitBtn.textContent = 'Subir';

      var type = document.getElementById('mediaPickerType')
        ? document.getElementById('mediaPickerType').value
        : 'image';
      loadMediaGrid(type, null);
    });
  }

})();

// ── Validación de formularios admin ──────────────────────────────────────────
(function () {
  'use strict';

  var MSGS = {
    required   : 'Este campo es requerido.',
    url        : 'Ingresá una URL válida (https://ejemplo.com o /pagina).',
    email      : 'Ingresá un email válido.',
    minlength  : function (n) { return 'Mínimo ' + n + ' caracteres.'; },
    min        : function (n) { return 'El valor mínimo es ' + n + '.'; },
    max        : function (n) { return 'El valor máximo es ' + n + '.'; },
    pattern    : 'Formato inválido.',
    match      : 'Las contraseñas no coinciden.',
  };

  // Formularios que se excluyen de la validación (son AJAX de subida de archivos)
  var SKIP_FORMS = ['mediaPickerUploadForm', 'uploadModalForm'];

  function isValidUrl(val) {
    if (val.startsWith('/')) return true;
    try {
      var u = new URL(val);
      return u.protocol === 'http:' || u.protocol === 'https:';
    } catch (e) { return false; }
  }

  function isValidEmail(val) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
  }

  function getErrorEl(el) {
    // Buscar contenedor padre directo que tenga .hb-field-error
    var parent = el.closest('.mb-3') || el.parentNode;
    return parent.querySelector('.hb-field-error');
  }

  function showError(el, msg) {
    el.classList.add('is-invalid');
    var parent = el.closest('.mb-3') || el.parentNode;
    var err = parent.querySelector('.hb-field-error');
    if (!err) {
      err = document.createElement('div');
      err.className = 'invalid-feedback hb-field-error d-block';
      // Insertarlo justo después del campo
      el.parentNode.insertBefore(err, el.nextSibling);
    }
    err.textContent = msg;
  }

  function clearError(el) {
    el.classList.remove('is-invalid');
    var parent = el.closest('.mb-3') || el.parentNode;
    var err = parent.querySelector('.hb-field-error');
    if (err) { err.textContent = ''; }
  }

  function validateField(el) {
    var val     = (el.value || '').trim();
    var type    = el.getAttribute('data-type') || el.type;
    var isEmpty = val === '';

    // Required
    if (el.hasAttribute('required') && isEmpty) {
      showError(el, MSGS.required);
      return false;
    }

    // Si está vacío y no es required → válido
    if (isEmpty) { clearError(el); return true; }

    // URL (type="url" o data-type="url")
    if (type === 'url') {
      if (!isValidUrl(val)) { showError(el, MSGS.url); return false; }
    }

    // Email
    if (type === 'email') {
      if (!isValidEmail(val)) { showError(el, MSGS.email); return false; }
    }

    // minlength
    var minLen = el.getAttribute('minlength');
    if (minLen && val.length < +minLen) {
      showError(el, MSGS.minlength(minLen)); return false;
    }

    // Número: min / max
    if (el.type === 'number') {
      var num    = parseFloat(val);
      var minVal = el.getAttribute('min');
      var maxVal = el.getAttribute('max');
      if (minVal !== null && minVal !== '' && num < +minVal) {
        showError(el, MSGS.min(minVal)); return false;
      }
      if (maxVal !== null && maxVal !== '' && num > +maxVal) {
        showError(el, MSGS.max(maxVal)); return false;
      }
    }

    // Pattern
    var pat = el.getAttribute('pattern');
    if (pat && val) {
      try {
        if (!new RegExp('^(?:' + pat + ')$').test(val)) {
          showError(el, el.getAttribute('data-pattern-msg') || MSGS.pattern);
          return false;
        }
      } catch (e) { /* pattern inválida, ignorar */ }
    }

    // data-match (confirmación de contraseña)
    var matchId = el.getAttribute('data-match');
    if (matchId) {
      var target = document.getElementById(matchId);
      if (target && val !== target.value) {
        showError(el, MSGS.match); return false;
      }
    }

    clearError(el);
    return true;
  }

  // Selector de campos a validar (excluye hidden, checkbox, radio, file y textareas de TinyMCE)
  var FIELD_SEL = [
    'input:not([type=hidden]):not([type=checkbox]):not([type=radio]):not([type=file])',
    'textarea:not(.tox-textarea)',
    'select',
  ].join(',');

  // Deshabilitar validación nativa del browser en todos los forms admin
  // (usamos novalidate para controlar todo desde JS con mensajes en español)
  document.querySelectorAll('form').forEach(function (f) {
    f.setAttribute('novalidate', '');
  });

  // Intercepción del submit
  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.tagName.toLowerCase() !== 'form') return;
    if (SKIP_FORMS.indexOf(form.id) !== -1) return;

    var fields = form.querySelectorAll(FIELD_SEL);
    var valid  = true;
    fields.forEach(function (f) {
      if (!validateField(f)) valid = false;
    });

    if (!valid) {
      e.preventDefault();
      var first = form.querySelector('.is-invalid');
      if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }, true);

  // Validación en tiempo real al perder el foco
  document.addEventListener('blur', function (e) {
    var el = e.target;
    if (!el.matches || !el.matches(FIELD_SEL)) return;
    // No validar si el campo está dentro de un form excluido
    var form = el.closest('form');
    if (form && SKIP_FORMS.indexOf(form.id) !== -1) return;
    validateField(el);
  }, true);

})();

// ── Slug URL copy ────────────────────────────────────────────────────────────
function hbCopySlugUrl(btn) {
  var group  = btn.closest('.hb-slug-group');
  var prefix = group.querySelector('.input-group-text').title;
  var slug   = group.querySelector('input[name="slug"]').value.trim();
  var url    = prefix + slug;
  navigator.clipboard.writeText(url).then(function () {
    var icon = btn.querySelector('i');
    icon.className = 'bi bi-check2';
    setTimeout(function () { icon.className = 'bi bi-clipboard'; }, 2000);
  });
}
