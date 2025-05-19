<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::all();

        return response()->json($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'content' => 'required|string',
            'read' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'booking_id' => $request->booking_id,
            'content' => $request->content,
            'read' => $request->read,
        ]);

        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = Message::findOrFail($id);

        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'sender_id' => 'exists:users,id',
            'receiver_id' => 'exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'content' => 'string',
            'read' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $message = Message::findOrFail($id);

        $message->sender_id = $request->input('sender_id', $message->sender_id);
        $message->receiver_id = $request->input('receiver_id', $message->receiver_id);
        $message->booking_id = $request->input('booking_id', $message->booking_id);
        $message->content = $request->input('content', $message->content);
        $message->read = $request->input('read', $message->read);

        $message->save();

        return response()->json($message, 200);
    }

    /**
     */
    public function destroy(string $id)
    {
        $message = Message::findOrFail($id);

        $message->delete();

        return response()->json(null, 204);
    }
}
