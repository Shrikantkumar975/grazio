<button {{ $attributes->merge(['type' => 'button', 'class' => 'sky-btn-secondary disabled:opacity-40']) }}>
    {{ $slot }}
</button>
