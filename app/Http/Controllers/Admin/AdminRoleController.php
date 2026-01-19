<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission; // Asumimos que tienes este modelo
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdminRoleController extends Controller
{
    /**
     * Muestra una lista de todos los roles (CRUD: index).
     */
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('nivel', 'desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo rol (CRUD: create).
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Almacena un nuevo rol (CRUD: store).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre',
            'nivel' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id', // Verifica que todos los IDs existan
        ]);

        // 🚨 Seguridad: Solo un Super Admin puede crear roles con nivel 100 (máximo)
        if ($validatedData['nivel'] >= 100 && !Auth::user()->isSuperAdmin()) {
            return back()->with('error', 'Solo un Super Administrador puede crear roles de máximo nivel.')
                         ->withInput();
        }

        $role = Role::create($validatedData);
        
        // Sincronizar permisos (Many-to-Many)
        if (isset($validatedData['permissions'])) {
            $role->permissions()->sync($validatedData['permissions']);
        }

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Rol creado y permisos asignados exitosamente.');
    }

    /**
     * Muestra un rol específico (CRUD: show).
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Muestra el formulario para editar un rol (CRUD: edit).
     */
    public function edit(Role $role)
    {
        // 🚨 Seguridad: No permitir editar el rol 'super_administrador' si no eres Super Admin
        if ($role->nombre === 'super_administrador' && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Solo el Super Administrador puede editar este rol.');
        }

        $permissions = Permission::all();
        // Obtener los IDs de los permisos actualmente asignados
        $rolePermissions = $role->permissions->pluck('id')->toArray(); 

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualiza un rol específico (CRUD: update).
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('roles', 'nombre')->ignore($role->id)],
            'nivel' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        // 🚨 Seguridad: Impedir que un admin normal baje el nivel del Super Admin o lo edite sin ser Super Admin.
        if ($role->nombre === 'super_administrador' && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Solo el Super Administrador puede editar este rol.');
        }

        // 🚨 Seguridad: Un admin normal no puede cambiar el nivel de un rol a Super Admin (100)
        if ($validatedData['nivel'] >= 100 && !Auth::user()->isSuperAdmin()) {
             return back()->with('error', 'Solo un Super Administrador puede asignar roles de máximo nivel.')
                          ->withInput();
        }
        
        $role->update($validatedData);
        
        // Sincronizar permisos
        if (isset($validatedData['permissions'])) {
            $role->permissions()->sync($validatedData['permissions']);
        } else {
            // Si no se selecciona ninguno, desasociar todos
            $role->permissions()->sync([]); 
        }

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Rol ' . $role->nombre . ' actualizado exitosamente.');
    }

    /**
     * Elimina un rol del sistema (CRUD: destroy).
     */
    public function destroy(Role $role)
    {
        // 🚨 Seguridad: No permitir eliminar el rol 'super_administrador' o roles asociados a usuarios.
        if ($role->nombre === 'super_administrador') {
            return back()->with('error', 'El rol principal de Super Administrador no puede ser eliminado.');
        }

        // Verifica si hay usuarios asociados (gracias a onDelete('restrict') en la migración de usuarios)
        if ($role->usuarios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el rol. Hay ' . $role->usuarios()->count() . ' usuarios asociados.');
        }
        
        // Eliminar las asociaciones de permisos primero (si el onDelete en la pivote es 'cascade', esto es opcional)
        $role->permissions()->sync([]); 
        
        $role->delete();

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Rol ' . $role->nombre . ' eliminado.');
    }
}