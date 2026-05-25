@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-mono font-medium text-sky-400/80 tracking-widest uppercase']) }}>
    {{ $value ?? $slot }}
</label>
