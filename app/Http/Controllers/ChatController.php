<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::query();
        
        // Search by name or message content
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        return $query->latest()->limit(100)->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'name' => 'nullable|string|max:191',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'name' => $data['name'] ?? auth()->user()->name ?? 'Guest',
            'message' => $data['message'],
        ]);

        return response()->json($message, 201);
    }
}
