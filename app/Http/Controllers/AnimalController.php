<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Animal;

class AnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::where('user_id', auth()->id())->paginate(15);
        return view('animals.index', compact('animals'));
    }

    public function show(Animal $animal)
    {
        if ($animal->user_id !== auth()->id()) {
            abort(403);
        }

        $logs = $animal->gpsLogs()->orderByDesc('recorded_at')->paginate(20);
        return view('animals.show', compact('animal', 'logs'));
    }
}
