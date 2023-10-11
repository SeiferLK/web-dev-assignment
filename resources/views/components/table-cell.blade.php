@props(['highlight' => false])

@php
    $classes = $highlight ? 'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white' : 'px-6 py-4';
@endphp

<td scope="row" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>
