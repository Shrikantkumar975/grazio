<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::create([
                'name'     => 'Test User',
                'email'    => 'test@grazio.com',
                'password' => Hash::make('password'),
            ]);

            User::create([
                'name'     => 'Demo Analyst',
                'email'    => 'demo@grazio.com',
                'password' => Hash::make('password'),
            ]);
        }
        $u = User::first();

        $a1 = \App\Models\Animal::create(['user_id' => $u->id, 'name' => 'Bessie', 'type' => 'Cow', 'age' => 4, 'status' => 'Grazing']);
        $a2 = \App\Models\Animal::create(['user_id' => $u->id, 'name' => 'Daisy', 'type' => 'Cow', 'age' => 3, 'status' => 'Resting']);
        $a3 = \App\Models\Animal::create(['user_id' => $u->id, 'name' => 'Bolt', 'type' => 'Horse', 'age' => 5, 'status' => 'Distressed']);

        foreach ([$a1, $a2, $a3] as $a) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\GpsLog::create([
                    'animal_id' => $a->id,
                    'recorded_at' => now()->subMinutes($i * 10),
                    'latitude' => 34.05 + ($i * 0.001),
                    'longitude' => -118.24 + ($i * 0.001),
                    'altitude' => $a->status === 'Distressed' && $i == 1 ? 100 : 150,
                    'speed' => $a->status === 'Distressed' ? 12 : ($a->status === 'Grazing' ? 1.5 : 0)
                ]);
            }
        }
    }
}