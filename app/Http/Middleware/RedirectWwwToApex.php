<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWwwToApex
{
    /**
     * SEO: evita contenido duplicado entre www.hamiltonbeach.com.py y
     * hamiltonbeach.com.py — ambos apuntan al mismo vhost/BD (confirmado),
     * así que sin esto Google ve dos copias del sitio entero.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (str_starts_with($host, 'www.')) {
            $apexHost = substr($host, 4);
            $url = $request->getScheme() . '://' . $apexHost . $request->getRequestUri();

            return redirect()->away($url, 301);
        }

        return $next($request);
    }
}
