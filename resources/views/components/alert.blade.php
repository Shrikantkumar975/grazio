{{-- Alert Component with Animations --}}
<div
    x-data="{ show: true }"
    x-init="setTimeout(() => { show = false }, 5000)"
    x-show="show"
    x-transition
    @class([
        'sky-card px-4 py-3 rounded-lg border flex items-center gap-3 animate-fade-in-up',
        'sky-flash-success bg-emerald-500/10 border-emerald-500/30 text-emerald-400' => ($type ?? 'info') === 'success',
        'sky-flash-error bg-red-500/10 border-red-500/30 text-red-400' => ($type ?? 'info') === 'error',
        'sky-flash-warning bg-amber-500/10 border-amber-500/30 text-amber-400' => ($type ?? 'info') === 'warning',
        'bg-sky-500/10 border-sky-500/30 text-sky-400' => ($type ?? 'info') === 'info',
    ])
    {{ $attributes }}
>
    <div class="flex-shrink-0">
        @switch($type ?? 'info')
            @case('success')
                <x-icon icon="check" class="w-5 h-5" />
                @break
            @case('error')
                <x-icon icon="alert" class="w-5 h-5" />
                @break
            @case('warning')
                <x-icon icon="alert" class="w-5 h-5" />
                @break
            @default
                <x-icon icon="info" class="w-5 h-5" />
        @endswitch
    </div>
    <div class="flex-1">
        <p class="text-sm font-medium">{{ $title ?? ($message ?? 'Alert') }}</p>
        @if (isset($message) && $message && isset($title))
            <p class="text-xs opacity-80">{{ $message }}</p>
        @endif
    </div>
    <button
        @click="show = false"
        class="flex-shrink-0 text-current hover:opacity-75 transition-opacity"
    >
        <x-icon icon="close" class="w-4 h-4" />
    </button>
</div>
