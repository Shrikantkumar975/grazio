<button {{ $attributes->merge(['type' => 'submit', 'class' => 'sky-btn-primary']) }}>
    {{ $slot }}
</button>
