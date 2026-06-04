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

                <form id="filter-form" action="/productos" method="GET">
                    @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                    @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif

                    <!-- Categorías -->
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Categoría</h4>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="categoria" value="" class="mr-2.5 accent-brand" {{ request('categoria', '') === '' ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-brand transition">Todas</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="categoria" value="{{ $cat->slug }}" class="mr-2.5 accent-brand" {{ request('categoria') === $cat->slug ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-brand transition">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white py-2.5 rounded-lg text-sm font-semibold transition">
                            Aplicar
                        </button>
                        <a href="/productos" class="block w-full text-center text-gray-500 hover:text-brand py-2 rounded-lg text-sm transition mt-1">
                            Limpiar filtros
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Grid de Productos -->
        <div class="lg:col-span-3">
            <!-- Ordenamiento -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <p class="text-sm text-gray-500">
                    @if(request('q'))
                        <span class="font-semibold text-gray-800">{{ $products->total() }}</span> resultado{{ $products->total() !== 1 ? 's' : '' }} para
                        "<span class="text-brand font-semibold">{{ request('q') }}</span>"
                        — <a href="/productos" class="text-gray-400 hover:text-brand transition underline">limpiar</a>
                    @else
                        Mostrando <span class="font-semibold text-gray-800">{{ $products->total() }}</span> productos
                    @endif
                </p>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Ordenar:</label>
                    <select name="sort" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand bg-white"
                            onchange="window.location.href='/productos?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), sort: this.value}).toString()">
                        <option value="relevancia" {{ request('sort','relevancia')==='relevancia' ? 'selected' : '' }}>Relevancia</option>
                        <option value="az"         {{ request('sort')==='az'         ? 'selected' : '' }}>Nombre: A–Z</option>
                        <option value="za"         {{ request('sort')==='za'         ? 'selected' : '' }}>Nombre: Z–A</option>
                        <option value="nuevo"      {{ request('sort')==='nuevo'      ? 'selected' : '' }}>Más nuevo</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                @forelse($products as $producto)
                <a href="/productos/{{ $producto->slug }}"
                   class="group bg-white rounded-xl border border-gray-200 hover:border-brand-muted hover:shadow-md transition overflow-hidden">
                    <!-- Imagen -->
                    <div class="bg-gray-50 aspect-square relative overflow-hidden">
                        <img src="{{ $producto->featuredImage?->url ?? '/images/products/cafetera-retro-black-1.webp' }}" alt="{{ $producto->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-3 left-3 bg-white border border-gray-200 text-gray-600 text-xs font-medium px-2.5 py-1 rounded-full">
                            {{ $producto->category?->name ?? '' }}
                        </span>
                    </div>
                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-brand transition text-sm leading-snug mb-1 line-clamp-2">
                            {{ $producto->title }}
                        </h3>
                        @if($producto->sku)
                        <p class="text-xs text-gray-400 font-mono mb-2">{{ $producto->sku }}</p>
                        @endif
                        <div class="mb-3">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Precio sugerido</p>
                            <p class="text-sm font-bold text-gray-800">≈ {{ $producto->formatted_price }}</p>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-500">Ver puntos de venta</span>
                            <svg class="w-4 h-4 text-brand group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-3 text-center py-16 text-gray-400">
                    <p class="text-lg font-medium">No hay productos disponibles</p>
                    <a href="/productos" class="text-brand hover:underline text-sm mt-2 inline-block">Ver todos los productos</a>
                </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($products->hasPages())
            <div class="flex justify-center mt-10 gap-1">
                @if($products->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg text-gray-300 text-sm cursor-not-allowed">‹</span>
                @else
                <a href="{{ $products->withQueryString()->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">‹</a>
                @endif

                @foreach($products->withQueryString()->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                <span class="w-9 h-9 flex items-center justify-center bg-brand text-white rounded-lg text-sm font-semibold">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">{{ $page }}</a>
                @endif
                @endforeach

                @if($products->hasMorePages())
                <a href="{{ $products->withQueryString()->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition text-sm">›</a>
                @else
                <span class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg text-gray-300 text-sm cursor-not-allowed">›</span>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
