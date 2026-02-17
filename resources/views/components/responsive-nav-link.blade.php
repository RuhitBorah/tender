@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 rounded-xl text-start text-base font-bold text-white bg-indigo-500/20 shadow-lg shadow-indigo-500/10 border border-indigo-500/20 focus:outline-none focus:text-indigo-400 focus:bg-indigo-500/30 transition duration-150 ease-in-out'
            : 'block w-full px-4 py-3 rounded-xl text-start text-base font-medium text-slate-400 hover:text-white hover:bg-white/5 hover:border-slate-600 focus:outline-none focus:text-white focus:bg-slate-800 focus:border-slate-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
