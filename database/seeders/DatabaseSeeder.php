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

        // GPS base coordinates: Ludhiana, Punjab, India
        // Bessie — grazing near the centre of the farm (inside a typical zone)
        $a1 = \App\Models\Animal::create(['user_id' => $u->id, 'name' => 'Bessie', 'type' => 'Cow',   'age' => 4, 'status' => 'Grazing']);
        // Daisy — resting inside a typical zone
        $a2 = \App\Models\Animal::create(['user_id' => $u->id, 'name' => 'Daisy',  'type' => 'Cow',   'age' => 3, 'status' => 'Resting']);
        // Bolt — has wandered outside (distressed)
        $a3 = \App\Models\Animal::create(['user_id' => $u->id, 'name' => 'Bolt',   'type' => 'Horse', 'age' => 5, 'status' => 'Distressed']);

        $baseCoords = [
            // [lat, lng]  — spread across Ludhiana district, Punjab
            $a1->id => [30.9010, 75.8573],   // Ludhiana city centre
            $a2->id => [30.9085, 75.8620],   // ~700 m north-east
            $a3->id => [30.8750, 75.9100],   // ~5 km south-east (wandered away)
        ];

        foreach ([$a1, $a2, $a3] as $a) {
            [$baseLat, $baseLng] = $baseCoords[$a->id];
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\GpsLog::create([
                    'animal_id'   => $a->id,
                    'recorded_at' => now()->subMinutes($i * 10),
                    'latitude'    => $baseLat + ($i * 0.0005),  // small realistic drift
                    'longitude'   => $baseLng + ($i * 0.0005),
                    'altitude'    => $a->status === 'Distressed' && $i == 1 ? 280 : 295,
                    'speed'       => $a->status === 'Distressed' ? 12 : ($a->status === 'Grazing' ? 1.5 : 0),
                ]);
            }
        }
    }
}