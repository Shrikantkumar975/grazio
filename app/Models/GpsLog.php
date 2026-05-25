<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\Concerns\HasNumericId;

class GpsLog extends Model
{
    use HasFactory, HasNumericId;

    protected $connection = 'mongodb';
    protected $collection = 'gps_logs';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['animal_id', 'recorded_at', 'latitude', 'longitude', 'altitude', 'speed'];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id', 'id');
    }
}
