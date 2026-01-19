<section>
    <header>
        {{-- Título más audaz --}}
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            {{ __('Actualizar Contraseña') }}
        </h2>

        {{-- Texto de ayuda --}}
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Usa una contraseña fuerte y única para proteger tu cuenta.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6">
        @csrf
        @method('put')

        {{-- Contenedor principal de la cuadrícula de 3 columnas --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            
            {{-- 1. CAMPO: CONTRASEÑA ACTUAL (Columna 1) --}}
            <div>
                <x-input-label for="current_password" :value="__('Contraseña Actual')" />
                <x-text-input 
                    id="current_password" 
                    name="current_password" 
                    type="password" 
                    class="mt-1 block w-full" 
                    autocomplete="current-password" 
                />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            {{-- 2. CAMPO: NUEVA CONTRASEÑA (Columna 2) --}}
            <div>
                <x-input-label for="password" :value="__('Nueva Contraseña')" />
                <x-text-input 
                    id="password" 
                    name="password" 
                    type="password" 
                    class="mt-1 block w-full" 
                    autocomplete="new-password" 
                />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            {{-- 3. CAMPO: CONFIRMAR NUEVA CONTRASEÑA (Columna 3) --}}
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="mt-1 block w-full" 
                    autocomplete="new-password" 
                />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        {{-- Botón de guardar --}}
        <div class="flex items-center gap-4 pt-4 mt-2">
            <x-primary-button>{{ __('Guardar Contraseña') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Contraseña Guardada.') }}</p>
            @endif
        </div>
    </form>
</section>