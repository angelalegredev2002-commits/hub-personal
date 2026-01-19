<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si el usuario está autenticado.
        if (!Auth::check()) {
            
            // Si la solicitud es AJAX/Fetch (espera JSON), DEVOLVER JSON DE ERROR (401).
            // ESTA ES LA CLAVE PARA SOLUCIONAR EL ERROR DE '<'.
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No autenticado.'], 401);
            }
            
            // Si es una solicitud normal de navegador, redirigir al login (HTML).
            return redirect()->route('login');
        }

        // 2. Si está autenticado, verificar el rol de administrador.
        if (Auth::user()->es_administrador) {
            // Si es administrador, permitir el acceso.
            return $next($request);
        }
        
        // 3. Si está autenticado pero NO es administrador, denegar el acceso.
        // También aquí, si es API, devolver JSON 403.
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Acceso denegado. Se requieren privilegios de administrador.'], 403);
        }

        // Para el navegador normal, abortar (devuelve HTML 403).
        return abort(403, 'Acceso denegado. Se requieren privilegios de administrador para ver esta página.');
    }
}
