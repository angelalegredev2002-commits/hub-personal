<section>
    <header>
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            {{ __('Información Completa del Usuario') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Actualiza todos tus datos de contacto, personales y de configuración en un solo formulario extenso y organizado.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- CLAVE: space-y-4 para reducir la altura --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- ========================================================== --}}
        {{-- GRUPO 1: CUENTA Y CONTACTO PRINCIPAL --}}
        {{-- ========================================================== --}}
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 border-b pb-2 border-gray-200 dark:border-gray-700">
            {{ __('Datos de Acceso y Contacto Principal') }}
        </h3>
        
        {{-- FOTO Y NOMBRE/EMAIL/TELÉFONOS --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            
            {{-- Columna 1: FOTO DE PERFIL (Mantiene altura, es necesario) --}}
            <div class="md:col-span-1">
                <x-input-label for="foto_perfil" :value="__('Foto de Perfil')" class="mb-3"/>
                
                <div class="flex items-center space-x-4">
                    @php
                        $user = Auth::user(); 
                    @endphp
                    @if ($user->foto_perfil_ruta)
                        <img src="{{ Storage::url($user->foto_perfil_ruta) }}" alt="Foto de perfil actual" class="h-24 w-24 rounded-full object-cover border-2 border-fuchsia-600 dark:border-fuchsia-600">
                    @else
                        <div class="h-24 w-24 rounded-full bg-fuchsia-100 dark:bg-gray-700 flex items-center justify-center text-fuchsia-700 dark:text-gray-400 text-lg font-medium">
                            {{ substr($user->nombre, 0, 2) }}
                        </div>
                    @endif
                    
                    <input id="foto_perfil" name="foto_perfil" type="file" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-fuchsia-50 file:text-fuchsia-700 hover:file:bg-fuchsia-100 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('foto_perfil')" />
            </div>
            
            {{-- Columna 2: NOMBRE y TELÉFONO PRINCIPAL --}}
            <div class="md:col-span-1 space-y-4"> 
                <div>
                    <x-input-label for="nombre" :value="__('Nombre Completo')" />
                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $user->nombre)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
                </div>
                
                <div>
                    <x-input-label for="telefono_principal" :value="__('Teléfono Principal')" />
                    <x-text-input id="telefono_principal" name="telefono_principal" type="text" class="mt-1 block w-full" :value="old('telefono_principal', $user->telefono_principal)" autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('telefono_principal')" />
                </div>
            </div>

            {{-- Columna 3: EMAIL y CELULAR --}}
            <div class="md:col-span-1 space-y-4">
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    {{-- Bloque de verificación de email omitido por brevedad --}}
                </div>
                
                <div>
                    <x-input-label for="numero_celular" :value="__('Número Celular')" />
                    <x-text-input id="numero_celular" name="numero_celular" type="text" class="mt-1 block w-full" :value="old('numero_celular', $user->numero_celular)" autocomplete="tel-national" />
                    <x-input-error class="mt-2" :messages="$errors->get('numero_celular')" />
                </div>
            </div>
        </div>
        
        
        {{-- ========================================================== --}}
        {{-- GRUPO 2: DATOS PERSONALES --}}
        {{-- ========================================================== --}}
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 pt-4 border-t border-gray-200 dark:border-gray-700">
            {{ __('Datos Personales e Identificación') }}
        </h3>

        {{-- FILA 1: Identificación, Fecha, Género --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            
            {{-- DNI/Identificación --}}
            <div>
                <x-input-label for="identificacion_dni" :value="__('DNI / Identificación')" />
                <x-text-input id="identificacion_dni" name="identificacion_dni" type="text" class="mt-1 block w-full" :value="old('identificacion_dni', $user->identificacion_dni)" />
                <x-input-error class="mt-2" :messages="$errors->get('identificacion_dni')" />
            </div>

            {{-- Fecha de Nacimiento --}}
            <div>
                <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                <x-text-input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="mt-1 block w-full" :value="old('fecha_nacimiento', $user->fecha_nacimiento ? \Carbon\Carbon::parse($user->fecha_nacimiento)->format('Y-m-d') : '')" />
                <x-input-error class="mt-2" :messages="$errors->get('fecha_nacimiento')" />
            </div>

            {{-- Género --}}
            <div>
                <x-input-label for="genero" :value="__('Género')" />
                <select id="genero" name="genero" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-md shadow-sm">
                    <option value="" disabled selected>{{ __('Selecciona') }}</option>
                    <option value="masculino" {{ old('genero', $user->genero) == 'masculino' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                    <option value="femenino" {{ old('genero', $user->genero) == 'femenino' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                    <option value="otro" {{ old('genero', $user->genero) == 'otro' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                    <option value="no_especificado" {{ old('genero', $user->genero) == 'no_especificado' ? 'selected' : '' }}>{{ __('No especificar') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('genero')" />
            </div>
            
            {{-- Enlace LinkedIn (Ocupa 3 columnas para maximizar el ancho disponible) --}}
            <div class="md:col-span-3">
                <x-input-label for="enlace_linkedin" :value="__('Enlace de LinkedIn (URL)')" />
                <x-text-input id="enlace_linkedin" name="enlace_linkedin" type="url" class="mt-1 block w-full" :value="old('enlace_linkedin', $user->enlace_linkedin)" />
                <x-input-error class="mt-2" :messages="$errors->get('enlace_linkedin')" />
            </div>
        </div>


        {{-- ========================================================== --}}
        {{-- GRUPO 3: DIRECCIÓN Y PREFERENCIAS --}}
        {{-- ========================================================== --}}
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 pt-4 border-t border-gray-200 dark:border-gray-700">
            {{ __('Dirección y Configuración del Sistema') }}
        </h3>

        {{-- FILA 1: Direcciones Línea 1 y 2 (Se muestran en dos filas separadas para claridad) --}}
        <div class="grid grid-cols-1 gap-4">
            
            {{-- DIRECCIÓN CALLE (1 Columna en toda la fila) --}}
            <div>
                <x-input-label for="direccion_calle" :value="__('Dirección Línea 1 (Calle, Nro, Urb.)')" />
                <x-text-input id="direccion_calle" name="direccion_calle" type="text" class="mt-1 block w-full" :value="old('direccion_calle', $user->direccion_calle)" autocomplete="street-address" />
                <x-input-error class="mt-2" :messages="$errors->get('direccion_calle')" />
            </div>

            {{-- DIRECCIÓN LINEA 2 (1 Columna en toda la fila) --}}
            <div>
                <x-input-label for="direccion_linea_2" :value="__('Dirección Línea 2 (Apt, Piso, Referencia)')" />
                <x-text-input id="direccion_linea_2" name="direccion_linea_2" type="text" class="mt-1 block w-full" :value="old('direccion_linea_2', $user->direccion_linea_2)" />
                <x-input-error class="mt-2" :messages="$errors->get('direccion_linea_2')" />
            </div>
        </div>
        
        {{-- FILA 2: Ciudad, Estado/Provincia, Código Postal --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            
            {{-- CIUDAD --}}
            <div>
                <x-input-label for="direccion_ciudad" :value="__('Ciudad')" />
                <x-text-input id="direccion_ciudad" name="direccion_ciudad" type="text" class="mt-1 block w-full" :value="old('direccion_ciudad', $user->direccion_ciudad)" autocomplete="address-level2" />
                <x-input-error class="mt-2" :messages="$errors->get('direccion_ciudad')" />
            </div>

            {{-- ESTADO/PROVINCIA --}}
            <div>
                <x-input-label for="direccion_estado_provincia" :value="__('Estado / Provincia')" />
                <x-text-input id="direccion_estado_provincia" name="direccion_estado_provincia" type="text" class="mt-1 block w-full" :value="old('direccion_estado_provincia', $user->direccion_estado_provincia)" autocomplete="address-level1" />
                <x-input-error class="mt-2" :messages="$errors->get('direccion_estado_provincia')" />
            </div>

            {{-- CÓDIGO POSTAL --}}
            <div>
                <x-input-label for="codigo_postal" :value="__('Código Postal')" />
                <x-text-input id="codigo_postal" name="codigo_postal" type="text" class="mt-1 block w-full" :value="old('codigo_postal', $user->codigo_postal)" autocomplete="postal-code" />
                <x-input-error class="mt-2" :messages="$errors->get('codigo_postal')" />
            </div>
        </div>

        {{-- FILA 3: País, Zona Horaria, Idioma Preferido --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            
            {{-- PAÍS --}}
            <div>
                <x-input-label for="direccion_pais" :value="__('País')" />
                <x-text-input id="direccion_pais" name="direccion_pais" type="text" class="mt-1 block w-full" :value="old('direccion_pais', $user->direccion_pais)" autocomplete="country-name" />
                <x-input-error class="mt-2" :messages="$errors->get('direccion_pais')" />
            </div>
            
            {{-- ZONA HORARIA --}}
            <div>
                <x-input-label for="zona_horaria" :value="__('Zona Horaria')" />
                <x-text-input id="zona_horaria" name="zona_horaria" type="text" class="mt-1 block w-full" :value="old('zona_horaria', $user->zona_horaria)" required autocomplete="off" />
                <x-input-error class="mt-2" :messages="$errors->get('zona_horaria')" />
            </div>
            
            {{-- IDIOMA PREFERIDO --}}
            <div>
                <x-input-label for="idioma_preferido" :value="__('Idioma Preferido')" />
                <x-text-input id="idioma_preferido" name="idioma_preferido" type="text" class="mt-1 block w-full" :value="old('idioma_preferido', $user->idioma_preferido)" required autocomplete="off" />
                <x-input-error class="mt-2" :messages="$errors->get('idioma_preferido')" />
            </div>
        </div>
        
        {{-- Botón de guardar --}}
        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
            <x-primary-button>{{ __('Guardar Toda la Información') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Información Guardada Correctamente.') }}</p>
            @endif
        </div>
    </form>
</section>