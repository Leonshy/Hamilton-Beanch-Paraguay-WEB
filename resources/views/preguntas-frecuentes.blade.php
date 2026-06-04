@extends('layouts.app')

@section('title', 'Preguntas Frecuentes - Hamilton Beach Paraguay')

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
        <a href="/" class="hover:text-brand transition">Inicio</a>
        <span class="mx-2">›</span>
        <a href="/centro-ayuda" class="hover:text-brand transition">Centro de ayuda</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-medium">Preguntas frecuentes</span>
    </div>
</div>

<!-- Hero -->
<div class="bg-brand text-white py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold mb-3">Preguntas frecuentes</h1>
        <p class="text-lg text-brand-muted">Encontrá respuestas a las consultas más comunes sobre nuestros productos y servicios.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="space-y-2">
        @forelse($faqs as $faq)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button
                class="w-full px-6 py-5 text-left flex justify-between items-center gap-4 hover:bg-gray-50 transition"
                onclick="toggleFaq(this)">
                <span class="font-semibold text-gray-800 text-sm leading-snug">{{ $faq->question }}</span>
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="faq-content hidden px-6 pb-5">
                <div class="border-t border-gray-100 pt-4">
                    <div class="text-gray-600 text-sm leading-relaxed prose prose-sm max-w-none">{!! $faq->answer !!}</div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-gray-400 py-8">No hay preguntas frecuentes disponibles.</p>
        @endforelse
    </div>

    <!-- CTA unificado -->
    <div class="mt-12">
        @include('partials.cta-ayuda')
    </div>

</div>

<script>
function toggleFaq(btn) {
    const content = btn.nextElementSibling;
    const icon = btn.querySelector('svg');
    const isOpen = !content.classList.contains('hidden');

    // Cerrar todos los abiertos
    document.querySelectorAll('.faq-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[onclick="toggleFaq(this)"] svg').forEach(el => {
        el.style.transform = '';
    });

    // Abrir el clickeado (si estaba cerrado)
    if (!isOpen) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

@endsection
