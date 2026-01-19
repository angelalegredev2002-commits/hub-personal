<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role; // Opcional, para mostrar qué roles usan un permiso
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdminPermissionController extends Controller
{
    /**
     * Muestra una lista de todos los permisos (CRUD: index).
     */
    public function index()
    {
        // Cargamos la relación 'roles' si está definida en el modelo Permission
        $permissions = Permission::with('roles')->orderBy('nombre')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Muestra el formulario para crear un nuevo permiso (CRUD: create).
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Almacena un nuevo permiso (CRUD: store).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:permissions,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);
        
        // 🚨 Seguridad: Solo un Super Admin puede crear permisos nuevos, ya que son críticos.
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Solo el Super Administrador puede crear permisos.');
        }

        Permission::create($validatedData);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permiso creado exitosamente.');
    }

    /**
     * Muestra un permiso específico (CRUD: show).
     */
    public function show(Permission $permission)
    {
        // Cargamos los roles que tienen este permiso
        $permission->load('roles');
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Muestra el formulario para editar un permiso (CRUD: edit).
     */
    public function edit(Permission $permission)
    {
        // 🚨 Seguridad: Solo un Super Admin puede editar permisos.
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Solo el Super Administrador puede editar permisos.');
        }
        
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Actualiza un permiso específico (CRUD: update).
     */
    public function update(Request $request, Permission $permission)
    {
        // 🚨 Seguridad: Solo un Super Admin puede actualizar permisos.
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Solo el Super Administrador puede actualizar permisos.');
        }
        
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('permissions', 'nombre')->ignore($permission->id)],
            'descripcion' => 'nullable|string|max:500',
        ]);
        
        $permission->update($validatedData);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permiso ' . $permission->nombre . ' actualizado exitosamente.');
    }

    /**
     * Elimina un permiso del sistema (CRUD: destroy).
     */
    public function destroy(Permission $permission)
    {
        // 🚨 Seguridad: Solo un Super Admin puede eliminar permisos.
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Solo el Super Administrador puede eliminar permisos.');
        }
        
        // Antes de eliminar, verificar si algún rol depende de este permiso.
        // Si tienes configurado onDelete('cascade') en la tabla pivote, esto es opcional,
        // pero es una buena práctica para el mensaje de error.
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el permiso. ' 
                                        . 'Está asignado a ' . $permission->roles()->count() . ' roles.');
        }
        
        $permission->delete();

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permiso ' . $permission->nombre . ' eliminado.');
    }
}
