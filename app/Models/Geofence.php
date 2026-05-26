<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\Concerns\HasNumericId;

class Geofence extends Model
{
    use HasFactory, HasNumericId;

    protected $connection = 'mongodb';
    protected $collection = 'geofences';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'name',
        'color',
        'coordinates', // array of [lat, lng] points forming a polygon
        'active',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'active'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
