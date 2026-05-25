{{-- Status Badge Component --}}
<span @class([
    'sky-badge',
    'sky-badge-success' => ($type ?? 'info') === 'success',
    'sky-badge-warning' => ($type ?? 'info') === 'warning',
    'sky-badge-danger' => ($type ?? 'info') === 'danger',
    'sky-badge-info' => ($type ?? 'info') === 'info',
    'sky-badge-processing' => ($type ?? 'info') === 'processing',
])>
    @if (isset($icon) && $icon)
        <x-icon :icon="$icon" class="w-3 h-3" />
    @endif
    {{ $slot }}
</span>
