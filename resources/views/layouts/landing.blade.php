<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- AÑADIDO: Título, Font Awesome y Fonts específicos --}}
    <title>Ángel.dev | Soluciones Full-Stack y Cierre de Proyectos</title> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ81lKKYazl4A76V0N5t7I3S+R+jRzI8R7B3X7gD+02F5A3gD+02F5A3g6/t0+C/2/V+G0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Mover estilos globales y de utilidades */
        body {
            font-size: 0.95rem; 
        }
        .promise-box-bg {
            background: linear-gradient(135deg, #fef2f2 0%, #fce7f3 100%);
        }
        .tech-card {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); 
        }
        .tech-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1), 0 0 0 2px rgba(236, 72, 153, 0.1); 
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-white min-h-screen flex flex-col"> 

    {{-- 1. HEADER / BARRA DE NAVEGACIÓN PÚBLICA --}}
    @include('layouts.landing-header') 

    {{-- 2. CONTENIDO PRINCIPAL (EL SLIDER, SECCIONES, ETC.) --}}
    <main class="flex-grow">
        {{-- ¡CORRECCIÓN CLAVE AQUÍ! Usamos @yield para la sintaxis @extends --}}
        @yield('content')
    </main>
    
    {{-- 3. FOOTER PÚBLICO --}}
    @include('layouts.landing-footer')

</body>
</html>