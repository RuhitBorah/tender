@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 rounded-xl text-start text-base font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 transition duration-150 ease-in-out'
            : 'block w-full px-4 py-3 rounded-xl text-start text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-900 focus:bg-gray-100 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
