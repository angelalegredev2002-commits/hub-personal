<x-app-layout>
    <x-slot name="header">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Mi Agenda de Reuniones') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- CONTENEDOR PRINCIPAL: Usa el ancho completo con padding horizontal --}}
        <div class="max-w-full mx-auto space-y-6 px-4 sm:px-6 lg:px-8"> 
            
            {{-- 1. BOTÓN DE CREACIÓN: Visible SOLO para administradores (con SVG moderno) --}}
            @if (auth()->user()->es_administrador ?? false)
            <div class="flex justify-end">
                <a href="{{ route('admin.reuniones.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-700 hover:bg-indigo-600 active:bg-indigo-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-wider transition ease-in-out duration-150 shadow-md transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-indigo-500/50">
                    {{-- SVG INLINE para un estilo más limpio --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Programar Nueva Reunión
                </a>
            </div>
            @endif

            {{-- 2. MENSAJES DE ESTADO (Éxito/Error) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg shadow-md dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
                    <span class="font-semibold text-sm">{{ __('¡Éxito!') }}</span> <span class="text-xs">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg shadow-md dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                    <span class="font-semibold text-sm">{{ __('¡Error!') }}</span> <span class="text-xs">{{ session('error') }}</span>
                </div>
            @endif

            {{-- 3. LISTADO DE REUNIONES (Diseño de Card y Tipografía Compacta) --}}
            @if ($reuniones->isEmpty())
                <div class="text-center py-10 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-100 dark:border-gray-700 mt-4">
                    <p class="text-lg text-gray-500 dark:text-gray-400 font-medium mb-1">Sin Reuniones Programadas </p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                        @if (auth()->user()->es_administrador ?? false)
                            Usa el botón superior para empezar a programar.
                        @else
                            Aún no has sido invitado a ninguna reunión.
                        @endif
                    </p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Título
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Fecha y Hora
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                                        Proyecto
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                                        Rol
                                    </th>
                                    <th class="px-4 py-3"></th> {{-- Acciones --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($reuniones as $reunion)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $reunion->titulo }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700 dark:text-gray-300">
                                            {{ $reunion->fecha_hora_inicio->translatedFormat('j M Y') }}<br class="sm:hidden" />
                                            <span class="font-semibold">{{ $reunion->fecha_hora_inicio->translatedFormat('H:i') }} hrs</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap hidden md:table-cell">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $reunion->proyecto ? $reunion->proyecto->nombre : 'Personal' }}</p>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{-- Lógica para mostrar el estado con un badge --}}
                                            @php
                                                $color = match($reunion->estado) {
                                                    'finalizada' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                    'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    'en_curso' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                    default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $reunion->estado)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                                            @if ($reunion->usuario_creador_id === auth()->id())
                                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">CREADOR</span>
                                            @else
                                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">INVITADO</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-xs font-medium">
                                            <a href="{{ route('reuniones.show', $reunion) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-semibold transition duration-150 mr-2">
                                                Ver
                                            </a>
                                            {{-- Editar solo si es administrador --}}
                                            @if (auth()->user()->es_administrador ?? false) 
                                                <a href="{{ route('admin.reuniones.edit', $reunion) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 font-semibold transition duration-150">
                                                    Editar
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Paginación --}}
                <div class="pt-3 flex justify-center">
                    {{ $reuniones->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
