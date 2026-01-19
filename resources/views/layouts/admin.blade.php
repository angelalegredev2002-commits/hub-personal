<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Usamos @yield('title') para que las vistas específicas puedan cambiar el título --}}
        <title>@yield('title', config('app.name', 'hub-personal') . ' | Admin')</title> 

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css']) 
        {{-- Aquí puedes agregar CSS específico de administrador si lo necesitas más tarde --}}
    </head>
    <body class="font-sans antialiased">
        
        {{-- Mantenemos el estado del menú y el contenedor principal --}}
        <div x-data="{ isMenuOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">

            {{-- 1. MENÚ OFF-CANVAS: Se usa el mismo, confiando en que tiene la lógica condicional para mostrar los enlaces de admin --}}
            @include('layouts.off-canvas-menu')
            
            {{-- 2. CONTENIDO PRINCIPAL --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                
                {{-- 🚀 BARRA DE NAVEGACIÓN SUPERIOR (FIJA) --}}
                @include('layouts.navigation')

                {{-- 3. AREA DE CONTENIDO --}}
                <main class="flex-1 overflow-x-hidden overflow-y-auto pt-16">
                    
                    {{-- SLOT HEADER: Título opcional, ideal para breadcrumbs o títulos de página --}}
                    @hasSection('header')
                        <div class="px-4 sm:px-6 lg:px-8 py-3 bg-white dark:bg-gray-800 shadow-sm">
                            @yield('header') 
                        </div>
                    @else
                        {{-- Añadimos un título predeterminado si no se define 'header' --}}
                        <div class="px-4 sm:px-6 lg:px-8 py-3 bg-white dark:bg-gray-800 shadow-sm">
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                Administración
                            </h2>
                        </div>
                    @endif

                    {{-- CONTENIDO PRINCIPAL: Usa @yield('contenido') para inyectar la vista admin/proyectos/index, etc. --}}
                    <div class="py-6">
                        @yield('content') 
                        {{-- NOTA: Tus vistas de admin usan @section('content'), así que cambié el @yield de 'contenido' a 'content' aquí. --}}
                    </div>
                    
                </main>
                
                {{-- Opcional: Puedes incluir un footer de admin diferente si lo deseas --}}
                {{-- @include('layouts.admin-footer') --}}
                
            </div>
        </div>
        
        @vite(['resources/js/app.js'])
    </body>
</html>