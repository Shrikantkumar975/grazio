<?php
use App\Models\Animal;
use App\Models\GpsLog;
use App\Models\User;

$users = User::all();
if ($users->isEmpty()) {
    $users->push(User::factory()->create());
}

foreach ($users as $u) {
    Animal::where('user_id', $u->id)->delete();
    
    $a1 = Animal::create(['user_id' => $u->id, 'name' => 'Bessie', 'type' => 'Cow', 'age' => 4, 'status' => 'Grazing']);
    $a2 = Animal::create(['user_id' => $u->id, 'name' => 'Daisy', 'type' => 'Cow', 'age' => 3, 'status' => 'Resting']);
    $a3 = Animal::create(['user_id' => $u->id, 'name' => 'Bolt', 'type' => 'Horse', 'age' => 5, 'status' => 'Distressed']);
    $a4 = Animal::create(['user_id' => $u->id, 'name' => 'Thunder', 'type' => 'Bull', 'age' => 6, 'status' => 'Grazing']);
    $a5 = Animal::create(['user_id' => $u->id, 'name' => 'Snowball', 'type' => 'Sheep', 'age' => 2, 'status' => 'Resting']);

    foreach ([$a1, $a2, $a3, $a4, $a5] as $a) {
        for ($i = 1; $i <= 10; $i++) {
            GpsLog::create([
                'animal_id' => $a->id,
                'recorded_at' => now()->subMinutes($i * 10),
                'latitude' => 34.05 + ($i * rand(1, 10) / 1000),
                'longitude' => -118.24 + ($i * rand(1, 10) / 1000),
                'altitude' => $a->status === 'Distressed' && $i == 1 ? 100 : 150,
                'speed' => $a->status === 'Distressed' ? 12 : ($a->status === 'Grazing' ? 1.5 : 0)
            ]);
        }
    }
}
echo "Seed complete for all users!\n";
