<div class="flex flex-col">
    {{-- $label: Se usa para el título del campo, e.g., 'ID Único' --}}
    <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __($label) }}</dt>
    
    {{-- $value: Contiene el valor del usuario, e.g., $usuario->id --}}
    {{-- $class: Opcional, permite añadir clases extra como 'font-mono' --}}
    <dd class="mt-0.5 text-sm text-gray-900 dark:text-gray-100 {{ $class ?? '' }}">{{ $value }}</dd>
</div>