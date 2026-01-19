<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ángel.dev') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ81lKKYazl4A76V0N5t7I3S+R+jRzI8R7B3X7gD+02F5A3gD+02F5A3g6/t0+C/2/V+G0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    {{-- Tipografía base para el cuerpo --}}
    <body class="font-sans text-gray-800 antialiased">
        
        {{-- CONTENEDOR PRINCIPAL: Fondo a un gris muy sutil (bg-gray-50) y centrado --}}
        {{-- Quitamos el padding vertical (py-8) para centrar el contenido más ajustado --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-50">
            
            {{-- ELIMINAMOS EL LOGO EXTERNO. Ahora el logo estará DENTRO del $slot (el formulario de login) --}}
            
            {{-- Simplemente renderiza el slot. El formulario de login define su propia tarjeta y ancho máximo. --}}
            {{ $slot }}
        </div>
    </body>
</html>
