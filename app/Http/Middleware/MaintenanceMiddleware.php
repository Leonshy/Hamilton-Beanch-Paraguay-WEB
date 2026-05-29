<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Rutas siempre accesibles
        if ($request->routeIs('admin.*') || $request->routeIs('filament.*')) {
            return $next($request);
        }

        if (SiteSetting::get('maintenance_mode') === '1') {
            // Usuarios logueados pasan igual
            if (auth()->check()) {
                return $next($request);
            }

            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
