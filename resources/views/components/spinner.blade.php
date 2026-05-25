{{-- Spinner Loader Component --}}
@props(['type' => 'default', 'label' => null])
<div class="flex items-center justify-center gap-2" {{ $attributes }}>
    @if ($type === 'ring')
        <div class="spinner-ring"></div>
    @elseif ($type === 'dots')
        <span class="spinner-dot bg-sky-500"></span>
        <span class="spinner-dot bg-sky-400"></span>
        <span class="spinner-dot bg-sky-300"></span>
    @else
        <svg class="animate-spin-slow w-6 h-6 text-sky-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    @if ($label)
        <span class="text-sm font-medium text-slate-300">{{ $label }}</span>
    @endif
</div>
