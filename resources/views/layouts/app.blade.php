<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName    = $siteSettings['site_name']    ?? 'Hamilton Beach Paraguay';
        $siteTagline = $siteSettings['site_tagline'] ?? '';
        $siteLogo    = $siteSettings['logo']         ?? '/images/logo.webp';
        $siteFavicon = $siteSettings['favicon']      ?? '/favicon.ico';
        $metaDesc    = $siteSettings['meta_description'] ?? $siteSettings['site_description'] ?? '';
    @endphp
    <title>@yield('title', $siteName . ($siteTagline ? ' — ' . $siteTagline : ''))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($metaDesc)
    <meta name="description" content="{{ $metaDesc }}">
    @endif
    <link rel="icon" href="{{ $siteFavicon }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Analytics --}}
    @if(!empty($siteSettings['google_analytics_id']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['google_analytics_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $siteSettings['google_analytics_id'] }}');
    </script>
    @endif

    {{-- Meta Pixel --}}
    @if(!empty($siteSettings['meta_pixel_id']))
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $siteSettings['meta_pixel_id'] }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $siteSettings['meta_pixel_id'] }}&ev=PageView&noscript=1"/></noscript>
    @endif

    {{-- Scripts personalizados en <head> --}}
    @if(!empty($siteSettings['custom_scripts_head']))
    {!! $siteSettings['custom_scripts_head'] !!}
    @endif
</head>
<body class="bg-white">
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- WhatsApp Flotante -->
    @php $waNumber = preg_replace('/\D/', '', $siteSettings['whatsapp'] ?? '595911234567'); @endphp
    @if(!isset($siteSettings['whatsapp_float_enabled']) || $siteSettings['whatsapp_float_enabled'])
    <a href="https://wa.me/{{ $waNumber }}" target="_blank"
       class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition"
       title="Contactar por WhatsApp">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    @endif

    {{-- Scripts personalizados antes de </body> --}}
    @if(!empty($siteSettings['custom_scripts_body']))
    {!! $siteSettings['custom_scripts_body'] !!}
    @endif
</body>
</html>
