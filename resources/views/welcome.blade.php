@extends('layouts.landing') {{-- USAR @extends en lugar de <x-landing-layout> --}}

@section('content') {{-- Abrir la sección para inyectar en @yield('content') --}}

    <style>
        /* 1. Animación de flotación suave (Minimalista) */
        @keyframes float {
            0% { transform: translatey(0px); }
            50% { transform: translatey(-4px); }
            100% { transform: translatey(0px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* 2. Animación de entrada de las secciones (Rápida y Sutil) */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-section {
            opacity: 0;
            animation: fadeIn 0.6s ease-out forwards;
        }
        /* Retraso escalonado para un efecto visual limpio */
        .animate-section:nth-child(2) { animation-delay: 100ms; }
        .animate-section:nth-child(3) { animation-delay: 200ms; }
        .animate-section:nth-child(4) { animation-delay: 300ms; }
        .animate-section:nth-child(5) { animation-delay: 400ms; }
        .animate-section:nth-child(6) { animation-delay: 500ms; }


        /* 3. Estilo de tarjetas: Limpio y Compacto */
        .clean-card {
            transition: all 0.2s ease-in-out;
            border: 1px solid #e5e7eb; /* Borde gris muy claro por defecto */
        }

        .clean-card:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04); /* Sombra súper suave */
            transform: translateY(-2px);
            border-color: #0ea5e9; /* Borde de acento Sky Blue */
        }
        
        /* 4. Efecto de "presión" limpio para botones */
        .btn-press:active {
            transform: scale(0.98) translateZ(0) !important;
            opacity: 0.95;
        }
    </style>

    {{-- 1. SECCIÓN HERO: MÁXIMA COMPACIDAD (py-8) y GRID --}}
    <section class="py-8 md:py-10 text-gray-800 bg-white overflow-hidden animate-section" id="propuesta">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6 items-center justify-between">
            
            {{-- Contenido de texto (Columna 1) --}}
            <div class="md:pr-8 space-y-2 text-center md:text-left">
                {{-- Etiqueta (diminuta) --}}
                <span class="inline-block px-3 py-1 text-xs font-semibold text-teal-600 border border-teal-200 rounded-full tracking-wider">
                    SOFTWARE DE ALTO IMPACTO
                </span>

                {{-- Título Principal (tamaño ajustado y audaz) --}}
                <h1 class="text-3xl md:text-4xl font-extrabold leading-snug tracking-tight text-gray-900">
                    Aplicaciones <span class="text-blue-700">Full-Stack Colaborativas</span>.
                </h1>

                {{-- Párrafo de cuerpo (text-sm, ligero) --}}
                <p class="text-sm text-gray-700 max-w-xl mx-auto md:mx-0 pt-1 font-light leading-snug">
                    Construimos plataformas complejas con funcionalidades en tiempo real, chat, y gestión de tareas, garantizando escalabilidad.
                </p>

                {{-- Botón CTA principal (compacto) --}}
                <div class="pt-4">
                    <a href="#contacto" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold rounded-full text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-200 transition duration-200 transform hover:scale-[1.03] btn-press">
                        Empezar un Proyecto →
                    </a>
                </div>
            </div>

            {{-- Caja de promesa FLOTANTE (Columna 2, limpia, tamaño muy ajustado) --}}
            <div class="mt-4 md:mt-0 flex justify-center md:justify-end">
                <div class="promise-box w-56 h-56 rounded-xl flex flex-col items-center justify-center border border-teal-300 p-4 text-center bg-teal-50 animate-float">
                    <i class="fas fa-signal text-3xl text-sky-600 mb-1"></i>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tiempo Real (RT)</h3>
                    <p class="text-gray-600 text-xs font-light">
                        WebSockets y Event Sourcing para una UX instantánea y fluida.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. SECCIÓN: Funcionalidades Core (Grid 5x1 Compacto) --}}
    <section class="py-6 bg-white border-t border-b border-gray-100 animate-section" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-center text-lg font-bold text-gray-900 mb-5">
                Capacidades de Colaboración
            </h3>
            
            {{-- Grid de 5 elementos: ULTRA COMPACTO --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 text-center">
                
                @php
                    $features = [
                        ['icon' => 'fas fa-comments', 'title' => 'Chat & Mensajería', 'color' => 'text-blue-600'],
                        ['icon' => 'fas fa-tasks', 'title' => 'Gestión de Tareas', 'color' => 'text-green-600'],
                        ['icon' => 'fas fa-users', 'title' => 'Reuniones Grupales', 'color' => 'text-purple-600'],
                        ['icon' => 'fas fa-address-book', 'title' => 'Contactos Centralizados', 'color' => 'text-yellow-600'],
                        ['icon' => 'fas fa-bell', 'title' => 'Notificaciones RT', 'color' => 'text-red-600'],
                    ];
                @endphp

                @foreach ($features as $feature)
                    {{-- Tarjeta de feature ultra-compacta (clean-card) --}}
                    <div class="flex flex-col items-center p-3 clean-card bg-white rounded-lg cursor-pointer">
                        <i class="{{ $feature['icon'] }} text-xl {{ $feature['color'] }} mb-1.5"></i>
                        <p class="font-semibold text-gray-900 text-xs">{{ $feature['title'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    {{-- 3. SECCIÓN: Servicios Complementarios (Grid 4x1) --}}
    <section class="py-6 bg-gray-50 border-b border-gray-100 animate-section" id="servicios">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-center text-lg font-bold text-gray-900 mb-5">
                Servicios Complementarios
            </h3>
            
            {{-- Grid de 4 columnas --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
                
                @php
                    $services = [
                        ['icon' => 'fas fa-shield-alt', 'title' => 'Seguridad Avanzada', 'desc' => 'OAuth2, MFA y protección total contra XSS/CSRF.', 'color' => 'text-blue-500'],
                        ['icon' => 'fas fa-cogs', 'title' => 'Integración CI/CD', 'desc' => 'Despliegue automatizado y configuración de infraestructura cloud (AWS/DO).', 'color' => 'text-green-500'],
                        ['icon' => 'fas fa-wrench', 'title' => 'Mantenimiento & Bugfix', 'desc' => 'Soporte y corrección post-lanzamiento para una operación estable.', 'color' => 'text-yellow-600'],
                        ['icon' => 'fas fa-layer-group', 'title' => 'Arquitectura Modular', 'desc' => 'Estructuras modulares basadas en Domain Driven Design (DDD) para escalar.', 'color' => 'text-purple-500'],
                    ];
                @endphp

                @foreach ($services as $service)
                    {{-- Tarjeta de servicio compacta (p-3) --}}
                    <div class="p-3 clean-card bg-white rounded-xl shadow-sm hover:shadow-md transition duration-200">
                        <i class="{{ $service['icon'] }} text-xl {{ $service['color'] }} mb-1.5"></i>
                        <p class="font-bold text-gray-900 text-xs mb-0.5">{{ $service['title'] }}</p>
                        <p class="text-gray-600 text-[10px] font-light leading-tight">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. BARRA DE DIFERENCIADORES: MUY LIGERA Y COMPACTA --}}
    <section class="bg-white py-3 border-b border-gray-100 animate-section" id="diferenciadores">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-700">
            <div class="grid grid-cols-3 divide-x divide-gray-200">
                <div class="text-center">
                    <p class="font-bold text-xl text-blue-700">99.9%</p>
                    <p class="text-xs uppercase tracking-wider mt-0.5 font-medium">Uptime</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-xl text-blue-700">24/7</p>
                    <p class="text-xs uppercase tracking-wider mt-0.5 font-medium">Soporte</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-xl text-blue-700">Doble QA</p>
                    <p class="text-xs uppercase tracking-wider mt-0.5 font-medium">Sin Bugs</p>
                </div>
            </div>
        </div>
    </section>
    
    {{-- 5. SECCIÓN STACK TÉCNICO: Limpio y Compacto (Grid 8x1) --}}
    <section class="py-8 bg-white animate-section" id="habilidades">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold mb-5 text-gray-900">
                El Stack Técnico
            </h2>
            
            {{-- GRID 8x1 en desktop, 4x1 en móvil: MÁXIMA COMPACIDAD --}}
            <div class="grid grid-cols-4 sm:grid-cols-8 gap-3">
                
                @php
                    $stack = [
                        ['name' => 'Laravel', 'subtitle' => 'Backend', 'icon_color' => 'text-red-600'],
                        ['name' => 'Livewire / Volt', 'subtitle' => 'Dinámico', 'icon_color' => 'text-purple-600'],
                        ['name' => 'Tailwind CSS', 'subtitle' => 'Frontend', 'icon_color' => 'text-cyan-600'],
                        ['name' => 'WebSockets', 'subtitle' => 'Tiempo Real', 'icon_color' => 'text-teal-500'],
                        ['name' => 'Redis', 'subtitle' => 'Cache & Queue', 'icon_color' => 'text-red-500'],
                        ['name' => 'MySQL/PG', 'subtitle' => 'Base de Datos', 'icon_color' => 'text-blue-600'],
                        ['name' => 'Tests (Pest)', 'subtitle' => 'Calidad', 'icon_color' => 'text-yellow-600'],
                        ['name' => 'Git / GitHub', 'subtitle' => 'Control', 'icon_color' => 'text-gray-700'],
                    ];
                @endphp

                @foreach ($stack as $item)
                    {{-- Tarjeta compacta limpia con tamaño de letra mínimo (p-2) --}}
                    <div class="flex flex-col items-center p-2 clean-card bg-white rounded-lg cursor-pointer text-center">
                        <div class="mb-1">
                            <svg class="w-5 h-5 {{ $item['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1v-3.25M7 11h10M7 5h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z"></path></svg>
                        </div>
                        <p class="font-semibold text-gray-900 text-xs">{{ $item['name'] }}</p>
                        <span class="text-[10px] text-gray-500 mt-0.5 font-light">{{ $item['subtitle'] }}</span>
                    </div>
                @endforeach
                
            </div>
        </div>
    </section>
    
@endsection {{-- Cerrar la sección content --}}