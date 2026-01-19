<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'hub-personal'))</title> 

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css']) 
    </head>
    <body class="font-sans antialiased">
        
        <div x-data="{ isMenuOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">

            {{-- 1. MENÚ OFF-CANVAS --}}
            @include('layouts.off-canvas-menu')
            
            {{-- 2. CONTENIDO PRINCIPAL --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                
                {{-- 🚀 BARRA DE NAVEGACIÓN SUPERIOR (FIJA) --}}
                @include('layouts.navigation')

                {{-- 3. AREA DE CONTENIDO --}}
                <main class="flex-1 overflow-x-hidden overflow-y-auto pt-16">
                    
                    {{-- SLOT HEADER: Título opcional debajo de la barra fija --}}
                    @hasSection('header')
                        <div class="px-4 sm:px-6 lg:px-8 py-3 bg-white dark:bg-gray-800 shadow-sm">
                            @yield('header') 
                        </div>
                    @else
                        {{-- Opcional: Mantener compatibilidad si se usa como componente --}}
                        @isset($header)
                            <div class="px-4 sm:px-6 lg:px-8 py-3 bg-white dark:bg-gray-800 shadow-sm">
                                {{ $header }}
                            </div>
                        @endisset
                    @endif

                    {{-- 
                        CONTENIDO PRINCIPAL: 
                        Se modificó para usar @yield('content'), que es lo que esperan las vistas de proyectos.
                        También incluimos el original @yield('contenido') y el slot para máxima compatibilidad. 
                    --}}
                    
                    @yield('content') 
                    
                    {{-- Bloque original (si se sigue usando en otras vistas) --}}
                    @yield('contenido') 
                    
                    {{-- Opcional: Mantener compatibilidad si se usa como componente --}}
                    @empty(trim($__env->yieldContent('content')))
                        @empty(trim($__env->yieldContent('contenido')))
                            {{ $slot ?? '' }} 
                        @endempty
                    @endempty
                </main>
            </div>
        </div>
        
        @vite(['resources/js/app.js'])
    </body>
</html>
