<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        // Ensure there are other users to talk to for the demo
        if (User::count() <= 1) {
            User::factory()->create(['name' => 'Sarah (Farm Manager)', 'email' => 'sarah@example.com']);
            User::factory()->create(['name' => 'Dr. Vet', 'email' => 'vet@example.com']);
            User::factory()->create(['name' => 'System Admin', 'email' => 'admin@example.com']);
        }

        $users = User::where('id', '!=', auth()->id())->get()->map(function($user) {
            $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
            
            // Get last message involving this user
            $lastMsg = ChatMessage::where(function($q) use ($user) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
            })->orWhere(function($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
            })->orderBy('created_at', 'desc')->first();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'initials' => strtoupper($initials),
                'color' => 'bg-zenith-teal/20 text-zenith-teal', // You can randomize this if you want
                'last_message' => $lastMsg ? $lastMsg->text : 'No messages yet.',
                'time' => $lastMsg ? $lastMsg->created_at->diffForHumans() : '',
            ];
        });

        return view('messages.index', compact('users'));
    }

    public function fetchMessages($receiverId)
    {
        $receiverId = (int) $receiverId;

        // Mark incoming messages from this user as read
        ChatMessage::where('receiver_id', auth()->id())
            ->where('sender_id', $receiverId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        $messages = ChatMessage::where(function($q) use ($receiverId) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $receiverId);
            })->orWhere(function($q) use ($receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'is_me' => $msg->sender_id === auth()->id(),
                    'text' => $msg->text,
                    'time' => $msg->created_at->format('h:i A')
                ];
            });

        return response()->json($messages);
    }

    public function store(Request $request, $receiverId)
    {
        $request->validate(['text' => 'required|string']);

        $msg = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => (int)$receiverId,
            'text' => $request->text,
            'is_read' => false,
            'created_at' => now()
        ]);

        return response()->json([
            'id' => $msg->id,
            'is_me' => true,
            'text' => $msg->text,
            'time' => $msg->created_at->format('h:i A')
        ]);
    }
}
