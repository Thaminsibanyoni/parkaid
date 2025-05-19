<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::all();

        return response()->json($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parking_space_id' => 'required|exists:parking_spaces,id',
            'user_id' => 'required|exists:users,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'total_price' => 'required|numeric',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $booking = Booking::create([
            'parking_space_id' => $request->parking_space_id,
            'user_id' => $request->user_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);

        return response()->json($booking, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::findOrFail($id);

        return response()->json($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'parking_space_id' => 'exists:parking_spaces,id',
            'user_id' => 'exists:users,id',
            'start_datetime' => 'date',
            'end_datetime' => 'date|after:start_datetime',
            'total_price' => 'numeric',
            'status' => 'in:pending,confirmed,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $booking = Booking::findOrFail($id);

        $booking->parking_space_id = $request->input('parking_space_id', $booking->parking_space_id);
        $booking->user_id = $request->input('user_id', $booking->user_id);
        $booking->start_datetime = $request->input('start_datetime', $booking->start_datetime);
        $booking->end_datetime = $request->input('end_datetime', $booking->end_datetime);
        $booking->total_price = $request->input('total_price', $booking->total_price);
        $booking->status = $request->input('status', $booking->status);

        $booking->save();

        return response()->json($booking, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->delete();

        return response()->json(null, 204);
    }
}
