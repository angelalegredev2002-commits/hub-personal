<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; 
use Illuminate\View\View;
use Illuminate\Support\Arr; // Importamos la clase Arr para excluir campos

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de perfil del usuario.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información de perfil del usuario (incluyendo todos los nuevos campos y la foto).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated(); // Obtener todos los datos validados

        // 1. MANEJO DE LA IMAGEN DE PERFIL
        if ($request->hasFile('foto_perfil')) {
            
            // Si ya existe una foto, la eliminamos de manera segura
            if ($user->foto_perfil_ruta) {
                if (Storage::disk('public')->exists($user->foto_perfil_ruta)) {
                    Storage::disk('public')->delete($user->foto_perfil_ruta);
                }
            }

            // Guardar la nueva imagen y asignar la ruta a la columna 'foto_perfil_ruta'
            $ruta = $request->file('foto_perfil')->store('profile-photos', 'public');
            $user->foto_perfil_ruta = $ruta;
        }

        // 2. ACTUALIZACIÓN DE CAMPOS DE TEXTO
        
        // Excluimos 'foto_perfil' de los datos a llenar. Su ruta ya está seteada en el paso 1.
        // Esto previene que el objeto UploadedFile cause un error al intentar asignarse a una columna string.
        $textFieldsData = Arr::except($validatedData, ['foto_perfil']);
        
        // Llenamos el resto de los campos validados: 
        // (name, email, identificacion_dni, fecha_nacimiento, telefono_principal, 
        // zona_horaria, idioma_preferido, y cualquier otro campo validado en ProfileUpdateRequest)
        $user->fill($textFieldsData);

        // Lógica para forzar la re-verificación de email si el campo 'email' ha cambiado
        if ($user->isDirty('email')) {
            $user->email_verificado_en = null; 
        }

        // 3. GUARDAR TODOS LOS CAMBIOS (Datos de texto, nueva ruta de la foto y estado del email)
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina la cuenta del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            // Validación contra tu columna 'clave'
            'password' => ['required', 'current_password:web,clave'], 
        ]);

        $user = $request->user();

        // Eliminar la imagen de perfil del disco al borrar la cuenta
        if ($user->foto_perfil_ruta) {
            Storage::disk('public')->delete($user->foto_perfil_ruta);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
