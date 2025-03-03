<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'profile' => $request->user()->profile,
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar_icon' => ['required', 'string'],
        ]);

        $profile = $request->user()->profile;
        $profile->update([
            'avatar_icon' => $request->avatar_icon,
        ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'profile' => $profile,
        ]);
    }

    public function addLike(Request $request, UserProfile $profile)
    {
        $profile->addLike();

        return response()->json([
            'message' => 'Like added successfully',
            'profile' => $profile->fresh(),
        ]);
    }

    public function addExperiencePoint(Request $request)
    {
        $profile = $request->user()->profile;
        $profile->addExperiencePoint();

        return response()->json([
            'message' => 'Experience point added successfully',
            'profile' => $profile->fresh(),
        ]);
    }
}
