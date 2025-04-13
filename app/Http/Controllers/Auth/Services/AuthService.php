<?php

namespace App\Http\Controllers\Auth\Services;

use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthService
{
    protected $userRepository;
    protected $userProfileRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserProfileRepositoryInterface $userProfileRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    public function register($request)
    {
        dd($request);
        $user = $this->userRepository->create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $this->userProfileRepository->create([
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user->load('profile'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('profile'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
