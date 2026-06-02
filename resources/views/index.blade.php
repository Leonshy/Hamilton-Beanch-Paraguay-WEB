@extends('layouts.app')

@section('title', 'Hamilton Beach Paraguay - Electrodomésticos de calidad')

@section('content')

<!-- Hero Section — Carrusel altura fija -->
@if($banners->isNotEmpty())
<div class="relative bg-brand-dark text-white overflow-hidden" id="heroSlider"
     style="height: clamp(380px, 55vw, 580px);">

    {{-- Slides: todos absolute, se muestran u ocultan con opacidad --}}
    @foreach($banners as $i => $b)
    <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
         data-slide="{{ $i }}">

        <div class="absolute inset-0 bg-brand-dark opacity-90"></div>

        @php $hasImage = !empty($b->image?->url); @endphp
        <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
            @if($hasImage)
            {{-- Con imagen: dos columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 w-full items-center">
                <div>
            @else
            {{-- Sin imagen: columna única centrada --}}
            <div class="w-full flex justify-center">
                <div class="text-center max-w-2xl">
            @endif
                    @if($b->tagline)
                    <p class="text-brand-muted font-medium mb-3 uppercase tracking-wider text-sm">{{ $b->tagline }}</p>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
                        {{ $b->title ?? 'Calidad Hamilton Beach' }}
                        @if($b->subtitle)
                        <br><span class="text-brand-muted">{{ $b->subtitle }}</span>
                        @endif
                    </h1>
                    @if($b->description)
                    <p class="text-lg mb-8 text-brand-muted leading-relaxed">{{ $b->description }}</p>
                    @endif
                    @if($b->cta_text || $b->cta2_text)
                    <div class="flex flex-wrap gap-4 {{ $hasImage ? '' : 'justify-center' }}">
                        @if($b->cta_text)
                        <a href="{{ $b->cta_url ?? '/productos' }}"
                           class="inline-block bg-white text-brand-dark px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow">
                            {{ $b->cta_text }}
                        </a>
                        @endif
                        @if($b->cta2_text)
                        <a href="{{ $b->cta2_url ?? '/contacto' }}"
                           class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-brand transition">
                            {{ $b->cta2_text }}
                        </a>
                        @endif
                    </div>
                    @endif
            @if($hasImage)
                </div>
                {{-- Columna imagen --}}
                <div class="hidden md:flex items-center justify-center h-full py-6 overflow-hidden">
                    <img src="{{ $b->image->url }}" alt="{{ $b->title ?? 'Hamilton Beach' }}"
                         class="max-h-full w-auto object-contain drop-shadow-2xl">
                </div>
            </div>
            @else
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach

    {{-- Controles: wrapper inset-0 con flexbox para posicionamiento garantizado --}}
    @if($banners->count() > 1)
    {{-- Flechas (ocultas en mobile) --}}
    <div class="absolute inset-0 z-20 hidden md:flex items-center justify-between px-4 pointer-events-none">
        <button id="heroPrev" aria-label="Anterior"
                class="pointer-events-auto w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button id="heroNext" aria-label="Siguiente"
                class="pointer-events-auto w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Dots centrados al fondo --}}
    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-20" style="bottom: 16px; top: auto;">
        @foreach($banners as $i => $b)
        <button class="hero-dot w-2.5 h-2.5 rounded-full transition {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}"
                data-dot="{{ $i }}"></button>
        @endforeach
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
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center flex-shrink-0">
                    @include('partials.trust-icon', ['icon' => $feature['icon'] ?? 'shield'])
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $feature['title'] ?? '' }}</h3>
                    @if(!empty($feature['description']))
                    <p class="text-gray-500 text-sm mt-0.5">{{ $feature['description'] }}</p>
                    @endif
                </div>
            </div>
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

<!-- Categorías -->
<div class="bg-white py-16">
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
