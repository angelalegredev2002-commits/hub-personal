<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Importamos el Request que vamos a usar
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Usuario; // Importamos el modelo Usuario (Necesario para este test manual)
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * Usaremos el LoginRequest para manejar la validación y el intento de Auth::attempt.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. EL REQUEST HACE TODO EL TRABAJO: 
        // Llama a LoginRequest::authenticate(), el cual usa Auth::attempt().
        // Como el modelo Usuario tiene getAuthPassword() apuntando a 'clave',
        // el Auth::attempt funcionará internamente.
        $request->authenticate(); 

        // 2. Si la autenticación es exitosa, regeneramos la sesión.
        $request->session()->regenerate();

        // 3. Redireccionamos al dashboard.
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
