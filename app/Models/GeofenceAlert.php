<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\Concerns\HasNumericId;

class GeofenceAlert extends Model
{
    use HasFactory, HasNumericId;

    protected $connection = 'mongodb';
    protected $collection = 'geofence_alerts';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'animal_id',
        'geofence_id',
        'geofence_name',
        'animal_name',
        'latitude',
        'longitude',
        'resolved',
    ];

    protected $casts = [
        'resolved' => 'boolean',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id', 'id');
    }

    public function geofence()
    {
        return $this->belongsTo(Geofence::class, 'geofence_id', 'id');
    }
}
