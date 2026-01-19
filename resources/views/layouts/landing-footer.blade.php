{{-- FOOTER PREMIUM: Diseño compacto, paleta de colores sofisticada (Teal/Azul) y mejor jerarquía visual. --}}
<footer class="bg-gray-950 pt-10 pb-4 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        {{-- SECCIÓN SUPERIOR: NAVEGACIÓN Y LINKS --}}
        {{-- Línea divisoria con degradado sutil --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-x-8 gap-y-8 pb-6 border-b border-gray-800/80 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-transparent after:via-teal-600/50 after:to-transparent">
            
            {{-- Columna 1 y 2 (Branding y Misión) --}}
            <div class="col-span-2 pr-0 md:pr-6 md:border-r border-gray-800/80 md:pr-10">
                <a href="{{ url('/') }}" class="inline-block mb-3">
                    {{-- Logo: Fuente más fuerte y color Teal --}}
                    <h4 class="text-1xl uppercase font-mono tracking-tight text-teal-400">
                        Ángel.dev
                    </h4>
                </a>
                <p class="text-sm  text-gray-400 leading-relaxed max-w-sm">
                    Tu partner en desarrollo Full-Stack.Garantizamos entregas usables, código documentado y escalabilidad a largo plazo.
                </p>

                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-300 uppercase tracking-widest mb-2">Conecta:</p>
                    <div class="flex space-x-6">
                        {{-- Enlaces sociales en gris sutil con hover Teal --}}
                        <a href="https://github.com/angelalegredev2002-commits" target="_blank" class="text-gray-500 hover:text-teal-400 transition duration-300 text-sm" aria-label="GitHub Profile">
                            GitHub
                        </a>
                        <a href="https://www.linkedin.com/in/angeldev" target="_blank" class="text-gray-500 hover:text-teal-400 transition duration-300 text-sm" aria-label="LinkedIn Profile">
                            LinkedIn
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Columna 3 (Servicios) --}}
            <div class="md:pl-8 md:border-r border-gray-800/80">
                <h4 class="text-sm font-bold text-gray-200 uppercase mb-3 tracking-widest">SERVICIOS</h4>
                <ul class="space-y-2 text-sm">
                    {{-- Hover en Teal --}}
                    <li><a href="#propuesta" class="text-gray-400 hover:text-teal-400 transition duration-200">Desarrollo a Medida</a></li>
                    <li><a href="#habilidades" class="text-gray-400 hover:text-teal-400 transition duration-200">Consultoría & Auditoría</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-teal-400 transition duration-200">Mantenimiento y Soporte</a></li>
                </ul>
            </div>

            {{-- Columna 4 (Información y Legal) --}}
            <div class="md:pl-8 md:border-r border-gray-800/80">
                <h4 class="text-sm font-bold text-gray-200 uppercase mb-3 tracking-widest">INFORMACIÓN</h4>
                <ul class="space-y-2 text-sm">
                    {{-- Hover en Teal --}}
                    <li><a href="#confian" class="text-gray-400 hover:text-teal-400 transition duration-200">Casos de Éxito</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-teal-400 transition duration-200">Términos de Servicio</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-teal-400 transition duration-200">Política de Privacidad</a></li>
                </ul>
            </div>

            {{-- Columna 5 (Contacto Rápido y CTA) --}}
            <div class="col-span-2 md:col-span-1 md:pl-8">
                <h4 class="text-sm font-bold text-gray-200 uppercase mb-3 tracking-widest">CONTACTO</h4>
                <div class="space-y-3 text-sm">
                    <p class="text-gray-400">
                        {{-- Email acentuado en Teal --}}
                        <span class="font-semibold text-teal-400 mr-1">Email:</span>
                        <a href="mailto:alegrebenitoangeljosein@gmail.com" class="hover:text-teal-400 transition underline-offset-4 hover:underline">@angel.dev</a>
                    </p>
                    
                    {{-- CTA Principal: Botón sólido y llamativo (Teal-600) --}}
                    <a href="#contacto" class="inline-block py-2 px-4 bg-teal-600 text-sm font-bold rounded-lg text-white hover:bg-teal-700 transition duration-300 shadow-lg shadow-teal-600/40 w-auto mt-2">
                        Empezar Proyecto →
                    </a>
                </div>
            </div>
            
        </div>

        {{-- SECCIÓN INFERIOR: COPYRIGHT Y ACCESO --}}
        <div class="mt-6 pt-3 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500">
            
            {{-- Texto de Copyright --}}
            <p class="order-2 md:order-1 mt-3 md:mt-0">
                &copy; {{ date('Y') }} angelalegredev2002-commits. Todos los derechos reservados.
                <span class="hidden sm:inline-block text-gray-700 mx-2">|</span>
                <span class="hidden sm:inline-block text-gray-600">Bruma-Cotalist-Wayrel</span>
            </p>
            
            {{-- Links de Acceso (Hover en Teal) --}}
            <div class="order-1 md:order-2">
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-teal-400 transition duration-300 underline-offset-4 hover:underline">
                    Acceder al Panel
                </a>
                <span class="mx-2 text-gray-700 hidden sm:inline">|</span>
                <a href="#" class="text-gray-500 hover:text-teal-400 transition duration-300 underline-offset-4 hover:underline hidden sm:inline">
                    Status
                </a>
            </div>
        </div>
    </div>
</footer>