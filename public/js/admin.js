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

  function openMediaPicker(type, cb) {
    var modalEl = document.getElementById('mediaPickerModal');
    if (!modalEl) return;
    if (!mediaPickerModal) {
      mediaPickerModal = new bootstrap.Modal(modalEl);
    }
    loadMediaGrid(type || 'image', cb);
    mediaPickerModal.show();
  }

  function loadMediaGrid(type, cb) {
    var grid = document.getElementById('mediaPickerGrid');
    var url  = window.ADMIN_MEDIA_PICKER_URL;
    if (!grid || !url) return;

    grid.innerHTML = '<div class="col-12 text-center py-4 text-muted"><i class="bi bi-arrow-repeat"></i> Cargando...</div>';

    fetch(url + '?type=' + (type || 'image'))
      .then(function (r) { return r.json(); })
      .then(function (items) {
        if (!items.length) {
          grid.innerHTML = '<div class="col-12 text-center py-4 text-muted">No hay archivos</div>';
          return;
        }
        grid.innerHTML = '';
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
            if (cb) {
              cb(this.dataset.url, this.dataset.id);
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
      })
      .catch(function () {
        grid.innerHTML = '<div class="col-12 text-center py-4 text-danger">Error al cargar archivos</div>';
      });
  }

  // Botones que abren el media picker
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
