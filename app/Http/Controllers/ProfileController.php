<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $userProfileRepository;

    public function __construct(UserProfileRepositoryInterface $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function show(Request $request)
    {
        $profile = $this->userProfileRepository->findByUserId($request->user()->id);
        return response()->json([
            'profile' => $profile,
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar_icon' => ['required', 'string'],
        ]);

        $profile = $this->userProfileRepository->findByUserId($request->user()->id);
        $updatedProfile = $this->userProfileRepository->updateAvatar($profile->id, $request->avatar_icon);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'profile' => $updatedProfile,
        ]);
    }

    public function addLike(Request $request, $profileId)
    {
        $profile = $this->userProfileRepository->addLike($profileId);

        return response()->json([
            'message' => 'Like added successfully',
            'profile' => $profile,
        ]);
    }

    public function addExperiencePoint(Request $request)
    {
        $profile = $this->userProfileRepository->findByUserId($request->user()->id);
        $updatedProfile = $this->userProfileRepository->addExperiencePoint($profile->id);

        return response()->json([
            'message' => 'Experience point added successfully',
            'profile' => $updatedProfile,
        ]);
    }
}
