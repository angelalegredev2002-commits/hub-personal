<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Obtiene la ruta a la que se debe redirigir al usuario cuando no está autenticado.
     * * CLAVE: Si la petición espera JSON, devolvemos null, forzando un error 401 JSON,
     * en lugar de devolver la vista de login (HTML).
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si la petición NO espera una respuesta JSON (es decir, es una petición de página normal),
        // redirigimos al login.
        if (! $request->expectsJson()) {
            return route('login');
        }

        // Si la petición ESPERA JSON, no redirigimos. Esto hace que Laravel lance la 
        // excepción de no autenticado, que por defecto se convierte en una respuesta 
        // 401 Unauthorized con formato JSON. Esto evita el SyntaxError en JavaScript.
        return null;
    }
}
