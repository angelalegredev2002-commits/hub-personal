<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    {{-- Contenedor principal sin el padding exagerado --}}
    <div class="px-4 sm:px-6 lg:px-8 py-4 space-y-6"> 
        
        {{-- 1. Formulario de Información Personal (Ocupa todo el ancho) --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="w-full"> 
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- 2. Formulario de Contraseña (Ancho limitado) --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- 3. Formulario de Eliminación de Cuenta (Ancho limitado) --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>