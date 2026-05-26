@extends('layouts.app')
@section('title', 'Livestock Registry')

@section('content')
<div class="space-y-6" x-data="{ modal: false }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zenith-text mb-1">Livestock Registry</h1>
            <p class="text-sm text-zenith-textLight">Manage and monitor all animals equipped with GPS collars.</p>
        </div>
        <button @click="modal = true"
            class="flex items-center gap-2 bg-zenith-primary text-white hover:opacity-90 transition-opacity text-sm font-bold py-2.5 px-5 rounded-xl shadow-lg">
            <i class="material-icons text-[18px]">add</i>
            Register Animal
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-zenith-text">
                <thead class="bg-zenith-hover/50 text-zenith-textLight text-xs uppercase font-semibold border-b border-zenith-border">
                    <tr>
                        <th class="px-6 py-4">Animal ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Type / Breed</th>
                        <th class="px-6 py-4">Age</th>
                        <th class="px-6 py-4">Gender</th>
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
                        <td class="px-6 py-4 font-semibold text-zenith-text">{{ $animal->name }}</td>
                        <td class="px-6 py-4">
                            {{ $animal->type }}
                            @if($animal->breed)
                                <span class="text-zenith-textLight text-xs">/ {{ $animal->breed }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $animal->age }} yrs</td>
                        <td class="px-6 py-4 text-zenith-textLight">{{ $animal->gender ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyles = [
                                    'Resting'    => 'bg-zenith-blue/10 text-zenith-blue border-zenith-blue/20',
                                    'Grazing'    => 'bg-zenith-teal/10 text-zenith-teal border-zenith-teal/20',
                                    'Distressed' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                ];
                                $s = $statusStyles[$animal->status] ?? 'bg-zenith-hover text-zenith-textLight border-zenith-border';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $s }}">
                                <div class="w-1.5 h-1.5 rounded-full {{ $animal->status === 'Distressed' ? 'bg-red-500 animate-ping' : 'bg-current' }}"></div>
                                {{ $animal->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('animals.show', ['animal' => $animal->id]) }}"
                                    class="inline-flex items-center gap-1.5 text-zenith-blue hover:text-zenith-blue/80 font-semibold text-sm transition-colors">
                                    <i class="material-icons text-[16px]">open_in_new</i>
                                    View Details
                                </a>
                                <form method="POST" action="{{ route('animals.destroy', ['animal' => $animal->id]) }}"
                                    onsubmit="return confirm('Remove {{ $animal->name }} from the registry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-zenith-textLight hover:text-red-400 transition-colors">
                                        <i class="material-icons text-[18px]">delete</i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-zenith-textLight">
                            <i class="material-icons text-5xl mb-3 text-zenith-border block">pets</i>
                            <p class="font-semibold">No animals registered yet.</p>
                            <p class="text-xs mt-1">Click "Register Animal" to add your first one.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($animals->hasPages())
        <div class="p-4 border-t border-zenith-border">{{ $animals->links() }}</div>
        @endif
    </div>

    {{-- ── REGISTER MODAL ── --}}
    <div x-show="modal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display:none">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="modal = false"></div>

        {{-- Panel --}}
        <div x-show="modal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="relative bg-zenith-card border border-zenith-border rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto custom-scrollbar z-10">

            <div class="sticky top-0 bg-zenith-card border-b border-zenith-border px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-zenith-text">Register New Animal</h2>
                    <p class="text-xs text-zenith-textLight mt-0.5">Default GPS location is set to the farm centre.</p>
                </div>
                <button @click="modal = false" class="text-zenith-textLight hover:text-zenith-text transition-colors">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <form method="POST" action="{{ route('animals.store') }}" class="p-6 space-y-5">
                @csrf

                {{-- Row 1: Name + Tag --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Animal Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Bessie"
                            class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Tag / Collar No.</label>
                        <input type="text" name="tag_number" placeholder="e.g. TAG-001"
                            class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                    </div>
                </div>

                {{-- Row 2: Type + Breed --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Animal Type *</label>
                        <select name="type" required class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors">
                            <option value="">Select type…</option>
                            @foreach(['Cow','Bull','Buffalo','Horse','Sheep','Goat','Pig','Camel','Donkey','Other'] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Breed</label>
                        <input type="text" name="breed" placeholder="e.g. Murrah, Holstein"
                            class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                    </div>
                </div>

                {{-- Row 3: Age + Gender + Weight --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Age (years) *</label>
                        <input type="number" name="age" required min="0" max="50" placeholder="4"
                            class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Gender *</label>
                        <select name="gender" required class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors">
                            <option value="">Select…</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Weight (kg)</label>
                        <input type="number" name="weight" step="0.1" min="0" placeholder="350"
                            class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                    </div>
                </div>

                {{-- Row 4: Color --}}
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Coat Color / Markings</label>
                    <input type="text" name="color" placeholder="e.g. Black & White, Brown"
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                </div>

                {{-- Farm Location (pre-filled, editable) --}}
                <div class="bg-emerald-500/5 border border-emerald-500/20 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <i class="material-icons text-emerald-400 text-[18px]">location_on</i>
                            <p class="text-sm font-bold text-emerald-400">Initial GPS Location</p>
                        </div>
                        @if(auth()->user()->farm_lat)
                        <span class="text-[11px] text-emerald-400 font-semibold">✓ Using your saved farm location</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-zenith-textLight mb-1">Latitude</label>
                            <input type="number" name="farm_lat" step="any"
                                value="{{ auth()->user()->farm_lat ?? 30.9010 }}"
                                class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-3 py-2 text-sm font-mono focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs text-zenith-textLight mb-1">Longitude</label>
                            <input type="number" name="farm_lng" step="any"
                                value="{{ auth()->user()->farm_lng ?? 75.8573 }}"
                                class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-3 py-2 text-sm font-mono focus:outline-none" />
                        </div>
                    </div>
                    <p class="text-[11px] text-zenith-textLight mt-2">
                        Default from your farm settings.
                        <a href="{{ route('settings.index') }}" class="text-zenith-blue hover:underline">Change farm location →</a>
                    </p>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight mb-1.5 uppercase tracking-wider">Notes</label>
                    <textarea name="notes" rows="2" placeholder="Health history, special care requirements…"
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors resize-none"></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 bg-zenith-primary text-white font-bold py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2 text-sm">
                        <i class="material-icons text-[18px]">pets</i>
                        Register Animal
                    </button>
                    <button type="button" @click="modal = false"
                        class="px-6 bg-zenith-hover border border-zenith-border text-zenith-textLight font-bold py-3 rounded-xl hover:bg-zenith-border transition-colors text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
