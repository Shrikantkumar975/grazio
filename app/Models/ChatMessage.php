<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\Concerns\HasNumericId;

class ChatMessage extends Model
{
    use HasFactory, HasNumericId;

    protected $connection = 'mongodb';
    protected $collection = 'chat_messages';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['sender_id', 'receiver_id', 'text', 'is_read', 'created_at'];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];
}
