<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Hamilton Beach Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body class="hb-admin-body">

<div class="hb-admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="hb-admin-sidebar" id="adminSidebar">
        <div class="hb-admin-sidebar__brand">
            <span class="hb-admin-brand-logo">
                <span class="fw-bold text-white" style="font-size:1.05rem;letter-spacing:.02em">Hamilton Beach</span>
                <span class="text-white ms-1 small opacity-50">Admin</span>
            </span>
            <button class="hb-admin-sidebar__close d-lg-none" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <nav class="hb-admin-nav">
            <div class="hb-admin-nav__label">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>

            <div class="hb-admin-nav__label mt-3">Catálogo</div>
            <a href="{{ route('admin.products.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> <span>Productos</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> <span>Categorías</span>
            </a>

            <div class="hb-admin-nav__label mt-3">Contenido</div>
            <a href="{{ route('admin.announcements.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> <span>Anuncios</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="bi bi-layout-text-window"></i> <span>Páginas</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> <span>Preguntas frecuentes</span>
            </a>
            <a href="{{ route('admin.help-center.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.help-center.*') ? 'active' : '' }}">
                <i class="bi bi-life-preserver"></i> <span>Centro de ayuda</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="bi bi-image"></i> <span>Banners</span>
            </a>
            <a href="{{ route('admin.media.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i> <span>Biblioteca de medios</span>
            </a>

            <div class="hb-admin-nav__label mt-3">Consultas</div>
            <a href="{{ route('admin.contacts.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="bi bi-envelope"></i> <span>Mensajes de contacto</span>
            </a>

            @if(auth()->user()->hasRole('admin'))
            <div class="hb-admin-nav__label mt-3">Configuración</div>
            <a href="{{ route('admin.settings.general') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> <span>Configuraciones</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="hb-admin-nav__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Usuarios</span>
            </a>
            @endif

            <div class="mt-3 mb-2">
                <a href="{{ route('frontend.home') }}" class="hb-admin-nav__item" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> <span>Ver sitio web</span>
                </a>
            </div>
        </nav>
    </aside>

    {{-- MAIN --}}
    <main class="hb-admin-main">
        <header class="hb-admin-topbar">
            <button class="hb-admin-topbar__toggle" id="openSidebar">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="hb-admin-topbar__title">@yield('title', 'Panel Admin')</h1>
            <div class="hb-admin-topbar__right">
                <span class="small text-muted me-3 d-none d-md-inline">{{ auth()->user()->name }}</span>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </button>
                </form>
            </div>
        </header>

        <div class="hb-admin-content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.ADMIN_MEDIA_PICKER_URL = '{{ route('admin.media.picker') }}';</script>
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
<script>
document.addEventListener('hidden.bs.modal', function () {
    if (!document.querySelector('.modal.show')) {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    }
});
</script>
@stack('scripts')
</body>
</html>
