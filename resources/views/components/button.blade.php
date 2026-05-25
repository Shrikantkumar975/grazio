{{-- Modern Button Component --}}
<button
    @class([
        'sky-btn relative',
        'sky-btn-primary' => ($variant ?? 'primary') === 'primary',
        'sky-btn-secondary' => ($variant ?? 'primary') === 'secondary',
        'sky-btn-violet' => ($variant ?? 'primary') === 'violet',
        'sky-btn-amber' => ($variant ?? 'primary') === 'amber',
        'sky-btn-sm' => ($size ?? 'default') === 'sm',
        'sky-btn-icon' => ($size ?? 'default') === 'icon',
        'disabled:opacity-50 disabled:cursor-not-allowed' => true,
    ])
    {{ $attributes }}
>
    @if (isset($icon) && $icon)
        <x-icon :icon="$icon" class="w-4 h-4" />
    @endif
    <span>{{ $slot }}</span>
    @if (isset($loading) && $loading)
        <x-spinner type="dots" class="absolute inset-0 flex items-center justify-center bg-current/20 rounded" />
    @endif
</button>
