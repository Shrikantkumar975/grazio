@extends('layouts.app')

@section('title', 'Livestock Registry')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zenith-text mb-1">Livestock Registry</h1>
            <p class="text-sm text-zenith-textLight">Manage and monitor all animals equipped with GPS collars.</p>
        </div>
        <button class="flex items-center gap-2 bg-zenith-primary text-zenith-card hover:opacity-90 transition-opacity text-sm font-medium py-2 px-4 rounded-lg shadow-sm">
            <i class="material-icons text-[16px]">add</i>
            Register Animal
        </button>
    </div>

    <div class="bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-zenith-text">
                <thead class="bg-zenith-hover/50 text-zenith-textLight text-xs uppercase font-semibold border-b border-zenith-border">
                    <tr>
                        <th class="px-6 py-4">Animal ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Age</th>
                        <th class="px-6 py-4">Current Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zenith-border">
                    @forelse ($animals as $animal)
                    <tr class="hover:bg-zenith-hover/30 transition-colors group">
                        <td class="px-6 py-4 font-mono text-xs text-zenith-textLight group-hover:text-zenith-text">
                            #{{ str_pad($animal->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 font-medium text-zenith-text">
                            {{ $animal->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $animal->type }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $animal->age }} years
                        </td>
                        <td class="px-6 py-4">
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
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('animals.show', $animal) }}" class="text-zenith-blue hover:underline font-medium text-sm">View Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-zenith-textLight">
                            <i class="material-icons text-4xl mb-3 text-zenith-border">pets</i>
                            <p>No animals registered yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($animals->hasPages())
        <div class="p-4 border-t border-zenith-border">
            {{ $animals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
