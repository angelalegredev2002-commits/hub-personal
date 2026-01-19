<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaccion;
use App\Models\RegistroActividad;
use Illuminate\Validation\Rule;

class TransaccionController extends Controller
{
    /**
     * Muestra el listado de transacciones del usuario.
     */
    public function index()
    {
        $transacciones = auth()->user()->transacciones()->with('proyecto')->orderByDesc('fecha_transaccion')->get();
        
        return view('transacciones.index', compact('transacciones'));
    }

    /**
     * Almacena una nueva transacción (ingreso o gasto) en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $validatedData = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'tipo' => ['required', Rule::in(['ingreso', 'gasto', 'transferencia'])],
            'descripcion' => 'required|string|max:255',
            'fecha_transaccion' => 'required|date',
            'categoria' => 'nullable|string|max:50',
            'proyecto_id' => 'nullable|exists:proyectos,id',
        ]);

        try {
            // 2. CREACIÓN DE LA TRANSACCIÓN
            $transaccion = Transaccion::create([
                'usuario_id' => auth()->id(), // Quién registra el movimiento
                'proyecto_id' => $validatedData['proyecto_id'] ?? null,
                'monto' => $validatedData['monto'],
                'tipo' => $validatedData['tipo'],
                'descripcion' => $validatedData['descripcion'],
                'fecha_transaccion' => $validatedData['fecha_transaccion'],
                'categoria' => $validatedData['categoria'] ?? 'Sin Categoría',
            ]);

            // 3. REGISTRO DE ACTIVIDAD (Auditoría financiera)
            RegistroActividad::create([
                'usuario_id' => auth()->id(),
                'accion' => 'transaccion_' . $transaccion->tipo . '_creada',
                'sujeto_type' => Transaccion::class,
                'sujeto_id' => $transaccion->id,
            ]);

            return redirect()->route('transacciones.index')
                             ->with('success', 'Transacción de ' . $transaccion->tipo . ' registrada con éxito.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al registrar la transacción: ' . $e->getMessage());
        }
    }
}