{{-- Interactive Card Component --}}
<div
    x-data="{ hover: false }"
    @mouseenter="hover = true"
    @mouseleave="hover = false"
    @class([
        'sky-card-interactive p-6 space-y-3' => true,
        $attributes->get('class'),
    ])
    {{ $attributes->except('class') }}
>
    @if (isset($icon) && $icon)
        <div class="flex items-start justify-between">
            <div class="flex-1">
                @if ($title)
                    <h3 class="sky-section-subtitle">{{ $title }}</h3>
                @endif
                @if ($description)
                    <p class="sky-section-desc">{{ $description }}</p>
                @endif
            </div>
            <div class="text-sky-400 group-hover:text-sky-300 transition-colors duration-300">
                <x-icon :icon="$icon" class="w-6 h-6" />
            </div>
        </div>
    @else
        @if ($title)
            <h3 class="sky-section-subtitle">{{ $title }}</h3>
        @endif
        @if ($description)
            <p class="sky-section-desc">{{ $description }}</p>
        @endif
    @endif
    
    {{ $slot }}
</div>
