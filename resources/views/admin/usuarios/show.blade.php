<x-app-layout>
    <x-slot name="header">
        {{-- Quitamos padding extra aquí --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Detalles del Usuario') }}: <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400">{{ $usuario->nombre }}</span>
        </h2>
    </x-slot>

    {{-- Contenedor Principal: Usa max-w-full y padding lateral mínimo --}}
    <div class="py-4 sm:py-6"> 
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6 space-y-4">
            
            {{-- Mensajes de Notificación Global --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg shadow-md text-sm mx-1 sm:mx-0 dark:bg-green-900/30 dark:border-green-600 dark:text-green-300" role="alert">
                    <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
                </div>
            @endif
            
            {{-- ENCABEZADO: Avatar y Acciones --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center mx-1 sm:mx-0">
                <header class="flex items-center space-x-4 mb-4 sm:mb-0">
                    
                    {{-- Avatar --}}
                    @php
                        $hash = md5(strtolower(trim($usuario->email)));
                        $avatarUrl = "https://www.gravatar.com/avatar/{$hash}?s=80&d=mp";
                    @endphp
                    <div class="flex-shrink-0">
                         @if ($usuario->foto_perfil_ruta)
                            <img class="h-14 w-14 sm:h-16 sm:w-16 rounded-full object-cover border-2 border-fuchsia-500" 
                                src="{{ Storage::url($usuario->foto_perfil_ruta) }}" 
                                alt="{{ $usuario->nombre }}">
                        @else
                            <img class="h-14 w-14 sm:h-16 sm:w-16 rounded-full object-cover" 
                                src="{{ $avatarUrl }}" 
                                alt="{{ $usuario->nombre }}"
                                onerror="this.onerror=null;this.src='https://placehold.co/64x64/7c3aed/ffffff?text={{ strtoupper(substr($usuario->nombre, 0, 1)) }}';">
                        @endif
                    </div>

                    {{-- Nombre y Título --}}
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight">
                            {{ $usuario->nombre }}
                        </h2>
                        <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                             {{ $usuario->posicion_laboral ?? 'Usuario sin puesto definido' }}
                        </p>
                    </div>
                </header>
                
                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex flex-col sm:flex-row justify-start gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="inline-flex items-center justify-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        {{ __('Editar Usuario') }}
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Volver a la Lista') }}
                    </a>
                </div>
            </div>

            {{-- GRID PRINCIPAL DE CONTENIDO: 2 Columnas en pantallas grandes --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mx-1 sm:mx-0">

                {{-- COLUMNA IZQUIERDA (2/3): INFORMACIÓN LABORAL y CONTACTO --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- 1. INFORMACIÓN LABORAL Y DE ESTADO --}}
                    <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">{{ __('Información Laboral y Estado') }}</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                            
                            {{-- Estado Cuenta --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Estado de Cuenta') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">
                                    @php
                                        $statusClass = match ($usuario->estado_cuenta) {
                                            'activo' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'inactivo' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'suspendido' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md {{ $statusClass }}">
                                        {{ ucwords($usuario->estado_cuenta ?? 'N/A') }}
                                    </span>
                                </dd>
                            </div>
                            
                            {{-- Departamento --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Departamento') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100 font-semibold">{{ $usuario->departamento ?? 'N/A' }}</dd>
                            </div>

                            {{-- Posición Laboral --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Posición Laboral') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->posicion_laboral ?? 'N/A' }}</dd>
                            </div>

                            {{-- Razón de Estado (Ancho completo si aplica) --}}
                            @if ($usuario->razon_estado)
                                <div class="flex flex-col sm:col-span-3">
                                    <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Razón del Estado') }}</dt>
                                    <dd class="mt-0.5 text-sm text-red-600 dark:text-red-400 italic">{{ $usuario->razon_estado }}</dd>
                                </div>
                            @endif

                            {{-- Es Supervisor --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Supervisor/Manager') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">
                                    @if ($usuario->es_supervisor)
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ __('Sí') }}</span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">{{ __('No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            
                            {{-- Fecha Contratación --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Fecha Contratación') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->fecha_contratacion ? \Carbon\Carbon::parse($usuario->fecha_contratacion)->format('d/M/Y') : 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                    
                    {{-- 2. CONTACTO Y LOCALIZACIÓN --}}
                    <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">{{ __('Información de Contacto y Regional') }}</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                            
                            {{-- Email --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Email') }}</dt>
                                <dd class="mt-0.5 text-fuchsia-600 dark:text-fuchsia-400 font-semibold break-all">{{ $usuario->email }}</dd>
                            </div>
                            
                            {{-- Teléfono Principal --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Teléfono Principal') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->telefono_principal ?? 'N/A' }}</dd>
                            </div>

                            {{-- Número Celular --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Número Celular') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->numero_celular ?? 'N/A' }}</dd>
                            </div>
                            
                            {{-- Zona Horaria --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Zona Horaria') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->zona_horaria ?? 'N/A' }}</dd>
                            </div>
                            
                            {{-- Idioma Preferido --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Idioma Preferido') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100 uppercase">{{ $usuario->idioma_preferido ?? 'es' }}</dd>
                            </div>

                            {{-- ID de Usuario --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('ID Único') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100 font-mono">{{ $usuario->id }}</dd>
                            </div>
                        </div>
                    </div>
                    
                </div>

                {{-- COLUMNA DERECHA (1/3): ROLES y METADATOS --}}
                <div class="lg:col-span-1 space-y-4">
                    
                    {{-- 3. ROLES ASIGNADOS --}}
                    <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">{{ __('Roles de Seguridad') }}</h3>
                        <div class="flex flex-col space-y-3">
                            @forelse ($usuario->roles as $role)
                                @php
                                    // Define colores basados en el nombre del rol
                                    $color = match ($role->nombre) {
                                        'super_administrador' => 'red',
                                        'administrador' => 'pink',
                                        'usuario_estandar' => 'blue',
                                        default => 'indigo',
                                    };
                                @endphp
                                <div class="p-2 border-l-4 border-{{ $color }}-500 bg-{{ $color }}-50 dark:bg-gray-700/50 rounded-md shadow-sm">
                                    <p class="text-sm font-semibold text-{{ $color }}-800 dark:text-{{ $color }}-400">
                                        {{ ucwords(str_replace('_', ' ', $role->nombre)) }}
                                    </p>
                                    <p class="text-[0.65rem] text-gray-600 dark:text-gray-400 mt-0.5">
                                        {{ Str::limit($role->descripcion, 70) }}
                                    </p>
                                </div>
                            @empty
                                <div class="p-2 border-l-4 border-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                        {{ __('Sin Rol Asignado') }}
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- 4. METADATOS Y TRAZABILIDAD --}}
                    <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">{{ __('Trazabilidad y Fechas') }}</h3>
                        <div class="space-y-3 text-sm">
                            
                            {{-- Último Login --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Último Login') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->ultimo_login_en ? $usuario->ultimo_login_en->format('d/M/Y H:i') : 'Nunca' }}</dd>
                            </div>

                            {{-- Registro Creado --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Registro Creado') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->created_at->format('d/M/Y H:i') }}</dd>
                            </div>

                            {{-- Última Actualización --}}
                            <div class="flex flex-col">
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Última Actualización') }}</dt>
                                <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $usuario->updated_at->diffForHumans() }}</dd>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</x-app-layout>