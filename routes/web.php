<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 🟢 CONTROLADORES DE ADMINISTRACIÓN
use App\Http\Controllers\Admin\AdminUsuarioController; 
use App\Http\Controllers\Admin\AdminConversacionController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminPermissionController; 
use App\Http\Controllers\Admin\AdminReunionController; 
use App\Http\Controllers\Admin\AdminProyectoController; 
use App\Http\Controllers\Admin\AdminTareaController; 

// 🟢 OTROS CONTROLADORES
use App\Http\Controllers\ConversacionController;
use App\Http\Controllers\ProyectoController; 
use App\Http\Controllers\ReunionController;  
use App\Http\Controllers\TareaController; 
// 🟢 CRÍTICO: Importamos la clase del middleware
use App\Http\Middleware\EnsureUserIsAdmin; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// =================================================================
// GRUPO DE RUTAS AUTENTICADAS (Auth)
// =================================================================
Route::middleware('auth')->group(function () {
    
    // RUTAS DE PERFIL
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 💼 RUTAS DE PROYECTOS (USUARIO)
    // 🛑 RESTRICCIÓN IMPLEMENTADA: Los usuarios solo pueden ver la lista (index) y el detalle (show).
    Route::resource('proyectos', ProyectoController::class)->only(['index', 'show']);

    // ✅ RUTAS DE TAREAS (USUARIO)
    Route::resource('tareas', TareaController::class);
    Route::post('/tareas/{tarea}/toggle', [TareaController::class, 'toggleCompletion'])->name('tareas.toggle'); // Ruta para marcar como completada/pendiente

    // 📅 RUTAS DE REUNIONES (USUARIO NORMAL)
    Route::resource('reuniones', ReunionController::class)->only(['index', 'show']);
    Route::post('/reuniones/{reunion}/asistencia', [ReunionController::class, 'confirmarAsistencia'])->name('reuniones.asistencia');
    Route::post('/reuniones/{reunion}/minuta', [ReunionController::class, 'guardarMinuta'])->name('reuniones.minuta'); 
    
    // 💬 CHAT ROUTES (Web y API)
    Route::get('/chat', [ConversacionController::class, 'index'])->name('chat.panel'); 
    
    Route::prefix('api')->group(function () {
        Route::get('/usuarios-disponibles', [ConversacionController::class, 'getAvailableUsers'])->name('chat.users');
        Route::post('/chat/iniciar/{userId}', [ConversacionController::class, 'createOrGetChat'])->name('chat.create');
        Route::get('/chats', [ConversacionController::class, 'getChats'])->name('api.chats.index'); 
        Route::get('/chats/{conversacion}', [ConversacionController::class, 'show'])->name('chat.show');
        Route::post('/chats/{conversacion}/mensajes', [ConversacionController::class, 'storeMessage'])->name('chat.mensaje.store');
    });

    // 🛡️ NUEVA RUTA: Iniciar chat con un Admin (para solicitudes de acceso/ayuda)
    Route::get('/chat/contactar-admin', [ConversacionController::class, 'contactAdmin'])->name('chat.admin');


    // =================================================================
    // 🛡️ RUTAS DE ADMINISTRACIÓN: Usando la clase completa del middleware
    // =================================================================
    Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        
        // 💼 CRUD de Proyectos (ADMIN) - Acceso Completo
        Route::resource('proyectos', AdminProyectoController::class);
        
        // ✅ CRUD de Tareas (ADMIN)
        Route::resource('tareas', AdminTareaController::class);

        // 📅 CRUD de Reuniones (ADMIN)
        Route::resource('reuniones', AdminReunionController::class);
        
        // CRUD de Usuarios
        Route::resource('usuarios', AdminUsuarioController::class);
        
        // CRUD de Roles
        Route::resource('roles', AdminRoleController::class); 
        
        // CRUD de Permisos
        Route::resource('permissions', AdminPermissionController::class)->except(['destroy']);
        
        // CRUD de Conversaciones (para Ver y Eliminar)
        Route::resource('conversaciones', AdminConversacionController::class)
             ->only(['index', 'show', 'destroy'])
             ->parameters(['conversaciones' => 'conversacion']);

        // Ruta para Eliminar Mensajes Específicos
        Route::delete('mensajes/{mensaje}', [AdminConversacionController::class, 'destroyMessage'])->name('mensajes.destroy');
        
    });

});


// Carga las rutas de Autenticación (login, register, logout, etc.)
require __DIR__.'/auth.php';
