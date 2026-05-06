<!-- Banner superior negro (marquee) -->
<div class="bg-gray-900 text-white py-2 overflow-hidden text-xs font-medium tracking-wide">
    <div class="marquee-track whitespace-nowrap">
        <span class="inline-block px-8">DISTRIBUIDOR OFICIAL HAMILTON BEACH® EN PARAGUAY</span>
        <span class="inline-block text-brand-muted px-2">|</span>
        <span class="inline-block px-8">GARANTÍA 1 AÑO CON SERVICIO TÉCNICO OFICIAL</span>
        <span class="inline-block text-brand-muted px-2">|</span>
        <span class="inline-block px-8">DISTRIBUIDOR OFICIAL HAMILTON BEACH® EN PARAGUAY</span>
        <span class="inline-block text-brand-muted px-2">|</span>
        <span class="inline-block px-8">GARANTÍA 1 AÑO CON SERVICIO TÉCNICO OFICIAL</span>
        <span class="inline-block text-brand-muted px-2">|</span>
        <span class="inline-block px-8">DISTRIBUIDOR OFICIAL HAMILTON BEACH® EN PARAGUAY</span>
        <span class="inline-block text-brand-muted px-2">|</span>
        <span class="inline-block px-8">GARANTÍA 1 AÑO CON SERVICIO TÉCNICO OFICIAL</span>
    </div>
</div>

<!-- Fila: Logo + Contacto -->
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-8">

            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img src="/images/logo.webp" alt="Hamilton Beach" class="h-6 w-auto">
            </a>

            <!-- Datos de contacto -->
            <div class="hidden md:flex items-center gap-6 text-sm text-gray-600">
                <a href="tel:+595911234567" class="flex items-center gap-2 hover:text-brand transition">
                    <svg class="w-4 h-4 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    +595 (9) 1234-567
                </a>
                <a href="mailto:info@hamiltonbeach.com.py" class="flex items-center gap-2 hover:text-brand transition">
                    <svg class="w-4 h-4 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    info@hamiltonbeach.com.py
                </a>
            </div>

            <!-- Hamburguesa mobile -->
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded text-gray-700 hover:text-brand hover:bg-gray-100 transition" aria-label="Menú">
                <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Fila de navegación + buscador (sticky, desktop) -->
<nav class="bg-white border-b-2 border-brand sticky top-0 z-50 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-11 gap-2">

            <!-- Links de navegación -->
            <a href="/" class="px-4 h-full flex items-center text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-brand transition whitespace-nowrap">
                Inicio
            </a>

            <!-- Dropdown Productos -->
            <div class="relative group h-full flex items-center">
                <button class="px-4 h-full flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-brand transition whitespace-nowrap">
                    Productos
                    <svg class="w-3.5 h-3.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="absolute left-0 top-full w-52 bg-white border border-gray-200 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50">
                    <a href="/productos" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-light hover:text-brand font-semibold border-b border-gray-100">Ver todos los productos</a>
                    <a href="/productos?categoria=cafeteras" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-light hover:text-brand">Cafeteras</a>
                    <a href="/productos?categoria=tostadoras" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-light hover:text-brand">Tostadoras</a>
                    <a href="/productos?categoria=molinillos" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-light hover:text-brand">Molinillo de café</a>
                    <a href="/productos?categoria=pavas" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-light hover:text-brand">Pavas eléctricas</a>
                </div>
            </div>

            <a href="/centro-ayuda" class="px-4 h-full flex items-center text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-brand transition whitespace-nowrap">
                Centro de ayuda
            </a>

            <a href="/contacto" class="px-4 h-full flex items-center text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-brand transition whitespace-nowrap">
                Contacto
            </a>

            <!-- Buscador (empuja al final) -->
            <div class="ml-auto flex items-center">
                <form action="/productos" method="GET" class="relative">
                    <input
                        type="text"
                        name="q"
                        placeholder="¿Qué estás buscando?"
                        class="w-56 border border-gray-300 rounded-lg pl-3 pr-9 py-1.5 text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent bg-gray-50"
                    >
                    <button type="submit" class="absolute right-0 top-0 h-full px-2.5 flex items-center text-gray-400 hover:text-brand transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>

<!-- Menu Mobile -->
<div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white z-40 relative">
    <div class="px-4 py-3 space-y-1">
        <!-- Buscador mobile -->
        <form action="/productos" method="GET" class="relative mb-3">
            <input type="text" name="q" placeholder="¿Qué estás buscando?"
                   class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand bg-gray-50">
            <button type="submit" class="absolute right-0 top-0 h-full px-3 flex items-center text-gray-400 hover:text-brand transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
        <a href="/" class="block py-2 text-sm font-medium text-gray-700 hover:text-brand">Inicio</a>
        <div class="py-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Productos</p>
            <a href="/productos" class="block py-1.5 pl-3 text-sm text-gray-700 hover:text-brand">Ver todos</a>
            <a href="/productos?categoria=cafeteras" class="block py-1.5 pl-3 text-sm text-gray-700 hover:text-brand">Cafeteras</a>
            <a href="/productos?categoria=tostadoras" class="block py-1.5 pl-3 text-sm text-gray-700 hover:text-brand">Tostadoras</a>
            <a href="/productos?categoria=molinillos" class="block py-1.5 pl-3 text-sm text-gray-700 hover:text-brand">Molinillo de café</a>
            <a href="/productos?categoria=pavas" class="block py-1.5 pl-3 text-sm text-gray-700 hover:text-brand">Pavas eléctricas</a>
        </div>
        <a href="/centro-ayuda" class="block py-2 text-sm font-medium text-gray-700 hover:text-brand">Centro de ayuda</a>
        <a href="/contacto" class="block py-2 text-sm font-medium text-gray-700 hover:text-brand">Contacto</a>
        <!-- Contacto mobile -->
        <div class="pt-3 border-t border-gray-100 space-y-2">
            <a href="tel:+595911234567" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                +595 (9) 1234-567
            </a>
            <a href="mailto:info@hamiltonbeach.com.py" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                info@hamiltonbeach.com.py
            </a>
        </div>
    </div>
</div>

<style>
.marquee-track {
    display: inline-block;
    animation: marquee 28s linear infinite;
}
@keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>

<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });
</script>
