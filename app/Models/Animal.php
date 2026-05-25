<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\Concerns\HasNumericId;

class Animal extends Model
{
    use HasFactory, HasNumericId;

    protected $connection = 'mongodb';
    protected $collection = 'animals';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['user_id', 'name', 'type', 'age', 'status'];

    public function gpsLogs()
    {
        return $this->hasMany(GpsLog::class, 'animal_id', 'id');
    }
}
