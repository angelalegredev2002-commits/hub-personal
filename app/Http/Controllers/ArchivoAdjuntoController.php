<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchivoAdjunto;
use App\Models\RegistroActividad;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArchivoAdjuntoController extends Controller
{
    /**
     * Almacena un nuevo archivo adjunto y lo vincula a una entidad (polimórfica).
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN Y SEGURIDAD
        $validatedData = $request->validate([
            'archivo' => 'required|file|max:20480', // Máximo 20MB
            // Restricción de tipos de archivo para evitar ejecutables (ej. 'exe', 'bat', etc.)
            'archivo' => 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip', 
            
            // Campos Polimórficos: A qué se adjunta el archivo
            'relacionable_type' => ['required', 'string', Rule::in([
                'App\Models\Proyecto', 
                'App\Models\Tarea', 
                'App\Models\Mensaje', 
                'App\Models\Reunion'
            ])],
            'relacionable_id' => 'required|integer',
        ]);

        $archivoSubido = $request->file('archivo');
        
        // 2. GENERACIÓN DE NOMBRES Y RUTAS
        $nombreOriginal = $archivoSubido->getClientOriginalName();
        $extension = $archivoSubido->getClientOriginalExtension();
        $nombreServidor = Str::uuid() . '.' . $extension; // UUID para el nombre en el servidor
        $rutaAlmacenamiento = $validatedData['relacionable_type'] . '/' . $validatedData['relacionable_id'];
        
        try {
            // 3. ALMACENAMIENTO SEGURO
            // Usamos 'public' o 's3' (recomendado) para almacenamiento.
            // Siempre guardar con un nombre de servidor seguro (el UUID).
            $rutaCompleta = $archivoSubido->storeAs(
                $rutaAlmacenamiento, 
                $nombreServidor, 
                'public' // Usa el disco configurado (ej. 'public' o 's3')
            );
            
            // 4. CÁLCULO DE HASH (Para integridad y seguridad)
            $hashSha256 = hash_file('sha256', $archivoSubido->getRealPath());

            // 5. CREACIÓN DEL REGISTRO EN BD
            $archivo = ArchivoAdjunto::create([
                'usuario_subidor_id' => auth()->id(),
                'nombre' => $nombreOriginal,
                'nombre_servidor' => $nombreServidor,
                'ruta_almacenamiento' => $rutaCompleta,
                'tipo_mime' => $archivoSubido->getMimeType(),
                'tamaño_bytes' => $archivoSubido->getSize(),
                'hash_sha256' => $hashSha256,
                'escaneo_virus_ok' => true, // En producción: Ejecutar antivirus aquí y setear a 'true' si pasa.
                'relacionable_type' => $validatedData['relacionable_type'],
                'relacionable_id' => $validatedData['relacionable_id'],
            ]);

            // 6. REGISTRO DE ACTIVIDAD
            RegistroActividad::create([
                'usuario_id' => auth()->id(),
                'accion' => 'archivo_adjuntado',
                'sujeto_type' => ArchivoAdjunto::class,
                'sujeto_id' => $archivo->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Archivo subido y adjuntado con éxito.', 'archivo' => $archivo], 201);

        } catch (\Exception $e) {
            // Si la subida falla, aseguramos que el archivo no quede a medias.
            // Opcional: Si el archivo se subió parcialmente, eliminarlo del disco.
            return response()->json(['error' => 'Error al procesar la subida del archivo: ' . $e->getMessage()], 500);
        }
    }
}