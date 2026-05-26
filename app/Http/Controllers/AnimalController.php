<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\GpsLog;

class AnimalController extends Controller
{
    // Fallback farm location — Ludhiana, Punjab, India
    const FARM_LAT = 30.9010;
    const FARM_LNG = 75.8573;

    public function index()
    {
        $animals = Animal::where('user_id', auth()->id())->paginate(15);
        return view('animals.index', compact('animals'));
    }

    public function create()
    {
        return view('animals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'type'       => 'required|string|max:50',
            'breed'      => 'nullable|string|max:100',
            'age'        => 'required|integer|min:0|max:50',
            'gender'     => 'required|in:Male,Female',
            'weight'     => 'nullable|numeric|min:0',
            'color'      => 'nullable|string|max:60',
            'tag_number' => 'nullable|string|max:40',
            'notes'      => 'nullable|string|max:500',
            'farm_lat'   => 'nullable|numeric',
            'farm_lng'   => 'nullable|numeric',
        ]);

        $user   = auth()->user();
        $defLat = $user->farm_lat ?? self::FARM_LAT;
        $defLng = $user->farm_lng ?? self::FARM_LNG;

        $animal = Animal::create([
            ...$data,
            'user_id'  => $user->id,
            'status'   => 'Resting',
            'farm_lat' => $data['farm_lat'] ?? $defLat,
            'farm_lng' => $data['farm_lng'] ?? $defLng,
        ]);

        // Seed initial GPS log at farm location
        GpsLog::create([
            'animal_id'   => $animal->id,
            'recorded_at' => now(),
            'latitude'    => $animal->farm_lat,
            'longitude'   => $animal->farm_lng,
            'altitude'    => 295,
            'speed'       => 0,
        ]);

        return redirect()->route('animals.show', ['animal' => $animal->id])
            ->with('success', "{$animal->name} has been registered and placed at the farm location.");
    }

    public function show($id)
    {
        $animal = Animal::where('id', (int) $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $lastLog = $animal->gpsLogs()->latest('recorded_at')->first();
        $logs    = $animal->gpsLogs()->orderByDesc('recorded_at')->paginate(20);
        return view('animals.show', compact('animal', 'logs', 'lastLog'));
    }

    public function destroy($id)
    {
        $animal = Animal::where('id', (int) $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $name = $animal->name;
        $animal->gpsLogs()->delete();
        $animal->delete();
        return redirect()->route('animals.index')->with('success', "{$name} has been removed from the registry.");
    }
}
