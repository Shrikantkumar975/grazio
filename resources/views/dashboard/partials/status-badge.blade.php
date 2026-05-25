@php
    $classes = match ($status) {
        'clustered'  => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
        'pending'    => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
        'failed'     => 'bg-red-500/15 text-red-400 border-red-500/30',
        'processing' => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
        default      => 'bg-slate-500/15 text-slate-400 border-slate-500/30',
    };
@endphp

<span class="inline-flex px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-wider border {{ $classes }}">
    {{ $status }}
</span>
