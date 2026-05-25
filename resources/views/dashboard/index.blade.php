@extends('layouts.app')

@section('title', 'Livestock Monitoring Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zenith-text mb-1">Livestock Monitoring</h1>
        <p class="text-sm text-zenith-textLight">Welcome back, {{ auth()->user()->name }}. Here is the real-time behavioral analysis of your farm.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden relative pb-10 transition-colors">
            <div class="p-5 flex justify-between items-start">
                <div>
                    <p class="text-sm text-zenith-textLight mb-1">Total Livestock</p>
                    <h3 class="text-2xl font-bold text-zenith-text">{{ $total ?? 0 }}</h3>
                    <p class="text-xs text-zenith-textLight font-semibold mt-2 flex items-center gap-1">
                        Active GPS Collars
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-zenith-hover text-zenith-textLight border border-zenith-border flex items-center justify-center">
                    <i class="material-icons">pets</i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full">
                <div id="spark1"></div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden relative pb-10 transition-colors">
            <div class="p-5 flex justify-between items-start">
                <div>
                    <p class="text-sm text-zenith-textLight mb-1">Resting (State A)</p>
                    <h3 class="text-2xl font-bold text-zenith-text">{{ $resting ?? 0 }}</h3>
                    <p class="text-xs text-zenith-textLight mt-2">
                        Speed ~ 0 km/h
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-zenith-blue/10 text-zenith-blue flex items-center justify-center">
                    <i class="material-icons">bedtime</i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full">
                <div id="spark2"></div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden relative pb-10 transition-colors">
            <div class="p-5 flex justify-between items-start">
                <div>
                    <p class="text-sm text-zenith-textLight mb-1">Grazing (State B)</p>
                    <h3 class="text-2xl font-bold text-zenith-text">{{ $grazing ?? 0 }}</h3>
                    <p class="text-xs text-zenith-textLight mt-2">
                        Speed 0.5 - 2 km/h
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-zenith-teal/10 text-zenith-teal flex items-center justify-center">
                    <i class="material-icons">grass</i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full">
                <div id="spark3"></div>
            </div>
        </div>

        <!-- Stat Card 4 (Distressed) -->
        <div class="bg-zenith-card rounded-xl shadow-sm border border-red-500/20 overflow-hidden relative pb-10 transition-colors">
            <div class="p-5 flex justify-between items-start">
                <div>
                    <p class="text-sm text-red-500 font-medium mb-1">Distressed (State C)</p>
                    <h3 class="text-2xl font-bold text-red-500">{{ $distressed ?? 0 }}</h3>
                    <p class="text-xs text-red-400 mt-2 font-medium">
                        Requires Attention!
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center animate-pulse">
                    <i class="material-icons">warning</i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full">
                <div id="spark4"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Animals -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-zenith-card p-6 rounded-xl shadow-sm border border-zenith-border transition-colors">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-zenith-text">Recently Tracked Animals</h2>
                        <p class="text-sm text-zenith-textLight">Latest GPS logs processed by the system</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('animals.index') }}" class="text-sm font-medium text-zenith-text hover:text-zenith-textLight transition-colors border border-zenith-border rounded-lg px-3 py-1.5 hover:bg-zenith-hover">
                            View all
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-zenith-border">
                    @forelse ($recent ?? [] as $animal)
                        <div class="py-4 flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-zenith-hover flex items-center justify-center text-zenith-textLight border border-zenith-border transition-colors">
                                    <i class="material-icons text-[20px]">pets</i>
                                </div>
                                <div>
                                    <a href="{{ route('animals.show', $animal) }}" class="font-medium text-zenith-text hover:text-zenith-blue transition-colors">{{ $animal->name }}</a>
                                    <p class="text-xs text-zenith-textLight">{{ $animal->type }} • {{ $animal->age }} years old</p>
                                </div>
                            </div>
                            <div>
                                @if ($animal->status === 'Resting')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-zenith-blue/10 text-zenith-blue border border-zenith-blue/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-zenith-blue animate-pulse"></div> {{ $animal->status }}
                                    </span>
                                @elseif ($animal->status === 'Grazing')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-zenith-teal/10 text-zenith-teal border border-zenith-teal/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-zenith-teal"></div> {{ $animal->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></div> {{ $animal->status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-zenith-textLight">
                            <i class="material-icons text-4xl mb-2 text-zenith-border">pets</i>
                            <p class="text-sm">No animals registered yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Behavior Alert Panel -->
            @if($distressed > 0)
            <div class="bg-red-500 p-6 rounded-xl shadow-sm text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                    <i class="material-icons text-9xl">warning</i>
                </div>
                <h2 class="text-lg font-bold mb-1 flex items-center gap-2">
                    <i class="material-icons">notifications_active</i>
                    Distress Alerts Detected
                </h2>
                <p class="text-sm text-red-100 mb-4">{{ $distressed }} animals are showing abnormal speed (>10km/h) or sudden altitude drops.</p>
                <a href="{{ route('animals.index') }}" class="inline-block bg-white text-red-500 font-bold text-sm px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                    Review Urgent Logs
                </a>
            </div>
            @endif
        </div>

        <!-- Side Actions -->
        <div class="space-y-6">
            <!-- Sync Data -->
            <div class="bg-zenith-card p-6 rounded-xl shadow-sm border border-zenith-border transition-colors">
                <h3 class="text-lg font-bold text-zenith-text mb-2">GPS Sync</h3>
                <p class="text-sm text-zenith-textLight mb-6">Manually process the latest GPS collar batches.</p>
                
                <button class="w-full flex items-center justify-center gap-2 bg-zenith-primary hover:opacity-90 text-zenith-card font-medium py-2.5 px-4 rounded-lg transition-opacity shadow-sm">
                    <i class="material-icons text-[18px]">sync</i>
                    Sync Collar Data
                </button>
            </div>

            <!-- Behavior Distribution -->
            <div class="bg-zenith-card p-6 rounded-xl shadow-sm border border-zenith-border transition-colors">
                <h3 class="text-lg font-bold text-zenith-text mb-1">Behavior Distribution</h3>
                <p class="text-sm text-zenith-textLight mb-6">Current herd status</p>
                
                <!-- Chart Placeholder -->
                <div class="flex justify-center mb-8 relative">
                    <div id="donutChart"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                        <span class="text-xl font-bold text-zenith-text">{{ $total ?? 0 }}</span>
                        <span class="text-[10px] text-zenith-textLight uppercase tracking-wider">Total</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-zenith-blue"></div>
                            <span class="text-zenith-textLight">Resting</span>
                        </div>
                        <span class="font-bold text-zenith-text">{{ $total > 0 ? round(($resting / $total) * 100) : 0 }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-zenith-teal"></div>
                            <span class="text-zenith-textLight">Grazing</span>
                        </div>
                        <span class="font-bold text-zenith-text">{{ $total > 0 ? round(($grazing / $total) * 100) : 0 }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                            <span class="text-zenith-textLight">Distressed</span>
                        </div>
                        <span class="font-bold text-zenith-text">{{ $total > 0 ? round(($distressed / $total) * 100) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Passing PHP variables to JS for charts
    window.chartData = {
        resting: {{ $resting ?? 0 }},
        grazing: {{ $grazing ?? 0 }},
        distressed: {{ $distressed ?? 0 }}
    };
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof ApexCharts === 'undefined') return;
        
        const sparklineOptions = {
            chart: { type: 'area', height: 50, sparkline: { enabled: true }, animations: { enabled: false } },
            stroke: { curve: 'smooth', width: 2 },
            fill: { opacity: 0.1, type: 'solid' },
            tooltip: { fixed: { enabled: false }, x: { show: false }, y: { title: { formatter: () => '' } }, marker: { show: false } }
        };

        // Render sparkline 1 (Total - Grey/Neutral)
        if (document.querySelector("#spark1")) {
            new ApexCharts(document.querySelector("#spark1"), {
                ...sparklineOptions,
                colors: ['#6B7280'],
                series: [{ data: [12, 14, 13, 20, 25, 23, 28] }]
            }).render();
        }

        // Render sparkline 2 (Resting - Blue)
        if (document.querySelector("#spark2")) {
            new ApexCharts(document.querySelector("#spark2"), {
                ...sparklineOptions,
                colors: ['#3B82F6'],
                series: [{ data: [30, 25, 36, 30, 45, 35, 64] }]
            }).render();
        }

        // Render sparkline 3 (Grazing - Teal)
        if (document.querySelector("#spark3")) {
            new ApexCharts(document.querySelector("#spark3"), {
                ...sparklineOptions,
                colors: ['#14B8A6'],
                series: [{ data: [47, 45, 54, 38, 56, 24, 65] }]
            }).render();
        }

        // Render sparkline 4 (Distressed - Red)
        if (document.querySelector("#spark4")) {
            new ApexCharts(document.querySelector("#spark4"), {
                ...sparklineOptions,
                colors: ['#EF4444'],
                series: [{ data: [0, 0, 1, 3, 2, 0, 1] }]
            }).render();
        }

        // Render Donut Chart based on real data
        if (document.querySelector("#donutChart")) {
            let dataSeries = [window.chartData.resting, window.chartData.grazing, window.chartData.distressed];
            if (dataSeries.every(val => val === 0)) dataSeries = [1, 1, 1]; // Prevent empty chart display

            new ApexCharts(document.querySelector("#donutChart"), {
                series: dataSeries,
                chart: {
                    type: 'donut',
                    height: 160,
                    background: 'transparent'
                },
                colors: ['#3B82F6', '#14B8A6', '#EF4444'],
                plotOptions: {
                    pie: { donut: { size: '80%', background: 'transparent' }, expandOnClick: false }
                },
                dataLabels: { enabled: false },
                stroke: { show: false },
                legend: { show: false },
                tooltip: { theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
            }).render();
        }
    });
</script>
@endpush
@endsection
