<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio en mantenimiento — Hamilton Beach Paraguay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white flex flex-col min-h-screen">

    @php
        $contact  = \App\Models\SiteSetting::getGroup('contact');
        $phone    = $contact['phone']    ?? '';
        $wa       = preg_replace('/\D/', '', $contact['whatsapp'] ?? '');
        $email    = $contact['email']    ?? '';
        $address  = $contact['address'] ?? '';
        $schedule = $contact['schedule'] ?? '';
    @endphp

    {{-- 1. Hero — fondo gris --}}
    <section class="bg-gray-50 flex-1 flex items-center justify-center py-20 px-4">
        <div class="text-center max-w-xl">

            <img src="/images/logo.webp" alt="Hamilton Beach Paraguay" class="h-5 w-auto mx-auto mb-8">

            {{-- Ícono de mantenimiento --}}
            <div class="flex items-center justify-center mb-8">
                <div class="relative w-24 h-24 bg-brand-light rounded-full flex items-center justify-center">
                    {{-- Engranaje grande de fondo --}}
                    <svg class="absolute w-24 h-24 text-brand-muted opacity-40 animate-spin" style="animation-duration:12s" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 15.5A3.5 3.5 0 018.5 12 3.5 3.5 0 0112 8.5a3.5 3.5 0 013.5 3.5 3.5 3.5 0 01-3.5 3.5m7.43-2.92c.04-.34.07-.68.07-1.08s-.03-.73-.07-1.08l2.32-1.82c.21-.16.27-.46.13-.7l-2.2-3.82c-.13-.24-.43-.32-.67-.24l-2.73 1.1c-.57-.44-1.18-.8-1.86-1.08l-.41-2.9C14.32 2.18 14.06 2 13.78 2h-4.4c-.28 0-.54.18-.58.44l-.41 2.9c-.68.28-1.3.64-1.86 1.08l-2.73-1.1c-.24-.09-.54 0-.67.24L1.93 9.38c-.14.24-.08.54.13.7l2.32 1.82c-.04.35-.07.69-.07 1.1s.03.73.07 1.08L1.06 15.9c-.21.16-.27.46-.13.7l2.2 3.82c.13.24.43.32.67.24l2.73-1.1c.57.44 1.18.8 1.86 1.08l.41 2.9c.04.26.3.44.58.44h4.4c.28 0 .54-.18.58-.44l.41-2.9c.68-.28 1.3-.64 1.86-1.08l2.73 1.1c.24.09.54 0 .67-.24l2.2-3.82c.14-.24.08-.54-.13-.7l-2.32-1.82z"/>
                    </svg>
                    {{-- Llave inglesa en primer plano --}}
                    <svg class="relative w-10 h-10 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                    </svg>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 bg-brand-light text-brand text-sm font-semibold px-4 py-1.5 rounded-full mb-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Volvemos pronto
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight mb-5">
                Sitio en <span class="text-brand">mantenimiento</span>
            </h1>

            <p class="text-lg text-gray-500 leading-relaxed">
                Estamos trabajando para mejorar tu experiencia con nuevas funciones y mejoras.
                ¡Gracias por tu paciencia!
            </p>

        </div>
    </section>

    {{-- 2. Contacto — fondo verde --}}
    <section class="bg-brand py-16 px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

            {{-- Columna izquierda: datos de contacto --}}
            <div class="space-y-6">
                @if($phone)
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-brand-light opacity-75 mb-0.5">Teléfono</p>
                        <a href="tel:{{ preg_replace('/\s/','',$phone) }}" class="text-white font-semibold hover:text-brand-light transition">{{ $phone }}</a>
                    </div>
                </div>
                @endif

                @if($email)
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-brand-light opacity-75 mb-0.5">Correo</p>
                        <a href="mailto:{{ $email }}" class="text-white font-semibold hover:text-brand-light transition">{{ $email }}</a>
                    </div>
                </div>
                @endif

                @if($address)
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-brand-light opacity-75 mb-0.5">Dirección</p>
                        <p class="text-white font-semibold">{{ $address }}</p>
                    </div>
                </div>
                @endif

                @if($schedule)
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-brand-light opacity-75 mb-0.5">Horario de atención</p>
                        <p class="text-white font-semibold leading-relaxed">{!! nl2br(e($schedule)) !!}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Columna derecha: card WhatsApp --}}
            @if($wa)
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">¿Necesitás ayuda urgente?</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Escribinos por WhatsApp y te respondemos a la brevedad.
                </p>
                <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
                   class="w-full flex items-center justify-center gap-2.5 bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Escribinos por WhatsApp
                </a>
            </div>
            @endif

        </div>
    </section>

    {{-- 3. Footer --}}
    <footer class="border-t border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-400">
            <img src="/images/logo.webp" alt="Hamilton Beach Paraguay" class="h-5 w-auto opacity-50">
            <span>© {{ date('Y') }} Hamilton Beach Paraguay. Todos los derechos reservados.</span>
            <a href="{{ route('admin.login') }}" class="hover:text-brand transition">Acceso administradores</a>
        </div>
    </footer>

</body>
</html>
