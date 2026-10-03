@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-zinc-900/90 border-zinc-800 text-zinc-100 placeholder-zinc-500 focus:border-red-600 focus:ring-red-600 rounded-xl text-xs py-2.5 px-3.5 shadow-sm transition']) }}>
