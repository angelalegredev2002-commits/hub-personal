<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            {{ __('Eliminación Permanente de Cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-semibold">
            {{ __('¡Advertencia! Esta acción es irreversible.') }}
        </p>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Antes de proceder, asegúrate de descargar cualquier dato importante que desees conservar.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Eliminar Cuenta Definitivamente') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable class="sm:max-w-md">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-extrabold text-red-600 dark:text-red-400">
                {{ __('CONFIRMAR ELIMINACIÓN') }}
            </h2>

            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                {{ __('Estás a punto de eliminar tu cuenta de forma **permanente**. Esta acción no se puede deshacer.') }}
            </p>
            
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Por favor, ingresa tu clave para autorizar la eliminación total de tu cuenta.') }}
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Clave') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full focus:ring-red-500 focus:border-red-500"
                    placeholder="{{ __('Ingresa tu Clave Aquí') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar / Mantener Cuenta') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Eliminar y Cerrar Sesión') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>