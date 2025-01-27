@props(['textColor','bgColor'])

@php
    $textColor = match ($textColor) {
        'gray' => 'text-gray-800',
        'blue' => 'text-blue-800',
        'red' => 'text-red-800',
        'yellow' => 'text-yellow-800',
        default => 'text-gray-800',
    };
@endphp

<a href="#" class="bg-red-600 text-white rounded-xl px-3 py-1 text-base">
    {{$slot}}
</a> 