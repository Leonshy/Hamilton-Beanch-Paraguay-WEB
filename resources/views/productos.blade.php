@extends('layouts.app')

@section('title', 'Productos - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Productos</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Catálogo de Productos</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sidebar Filtros -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-20">
                <h3 class="text-base font-bold text-gray-900 mb-4">Filtrar por</h3>

                <!-- Categorías -->
                <div class="mb-6">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Categoría</h4>
                    <div class="space-y-2">
                        @php
                            $categorias = ['Todas', 'Cafeteras', 'Tostadoras', 'Pavas Eléctricas', 'Molinillos'];
                        @endphp
                        @foreach($categorias as $i => $cat)
                        <label class="flex items-center cursor-pointer group">
                            <input type="{{ $i === 0 ? 'radio' : 'radio' }}" name="categoria" class="mr-2.5 accent-brand" {{ $i === 0 ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 group-hover:text-brand transition">{{ $cat }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <button class="w-full bg-brand hover:bg-brand-dark text-white py-2.5 rounded-lg text-sm font-semibold transition">
                        Aplicar
                    </button>
                    <button class="w-full text-gray-500 hover:text-brand py-2 rounded-lg text-sm transition mt-1">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        </aside>

        <!-- Grid de Productos -->
        <div class="lg:col-span-3">
            <!-- Ordenamiento -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <p class="text-sm text-gray-500">Mostrando <span class="font-semibold text-gray-800">6</span> productos</p>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Ordenar:</label>
                    <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand bg-white">
                        <option>Relevancia</option>
                        <option>Nombre: A–Z</option>
                        <option>Nombre: Z–A</option>
                        <option>Más nuevo</option>
                        <option>Más vendido</option>
                    </select>
                </div>
            </div>

            @php
                $productos = [
                    ['id' => 1, 'nombre' => 'Cafetera Espresso Retro Black',  'categoria' => 'Cafeteras',  'precio' => '850.000',   'imagen' => '/images/products/cafetera-retro-black-1.webp'],
                    ['id' => 2, 'nombre' => 'Cafetera Home Barista 7-in-1',   'categoria' => 'Cafeteras',  'precio' => '1.200.000', 'imagen' => '/images/products/cafetera-home-barista.webp'],
                    ['id' => 3, 'nombre' => 'Tostadora Toaster Silver',       'categoria' => 'Tostadoras', 'precio' => '320.000',   'imagen' => '/images/products/tostadora-silver.webp'],
                    ['id' => 4, 'nombre' => 'Pava Eléctrica Cool-Touch',      'categoria' => 'Pavas',      'precio' => '280.000',   'imagen' => '/images/products/pava-cool-touch.webp'],
                    ['id' => 5, 'nombre' => 'Pava Eléctrica Double-Wall',     'categoria' => 'Pavas',      'precio' => '350.000',   'imagen' => '/images/products/pava-double-wall.webp'],
                    ['id' => 6, 'nombre' => 'Molinillo de Café Profesional',  'categoria' => 'Molinillos', 'precio' => '450.000',   'imagen' => '/images/products/pava-digital.webp'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($productos as $producto)
                <a href="/productos/{{ $producto['id'] }}"
                   class="group bg-white rounded-xl border border-gray-200 hover:border-brand-muted hover:shadow-md transition overflow-hidden">
                    <!-- Imagen -->
                    <div class="bg-gray-50 h-52 relative overflow-hidden">
                        <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}"
                             class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-3 left-3 bg-white border border-gray-200 text-gray-600 text-xs font-medium px-2.5 py-1 rounded-full">
                            {{ $producto['categoria'] }}
                        </span>
                    </div>
                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-brand transition text-sm leading-snug mb-2 line-clamp-2">
                            {{ $producto['nombre'] }}
                        </h3>
                        <div class="mb-3">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Precio sugerido</p>
                            <p class="text-sm font-bold text-gray-800">≈ Gs. {{ $producto['precio'] }}</p>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-500">Ver puntos de venta</span>
                            <svg class="w-4 h-4 text-brand group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="flex justify-center mt-10 gap-1">
                <button class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">
                    ‹
                </button>
                <button class="w-9 h-9 flex items-center justify-center bg-brand text-white rounded-lg text-sm font-semibold">1</button>
                <button class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">2</button>
                <button class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">3</button>
                <button class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">
                    ›
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
