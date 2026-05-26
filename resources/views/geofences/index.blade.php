@extends('layouts.app')
@section('title', 'Geofences')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<style>
    #map { height: 100%; width: 100%; border-radius: 1rem; z-index: 1; }
    .leaflet-container { background: #0f172a; }
    .alert-item { transition: all 0.2s; }
    .alert-item:hover { background: rgba(239,68,68,0.05); }
</style>
@endpush

@section('content')
<div class="flex flex-col h-full gap-4" style="height: calc(100vh - 136px);">

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between flex-shrink-0">
        <div>
            <h1 class="text-2xl font-black text-zenith-text">Smart Geofencing</h1>
            <p class="text-sm text-zenith-textLight mt-0.5">Draw zones on the map — get instant alerts when animals leave them.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-zenith-hover border border-zenith-border text-sm font-semibold text-zenith-textLight">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
                {{ $geofences->where('active', true)->count() }} active zones
            </div>
            @if($alerts->count() > 0)
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/10 border border-red-500/20 text-sm font-semibold text-red-400">
                <i class="material-icons text-[16px]">warning</i>
                {{ $alerts->count() }} breach alerts
            </div>
            @endif
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="flex gap-4 flex-1 min-h-0">

        {{-- MAP --}}
        <div class="flex-1 bg-zenith-card border border-zenith-border rounded-2xl overflow-hidden relative">
            <div id="map" style="height:100%"></div>

            {{-- Draw toolbar hint --}}
            <div class="absolute top-3 left-1/2 -translate-x-1/2 z-[999] bg-zenith-sidebar/90 backdrop-blur border border-zenith-border rounded-xl px-4 py-2 text-xs text-zenith-textLight font-semibold pointer-events-none">
                <i class="material-icons text-[14px] align-middle">draw</i>
                Click the polygon tool on the left to draw a new zone
            </div>

            {{-- Save Zone Panel (hidden until polygon drawn) --}}
            <div id="save-panel" class="absolute bottom-4 left-1/2 -translate-x-1/2 z-[999] bg-zenith-sidebar border border-zenith-border rounded-2xl shadow-2xl p-4 w-80 hidden">
                <h3 class="font-bold text-zenith-text mb-3 text-sm">Save New Zone</h3>
                <input id="fence-name" type="text" placeholder="Zone name (e.g. North Pasture)" maxlength="60"
                    class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-3 py-2 text-sm mb-3 focus:outline-none focus:border-zenith-blue" />
                <div class="flex items-center gap-2 mb-3">
                    <label class="text-xs text-zenith-textLight font-semibold">Color:</label>
                    @foreach(['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'] as $c)
                    <button onclick="selectColor('{{ $c }}')" class="color-btn w-6 h-6 rounded-full border-2 border-transparent transition-all" style="background:{{ $c }}" data-color="{{ $c }}"></button>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <button onclick="saveZone()" class="flex-1 bg-zenith-blue text-white font-bold text-sm py-2 rounded-xl hover:opacity-90 transition-opacity">
                        Save Zone
                    </button>
                    <button onclick="cancelDraw()" class="px-4 bg-zenith-hover text-zenith-textLight font-bold text-sm py-2 rounded-xl hover:bg-zenith-border transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="w-80 flex flex-col gap-4 overflow-y-auto min-h-0 custom-scrollbar">

            {{-- BREACH ALERTS --}}
            @if($alerts->count() > 0)
            <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold text-red-400 text-sm flex items-center gap-2">
                        <i class="material-icons text-[18px]">crisis_alert</i>
                        Active Breaches
                    </h2>
                </div>
                <div class="space-y-2">
                    @foreach($alerts as $alert)
                    <div class="alert-item bg-zenith-hover rounded-xl p-3 flex items-start gap-3 border border-red-500/10">
                        <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
                            <i class="material-icons text-red-400 text-[16px]">pets</i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-zenith-text truncate">{{ $alert->animal_name }}</p>
                            <p class="text-[11px] text-zenith-textLight truncate">Left: {{ $alert->geofence_name }}</p>
                            <p class="text-[10px] text-red-400 mt-0.5">{{ $alert->created_at->diffForHumans() }}</p>
                        </div>
                        <button onclick="resolveAlert({{ $alert->id }}, this)"
                            class="flex-shrink-0 w-6 h-6 rounded-full bg-zenith-border hover:bg-emerald-500/20 hover:text-emerald-400 text-zenith-textLight transition-colors flex items-center justify-center">
                            <i class="material-icons text-[14px]">check</i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-emerald-500/5 border border-emerald-500/20 rounded-2xl p-4 flex items-center gap-3">
                <i class="material-icons text-emerald-400">verified</i>
                <div>
                    <p class="text-sm font-bold text-emerald-400">All Clear</p>
                    <p class="text-xs text-zenith-textLight">No active breach alerts</p>
                </div>
            </div>
            @endif

            {{-- CHECK ANIMAL MANUALLY --}}
            <div class="bg-zenith-card border border-zenith-border rounded-2xl p-4">
                <h2 class="font-bold text-zenith-text text-sm mb-3 flex items-center gap-2">
                    <i class="material-icons text-[18px] text-zenith-blue">gps_fixed</i>
                    Check Animal Position
                </h2>
                <select id="check-animal" class="w-full bg-zenith-hover border border-zenith-border text-zenith-text rounded-xl px-3 py-2 text-sm mb-2 focus:outline-none">
                    <option value="">Select animal…</option>
                    @foreach($animals as $animal)
                    <option value="{{ $animal->id }}" data-lat="{{ optional($animal->gpsLogs()->latest('recorded_at')->first())->latitude ?? '' }}" data-lng="{{ optional($animal->gpsLogs()->latest('recorded_at')->first())->longitude ?? '' }}">
                        {{ $animal->name }} ({{ $animal->type }})
                    </option>
                    @endforeach
                </select>
                <button onclick="checkAnimal()" class="w-full bg-zenith-blue text-white font-bold text-sm py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <i class="material-icons text-[18px]">search</i>
                    Check Against Zones
                </button>
                <div id="check-result" class="mt-3 hidden"></div>
            </div>

            {{-- ZONES LIST --}}
            <div class="bg-zenith-card border border-zenith-border rounded-2xl p-4 flex-1">
                <h2 class="font-bold text-zenith-text text-sm mb-3 flex items-center gap-2">
                    <i class="material-icons text-[18px] text-zenith-teal">layers</i>
                    Your Zones ({{ $geofences->count() }})
                </h2>

                @forelse($geofences as $fence)
                <div id="fence-row-{{ $fence->id }}" class="flex items-center gap-3 py-3 border-b border-zenith-border/50 last:border-0">
                    <div class="w-4 h-4 rounded-full flex-shrink-0 border-2 border-white/20" style="background: {{ $fence->color ?? '#3b82f6' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-zenith-text truncate">{{ $fence->name }}</p>
                        <p class="text-[11px] text-zenith-textLight">{{ count($fence->coordinates ?? []) }} points</p>
                    </div>
                    <button onclick="toggleFence({{ $fence->id }}, this)"
                        class="text-[11px] font-bold px-2 py-1 rounded-lg transition-colors {{ $fence->active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-zenith-hover text-zenith-textLight' }}">
                        {{ $fence->active ? 'ON' : 'OFF' }}
                    </button>
                    <button onclick="deleteFence({{ $fence->id }}, this)"
                        class="text-zenith-textLight hover:text-red-400 transition-colors">
                        <i class="material-icons text-[18px]">delete</i>
                    </button>
                </div>
                @empty
                <div class="text-center py-8 text-zenith-textLight">
                    <i class="material-icons text-3xl mb-2 text-zenith-border">fence</i>
                    <p class="text-sm">No zones yet</p>
                    <p class="text-xs mt-1">Draw one on the map →</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script>
const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
const BASE   = window.location.origin;
let currentLayer = null;
let selectedColor = '#3b82f6';
let drawnItems;
let map;

// ── Init Map ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    map = L.map('map', {
        center: [30.9010, 75.8573], // Ludhiana, Punjab, India
        zoom: 11,
        zoomControl: true,
        minZoom: 6,
        maxZoom: 18,
    });

    // CartoDB Positron — clean, minimal, no clutter
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(map);

    drawnItems = new L.FeatureGroup().addTo(map);

    const drawControl = new L.Control.Draw({
        draw: {
            polygon:   { shapeOptions: { color: selectedColor, fillOpacity: 0.15, weight: 2 } },
            polyline:  false,
            rectangle: false,
            circle:    false,
            marker:    false,
            circlemarker: false,
        },
        edit: { featureGroup: drawnItems, remove: false },
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, (e) => {
        currentLayer = e.layer;
        drawnItems.addLayer(currentLayer);
        document.getElementById('save-panel').classList.remove('hidden');
    });

    // Load existing fences
    loadExistingFences();

    // Load animal markers
    loadAnimalMarkers();
});

// ── Load existing fences from DB ─────────────────────────────
function loadExistingFences() {
    const fences = @json($geofences);
    fences.forEach(fence => {
        if (!fence.coordinates || fence.coordinates.length < 3) return;
        const latlngs = fence.coordinates.map(c => [parseFloat(c[0]), parseFloat(c[1])]);
        const poly = L.polygon(latlngs, {
            color:       fence.color ?? '#3b82f6',
            fillOpacity: fence.active ? 0.12 : 0.04,
            weight:      fence.active ? 2 : 1,
            dashArray:   fence.active ? null : '6,6',
        }).addTo(map);
        poly.bindTooltip(`<strong>${fence.name}</strong><br>${fence.active ? '✅ Active' : '⏸ Inactive'}`, { sticky: true });
    });

    // Only fit bounds if all fences are within Punjab region (lat 28-33, lng 73-78)
    const localFences = fences.filter(f => {
        if (!f.coordinates || f.coordinates.length < 3) return false;
        return f.coordinates.every(c => {
            const la = parseFloat(c[0]), lo = parseFloat(c[1]);
            return la > 28 && la < 33 && lo > 73 && lo < 78;
        });
    });
    if (localFences.length > 0) {
        const allLatLngs = localFences.flatMap(f => f.coordinates.map(c => [parseFloat(c[0]), parseFloat(c[1])]));
        map.fitBounds(allLatLngs, { padding: [60, 60], maxZoom: 14 });
    }
}

// ── Load animal markers ───────────────────────────────────────
function loadAnimalMarkers() {
    const animals = @json($animalMapData);

    animals.forEach(animal => {
        // Skip if no GPS coords or coords are zero/null
        if (animal.lat === null || animal.lng === null || animal.lat === undefined) return;
        const lat = parseFloat(animal.lat);
        const lng = parseFloat(animal.lng);
        if (isNaN(lat) || isNaN(lng) || (lat === 0 && lng === 0)) return;

        const emoji = animal.type === 'Horse' ? '🐎' : animal.type === 'Sheep' ? '🐑' : animal.type === 'Goat' ? '🐐' : '🐄';
        const color = animal.status === 'Distressed' ? '#ef4444' : animal.status === 'Grazing' ? '#10b981' : '#3b82f6';
        const icon = L.divIcon({
            className: '',
            html: `<div style="width:34px;height:34px;border-radius:50%;background:${color}22;border:2px solid ${color};display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow:0 0 8px ${color}66">${emoji}</div>`,
            iconSize: [34, 34], iconAnchor: [17, 17],
        });
        L.marker([lat, lng], { icon })
         .addTo(map)
         .bindPopup(`<strong>${animal.name}</strong><br>Status: ${animal.status}<br><small>${lat.toFixed(5)}, ${lng.toFixed(5)}</small>`);
    });
}

// ── Color selection ───────────────────────────────────────────
function selectColor(color) {
    selectedColor = color;
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.style.border = btn.dataset.color === color ? '2px solid white' : '2px solid transparent';
    });
    if (currentLayer) {
        currentLayer.setStyle({ color, fillColor: color });
    }
}

// ── Save new zone ─────────────────────────────────────────────
async function saveZone() {
    const name = document.getElementById('fence-name').value.trim();
    if (!name) { alert('Please enter a zone name.'); return; }
    if (!currentLayer) return;

    const latlngs = currentLayer.getLatLngs()[0];
    const coordinates = latlngs.map(ll => [ll.lat, ll.lng]);

    const res = await fetch(`${BASE}/geofences`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ name, color: selectedColor, coordinates }),
    });

    if (res.ok) {
        document.getElementById('save-panel').classList.add('hidden');
        document.getElementById('fence-name').value = '';
        location.reload();
    }
}

function cancelDraw() {
    if (currentLayer) drawnItems.removeLayer(currentLayer);
    currentLayer = null;
    document.getElementById('save-panel').classList.add('hidden');
}

// ── Toggle fence active state ─────────────────────────────────
async function toggleFence(id, btn) {
    const res = await fetch(`${BASE}/geofences/${id}/toggle`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF },
    });
    if (res.ok) {
        const data = await res.json();
        btn.textContent = data.active ? 'ON' : 'OFF';
        btn.className = `text-[11px] font-bold px-2 py-1 rounded-lg transition-colors ${data.active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-zenith-hover text-zenith-textLight'}`;
    }
}

// ── Delete fence ──────────────────────────────────────────────
async function deleteFence(id, btn) {
    if (!confirm('Delete this zone?')) return;
    const res = await fetch(`${BASE}/geofences/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
    });
    if (res.ok) {
        document.getElementById(`fence-row-${id}`)?.remove();
    }
}

// ── Check animal against zones ────────────────────────────────
async function checkAnimal() {
    const select = document.getElementById('check-animal');
    const opt    = select.options[select.selectedIndex];
    const id     = select.value;
    const result = document.getElementById('check-result');

    if (!id) { result.innerHTML = '<p class="text-xs text-red-400 font-semibold">⚠ Select an animal first.</p>'; result.classList.remove('hidden'); return; }

    const rawLat = opt.dataset.lat;
    const rawLng = opt.dataset.lng;
    if (!rawLat || !rawLng) {
        result.innerHTML = '<p class="text-xs text-amber-400 font-semibold">⚠ No GPS data for this animal yet.</p>';
        result.classList.remove('hidden');
        return;
    }

    const lat = parseFloat(rawLat);
    const lng = parseFloat(rawLng);
    if (isNaN(lat) || isNaN(lng)) {
        result.innerHTML = '<p class="text-xs text-red-400 font-semibold">⚠ Invalid GPS coordinates.</p>';
        result.classList.remove('hidden');
        return;
    }

    const res = await fetch(`${BASE}/geofences/check`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ animal_id: parseInt(id), latitude: lat, longitude: lng }),
    });

    if (!res.ok) {
        result.innerHTML = `<div class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-xs text-red-400 font-semibold">⚠ Server error ${res.status}. Check that a geofence zone exists and is active.</div>`;
        result.classList.remove('hidden');
        return;
    }

    const data = await res.json();
    result.classList.remove('hidden');

    if (data.breach) {
        result.innerHTML = `<div class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-xs text-red-400 font-semibold">⚠️ Breach detected! ${data.alerts.length} alert(s) created.</div>`;
        setTimeout(() => location.reload(), 2000);
    } else {
        result.innerHTML = `<div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-xs text-emerald-400 font-semibold">✅ Inside all zones (${data.fences_checked} checked)</div>`;
    }
}

// ── Resolve alert ─────────────────────────────────────────────
async function resolveAlert(id, btn) {
    // Immediately remove from DOM so UX feels instant
    const alertItem = btn.closest('.alert-item');
    if (alertItem) {
        alertItem.style.transition = 'all 0.3s';
        alertItem.style.opacity   = '0';
        alertItem.style.transform = 'translateX(20px)';
    }

    try {
        const res = await fetch(`${BASE}/geofences/alerts/${parseInt(id)}/resolve`, {
            method:  'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        // Reload after short delay to update sidebar badge count and top alert count
        setTimeout(() => location.reload(), 400);
    } catch (e) {
        // Even on network error, reload to get fresh state
        setTimeout(() => location.reload(), 400);
    }
}
</script>
@endpush
