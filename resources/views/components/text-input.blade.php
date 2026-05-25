@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'sky-input']) }}>
