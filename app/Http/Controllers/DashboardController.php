<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $total       = Animal::where('user_id', $userId)->count();
        $resting     = Animal::where('user_id', $userId)->where('status', 'Resting')->count();
        $grazing     = Animal::where('user_id', $userId)->where('status', 'Grazing')->count();
        $distressed  = Animal::where('user_id', $userId)->where('status', 'Distressed')->count();
        $recent      = Animal::where('user_id', $userId)->orderByDesc('created_at')->take(5)->get();

        return view('dashboard.index', compact(
            'total',
            'resting',
            'grazing',
            'distressed',
            'recent'
        ));
    }
}
