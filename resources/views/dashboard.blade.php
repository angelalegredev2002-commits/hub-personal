<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    {{-- Contenedor Principal: Se usa py-8 para más espacio --}}
    <div class="py-8"> 
        {{-- CONTENEDOR DE CONTENIDO: Ancho completo (w-full) y padding horizontal mínimo (px-2 md:px-4) --}}
        <div class="w-full space-y-8 px-2 md:px-4 lg:px-6"> 
            
            {{-- TARJETA PRINCIPAL (Unifica Saludo, KPIs y Widgets) --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-8">
                
                @auth
                    {{-- 1. SALUDO DINÁMICO Y ROL (Lógica de Rol Corregida) --}}
                    <div>
                        {{-- Definición del Rol y Color basado en la colección de roles M:N --}}
                        @php
                            $userRoles = Auth::user()->roles->pluck('nombre')->toArray();
                            
                            $roleName = 'Usuario';
                            $roleColor = 'bg-gray-500';

                            if (in_array('super_administrador', $userRoles)) {
                                $roleName = 'Super Administrador';
                                $roleColor = 'bg-red-600';
                            } elseif (in_array('administrador', $userRoles)) {
                                $roleName = 'Administrador';
                                $roleColor = 'bg-indigo-600';
                            } elseif (in_array('empleado', $userRoles)) {
                                $roleName = 'Empleado';
                                $roleColor = 'bg-sky-600'; // Nuevo color para Empleado
                            }
                        @endphp

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div>
                                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                                    ¡Bienvenido de vuelta, {{ Auth::user()->nombre ?? Auth::user()->name }}!
                                </h1>
                                <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                                    Aquí tienes un resumen rápido de tu actividad y recursos.
                                </p>
                            </div>
                            
                            <div class="mt-4 md:mt-0">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold uppercase tracking-wider text-white {{ $roleColor }} shadow-lg">
                                    <i class="fas fa-user-tag me-2 text-xs opacity-90"></i>
                                    Rol: {{ $roleName }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endauth
                
                {{-- 2. INDICADORES CLAVE (KPIs) - 3 COLUMNAS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
                    
                    {{-- Indicador 1: Tareas Pendientes (KPI de Alerta - Rojo) --}}
                    <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-inner border-l-4 border-red-500">
                        <h4 class="text-xs uppercase font-bold text-red-700 dark:text-red-400 mb-1 flex items-center">
                            <i class="fas fa-tasks mr-2"></i> Tareas Pendientes
                        </h4>
                        <p class="text-3xl font-extrabold text-red-800 dark:text-red-300 mt-1">
                            5
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Requieren tu atención inmediata.</p>
                    </div>
                    
                    {{-- Indicador 2: Estado de la Cuenta (KPI de Éxito - Verde) --}}
                    <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-inner border-l-4 border-green-500">
                        <h4 class="text-xs uppercase font-bold text-green-700 dark:text-green-400 mb-1 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i> Estado de la Cuenta
                        </h4>
                        <p class="text-3xl font-extrabold text-green-800 dark:text-green-300 mt-1">
                            Verificada
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Última verificación: Hace 2 días.</p>
                    </div>
                    
                    {{-- Indicador 3: Última Conexión (KPI de Información - Sky Blue) --}}
                    <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-inner border-l-4 border-sky-500">
                        <h4 class="text-xs uppercase font-bold text-sky-700 dark:text-sky-400 mb-1 flex items-center">
                            <i class="fas fa-history mr-2"></i> Último Acceso
                        </h4>
                        <p class="text-xl font-extrabold text-sky-800 dark:text-sky-300 mt-1">
                            {{ Auth::user()->ultimo_login_en ? Auth::user()->ultimo_login_en->diffForHumans() : 'Hoy' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Desde {{ request()->ip() ?? 'Ubicación desconocida' }}.</p>
                    </div>
                </div>

                {{-- 3. WIDGETS DE TAREAS Y ACTIVIDAD - 2 COLUMNAS --}}
                <div class="grid md:grid-cols-2 gap-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    
                    {{-- WIDGET A: Notificaciones / Avisos Importantes --}}
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold mb-4 text-slate-800 dark:text-white flex items-center">
                            <i class="fas fa-bell text-yellow-500 mr-2"></i>
                            Avisos y Alertas
                        </h3>
                        <ul class="text-sm space-y-3 text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3 text-sm"></i>
                                <div>
                                    <span class="font-bold">URGENTE:</span> Hay 2 tickets pendientes sin asignar.
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-shield-alt text-sky-500 mt-1 mr-3 text-sm"></i>
                                <div>
                                    <span class="font-bold">Seguridad:</span> Verifica tu perfil para la autenticación de dos factores.
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-info-circle text-green-500 mt-1 mr-3 text-sm"></i>
                                <div>
                                    <span class="font-bold">Noticia:</span> Sistema actualizado a la versión 0.0.1.
                                </div>
                            </li>
                        </ul>
                        <a href="#" class="text-sky-600 hover:text-sky-700 font-medium text-sm flex items-center mt-5">
                            Ver Panel de Notificaciones
                            <i class="fas fa-arrow-right text-xs ms-2"></i>
                        </a>
                    </div>
                    
                    {{-- WIDGET B: Acceso Rápido --}}
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold mb-4 text-slate-800 dark:text-white flex items-center">
                            <i class="fas fa-link text-indigo-500 mr-2"></i>
                            Recursos y Enlaces Rápidos
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Accede rápidamente a documentación clave o ajusta la configuración principal de tu cuenta.
                        </p>
                        <ul class="text-sm space-y-3">
                            <li>
                                <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 font-medium flex items-center transition duration-150">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Manual de Procesos y Guías (PDF) 
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 font-medium flex items-center transition duration-150">
                                    <i class="fas fa-cog mr-2"></i>
                                    Editar mi Perfil y Seguridad 
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 font-medium flex items-center transition duration-150">
                                    <i class="fas fa-headset mr-2"></i>
                                    Abrir un Ticket de Soporte
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</x-app-layout>