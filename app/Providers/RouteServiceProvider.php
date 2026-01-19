<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la que se debe redirigir a los usuarios después de la autenticación.
     *
     * @var string
     */
    // ¡IMPORTANTE! Hemos cambiado el destino predeterminado de /home a /dashboard.
    // Esto asegura que, tras el login, el usuario vaya a nuestro Panel Principal.
    public const HOME = '/dashboard'; 

    /**
     * Define tus enlaces de modelo de ruta, filtros de patrones y configuraciones de ruta.
     */
    public function boot(): void
    {
        // Define la limitación de velocidad (rate limiting) para las APIs
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Rutas de API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rutas Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}