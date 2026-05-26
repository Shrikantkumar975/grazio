@extends('layouts.app')
@section('title', $animal->name . ' — Profile')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
#animal-map { height: 100%; border-radius: 0.75rem; z-index: 1; }
.leaflet-container { background: #0f172a; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ── HEADER ── --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('animals.index') }}"
            class="w-9 h-9 rounded-xl bg-zenith-card border border-zenith-border flex items-center justify-center text-zenith-textLight hover:text-zenith-text hover:bg-zenith-hover transition-colors">
            <i class="material-icons text-[20px]">arrow_back</i>
        </a>
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-black text-zenith-text">{{ $animal->name }}</h1>
                @if($animal->tag_number)
                    <span class="font-mono text-xs bg-zenith-hover border border-zenith-border px-2 py-1 rounded-lg text-zenith-textLight">{{ $animal->tag_number }}</span>
                @endif
                @php
                    $statusStyles = ['Resting'=>'bg-zenith-blue/10 text-zenith-blue border-zenith-blue/20','Grazing'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','Distressed'=>'bg-red-500/15 text-red-400 border-red-500/30'];
                    $ss = $statusStyles[$animal->status] ?? 'bg-zenith-hover text-zenith-textLight border-zenith-border';
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $ss }} {{ $animal->status==='Distressed'?'animate-pulse':'' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $animal->status }}
                </span>
            </div>
            <p class="text-sm text-zenith-textLight mt-1">
                #{{ str_pad($animal->id,5,'0',STR_PAD_LEFT) }} &nbsp;·&nbsp; {{ $animal->type }}
                @if($animal->breed) / {{ $animal->breed }}@endif
                &nbsp;·&nbsp; {{ $animal->age }} years
                @if($animal->gender) &nbsp;·&nbsp; {{ $animal->gender }}@endif
            </p>
        </div>
        <form method="POST" action="{{ route('animals.destroy', ['animal' => $animal->id]) }}"
            onsubmit="return confirm('Permanently delete {{ $animal->name }}?')">
            @csrf @method('DELETE')
            <button class="flex items-center gap-2 border border-red-500/30 text-red-400 hover:bg-red-500/10 transition-colors px-4 py-2 rounded-xl text-sm font-semibold">
                <i class="material-icons text-[18px]">delete</i> Remove
            </button>
        </form>
    </div>

    {{-- ── STATS ROW ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $last = $lastLog;
            $totalLogs = $animal->gpsLogs()->count();
            $maxSpeed  = $animal->gpsLogs()->max('speed') ?? 0;
        @endphp
        <div class="bg-zenith-card border border-zenith-border rounded-xl p-4">
            <p class="text-xs text-zenith-textLight font-semibold uppercase tracking-wider mb-1">GPS Pings</p>
            <p class="text-2xl font-black text-zenith-text">{{ $totalLogs }}</p>
        </div>
        <div class="bg-zenith-card border border-zenith-border rounded-xl p-4">
            <p class="text-xs text-zenith-textLight font-semibold uppercase tracking-wider mb-1">Weight</p>
            <p class="text-2xl font-black text-zenith-text">{{ $animal->weight ? $animal->weight.'kg' : '—' }}</p>
        </div>
        <div class="bg-zenith-card border border-zenith-border rounded-xl p-4">
            <p class="text-xs text-zenith-textLight font-semibold uppercase tracking-wider mb-1">Max Speed</p>
            <p class="text-2xl font-black {{ $maxSpeed > 10 ? 'text-red-400' : 'text-zenith-text' }}">{{ $maxSpeed }} <span class="text-sm font-normal text-zenith-textLight">km/h</span></p>
        </div>
        <div class="bg-zenith-card border border-zenith-border rounded-xl p-4">
            <p class="text-xs text-zenith-textLight font-semibold uppercase tracking-wider mb-1">Last Seen</p>
            <p class="text-sm font-bold text-zenith-text">{{ $last ? $last->recorded_at->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- GPS LOG TABLE --}}
        <div class="lg:col-span-2 bg-zenith-card rounded-xl border border-zenith-border overflow-hidden flex flex-col">
            <div class="p-5 border-b border-zenith-border flex justify-between items-center">
                <h2 class="text-base font-bold text-zenith-text">GPS History</h2>
                <span class="text-xs text-zenith-textLight">{{ $logs->total() }} entries</span>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-sm text-zenith-text">
                    <thead class="bg-zenith-hover/50 text-zenith-textLight text-xs uppercase font-semibold border-b border-zenith-border">
                        <tr>
                            <th class="px-5 py-3 text-left">Timestamp</th>
                            <th class="px-5 py-3 text-left">Latitude</th>
                            <th class="px-5 py-3 text-left">Longitude</th>
                            <th class="px-5 py-3 text-left">Alt.</th>
                            <th class="px-5 py-3 text-left">Speed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zenith-border">
                        @forelse($logs as $log)
                        <tr class="hover:bg-zenith-hover/30 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-zenith-textLight whitespace-nowrap">{{ $log->recorded_at->format('d M, H:i') }}</td>
                            <td class="px-5 py-3 font-mono text-xs">{{ number_format($log->latitude,6) }}</td>
                            <td class="px-5 py-3 font-mono text-xs">{{ number_format($log->longitude,6) }}</td>
                            <td class="px-5 py-3 text-xs">{{ $log->altitude }}m</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-semibold {{ $log->speed > 10 ? 'text-red-400' : ($log->speed > 0.5 ? 'text-emerald-400' : 'text-zenith-textLight') }}">
                                    {{ $log->speed }} km/h
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-zenith-textLight">
                                <i class="material-icons text-4xl mb-2 text-zenith-border block">location_off</i>
                                No tracking data yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="p-4 border-t border-zenith-border">{{ $logs->links() }}</div>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-5">

            {{-- MAP --}}
            <div class="bg-zenith-card border border-zenith-border rounded-xl overflow-hidden">
                <div class="px-5 py-3.5 border-b border-zenith-border flex items-center gap-2">
                    <i class="material-icons text-[18px] text-emerald-400">location_on</i>
                    <h3 class="text-sm font-bold text-zenith-text">Last Known Position</h3>
                </div>
                <div style="height:220px">
                    @if($last)
                        <div id="animal-map" style="height:220px"></div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-zenith-textLight">
                            <i class="material-icons text-4xl mb-2 text-zenith-border">gps_off</i>
                            <p class="text-sm">No GPS data yet</p>
                        </div>
                    @endif
                </div>
                @if($last)
                <div class="px-4 py-3 border-t border-zenith-border">
                    <p class="text-xs font-mono text-zenith-textLight">{{ number_format($last->latitude,6) }}, {{ number_format($last->longitude,6) }}</p>
                    <p class="text-[11px] text-zenith-textLight mt-0.5">{{ $last->recorded_at->format('d M Y, H:i:s') }}</p>
                </div>
                @endif
            </div>

            {{-- PROFILE CARD --}}
            <div class="bg-zenith-card border border-zenith-border rounded-xl p-5">
                <h3 class="text-sm font-bold text-zenith-text mb-4 flex items-center gap-2">
                    <i class="material-icons text-[18px] text-zenith-blue">badge</i>
                    Animal Profile
                </h3>
                <dl class="space-y-3">
                    @foreach([
                        ['Name', $animal->name],
                        ['Type', $animal->type],
                        ['Breed', $animal->breed ?: '—'],
                        ['Age', $animal->age . ' years'],
                        ['Gender', $animal->gender ?: '—'],
                        ['Weight', $animal->weight ? $animal->weight . ' kg' : '—'],
                        ['Color', $animal->color ?: '—'],
                        ['Tag No.', $animal->tag_number ?: '—'],
                    ] as [$label, $value])
                    <div class="flex justify-between items-center py-1.5 border-b border-zenith-border/50 last:border-0">
                        <dt class="text-xs text-zenith-textLight font-semibold uppercase tracking-wider">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-zenith-text">{{ $value }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            {{-- NOTES --}}
            @if($animal->notes)
            <div class="bg-amber-500/5 border border-amber-500/20 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <i class="material-icons text-amber-400 text-[16px]">notes</i>
                    <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">Notes</p>
                </div>
                <p class="text-sm text-zenith-text leading-relaxed">{{ $animal->notes }}</p>
            </div>
            @endif

            {{-- BEHAVIOUR STATES --}}
            <div class="bg-zenith-card border border-zenith-border rounded-xl p-5">
                <h3 class="text-sm font-bold text-zenith-text mb-3 flex items-center gap-2">
                    <i class="material-icons text-[18px] text-violet-400">psychology</i>
                    Behavioural State
                </h3>
                <div class="space-y-2">
                    @foreach([
                        ['Resting',    'bedtime',  'zenith-blue', 'Speed ≈ 0 km/h. Stationary.'],
                        ['Grazing',    'grass',    'zenith-teal', 'Speed 0.5–2 km/h. Slow drift.'],
                        ['Distressed', 'warning',  'red-400',     'Speed > 10 km/h or zone breach.'],
                    ] as [$state, $icon, $color, $desc])
                    <div class="p-3 rounded-xl border {{ $animal->status === $state ? 'bg-'.$color.'/10 border-'.$color.'/30' : 'bg-zenith-hover border-zenith-border' }} transition-colors">
                        <div class="flex items-center gap-2 mb-0.5">
                            <i class="material-icons text-[15px] text-{{ $color }}">{{ $icon }}</i>
                            <span class="text-xs font-bold text-{{ $animal->status === $state ? $color : 'zenith-textLight' }}">{{ $state }}</span>
                        </div>
                        <p class="text-[11px] text-zenith-textLight">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if($last)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const lat = {{ $last->latitude }}, lng = {{ $last->longitude }};
const map = L.map('animal-map', { center: [lat, lng], zoom: 15, zoomControl: false });
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    subdomains: 'abcd', maxZoom: 19,
}).addTo(map);
const icon = L.divIcon({
    className: '',
    html: `<div style="width:32px;height:32px;border-radius:50%;background:rgba(52,211,153,.2);border:2px solid #34d399;display:flex;align-items:center;justify-content:center;font-size:16px;">🐄</div>`,
    iconSize: [32,32], iconAnchor: [16,16],
});
L.marker([lat,lng],{icon}).addTo(map)
    .bindPopup(`<strong>{{ $animal->name }}</strong><br>{{ $animal->status }}`).openPopup();
L.circle([lat,lng],{radius:50,color:'#34d399',fillOpacity:0.08,weight:1}).addTo(map);
</script>
@endpush
@endif
