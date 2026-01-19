@extends('layouts.admin')

@section('title', 'Crear Nuevo Proyecto')

{{-- Usando la misma estructura de Header que el de Reuniones/Tareas --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
        {{ __('Crear Nuevo Proyecto') }}
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    {{-- CAMBIOS CLAVE: max-w-full (ancho completo) y reducción de px-* --}}
    <div class="max-w-full mx-auto space-y-6 px-2 sm:px-4 lg:px-6"> 
        
        {{-- Enlaces de navegación --}}
        {{-- Mantenemos el padding mínimo para que no toque el borde en móviles --}}
        <div class="mb-4 px-2 sm:px-0">
            <a href="{{ route('admin.proyectos.index') }}" class="text-fuchsia-600 hover:text-fuchsia-700 dark:text-fuchsia-400 dark:hover:text-fuchsia-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Volver a Proyectos') }}
            </a>
        </div>

        {{-- Contenedor del Formulario --}}
        {{-- Nota: El padding interno (p-6 lg:p-8) mantiene el contenido legible --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 lg:p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                {{ __('Detalles del Nuevo Proyecto') }}
            </h3>

            {{-- FORMULARIO --}}
            <form method="POST" action="{{ route('admin.proyectos.store') }}" class="space-y-6">
                @csrf

                {{-- SECCIÓN 1: INFORMACIÓN PRINCIPAL (Nombre y Descripción) --}}
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-l-4 border-fuchsia-500 pl-3 mb-4">{{ __('Información Principal') }}</h4>
                <div class="space-y-4">
                    {{-- Nombre del Proyecto --}}
                    <div>
                        <label for="nombre" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nombre del Proyecto') }} <span class="text-red-500">*</span></label>
                        <input id="nombre" name="nombre" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm" value="{{ old('nombre') }}" required autofocus />
                        @error('nombre')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Descripción') }}</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SECCIÓN 2: CONTROL ADMINISTRATIVO Y FECHAS --}}
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-l-4 border-fuchsia-500 pl-3 pt-4 mb-4">{{ __('Control Administrativo y Fechas') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Usuario Creador ID (Administrador puede elegir) --}}
                    <div>
                        <label for="usuario_creador_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Creador del Proyecto') }} <span class="text-red-500">*</span></label>
                        <select id="usuario_creador_id" name="usuario_creador_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm" required>
                            <option value="">{{ __('Seleccionar Creador') }}</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('usuario_creador_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_creador_id')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Límite Estimada --}}
                    <div>
                        <label for="fecha_limite_estimada" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Fecha Límite Estimada') }}</label>
                        <input id="fecha_limite_estimada" name="fecha_limite_estimada" type="date" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm" value="{{ old('fecha_limite_estimada') }}" />
                        @error('fecha_limite_estimada')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Progreso Porcentaje (Admin puede establecer inicialmente) --}}
                    <div>
                        <label for="progreso_porcentaje" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Progreso (%)') }} <span class="text-red-500">*</span></label>
                        <input id="progreso_porcentaje" name="progreso_porcentaje" type="number" min="0" max="100" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm" value="{{ old('progreso_porcentaje', 0) }}" required />
                        @error('progreso_porcentaje')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Presupuesto Estimado --}}
                    <div>
                        <label for="presupuesto_estimado" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Presupuesto Estimado') }}</label>
                        <input id="presupuesto_estimado" name="presupuesto_estimado" type="number" min="0" step="0.01" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm" value="{{ old('presupuesto_estimado') }}" />
                        @error('presupuesto_estimado')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Es Privado --}}
                    <div class="flex items-center pt-5">
                        <input id="es_privado" name="es_privado" type="checkbox" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-fuchsia-600 shadow-sm focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600" {{ old('es_privado') ? 'checked' : '' }}>
                        <label for="es_privado" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Proyecto Privado') }}</label>
                        @error('es_privado')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SECCIÓN 3: EQUIPO DE TRABAJO (Miembros) --}}
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-l-4 border-fuchsia-500 pl-3 mb-4">{{ __('Equipo de Trabajo') }}</h4>
                    {{-- Miembros del Proyecto (Multi-select) --}}
                    <div>
                        <label for="miembros" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Asignar Miembros (Editores)') }}</label>
                        <select id="miembros" name="miembros[]" multiple class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm h-48">
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('miembros', [])) ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Mantén Ctrl (o Cmd) para seleccionar múltiples usuarios. El creador seleccionado arriba será añadido automáticamente como Líder.') }}</p>
                        @error('miembros')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Botón de Guardar --}}
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        {{ __('Guardar Proyecto') }}
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection