<?php

namespace App\Models;

use App\Models\Concerns\HasNumericId;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasNumericId, Notifiable;

    protected $connection = 'mongodb';

    protected $collection = 'users';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $attributes = [
        'remember_token' => null,
        'email_verified_at' => null,
    ];

    protected $fillable = [
        'name', 'email', 'password',
        'phone', 'bio',
        'farm_name', 'farm_lat', 'farm_lng',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];

}
