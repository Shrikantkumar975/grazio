<?php

namespace App\Http\Controllers;

use App\Models\Geofence;
use App\Models\GeofenceAlert;
use App\Models\Animal;
use App\Services\GeofenceService;
use Illuminate\Http\Request;

class GeofenceController extends Controller
{
    public function __construct(protected GeofenceService $service) {}

    public function index()
    {
        $geofences = Geofence::where('user_id', auth()->id())->get();
        $animals   = Animal::where('user_id', auth()->id())->get();
        $alerts    = GeofenceAlert::where('user_id', auth()->id())
                        ->where('resolved', false)
                        ->latest()
                        ->take(20)
                        ->get();

        // Pre-process for JS map — avoids complex closures inside Blade @json()
        $animalMapData = $animals->map(function ($a) {
            $last = $a->gpsLogs()->latest('recorded_at')->first();
            return [
                'id'     => $a->id,
                'name'   => $a->name,
                'status' => $a->status,
                'lat'    => $last ? $last->latitude  : null,
                'lng'    => $last ? $last->longitude : null,
            ];
        })->values()->toArray();

        return view('geofences.index', compact('geofences', 'animals', 'alerts', 'animalMapData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'color'       => 'required|string',
            'coordinates' => 'required|array|min:3',
        ]);

        Geofence::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'color'       => $request->color,
            'coordinates' => $request->coordinates,
            'active'      => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $fence = Geofence::where('id', (int)$id)->where('user_id', auth()->id())->firstOrFail();
        $fence->delete();
        return response()->json(['success' => true]);
    }

    public function toggle($id)
    {
        $fence = Geofence::where('id', (int)$id)->where('user_id', auth()->id())->firstOrFail();
        $fence->update(['active' => !$fence->active]);
        return response()->json(['success' => true, 'active' => $fence->active]);
    }

    /**
     * Called when a new GPS log is submitted for an animal.
     * Checks all active geofences and creates alerts for breaches.
     */
    public function checkAnimal(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|integer',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $animal = Animal::where('id', $request->animal_id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $breaches = $this->service->checkBreaches(
            (float) $request->latitude,
            (float) $request->longitude,
            auth()->id()
        );

        $newAlerts = [];
        foreach ($breaches as $fence) {
            // Avoid duplicate unresolved alerts for same animal+fence
            $exists = GeofenceAlert::where('animal_id', $animal->id)
                ->where('geofence_id', $fence->id)
                ->where('resolved', false)
                ->exists();

            if (!$exists) {
                $alert = GeofenceAlert::create([
                    'user_id'       => auth()->id(),
                    'animal_id'     => $animal->id,
                    'geofence_id'   => $fence->id,
                    'geofence_name' => $fence->name,
                    'animal_name'   => $animal->name,
                    'latitude'      => $request->latitude,
                    'longitude'     => $request->longitude,
                    'resolved'      => false,
                ]);
                $newAlerts[] = $alert;

                // Update animal status to Distressed
                $animal->update(['status' => 'Distressed']);
            }
        }

        return response()->json([
            'breach'    => count($newAlerts) > 0,
            'alerts'    => $newAlerts,
            'fences_checked' => $breaches->count(),
        ]);
    }

    public function resolveAlert($id)
    {
        $alert = GeofenceAlert::where('id', (int)$id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
        $alert->update(['resolved' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Return all geofences for the map as JSON.
     */
    public function apiIndex()
    {
        $geofences = Geofence::where('user_id', auth()->id())->get();
        return response()->json($geofences);
    }
}
