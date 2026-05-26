@extends('layouts.app')
@section('title', 'Settings')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
#farm-map { height: 320px; border-radius: 0.75rem; z-index: 1; }
.leaflet-container { background: #0f172a; }
.tab-btn { transition: all .2s; }
.tab-btn.active { background: rgba(56,189,248,.12); color: #38bdf8; border-color: rgba(56,189,248,.3); }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="{ tab: 'profile' }">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-zenith-text">Settings</h1>
        <p class="text-sm text-zenith-textLight mt-1">Manage your account, farm location and security.</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm font-semibold">
        <i class="material-icons text-[18px]">check_circle</i>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm">
        <i class="material-icons text-[18px] flex-shrink-0 mt-0.5">error</i>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-2 border-b border-zenith-border pb-1">
        @foreach([['profile','person','Profile'],['farm','location_on','Farm Location'],['password','lock','Password']] as [$t,$icon,$label])
        <button @click="tab = '{{ $t }}'"
            :class="tab === '{{ $t }}' ? 'active' : ''"
            class="tab-btn flex items-center gap-1.5 px-4 py-2 rounded-xl border border-transparent text-sm font-semibold text-zenith-textLight hover:text-zenith-text">
            <i class="material-icons text-[16px]">{{ $icon }}</i>
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ── TAB: PROFILE ── --}}
    <div x-show="tab === 'profile'" x-transition>
        <div class="bg-zenith-card border border-zenith-border rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-zenith-border flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zenith-blue/10 flex items-center justify-center">
                    <i class="material-icons text-zenith-blue text-[20px]">person</i>
                </div>
                <div>
                    <h2 class="font-bold text-zenith-text text-sm">Personal Information</h2>
                    <p class="text-xs text-zenith-textLight">Update your name, contact and bio.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('settings.profile') }}" class="p-6 space-y-5">
                @csrf
                {{-- Avatar placeholder --}}
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-zenith-blue to-zenith-teal flex items-center justify-center text-2xl font-black text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-zenith-text">{{ $user->name }}</p>
                        <p class="text-xs text-zenith-textLight">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">
                            Email Address
                            <span class="ml-1 text-amber-400 normal-case tracking-normal font-normal">(locked)</span>
                        </label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full bg-zenith-hover/40 border border-zenith-border text-zenith-textLight rounded-xl px-4 py-2.5 text-sm cursor-not-allowed opacity-60 pr-10" />
                            <i class="material-icons text-amber-400 text-[16px] absolute right-3 top-1/2 -translate-y-1/2">lock</i>
                        </div>
                        <p class="text-[11px] text-zenith-textLight mt-1">Email cannot be changed. Contact support if needed.</p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210"
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Tell us about yourself and your farm…"
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors resize-none">{{ old('bio', $user->bio) }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="flex items-center gap-2 bg-zenith-blue text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                        <i class="material-icons text-[18px]">save</i>
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TAB: FARM LOCATION ── --}}
    <div x-show="tab === 'farm'" x-transition style="display:none">
        <div class="bg-zenith-card border border-zenith-border rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-zenith-border flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <i class="material-icons text-emerald-400 text-[20px]">location_on</i>
                </div>
                <div>
                    <h2 class="font-bold text-zenith-text text-sm">Farm Location</h2>
                    <p class="text-xs text-zenith-textLight">Click the map to pin your farm. New animals will start here.</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                {{-- Farm name --}}
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Farm Name</label>
                    <input type="text" id="farm-name-input" value="{{ $user->farm_name ?? '' }}" placeholder="e.g. Green Valley Farm"
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                </div>

                {{-- Map --}}
                <div class="relative">
                    <div id="farm-map"></div>
                    <div class="absolute top-3 right-3 z-[999] bg-zenith-sidebar/90 backdrop-blur border border-zenith-border rounded-xl px-3 py-2 text-xs text-zenith-textLight pointer-events-none">
                        <i class="material-icons text-[13px] align-middle">touch_app</i>
                        Click map to set farm location
                    </div>
                </div>

                {{-- Coords display + form --}}
                <form method="POST" action="{{ route('settings.farm') }}" id="farm-form" class="space-y-3">
                    @csrf
                    <input type="hidden" name="farm_name" id="farm-name-hidden" value="{{ $user->farm_name ?? '' }}" />
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Latitude</label>
                            <input type="number" name="farm_lat" id="farm-lat" step="any"
                                value="{{ old('farm_lat', $user->farm_lat ?? 30.9010) }}" required
                                class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-emerald-400 transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Longitude</label>
                            <input type="number" name="farm_lng" id="farm-lng" step="any"
                                value="{{ old('farm_lng', $user->farm_lng ?? 75.8573) }}" required
                                class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-emerald-400 transition-colors" />
                        </div>
                    </div>

                    @if($user->farm_lat && $user->farm_lng)
                    <div class="flex items-center gap-2 text-xs text-emerald-400 font-semibold">
                        <i class="material-icons text-[14px]">check_circle</i>
                        Current farm: {{ $user->farm_name ?? 'Unnamed Farm' }}
                        ({{ number_format($user->farm_lat, 5) }}, {{ number_format($user->farm_lng, 5) }})
                    </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit"
                            class="flex items-center gap-2 bg-emerald-500 text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                            <i class="material-icons text-[18px]">save</i>
                            Save Farm Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── TAB: PASSWORD ── --}}
    <div x-show="tab === 'password'" x-transition style="display:none">
        <div class="bg-zenith-card border border-zenith-border rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-zenith-border flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <i class="material-icons text-amber-400 text-[20px]">lock</i>
                </div>
                <div>
                    <h2 class="font-bold text-zenith-text text-sm">Change Password</h2>
                    <p class="text-xs text-zenith-textLight">Use a strong password with at least 8 characters.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('settings.password') }}" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Current Password</label>
                    <input type="password" name="current_password" required
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">New Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-zenith-blue transition-colors" />
                </div>
                <div class="bg-amber-500/5 border border-amber-500/20 rounded-xl p-4 text-xs text-zenith-textLight">
                    <i class="material-icons text-amber-400 text-[14px] align-middle mr-1">info</i>
                    Min 8 characters. Mix letters, numbers and symbols for a strong password.
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="flex items-center gap-2 bg-amber-500 text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                        <i class="material-icons text-[18px]">lock_reset</i>
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Account Info Card --}}
    <div class="bg-zenith-card border border-zenith-border rounded-2xl p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1">Account ID</p>
            <p class="font-mono text-sm text-zenith-text">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-1">Member Since</p>
            <p class="text-sm text-zenith-text">{{ $user->created_at?->format('d M Y') ?? 'N/A' }}</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let farmMap, farmMarker;

// Init map when Farm tab is shown
document.querySelectorAll('[\\@click]').forEach(btn => {
    btn.addEventListener('click', () => {
        setTimeout(() => {
            if (!farmMap && document.getElementById('farm-map')?.offsetParent !== null) {
                initFarmMap();
            }
        }, 50);
    });
});

function initFarmMap() {
    const lat = parseFloat(document.getElementById('farm-lat').value) || 30.9010;
    const lng = parseFloat(document.getElementById('farm-lng').value) || 75.8573;

    farmMap = L.map('farm-map', { center: [lat, lng], zoom: 12 });
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        subdomains: 'abcd', maxZoom: 19,
    }).addTo(farmMap);

    const icon = L.divIcon({
        className: '',
        html: `<div style="width:36px;height:36px;border-radius:50%;background:rgba(52,211,153,.2);border:2px solid #34d399;display:flex;align-items:center;justify-content:center;font-size:18px;">🏡</div>`,
        iconSize: [36,36], iconAnchor: [18,18],
    });

    farmMarker = L.marker([lat, lng], { icon, draggable: true }).addTo(farmMap);
    farmMarker.bindPopup('Your Farm').openPopup();

    // Click map to move farm pin
    farmMap.on('click', (e) => {
        farmMarker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    // Drag marker to move
    farmMarker.on('dragend', (e) => {
        const pos = farmMarker.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });
}

function updateCoords(lat, lng) {
    document.getElementById('farm-lat').value = lat.toFixed(6);
    document.getElementById('farm-lng').value = lng.toFixed(6);
}

// Sync farm name hidden input
document.getElementById('farm-name-input')?.addEventListener('input', function() {
    document.getElementById('farm-name-hidden').value = this.value;
});

// Also init map if farm tab is already active on load (e.g. after redirect with ?tab=farm)
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.hash === '#farm') {
        setTimeout(initFarmMap, 100);
    }
});
</script>
@endpush
