<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role; 
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 

class AdminUsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios del sistema (CRUD: index).
     */
    public function index()
    {
        // 🟢 CRÍTICO: Usar paginate() en lugar de get() para resolver el error hasPages().
        // También cargamos la relación 'roles' y ordenamos por nombre.
        $usuarios = Usuario::with('roles')
                           ->orderBy('nombre')
                           ->paginate(15); // Mostrar 15 usuarios por página

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario (CRUD: create).
     */
    public function create()
    {
        $roles = Role::all(); 
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario creado desde el panel de administración (CRUD: store).
     */
    public function store(Request $request)
    {
        // Usamos validateUserData, que ahora validará el array 'roles'
        $validatedData = $this->validateUserData($request, null);

        // 🚨 Regla de seguridad: Si el rol de Super Admin está en la lista y no eres Super Admin
        $superAdminRoleId = Role::where('nombre', 'super_administrador')->value('id');
        if (in_array($superAdminRoleId, $validatedData['roles']) && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'Solo un Super Administrador puede asignar el rol de Super Administrador.')
                         ->withInput();
        }

        $usuario = Usuario::create([
            'nombre' => $validatedData['nombre'],
            'email' => $validatedData['email'],
            'clave' => $validatedData['password'], 
            
            'zona_horaria' => $validatedData['zona_horaria'] ?? config('app.timezone', 'America/Lima'),
            'idioma_preferido' => $validatedData['idioma_preferido'] ?? 'es',
            'estado_cuenta' => $validatedData['estado_cuenta'] ?? 'activo',
        ]);
        
        // 🟢 CRÍTICO: Adjuntar roles a través de la relación Many-to-Many
        $usuario->roles()->sync($validatedData['roles']);

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario creado y roles asignados exitosamente.');
    }

    /**
     * Muestra un usuario específico (CRUD: show).
     */
    public function show(Usuario $usuario)
    {
        // 🚨 CAMBIO: Cargamos la relación 'roles' (plural)
        $usuario->load('roles', 'configuracion'); 
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Muestra el formulario para editar los datos y roles de un usuario específico (CRUD: edit).
     */
    public function edit(Usuario $usuario)
    {
        $roles = Role::all(); 
        // 🚨 Nuevo: Obtener los IDs de los roles actualmente asignados
        $userRoleIds = $usuario->roles->pluck('id')->toArray(); 
        return view('admin.usuarios.edit', compact('usuario', 'roles', 'userRoleIds'));
    }

    /**
     * Actualiza los datos y roles del usuario (CRUD: update).
     */
    public function update(Request $request, Usuario $usuario)
    {
        $validatedData = $this->validateUserData($request, $usuario->id);
        
        $currentRoleIds = $usuario->roles->pluck('id')->toArray();
        $newRoleIds = $validatedData['roles'];
        $superAdminRoleId = Role::where('nombre', 'super_administrador')->value('id');

        // 🚨 REGLA DE SEGURIDAD CRÍTICA 1: No auto-despojarse de ser Super Admin
        $isRemovingSuperAdmin = in_array($superAdminRoleId, $currentRoleIds) && !in_array($superAdminRoleId, $newRoleIds);
        if ($usuario->id === Auth::id() && $isRemovingSuperAdmin) {
            return back()->with('error', 'No puedes auto-despojarte de los privilegios de Super Administrador.');
        }
        
        // 🚨 REGLA DE SEGURIDAD CRÍTICA 2: Solo un Super Admin puede asignar/cambiar el rol Super Admin
        $isAssigningSuperAdmin = in_array($superAdminRoleId, $newRoleIds) && !Auth::user()->isSuperAdmin();
        if ($isAssigningSuperAdmin) {
             return back()->with('error', 'Solo un Super Administrador puede asignar el rol de Super Administrador.');
        }
        
        // 3. PREPARAR DATOS (Excluyendo 'roles' del fill)
        $data = [
            'nombre' => $validatedData['nombre'],
            'email' => $validatedData['email'],
            'estado_cuenta' => $validatedData['estado_cuenta'], 
            'zona_horaria' => $validatedData['zona_horaria'],
            'idioma_preferido' => $validatedData['idioma_preferido'],
            
            // Campos de RRHH y Estado 
            'departamento' => $validatedData['departamento'] ?? null,
            'posicion_laboral' => $validatedData['posicion_laboral'] ?? null,
            'es_supervisor' => $validatedData['es_supervisor'] ?? false,
            'razon_estado' => $validatedData['razon_estado'] ?? null,
        ];

        if (!empty($validatedData['password'])) {
            $data['clave'] = $validatedData['password']; 
        }

        // 4. ACTUALIZACIÓN DE DATOS Y ROLES
        $usuario->update($data);
        // 🟢 CRÍTICO: Sincronizar roles a través de la relación
        $usuario->roles()->sync($newRoleIds); 

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario ' . $usuario->nombre . ' actualizado.');
    }

    /**
     * Elimina un usuario del sistema (CRUD: destroy).
     */
    public function destroy(Usuario $usuario)
    {
        // ... (La lógica de destroy se mantiene igual, ya que usa isSuperAdmin())
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        if ($usuario->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            return back()->with('error', 'Solo un Super Administrador puede eliminar a otro Super Administrador.');
        }
        
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario ' . $usuario->nombre . ' eliminado.');
    }
    
    /**
     * Método helper para centralizar la validación de datos del usuario.
     */
    protected function validateUserData(Request $request, $ignoreId)
    {
        // Limpiamos los valores nullable si no se envían
        $request->merge([
            'departamento' => $request->filled('departamento') ? $request->input('departamento') : null,
            'posicion_laboral' => $request->filled('posicion_laboral') ? $request->input('posicion_laboral') : null,
            'es_supervisor' => $request->filled('es_supervisor'),
            'razon_estado' => $request->filled('razon_estado') ? $request->input('razon_estado') : null,
        ]);
        
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($ignoreId)],
            'password' => $ignoreId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            
            // 🟢 CRÍTICO: Validación de Roles (Muchos a Muchos)
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id', // Verifica que cada ID en el array exista
            'estado_cuenta' => ['required', Rule::in(['activo', 'inactivo', 'suspendido', 'pendiente'])],

            // CAMPOS DE CONFIGURACIÓN Y RRHH
            'zona_horaria' => 'required|string|max:60', 
            'idioma_preferido' => 'required|string|max:5',
            'departamento' => 'nullable|string|max:100',
            'posicion_laboral' => 'nullable|string|max:100',
            'es_supervisor' => 'boolean', // Ya se transformó a boolean en el merge
            'razon_estado' => Rule::requiredIf($request->input('estado_cuenta') !== 'activo') . '|nullable|string|max:255',
        ]);
    }
}