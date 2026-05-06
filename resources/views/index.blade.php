@extends('layouts.app')

@section('title', 'Hamilton Beach Paraguay - Electrodomésticos de calidad')

@section('content')

<!-- Hero Section -->
<div class="relative bg-gray-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-brand-dark opacity-90"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="py-24 md:py-32">
                <p class="text-brand-muted font-medium mb-3 uppercase tracking-wider text-sm">Distribuidor Oficial en Paraguay</p>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                    Calidad Hamilton Beach<br>
                    <span class="text-brand-muted">para tu hogar</span>
                </h1>
                <p class="text-lg mb-8 text-brand-muted leading-relaxed">
                    Cafeteras, tostadoras, pavas eléctricas y molinillos de café con respaldo y garantía oficial en Paraguay.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/productos"
                       class="inline-block bg-white text-brand-dark px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow">
                        Ver catálogo
                    </a>
                    <a href="/contacto"
                       class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-brand transition">
                        Contacto
                    </a>
                </div>
            </div>
            <!-- Imagen hero -->
            <div class="hidden md:flex items-center">
                <img src="/images/products/cafetera-retro-black-1.webp"
                     alt="Cafetera Hamilton Beach Retro Black"
                     class="w-full h-full object-contain drop-shadow-2xl">
            </div>
        </div>
    </div>
</div>

<!-- Trust badges -->
<div class="bg-white border-b border-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Puntos de venta en todo Paraguay</h3>
                    <p class="text-gray-500 text-sm mt-0.5">Encontrá nuestros productos en tiendas autorizadas</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Garantía oficial 1 año</h3>
                    <p class="text-gray-500 text-sm mt-0.5">Con respaldo de servicio técnico autorizado</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Servicio técnico oficial</h3>
                    <p class="text-gray-500 text-sm mt-0.5">Técnicos capacitados con repuestos originales</p>
                </div>
            </div>
        </div>
    </div>
</div>

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

        @php
            $productos = [
                ['id' => 1, 'nombre' => 'Cafetera Espresso Retro Black',   'categoria' => 'Cafeteras',  'precio' => '850.000',   'imagen' => '/images/products/cafetera-retro-black-1.webp'],
                ['id' => 2, 'nombre' => 'Cafetera Home Barista 7-in-1',    'categoria' => 'Cafeteras',  'precio' => '1.200.000', 'imagen' => '/images/products/cafetera-home-barista.webp'],
                ['id' => 3, 'nombre' => 'Tostadora Toaster Silver',        'categoria' => 'Tostadoras', 'precio' => '320.000',   'imagen' => '/images/products/tostadora-silver.webp'],
                ['id' => 4, 'nombre' => 'Pava Eléctrica Cool-Touch',       'categoria' => 'Pavas',      'precio' => '280.000',   'imagen' => '/images/products/pava-cool-touch.webp'],
                ['id' => 5, 'nombre' => 'Pava Eléctrica Double-Wall',      'categoria' => 'Pavas',      'precio' => '350.000',   'imagen' => '/images/products/pava-double-wall.webp'],
                ['id' => 6, 'nombre' => 'Molinillo de Café Profesional',   'categoria' => 'Molinillos', 'precio' => '450.000',   'imagen' => '/images/products/pava-digital.webp'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($productos as $producto)
            <a href="/productos/{{ $producto['id'] }}"
               class="group bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
                <!-- Imagen -->
                <div class="bg-gray-50 h-56 relative overflow-hidden">
                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}"
                         class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 left-3">
                        <span class="bg-white text-gray-600 text-xs font-medium px-2 py-1 rounded-full border border-gray-200 shadow-sm">
                            {{ $producto['categoria'] }}
                        </span>
                    </div>
                </div>
                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-semibold text-gray-900 group-hover:text-brand transition line-clamp-2 mb-3">
                        {{ $producto['nombre'] }}
                    </h3>
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Precio sugerido</p>
                        <p class="text-sm font-bold text-gray-800">≈ Gs. {{ $producto['precio'] }}</p>
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
            @php
                $categorias = [
                    ['nombre' => 'Cafeteras', 'slug' => 'cafeteras', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                    ['nombre' => 'Tostadoras', 'slug' => 'tostadoras', 'icon' => 'M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z'],
                    ['nombre' => 'Pavas', 'slug' => 'pavas', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                    ['nombre' => 'Molinillos', 'slug' => 'molinillos', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                ];
            @endphp
            @foreach($categorias as $cat)
            <a href="/productos?categoria={{ $cat['slug'] }}"
               class="group bg-gray-50 hover:bg-brand-light border border-gray-200 hover:border-brand-muted rounded-xl p-6 text-center transition">
                <div class="w-12 h-12 bg-white group-hover:bg-brand-light rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm transition">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $cat['icon'] }}"/>
                    </svg>
                </div>
                <p class="font-semibold text-gray-800 group-hover:text-brand transition text-sm">{{ $cat['nombre'] }}</p>
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
