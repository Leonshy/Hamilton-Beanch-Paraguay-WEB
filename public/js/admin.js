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
            '<div class="hb-media-grid-item" data-url="' + item.value + '" data-id="' + item.id + '">' +
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
              var inp = document.getElementById(currentTarget);
              if (inp) inp.value = this.dataset.id;
              if (currentPreview) {
                var prev = document.getElementById(currentPreview);
                if (prev) {
                  if (prev.tagName === 'IMG') {
                    prev.src = this.dataset.url;
                  } else {
                    var img = document.createElement('img');
                    img.src = this.dataset.url;
                    img.id = currentPreview;
                    img.className = 'img-fluid rounded mb-2 w-100';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
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
      currentTarget  = this.dataset.target;
      currentPreview = this.dataset.preview || null;
      openMediaPicker('image', null);
    });
  });

  // Upload desde modal picker
  var uploadForm = document.getElementById('mediaPickerUploadForm');
  if (uploadForm) {
    uploadForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var submitBtn = uploadForm.querySelector('[type="submit"]');
      var fileInput = document.getElementById('mediaPickerUploadFile');
      if (!fileInput || !fileInput.files.length) return;

      submitBtn.disabled = true;
      submitBtn.textContent = 'Subiendo...';

      var formData = new FormData(uploadForm);
      fetch('/admin/media', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: formData,
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success) {
            fileInput.value = '';
            var type = document.getElementById('mediaPickerType')
              ? document.getElementById('mediaPickerType').value
              : 'image';
            loadMediaGrid(type, null);
          } else {
            alert('Error al subir el archivo.');
          }
        })
        .catch(function () {
          alert('Error al subir el archivo. Verificá el tamaño o formato.');
        })
        .finally(function () {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Subir';
        });
    });
  }

})();
