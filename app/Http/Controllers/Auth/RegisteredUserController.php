<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role; // 🟢 NUEVO: Necesario para asignar el rol por defecto
use App\Models\Usuario; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Maneja la solicitud de registro entrante y crea el usuario.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validación de todos los campos del formulario
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Usuario::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Campos de configuración/contacto adicionales
            'zona_horaria' => ['required', 'string', 'max:60'],
            'idioma_preferido' => ['required', 'string', 'max:5'],
            'telefono_principal' => ['nullable', 'string', 'max:50'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'], // ⬅️ OPCIONAL: Si se agregó al formulario
        ]);

        // 2. Creación del nuevo usuario usando el modelo Usuario
        $user = Usuario::create([
            'nombre' => $request->name, 
            'email' => $request->email,
            'clave' => $request->password, // El 'hashed' cast del modelo Usuario lo hashea
            
            // Campos de configuración
            'zona_horaria' => $request->zona_horaria,
            'idioma_preferido' => $request->idioma_preferido,
            'telefono_principal' => $request->telefono_principal,
            'fecha_nacimiento' => $request->fecha_nacimiento, // ⬅️ OPCIONAL: Si se agregó
        ]);
        
        // 3. 🟢 ASIGNACIÓN DEL ROL POR DEFECTO (CRÍTICO para M-a-M)
        // Buscamos el rol por defecto (ej. 'usuario_estandar')
        $defaultRole = Role::where('nombre', 'usuario_estandar')->first();
        
        if ($defaultRole) {
            // Adjuntamos el rol al usuario usando la relación roles() y la tabla pivote
            $user->roles()->attach($defaultRole->id);
        } else {
            // Manejo de error si el rol por defecto no existe (importante en producción)
            \Log::error('El rol por defecto "usuario_estandar" no fue encontrado durante el registro.');
        }

        // 4. Autenticación y Redirección
        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
