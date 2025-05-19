<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();

        return response()->json($user->profile);
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_document' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $profile = $user->profile;

        $profile->address = $request->input('address', $profile->address);
        $profile->city = $request->input('city', $profile->city);
        $profile->province = $request->input('province', $profile->province);
        $profile->postal_code = $request->input('postal_code', $profile->postal_code);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture = $path;
        }

        // Handle ID document upload
        if ($request->hasFile('id_document')) {
            $path = $request->file('id_document')->store('id_documents', 'public');
            $profile->id_document = $path;
            $profile->verified = false; // Reset verification status
        }

        $profile->save();

        return response()->json($profile, 200);
    }
}
