<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use App\Models\RegistroActividad;

class ContactoController extends Controller
{
    /**
     * Muestra el listado de contactos del usuario propietario.
     */
    public function index()
    {
        // Solo muestra los contactos donde el usuario autenticado es el propietario
        $contactos = auth()->user()->contactosPropios()->orderBy('nombre_completo')->get();
        
        return view('contactos.index', compact('contactos'));
    }

    /**
     * Almacena un nuevo contacto en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $validatedData = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'organizacion' => 'nullable|string|max:255',
            'cargo_puesto' => 'nullable|string|max:100',
            'telefono_principal' => 'nullable|string|max:50',
            'relacion' => 'nullable|string|max:50',
            'notas_privadas' => 'nullable|string',
        ]);

        try {
            // 2. CREACIÓN DEL CONTACTO
            $contacto = Contacto::create([
                'usuario_propietario_id' => auth()->id(), // CRUCIAL: Asigna la propiedad al usuario logueado
                'nombre_completo' => $validatedData['nombre_completo'],
                'email' => $validatedData['email'] ?? null,
                'organizacion' => $validatedData['organizacion'] ?? null,
                'cargo_puesto' => $validatedData['cargo_puesto'] ?? null,
                'telefono_principal' => $validatedData['telefono_principal'] ?? null,
                'relacion' => $validatedData['relacion'] ?? null,
                'notas_privadas' => $validatedData['notas_privadas'] ?? null,
                // 'es_proveedor' y 'es_cliente' se pueden inferir o enviar desde el formulario
            ]);

            // 3. REGISTRO DE ACTIVIDAD
            RegistroActividad::create([
                'usuario_id' => auth()->id(),
                'accion' => 'contacto_creado',
                'sujeto_type' => Contacto::class,
                'sujeto_id' => $contacto->id,
            ]);

            return redirect()->route('contactos.index')
                             ->with('success', 'Contacto ' . $contacto->nombre_completo . ' registrado con éxito.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear el contacto: ' . $e->getMessage());
        }
    }
}