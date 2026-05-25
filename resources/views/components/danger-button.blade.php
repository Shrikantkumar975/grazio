<button {{ $attributes->merge(['type' => 'submit', 'class' => 'sky-btn border border-red-500/40 bg-red-600/90 text-white hover:bg-red-500 hover:border-red-400/60']) }}>
    {{ $slot }}
</button>
