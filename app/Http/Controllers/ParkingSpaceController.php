<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpace;
use Illuminate\Http\Request;

class ParkingSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ParkingSpace::query();

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        $parkingSpaces = $query->get();

        return response()->json($parkingSpaces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'vehicle_type' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric',
            'price_per_day' => 'nullable|numeric',
            'status' => 'nullable|in:active,pending,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $parkingSpace = ParkingSpace::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vehicle_type' => $request->vehicle_type,
            'price_per_hour' => $request->price_per_hour,
            'price_per_day' => $request->price_per_day,
            'status' => $request->status,
        ]);

        return response()->json($parkingSpace, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $parkingSpace = ParkingSpace::findOrFail($id);

        $parkingSpace->load('images', 'availability');

        return response()->json($parkingSpace);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'province' => 'string|max:255',
            'postal_code' => 'string|max:20',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'vehicle_type' => 'string|max:255',
            'price_per_hour' => 'numeric',
            'price_per_day' => 'nullable|numeric',
            'status' => 'in:active,pending,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $parkingSpace = ParkingSpace::findOrFail($id);

        $parkingSpace->user_id = $request->input('user_id', $parkingSpace->user_id);
        $parkingSpace->name = $request->input('name', $parkingSpace->name);
        $parkingSpace->description = $request->input('description', $parkingSpace->description);
        $parkingSpace->address = $request->input('address', $parkingSpace->address);
        $parkingSpace->city = $request->input('city', $parkingSpace->city);
        $parkingSpace->province = $request->input('province', $parkingSpace->province);
        $parkingSpace->postal_code = $request->input('postal_code', $parkingSpace->postal_code);
        $parkingSpace->latitude = $request->input('latitude', $parkingSpace->latitude);
        $parkingSpace->longitude = $request->input('longitude', $parkingSpace->longitude);
        $parkingSpace->vehicle_type = $request->input('vehicle_type', $parkingSpace->vehicle_type);
        $parkingSpace->price_per_hour = $request->input('price_per_hour', $parkingSpace->price_per_hour);
        $parkingSpace->price_per_day = $request->input('price_per_day', $parkingSpace->price_per_day);
        $parkingSpace->status = $request->input('status', $parkingSpace->status);

        $parkingSpace->save();

        return response()->json($parkingSpace, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $parkingSpace = ParkingSpace::findOrFail($id);

        $parkingSpace->delete();

        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10); // Default radius of 10 km

        $parkingSpaces = ParkingSpace::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having("distance", "<=", $radius)
            ->orderBy("distance")
            ->get();

        return response()->json($parkingSpaces, 200);
    }
}
