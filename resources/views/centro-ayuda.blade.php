@extends('layouts.app')

@section('title', 'Centro de Ayuda - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Centro de ayuda</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold mb-3">Centro de ayuda</h1>
        <p class="text-lg text-brand-muted">¿En qué podemos ayudarte hoy?</p>
    </div>
</div>

<!-- Hub de categorías -->
<div class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-16">

            @php
            $sectionUrls = [
                'preguntas-frecuentes' => '/preguntas-frecuentes',
                'servicio-tecnico'     => '/servicio-tecnico',
                'manuales'             => '/manuales-de-producto',
                'garantia'             => '/garantia-de-producto',
            ];
            $sectionOrder = ['preguntas-frecuentes', 'servicio-tecnico', 'manuales', 'garantia'];
            @endphp

            @foreach($sectionOrder as $section)
            @php $item = $helpItems->get($section); @endphp
            @if($item)
            <a href="{{ $sectionUrls[$section] }}"
               class="group flex flex-col items-center text-center p-8 bg-white border border-gray-200 rounded-2xl hover:border-brand-muted hover:shadow-md transition">
                <div class="w-16 h-16 bg-brand-light group-hover:bg-brand-light rounded-2xl flex items-center justify-center mb-5 transition">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @foreach(explode('||', $item->icon ?? '') as $path)
                            @if(trim($path))
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ trim($path) }}"/>
                            @endif
                        @endforeach
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-brand transition mb-1">{{ $item->title }}</h3>
                @if($item->subtitle)
                <p class="text-gray-500 text-sm">{{ $item->subtitle }}</p>
                @endif
            </a>
            @endif
            @endforeach

        </div>

        <!-- CTA -->
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-10 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">¿No encontraste la respuesta que buscabas?</h2>
            <p class="text-gray-500 mb-7">Nuestro equipo está listo para ayudarte.</p>
            <a href="/contacto"
               class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white px-8 py-3 rounded-lg font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Obtener ayuda
            </a>
        </div>

    </div>
</div>

@endsection
