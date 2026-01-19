{{-- HEADER FINAL: Altura funcional (h-24) con animación de Flujo de Energía de alta densidad. --}}
<style>
    /* 1. Definición de la animación de Flujo de Energía (movimiento diagonal y vertical) */
    @keyframes energyFlow {
        0% {
            transform: translate(0, 0) rotate(0deg);
            opacity: 0.8; 
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }
        50% {
            transform: translate(30px, -150px) rotate(180deg); /* Desplazamiento horizontal a la derecha */
            opacity: 0.3;
        }
        100% {
            transform: translate(-30px, -350px) rotate(360deg); /* Desplazamiento horizontal a la izquierda y elevación alta */
            opacity: 0;
            border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
        }
    }

    /* 2. Estilo base del contenedor de animación */
    .particle-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }

    /* 3. Generación y animación de las partículas (Luminosas y con Blur extremo) */
    .particle-container .particle {
        position: absolute;
        /* Default: Blanco Luminoso */
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5); /* Sombra para efecto de brillo */
        filter: blur(15px); /* Mayor blur para efecto de luz/vapor */
        animation: energyFlow infinite ease-in-out;
        z-index: -1;
    }

    /* Partículas de color TEAL FLUORESCENTE para contraste */
    .particle-container .teal-flow {
        background: rgba(0, 255, 200, 0.2); /* Teal Fluorescente */
        box-shadow: 0 0 15px rgba(0, 255, 200, 0.7);
    }
    
    /* Configuración de las 12 Partículas con valores aleatorios (Aumentada la densidad) */
    .particle-container .p1 { width: 70px; height: 70px; left: 10%; top: 80%; animation-duration: 25s; animation-delay: 0s; }
    .particle-container .p2 { width: 50px; height: 50px; left: 30%; top: 90%; animation-duration: 15s; animation-delay: 5s; }
    .particle-container .p3 { width: 90px; height: 90px; left: 60%; top: 70%; animation-duration: 30s; animation-delay: 10s; }
    .particle-container .p4 { width: 40px; height: 40px; left: 85%; top: 85%; animation-duration: 18s; animation-delay: 3s; }
    .particle-container .p5 { width: 65px; height: 65px; left: 20%; top: 60%; animation-duration: 22s; animation-delay: 7s; }
    .particle-container .p6 { width: 55px; height: 55px; left: 75%; top: 50%; animation-duration: 28s; animation-delay: 12s; }

    .particle-container .p7 { width: 45px; height: 45px; left: 5%; top: 50%; animation-duration: 14s; animation-delay: 1s; }
    .particle-container .p8 { width: 80px; height: 80px; left: 90%; top: 95%; animation-duration: 20s; animation-delay: 6s; }
    .particle-container .p9 { width: 35px; height: 35px; left: 45%; top: 55%; animation-duration: 24s; animation-delay: 9s; }
    .particle-container .p10 { width: 75px; height: 75px; left: 15%; top: 30%; animation-duration: 16s; animation-delay: 4s; }
    .particle-container .p11 { width: 50px; height: 50px; left: 65%; top: 40%; animation-duration: 19s; animation-delay: 8s; }
    .particle-container .p12 { width: 60px; height: 60px; left: 80%; top: 20%; animation-duration: 21s; animation-delay: 11s; }

    /* Estilo para el texto principal para que sobresalga de la animación */
    .logo-text-shadow {
        text-shadow: 0 0 5px rgba(0, 255, 200, 0.6), 0 0 10px rgba(0, 0, 0, 0.8);
    }
</style>

{{-- HEADER OSCURO Y AJUSTADO (h-24) --}}
<header class="sticky top-0 z-40 bg-gray-900 shadow-xl transition duration-500 ease-in-out relative">
    
    {{-- CAPA DE ANIMACIÓN DE FLUJO DE ENERGÍA --}}
    <div class="particle-container">
        <div class="particle p1"></div>
        <div class="particle p2 teal-flow"></div>
        <div class="particle p3"></div>
        <div class="particle p4 teal-flow"></div>
        <div class="particle p5"></div>
        <div class="particle p6 teal-flow"></div>
        <div class="particle p7"></div>
        <div class="particle p8 teal-flow"></div>
        <div class="particle p9"></div>
        <div class="particle p10 teal-flow"></div>
        <div class="particle p11"></div>
        <div class="particle p12 teal-flow"></div>
    </div>
    
    {{-- CONTENIDO PRINCIPAL (Altura estándar: h-24) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20 relative z-10">
        
        {{-- 1. LOGO / BRANDING --}}
        {{-- Acento en teal-400 (brillante) y sombra de texto --}}
        <a href="{{ url('/') }}" class="flex-shrink-0 text-1xl uppercase tracking-tight text-white transition duration-300 hover:text-teal-400 logo-text-shadow" aria-label="Inicio">
            Cotalist
            <span class="hidden sm:inline text-teal-400 font-normal text-sm ml-2 uppercase tracking-widest opacity-90 logo-text-shadow">
                | Soluciones Full-Stack
            </span>
        </a>

        {{-- 2. NAVEGACIÓN PRINCIPAL (Desktop) --}}
        <nav class="hidden md:flex items-center space-x-7 h-full">
            
            {{-- Enlaces de Navegación (Hover y Underline en Teal-400) --}}
            <a href="#propuesta" class="text-sm font-medium text-gray-300 hover:text-teal-400 transition duration-300 relative group py-1" aria-label="Nuestra Propuesta">
                Propuesta
                <span class="absolute bottom-0 left-0 w-full h-[2px] bg-teal-400 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
            </a>
            <a href="#confian" class="text-sm font-medium text-gray-300 hover:text-teal-400 transition duration-300 relative group py-1" aria-label="Clientes y Testimonios">
                Clientes
                <span class="absolute bottom-0 left-0 w-full h-[2px] bg-teal-400 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
            </a>
            <a href="#habilidades" class="text-sm font-medium text-gray-300 hover:text-teal-400 transition duration-300 relative group py-1" aria-label="Stack Técnico">
                Stack
                <span class="absolute bottom-0 left-0 w-full h-[2px] bg-teal-400 transform scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
            </a>
            
            {{-- 3. CTA DE ACCESO (Desktop) --}}
            @if (Route::has('login'))
                <span class="h-4 w-[2px] bg-gray-700 mx-2"></span>
                @auth
                    {{-- Botón Panel en Azul Marino Claro (blue-600) --}}
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 py-2.5 px-5 rounded-lg transition duration-300 shadow-xl shadow-blue-600/50" aria-label="Acceder al Panel de Control">
                        Panel de Control
                    </a>
                @else
                    {{-- Botón Acceder en Teal (Borde/Hover) --}}
                    <a href="{{ route('login') }}" class="text-xs uppercase text-teal-400 hover:text-gray-900 py-2.5 px-5 transition duration-300 border-2 border-teal-400 rounded-lg hover:bg-teal-400 shadow-xl ml-2" aria-label="Acceder al Hub de Clientes">
                        Acceder al Hub
                    </a>
                @endauth
            @endif
        </nav>

        {{-- 4. BOTONES MÓVILES --}}
        <div class="flex items-center space-x-2 md:hidden flex-shrink-0">
            
            @if (Route::has('login'))
                @auth
                    {{-- Panel Móvil en Azul Marino Claro (blue-600) --}}
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-full transition duration-300" aria-label="Panel de Control">
                        Panel
                    </a>
                @else
                    {{-- Acceder Móvil en Teal --}}
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-200 hover:text-teal-400 transition duration-300 py-2 px-4 border border-teal-400 rounded-full hover:bg-teal-400 hover:text-gray-900" aria-label="Acceder">
                        Acceder
                    </a>
                @endauth
            @endif

            {{-- Menú Hamburguesa (Icono en blanco, Hover en Teal) --}}
            <button type="button" class="text-white hover:text-teal-400 transition duration-300 p-2 rounded-full hover:bg-gray-800" aria-label="Abrir Menú Principal">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</header>
