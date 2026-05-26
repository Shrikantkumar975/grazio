<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', ['user' => auth()->user()]);
    }

    /** Update profile details (name, email, phone, bio) */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'bio'   => 'nullable|string|max:300',
        ]);

        // Email is intentionally locked — not updated here

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /** Update farm details + GPS location */
    public function updateFarm(Request $request)
    {
        $data = $request->validate([
            'farm_name' => 'nullable|string|max:120',
            'farm_lat'  => 'required|numeric|between:-90,90',
            'farm_lng'  => 'required|numeric|between:-180,180',
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Farm location saved. New animals will default to this position.');
    }

    /** Change password */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => $request->password]);

        return back()->with('success', 'Password changed successfully.');
    }
}
