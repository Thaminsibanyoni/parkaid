<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::all();

        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $review = Review::create([
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = Review::findOrFail($id);

        return response()->json($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $review = Review::findOrFail($id);

        $review->rating = $request->input('rating', $review->rating);
        $review->comment = $request->input('comment', $review->comment);

        $review->save();

        return response()->json($review, 200);
    }

    /**
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);

        $review->delete();

        return response()->json(null, 204);
    }
}
