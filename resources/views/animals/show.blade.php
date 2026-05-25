@extends('layouts.app')

@section('title', 'Animal Profile: ' . $animal->name)

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('animals.index') }}" class="w-8 h-8 rounded-lg bg-zenith-card border border-zenith-border flex items-center justify-center text-zenith-textLight hover:text-zenith-text hover:bg-zenith-hover transition-colors">
            <i class="material-icons text-[18px]">arrow_back</i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-zenith-text flex items-center gap-3">
                {{ $animal->name }}
                @if ($animal->status === 'Distressed')
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-500 text-white uppercase tracking-wider animate-pulse">Distressed</span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-zenith-hover text-zenith-textLight uppercase tracking-wider">{{ $animal->status }}</span>
                @endif
            </h1>
            <p class="text-sm text-zenith-textLight mt-1 font-mono">ID: #{{ str_pad($animal->id, 5, '0', STR_PAD_LEFT) }} • {{ $animal->type }} • {{ $animal->age }} years old</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main GPS Log Table -->
        <div class="lg:col-span-2">
            <div class="bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden transition-colors h-full flex flex-col">
                <div class="p-6 border-b border-zenith-border flex justify-between items-center">
                    <h2 class="text-lg font-bold text-zenith-text">Historical GPS Logs</h2>
                    <button class="text-zenith-blue text-sm font-medium hover:underline flex items-center gap-1">
                        <i class="material-icons text-[16px]">sync</i> Refresh
                    </button>
                </div>
                
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm text-zenith-text">
                        <thead class="bg-zenith-hover/50 text-zenith-textLight text-xs uppercase font-semibold border-b border-zenith-border">
                            <tr>
                                <th class="px-6 py-4">Timestamp</th>
                                <th class="px-6 py-4">Latitude</th>
                                <th class="px-6 py-4">Longitude</th>
                                <th class="px-6 py-4">Altitude</th>
                                <th class="px-6 py-4">Est. Speed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zenith-border">
                            @forelse ($logs as $log)
                            <tr class="hover:bg-zenith-hover/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-zenith-textLight font-mono text-xs">
                                    {{ $log->recorded_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $log->latitude }}</td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $log->longitude }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5">
                                        {{ $log->altitude }}m
                                        @if($loop->iteration > 1 && isset($logs[$loop->index + 1]) && $log->altitude < $logs[$loop->index + 1]->altitude - 5)
                                            <i class="material-icons text-[14px] text-red-500" title="Sudden Drop">arrow_downward</i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium {{ $log->speed > 10 ? 'text-red-500' : ($log->speed > 0.5 ? 'text-zenith-teal' : 'text-zenith-textLight') }}">
                                        {{ $log->speed }} km/h
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-zenith-textLight">
                                    <i class="material-icons text-4xl mb-3 text-zenith-border">location_off</i>
                                    <p>No tracking data recorded for this animal.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                <div class="p-4 border-t border-zenith-border">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <!-- Behavioral Analysis -->
            <div class="bg-zenith-card p-6 rounded-xl shadow-sm border border-zenith-border transition-colors">
                <h3 class="text-lg font-bold text-zenith-text mb-4">Behavioral Logic</h3>
                <div class="space-y-4">
                    <div class="p-4 rounded-lg border {{ $animal->status === 'Resting' ? 'bg-zenith-blue/10 border-zenith-blue/20' : 'bg-zenith-hover border-zenith-border' }} transition-colors">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="material-icons text-[18px] {{ $animal->status === 'Resting' ? 'text-zenith-blue' : 'text-zenith-textLight' }}">bedtime</i>
                            <h4 class="font-bold {{ $animal->status === 'Resting' ? 'text-zenith-text' : 'text-zenith-textLight' }}">State A: Resting</h4>
                        </div>
                        <p class="text-xs text-zenith-textLight">Speed ~ 0 km/h. Altitude matches ground level.</p>
                    </div>

                    <div class="p-4 rounded-lg border {{ $animal->status === 'Grazing' ? 'bg-zenith-teal/10 border-zenith-teal/20' : 'bg-zenith-hover border-zenith-border' }} transition-colors">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="material-icons text-[18px] {{ $animal->status === 'Grazing' ? 'text-zenith-teal' : 'text-zenith-textLight' }}">grass</i>
                            <h4 class="font-bold {{ $animal->status === 'Grazing' ? 'text-zenith-text' : 'text-zenith-textLight' }}">State B: Grazing</h4>
                        </div>
                        <p class="text-xs text-zenith-textLight">Speed 0.5 - 2 km/h. Zigzag movement.</p>
                    </div>

                    <div class="p-4 rounded-lg border {{ $animal->status === 'Distressed' ? 'bg-red-500/10 border-red-500/20' : 'bg-zenith-hover border-zenith-border' }} transition-colors">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="material-icons text-[18px] {{ $animal->status === 'Distressed' ? 'text-red-500' : 'text-zenith-textLight' }}">warning</i>
                            <h4 class="font-bold {{ $animal->status === 'Distressed' ? 'text-red-500' : 'text-zenith-textLight' }}">State C: Distressed</h4>
                        </div>
                        <p class="text-xs text-zenith-textLight">Speed > 10 km/h or sudden altitude drop.</p>
                    </div>
                </div>
            </div>
            
            <!-- Map Placeholder -->
            <div class="bg-zenith-card p-6 rounded-xl shadow-sm border border-zenith-border transition-colors">
                <h3 class="text-lg font-bold text-zenith-text mb-4">Last Known Position</h3>
                <div class="w-full h-48 bg-zenith-hover rounded-lg border border-zenith-border overflow-hidden relative group cursor-pointer flex items-center justify-center">
                    <i class="material-icons text-4xl text-zenith-border group-hover:scale-110 transition-transform">map</i>
                    <div class="absolute inset-0 bg-black/5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-black text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-lg">Open Map</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
