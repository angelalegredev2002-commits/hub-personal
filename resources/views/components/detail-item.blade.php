@props(['title', 'value' => null])

<div class="flex flex-col">
    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</dt>
    <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
        @if ($value !== null)
            {{ $value }}
        @else
            {{ $slot }}
        @endif
    </dd>
</div>