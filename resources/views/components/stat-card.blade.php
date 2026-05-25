{{-- Stat Card Component --}}
<div class="sky-stat-card p-6 relative">
    <div class="flex items-start justify-between">
        <div>
            <p class="sky-section-desc">{{ $label ?? 'Metric' }}</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $value ?? '0' }}</p>
            @if (isset($change) && $change !== null)
                <p @class([
                    'text-xs font-semibold mt-2',
                    'text-emerald-400' => $change > 0,
                    'text-red-400' => $change < 0,
                    'text-slate-400' => $change === 0,
                ])>
                    @if ($change > 0)
                        ↑
                    @elseif ($change < 0)
                        ↓
                    @else
                        ═
                    @endif
                    {{ abs($change) }}% from last period
                </p>
            @endif
        </div>
        @if (isset($icon) && $icon)
            <div class="p-3 rounded-lg bg-sky-500/10 text-sky-400 hover:bg-sky-500/20 transition-colors duration-300">
                <x-icon :icon="$icon" class="w-6 h-6" />
            </div>
        @endif
    </div>
</div>
