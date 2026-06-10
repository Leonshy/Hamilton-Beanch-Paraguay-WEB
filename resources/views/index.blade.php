@extends('layouts.app')

@section('title', 'Hamilton Beach Paraguay - Electrodomésticos de calidad')

@section('content')

<!-- Hero Section — Banner imagen pura 16:9 -->
@if($banners->isNotEmpty())
<div class="relative w-full bg-brand-dark overflow-hidden" id="heroSlider"
     style="aspect-ratio: 1280/720;">

    @foreach($banners as $i => $b)
    <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
         data-slide="{{ $i }}">
        @if($b->image?->url)
            <div class="relative w-full h-full {{ $b->link_url ? 'cursor-pointer' : '' }}"
                 @if($b->link_url)
                 onclick="window.location.href='{{ $b->link_url }}'"
                 onmouseenter="this.querySelector('.hero-hover-overlay').style.opacity='1'"
                 onmouseleave="this.querySelector('.hero-hover-overlay').style.opacity='0'"
                 @endif>
                <img src="{{ $b->image->url }}"
                     alt="{{ $b->title ?? 'Hamilton Beach' }}"
                     class="w-full h-full object-cover">
                @if($b->link_url)
                <div class="hero-hover-overlay" style="position:absolute;inset:0;background:rgba(255,255,255,0.25);opacity:0;transition:opacity 0.3s;pointer-events:none;"></div>
                @endif
            </div>
        @else
            {{-- Placeholder cuando no hay imagen cargada --}}
            <div class="w-full h-full flex flex-col items-center justify-center bg-brand-dark gap-3">
                <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-white/30 text-sm">Banner 1280 × 720 — Subí una imagen desde el admin</p>
            </div>
        @endif
    </div>
    @endforeach

    {{-- Flechas (solo si hay más de un banner) --}}
    @if($banners->count() > 1)
    <div class="absolute inset-0 z-20 hidden md:flex items-center justify-between px-4 pointer-events-none">
        <button id="heroPrev" aria-label="Anterior"
                class="pointer-events-auto w-10 h-10 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center transition">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button id="heroNext" aria-label="Siguiente"
                class="pointer-events-auto w-10 h-10 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center transition">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Dots --}}
    <div class="absolute bottom-4 left-0 right-0 flex justify-center z-20">
        <div class="flex items-center gap-2 bg-black/30 backdrop-blur-sm px-3 py-1.5 rounded-full">
        @foreach($banners as $i => $b)
        <button class="hero-dot w-2.5 h-2.5 rounded-full transition {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}"
                data-dot="{{ $i }}"></button>
        @endforeach
        </div>
    </div>
    @endif
</div>

@php $heroInterval = (int)(\App\Models\SiteSetting::get('hero_slide_interval', 6)) * 1000; @endphp
<script>
(function () {
    var slider = document.getElementById('heroSlider');
    var slides = document.querySelectorAll('.hero-slide');
    var dots   = document.querySelectorAll('.hero-dot');
    if (!slider || slides.length <= 1) return;

    var current  = 0;
    var interval = {{ $heroInterval }};
    var timer;

    function goTo(n) {
        slides[current].classList.replace('opacity-100', 'opacity-0');
        slides[current].classList.add('pointer-events-none');
        if (dots[current]) dots[current].classList.replace('bg-white', 'bg-white/40');
        current = (n + slides.length) % slides.length;
        slides[current].classList.replace('opacity-0', 'opacity-100');
        slides[current].classList.remove('pointer-events-none');
        if (dots[current]) dots[current].classList.replace('bg-white/40', 'bg-white');
    }

    function startTimer() { timer = setInterval(function () { goTo(current + 1); }, interval); }
    function resetTimer()  { clearInterval(timer); startTimer(); }

    var prevBtn = document.getElementById('heroPrev');
    var nextBtn = document.getElementById('heroNext');
    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); resetTimer(); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); resetTimer(); });
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () { goTo(+this.dataset.dot); resetTimer(); });
    });

    // Swipe en mobile
    var touchX = 0;
    slider.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener('touchend', function (e) {
        var diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) { goTo(current + (diff > 0 ? 1 : -1)); resetTimer(); }
    }, { passive: true });

    startTimer();
})();
</script>
@endif

<!-- Trust badges -->
@php
    $homeFeatures = json_decode(\App\Models\SiteSetting::get('home_features', '[]'), true) ?? [];
@endphp
@if(count($homeFeatures))
<div class="bg-white border-b border-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($homeFeatures as $feature)
            @php $featureUrl = $feature['url'] ?? null; @endphp
            @if($featureUrl)
            <a href="{{ $featureUrl }}"
               class="flex items-center gap-4 rounded-xl transition"
               style="text-decoration:none;padding:0.75rem;margin:-0.75rem;"
               onmouseenter="this.style.background='#f0faf4'"
               onmouseleave="this.style.background='transparent'">
            @else
            <div class="flex items-center gap-4">
            @endif
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center flex-shrink-0"
                     style="transition:transform .2s;" {{ $featureUrl ? 'onmouseenter="this.style.transform=\'scale(1.1)\'" onmouseleave="this.style.transform=\'scale(1)\'"' : '' }}>
                    @include('partials.trust-icon', ['icon' => $feature['icon'] ?? 'shield'])
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $feature['title'] ?? '' }}</h3>
                    @if(!empty($feature['description']))
                    <p class="text-gray-500 text-sm mt-0.5">{{ $feature['description'] }}</p>
                    @endif
                </div>
            @if($featureUrl)
            </a>
            @else
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Productos Destacados -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Productos destacados</h2>
                <p class="text-gray-500 mt-1">Nuestra selección de electrodomésticos más populares</p>
            </div>
            <a href="/productos" class="hidden md:inline-flex items-center gap-1 text-brand hover:text-brand-dark font-semibold text-sm transition">
                Ver todos
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $producto)
            <a href="/productos/{{ $producto->slug }}"
               class="group bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
                <!-- Imagen -->
                <div class="bg-gray-50 aspect-square relative overflow-hidden">
                    <img src="{{ $producto->featuredImage?->url ?? '/images/products/cafetera-retro-black-1.webp' }}" alt="{{ $producto->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 left-3">
                        <span class="bg-white text-gray-600 text-xs font-medium px-2 py-1 rounded-full border border-gray-200 shadow-sm">
                            {{ $producto->category?->name ?? '' }}
                        </span>
                    </div>
                </div>
                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-semibold text-gray-900 group-hover:text-brand transition line-clamp-2 mb-3">
                        {{ $producto->title }}
                    </h3>
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Precio sugerido</p>
                        <p class="text-sm font-bold text-gray-800">≈ {{ $producto->formatted_price }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Ver puntos de venta</span>
                        <span class="text-brand group-hover:translate-x-1 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="/productos"
               class="inline-block border-2 border-brand text-brand px-10 py-3 rounded-lg font-bold hover:bg-brand hover:text-white transition">
                Ver todos los productos
            </a>
        </div>
    </div>
</div>

<!-- Banner Nuevos Ingresos 970×250 -->
@if($midBanners->isNotEmpty())
<div class="bg-gray-50" style="padding-bottom: 3rem;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative w-full overflow-hidden rounded-xl" id="midBannerSlider"
             style="aspect-ratio: 970/250; max-height: 250px;">

            @foreach($midBanners as $i => $b)
            <div class="mid-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                 data-slide="{{ $i }}">
                @if($b->image?->url)
                    <div class="relative w-full h-full {{ $b->link_url ? 'cursor-pointer' : '' }}"
                         @if($b->link_url)
                         onclick="window.location.href='{{ $b->link_url }}'"
                         onmouseenter="this.querySelector('.mid-hover-overlay').style.opacity='1'"
                         onmouseleave="this.querySelector('.mid-hover-overlay').style.opacity='0'"
                         @endif>
                        <img src="{{ $b->image->url }}"
                             alt="Nuevos Ingresos"
                             class="w-full h-full object-cover">
                        @if($b->link_url)
                        <div class="mid-hover-overlay" style="position:absolute;inset:0;background:rgba(255,255,255,0.25);opacity:0;transition:opacity 0.3s;pointer-events:none;"></div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-full bg-brand-dark flex items-center justify-center">
                        <p class="text-white/30 text-sm">Banner 970 × 250 — Subí una imagen desde el admin</p>
                    </div>
                @endif
            </div>
            @endforeach

            {{-- Flechas --}}
            @if($midBanners->count() > 1)
            <div class="absolute inset-0 z-20 hidden md:flex items-center justify-between px-3 pointer-events-none">
                <button id="midPrev" aria-label="Anterior"
                        class="pointer-events-auto w-8 h-8 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button id="midNext" aria-label="Siguiente"
                        class="pointer-events-auto w-8 h-8 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Dots --}}
            <div class="absolute bottom-3 left-0 right-0 flex justify-center z-20">
                <div class="flex items-center gap-2 bg-black/30 backdrop-blur-sm px-3 py-1 rounded-full">
                    @foreach($midBanners as $i => $b)
                    <button class="mid-dot w-2 h-2 rounded-full transition {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}"
                            data-dot="{{ $i }}"></button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@php $midInterval = (int)(\App\Models\SiteSetting::get('hero_slide_interval', 6)) * 1000; @endphp
<script>
(function () {
    var slider = document.getElementById('midBannerSlider');
    var slides = document.querySelectorAll('.mid-slide');
    var dots   = document.querySelectorAll('.mid-dot');
    if (!slider || slides.length <= 1) return;

    var current = 0;
    var timer;

    function goTo(n) {
        slides[current].classList.replace('opacity-100', 'opacity-0');
        slides[current].classList.add('pointer-events-none');
        if (dots[current]) dots[current].classList.replace('bg-white', 'bg-white/40');
        current = (n + slides.length) % slides.length;
        slides[current].classList.replace('opacity-0', 'opacity-100');
        slides[current].classList.remove('pointer-events-none');
        if (dots[current]) dots[current].classList.replace('bg-white/40', 'bg-white');
    }

    function startTimer() { timer = setInterval(function () { goTo(current + 1); }, {{ $midInterval }}); }
    function resetTimer()  { clearInterval(timer); startTimer(); }

    var prevBtn = document.getElementById('midPrev');
    var nextBtn = document.getElementById('midNext');
    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); resetTimer(); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); resetTimer(); });
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () { goTo(+this.dataset.dot); resetTimer(); });
    });

    var touchX = 0;
    slider.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener('touchend', function (e) {
        var diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) { goTo(current + (diff > 0 ? 1 : -1)); resetTimer(); }
    }, { passive: true });

    startTimer();
})();
</script>
@endif

<!-- Puntos de Venta -->
@if($salePoints->isNotEmpty())
<div id="puntos-de-venta" class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-2">¿Dónde comprar?</h2>
        <p class="text-gray-500 text-center mb-10">Encontrá nuestros productos en estos puntos de venta</p>

        <div class="relative" id="spSliderWrap" style="padding:0 2.5rem;">
            {{-- Overflow oculto solo en el track --}}
            <div id="spViewport" style="overflow:hidden;">
                <div id="spTrack" style="display:flex;gap:2rem;transition:transform 0.4s ease;will-change:transform;">
                    @foreach($salePoints as $sp)
                    <div class="sp-card" style="flex:0 0 calc((100% - 8rem) / 5);min-width:140px;">
                        @if($sp->url)
                        <a href="{{ $sp->url }}" target="_blank" rel="noopener"
                           style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;padding:1rem;text-decoration:none;transition:opacity .2s,transform .2s;"
                           onmouseenter="this.style.opacity='.75';this.style.transform='scale(1.04)'"
                           onmouseleave="this.style.opacity='1';this.style.transform='scale(1)'">
                        @else
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;padding:1rem;">
                        @endif
                            <div style="height:100px;display:flex;align-items:center;justify-content:center;">
                                @if($sp->logo)
                                    <img src="{{ $sp->logo->url }}" alt="{{ $sp->name }}"
                                         style="max-height:100px;max-width:160px;object-fit:contain;">
                                @else
                                    <div style="width:80px;height:80px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700;color:#6b7280;">
                                        {{ strtoupper(substr($sp->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <p style="font-size:.85rem;font-weight:600;color:#374151;text-align:center;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $sp->name }}</p>
                        @if($sp->url)
                        </a>
                        @else
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Flechas --}}
            <button id="spPrev" aria-label="Anterior"
                    style="position:absolute;top:50%;left:0;transform:translateY(-50%);z-index:10;width:2.5rem;height:2.5rem;border-radius:50%;background:white;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button id="spNext" aria-label="Siguiente"
                    style="position:absolute;top:50%;right:0;transform:translateY(-50%);z-index:10;width:2.5rem;height:2.5rem;border-radius:50%;background:white;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var track    = document.getElementById('spTrack');
    var viewport = document.getElementById('spViewport');
    var cards    = track.querySelectorAll('.sp-card');
    if (!track || cards.length === 0) return;

    var gap = 32;
    var visibleCount = window.innerWidth < 640 ? 2 : window.innerWidth < 1024 ? 3 : 5;
    var cardWidth    = (viewport.offsetWidth - (visibleCount - 1) * gap) / visibleCount;
    var step         = cardWidth + gap;
    var current      = 0;
    var max          = Math.max(0, cards.length - visibleCount);

    function updateCardWidths() {
        visibleCount = window.innerWidth < 640 ? 2 : window.innerWidth < 1024 ? 3 : 5;
        cardWidth    = (viewport.offsetWidth - (visibleCount - 1) * gap) / visibleCount;
        step         = cardWidth + gap;
        max          = Math.max(0, cards.length - visibleCount);
        cards.forEach(function (c) { c.style.flex = '0 0 ' + cardWidth + 'px'; });
        goTo(Math.min(current, max));
    }

    function goTo(n) {
        current = ((n % (max + 1)) + (max + 1)) % (max + 1);
        track.style.transform = 'translateX(-' + (current * step) + 'px)';
    }

    var paused = false;
    var timer;

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(function () {
            if (!paused) goTo(current + 1);
        }, 3000);
    }

    document.getElementById('spPrev').addEventListener('click', function () { goTo(current - 1); startTimer(); });
    document.getElementById('spNext').addEventListener('click', function () { goTo(current + 1); startTimer(); });

    viewport.addEventListener('mouseenter', function () { paused = true; });
    viewport.addEventListener('mouseleave', function () { paused = false; });

    window.addEventListener('resize', updateCardWidths);
    updateCardWidths();
    startTimer();
})();
</script>
@endif

<!-- Categorías -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-10">Explorar por categoría</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
            <a href="/productos?categoria={{ $cat->slug }}"
               class="group bg-gray-50 hover:bg-brand-light border border-gray-200 hover:border-brand-muted rounded-xl p-6 text-center transition flex flex-col items-center">
                <div class="flex items-center justify-center h-24 mb-3">
                    @if($cat->icon_type === 'svg' && $cat->icon)
                        <img src="/images/icons/{{ $cat->icon }}" alt="{{ $cat->name }}" class="w-16 h-16 object-contain opacity-70 group-hover:opacity-100 transition">
                    @elseif($cat->icon_type === 'image' && $cat->image)
                        <img src="{{ $cat->image->url }}" alt="{{ $cat->name }}" class="w-24 h-24 object-contain">
                    @elseif($cat->icon)
                        <svg class="w-12 h-12 text-gray-500 group-hover:text-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $cat->icon }}"/>
                        </svg>
                    @else
                        <svg class="w-12 h-12 text-gray-500 group-hover:text-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    @endif
                </div>
                <p class="font-semibold text-gray-800 group-hover:text-brand transition text-sm mt-auto">{{ $cat->name }}</p>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Final -->
<div class="bg-brand text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold mb-4">¿Necesitás ayuda?</h2>
        <p class="text-lg text-brand-muted mb-8 max-w-xl mx-auto">
            Nuestro equipo está disponible para asistirte con información sobre productos, garantías y puntos de venta.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/contacto"
               class="bg-white text-brand px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                Contacto
            </a>
            <a href="/centro-ayuda"
               class="border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-brand-dark transition">
                Centro de ayuda
            </a>
        </div>
    </div>
</div>

@endsection
