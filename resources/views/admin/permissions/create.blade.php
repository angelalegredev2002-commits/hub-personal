<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Crear Nuevo Permiso') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-4xl mx-auto px-2 sm:px-4 lg:px-6">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8">
                    
                    <header class="mb-6 border-b pb-3 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ __('Información del Permiso') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Define un nuevo permiso para el sistema.') }}
                        </p>
                    </header>

                    <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-6">
                        @csrf

                        {{-- Campo: Nombre del Permiso --}}
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre del Permiso')" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre')" required autofocus placeholder="ej: users.create" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Formato recomendado: recurso.accion (ej: posts.delete).') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
                        </div>

                        {{-- Campo: Descripción --}}
                        <div>
                            <x-input-label for="descripcion" :value="__('Descripción')" />
                            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 focus:ring-fuchsia-500 rounded-lg shadow-sm" placeholder="Permite al usuario crear nuevas publicaciones.">{{ old('descripcion') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
                        </div>
                        
                        {{-- Botones de Acción --}}
                        <div class="flex items-center gap-4 pt-4 border-t dark:border-gray-700 mt-6">
                            <x-primary-button class="bg-fuchsia-600 hover:bg-fuchsia-700">
                                {{ __('Crear Permiso') }}
                            </x-primary-button>
                            
                            <a href="{{ route('admin.permissions.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>